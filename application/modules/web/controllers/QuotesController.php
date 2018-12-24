<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once "BaseController.php";
require_once APPPATH . "/libraries/Traits/LevelRoomCheck.php";
require_once APPPATH . "/libraries/Traits/TotalProjectPrice.php";
require_once APPPATH . "/libraries/Traits/TechnicianChargesCheck.php";
require_once APPPATH . "/libraries/Traits/InstallerPriceCheck.php";
require_once APPPATH . "/libraries/Traits/QuickCalc.php";
require_once APPPATH . "/libraries/Traits/QuotationPrice.php";


class QuotesController extends BaseController
{
    use LevelRoomCheck, InstallerPriceCheck, TotalProjectPrice,QuotationPrice, TechnicianChargesCheck,QuickCalc;
   
    
    private $validationData;

    function __construct()
    { 
        parent::__construct();
        $this->activeSessionGuard();
        $this->load->helper(['datetime']);
        $this->data['activePage'] = 'quotes';
    }



    public function index()
    {
        $this->data['userInfo'] = $this->userInfo;
        if (!empty($this->userInfo['user_id']) &&
            isset($this->userInfo['status']) &&
            $this->userInfo['status'] != BLOCKED) {
            $this->activeSessionGuard();

            $this->userTypeHandling([BUSINESS_USER, PRIVATE_USER], base_url('home/applications'));

            $this->load->model(['ProjectRequest']);
            $get = $this->input->get();

            $search = isset($get['search'])&&is_string($get['search'])&&strlen(trim($get['search']))>0?trim($get['search']):'';
            $page = isset($get['page']) && (int)$get['page'] > 1 ? (int)$get['page'] : 1;
            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            $params['user_id'] = $this->userInfo['user_id'];
            $params['language_code'] = $this->languageCode;
            if (strlen($search) > 0) {
                $params['where']["(name LIKE '%{$search}%' OR projects.address LIKE '%{$search}%')"] = null;
            }

            $data = $this->ProjectRequest->customerRequests($params);

            $this->data['csrf'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash()
            ]);

            $this->data['quotations'] = $data['data'];
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $data['count'], $params['limit']);
            $this->data['search'] = $search;
            $this->data['js'] = 'main';


            website_view("quotes/main", $this->data);
        } else {
            website_view("quotes/main_inactive_session", $this->data);
        }
    }

    public function customerQuotesList($projectId,$requestId)
    {
        try {
            $this->activeSessionGuard();
            $this->userTypeHandling([PRIVATE_USER, BUSINESS_USER], base_url('home/applications'));

            $this->load->model(['ProjectQuotation']);

            $projectId = encryptDecrypt($projectId, 'decrypt');
            $this->load->library(['form_validation']);

            $this->validationData = ['project_id' => $projectId];

            $this->valdiateCustomerQuote();

            $this->validationRun();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $get = $this->input->get();
            
            $search = isset($get['search'])&&is_string($get['search'])&&strlen(trim($get['search']))>0?trim($get['search']):'';
            $page = isset($get['page']) && (int)$get['page'] > 1 ? (int)$get['page'] : 1;
            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            $params['user_id'] = $this->userInfo['user_id'];
            $params['language_code'] = $this->languageCode;
            $params['search'] = $search;
            $params['project_id'] = $projectId;

            $data = $this->ProjectQuotation->quotations($params);

            $this->load->helper(['utility']);
            $data['data'] = $this->parseQuotationData($data['data']);

            $this->data['quotes'] = $data['data'];
            $this->data['search'] = $search;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item("basic-with-font-awesome");

            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['requestId'] = $requestId;

            //pr($this->data);

            website_view('quotes/quotes', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    public function customerQuotesListViaProject($projectId)
    {
        try {

           
            $this->activeSessionGuard();
            $this->userTypeHandling([PRIVATE_USER, BUSINESS_USER], base_url('home/applications'));

            $this->load->model(['ProjectQuotation']);

            $projectId = encryptDecrypt($projectId, 'decrypt');
            $this->load->library(['form_validation']);

            $this->validationData = ['project_id' => $projectId];

            $this->valdiateCustomerQuote();

            $this->validationRun();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $get = $this->input->get();
            
            $search = isset($get['search'])&&is_string($get['search'])&&strlen(trim($get['search']))>0?trim($get['search']):'';
            $page = isset($get['page']) && (int)$get['page'] > 1 ? (int)$get['page'] : 1;
            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            $params['user_id'] = $this->userInfo['user_id'];
            $params['language_code'] = $this->languageCode;
            $params['search'] = $search;
            $params['project_id'] = $projectId;

            
            
            $data = $this->ProjectQuotation->quotations($params);

            $this->load->helper(['utility']);
            $data['data'] = $this->parseQuotationData($data['data']);

            

            $this->data['quotes'] = $data['data'];
            $this->data['search'] = $search;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item("basic-with-font-awesome");

            $this->data['projectId'] = encryptDecrypt($projectId);
            

            //pr($this->data);

            website_view('quotes/project_quotes', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * Installer awaiting quotes listing
     *
     * @return void
     */
    public function awaiting()
    {
        try {
            $this->activeSessionGuard();
            $this->userTypeHandling([INSTALLER], base_url('home/applications'));

            $this->load->model(['ProjectRequest']);
            $get = $this->input->get();

            $permissions = $this->handleEmployeePermission([INSTALLER], ['quote_view'], base_url('home/applications'));


            $search = isset($get['search'])&&is_string($get['search'])&&strlen(trim($get['search']))>0?trim($get['search']):'';
            $page = isset($get['page']) && (int)$get['page'] > 1 ? (int)$get['page'] : 1;
            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            $params['company_id'] = $this->userInfo['company_id'];
            $params['language_code'] = $this->languageCode;
            $params['lat'] = 0;
            $params['lng'] = 0;
            if (strlen($search) > 0) {
                $params['where']["(name LIKE '%{$search}%' OR projects.address LIKE '%{$search}%')"] = null;
            }

            $data = $this->ProjectRequest->awaitingRequest($params);

            $this->data['quotations'] = $data['data'];
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $data['count'], $params['limit']);
            $this->data['search'] = $search;

            website_view('quotes/awaiting', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    


    /**
     * Installer submitted quotes listing
     *
     * @return void
     */
    public function submitted()
    {
        try {
            $this->activeSessionGuard();
            $this->userTypeHandling([INSTALLER], base_url('home/applications'));

            $this->load->model(['ProjectRequest', 'ProjectRoomQuotation']);
            $get = $this->input->get();

            $permissions = $this->handleEmployeePermission([INSTALLER], ['quote_view'], base_url('home/applications'));

            $search = isset($get['search'])&&is_string($get['search'])&&strlen(trim($get['search']))>0?trim($get['search']):'';
            $page = isset($get['page']) && (int)$get['page'] > 1 ? (int)$get['page'] : 1;
            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            $params['company_id'] = $this->userInfo['company_id'];
            $params['language_code'] = $this->languageCode;
            if (strlen($search) > 0) {
                $params['where']["(name LIKE '%{$search}%' OR projects.address LIKE '%{$search}%')"] = null;
            }

            $data = $this->ProjectRequest->submittedRequestList($params);

            $this->load->helper(['utility']);
            $data['data'] = $this->parseQuotationDataPartially($data['data']);

            
            
            if (!empty($data['data'])) {
                $projectIds = array_column($data['data'], 'project_id');
                $price = $this->ProjectRoomQuotation->quotationPrice($this->userInfo['company_id'], $projectIds);
                $this->load->helper(['db', 'utility']);
                $data['data'] = getDataWith($data['data'], $price, 'project_id', 'project_id', 'price');
                $data['data'] = $this->processPriceData($data['data']);
            }

           
            $this->data['quotations'] = $data['data'];
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $data['count'], $params['limit']);
            $this->data['search'] = $search;

            

            website_view('quotes/submitted', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * Installer approved quotation listing
     *
     * @return void
     */
    public function approved()
    {
        try {
            $this->activeSessionGuard();
            $this->userTypeHandling([INSTALLER], base_url('home/applications'));

            $this->load->model(['ProjectRequest', 'ProjectRoomQuotation']);
            $get = $this->input->get();

            $permissions = $this->handleEmployeePermission([INSTALLER], ['quote_view'], base_url('home/applications'));

            $search = isset($get['search'])&&is_string($get['search'])&&strlen(trim($get['search']))>0?trim($get['search']):'';
            $page = isset($get['page']) && (int)$get['page'] > 1 ? (int)$get['page'] : 1;
            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            $params['company_id'] = $this->userInfo['company_id'];
            $params['language_code'] = $this->languageCode;
            if (strlen($search) > 0) {
                $params['where']["(name LIKE '%{$search}%' OR projects.address LIKE '%{$search}%')"] = null;
            }

            $data = $this->ProjectRequest->approvedRequests($params);

            if (!empty($data['data'])) {
                $projectIds = array_column($data['data'], 'project_id');
                $price = $this->ProjectRoomQuotation->quotationPrice($this->userInfo['company_id'], $projectIds);
                $this->load->helper(['db', 'utility']);
                $data['data'] = getDataWith($data['data'], $price, 'project_id', 'project_id', 'price');
                $data['data'] = $this->processPriceData($data['data']);
            }

            $this->data['quotations'] = $data['data'];
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $data['count'], $params['limit']);
            $this->data['search'] = $search;

            website_view('quotes/approved', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * Process price data
     *
     * @param array $data
     * @return array
     */
    private function processPriceData($data) 
    {
        $priceData = array_map(function ($quotation) {
            $initialPrice = [
                "price_per_luminaries" => 0.00,
                "installation_charges" => 0.00,
                "discount_price" => 0.00,
                "subtotal" => 0.00,
                "total" => 0.00
            ];
            $quotation['price'] = array_reduce($quotation['price'], function ($carry, $item) {
                $sum = $item['price_per_luminaries'] + $item['installation_charges'];
                $carry['price_per_luminaries'] += $item['price_per_luminaries'];
                $carry['installation_charges'] += $item['installation_charges'];
                $carry['discount_price'] += $item['discount_price'];
                $carry['subtotal'] += $sum;
                $carry['total'] += get_percentage($sum, $item['discount_price']);
                return $carry;
            },$initialPrice);
            $sum = $quotation['price']['total'] + $quotation['additional_product_charges'];
            $quotation['subtotal_price'] = $sum;
            $quotation['total_price'] = sprintf("%.2f", get_percentage($sum, $quotation['discount']));

            return $quotation;
        }, $data);

        return $priceData;
    }

    private function valdiateCustomerQuote()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    private function parseQuotationData($data)
    {
        $data = array_map(function ($quotation) {
            $quotation['quotation_data'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                'quotation_id' => encryptDecrypt($quotation['quotation_id'])
            ]);
            $quotation['quotation_price'] = json_decode($quotation['quotation_price'], true);
            $quotation['quotation_price']['additional_product_charges'] = (double)$quotation['additional_product_charges'];
            $quotation['quotation_price']['discount'] = (double)$quotation['discount'];
            $quotation['quotation_price']['main_product_charge'] = 0.00;
            $quotation['quotation_price']['accessory_product_charge'] = 0.00;
            $quotation['quotation_price']['total'] = 0.00;
            $quotation['quotation_price']['total'] = $quotation['quotation_price']['main_product_charge'] +
                                                $quotation['quotation_price']['accessory_product_charge'] +
                                                get_percentage(
                                                    $quotation['quotation_price']['price_per_luminaries'] +
                                                    $quotation['quotation_price']['installation_charges'] +
                                                    $quotation['quotation_price']['additional_product_charges'],
                                                    $quotation['quotation_price']['discount']
                                                );

            unset($quotation['discount'], $quotation['additional_product_charges']);
            return $quotation;
        }, $data);

        return $data;
    }

    private function parseQuotationDataPartially($data)
    {
        $data = array_map(function ($quotation) {
            $quotation['quotation_data'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                'quotation_id' => encryptDecrypt($quotation['request_id'])
            ]);
            return $quotation;
        }, $data);

        return $data;
    }


    /**
     * Project details from quotes
     *
     * @param array project id
     * @return object
     */

    public function project_details($project_id,$request_id)
    {
        
        try {
            $this->activeSessionGuard();
            $id = encryptDecrypt($project_id, "decrypt");
            $this->load->model(["Project", "ProjectRooms", "UtilModel"]);
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('quotes-project-detail');
            $this->data['js'] = 'level-listing';
            $this->data['userInfo'] = $this->userInfo;
            $roomParams['where']['project_id'] = $id;
            $roomParams['limit']               = 5;
            $roomData                          = $this->ProjectRooms->get($roomParams);
            $this->data['project']             = $this->Project->details(["project_id" => $id]);
            $rooms                             = $roomData['data'];
            $roomCount                         = (int) $roomData['count'];
            if (! empty($rooms)) {
                $roomIds                                       = array_column($rooms, 'project_room_id');
                $this->load->model('ProjectRoomProducts');
                $roomProductParams['where']['project_room_id'] = $roomIds;
                $roomProducts                                  = $this->ProjectRoomProducts->get($roomProductParams);
                $roomProducts                                  = $roomProducts['data'];
                $roomProducts                                  = array_map(function ($product) {
                    $product['article_image'] = preg_replace("/^\/home\/forge\//", "https://", $product['article_image']);
                    return $product;
                }, $roomProducts);
                $this->load->helper('db');
                $rooms = getDataWith($rooms, $roomProducts,'project_room_id', 'project_room_id', 'products');
            }

            $this->data['isRequested'] = false;
            $this->data['quoteCount'] = 0;
            $this->data['projectId'] = encryptDecrypt($id);

            if (in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $projectRequest = $this->UtilModel->selectQuery('id as request_id', 'project_requests', [
                    'where' => ['project_id' => $id], 'single_row' => true
                ]);

                $this->data['isRequested'] = (bool)!empty($projectRequest);
                if (!empty($projectRequest)) {
                    $projectCount  = $this->UtilModel->selectQuery('COUNT(id) as count', 'project_quotations', [
                        'where' => ['request_id' => $projectRequest['request_id']], 'single_row' => true
                    ]);

                    $this->data['quoteCount'] = $projectCount['count'];
                }
            }

            $permissions = $this->handleEmployeePermission([INSTALLER], ['project_view'], base_url('home/applications'));

            $this->data['permission'] = $permissions;

            $this->data['rooms']           = $rooms;
            $this->data['room_count']      = $roomCount;
            $this->data['has_more_rooms']  = $roomCount > 4;
            $this->data['page_room_count'] = $roomParams['limit'];
            
            $this->load->model(['UtilModel']);
            $this->data['quotationRequest'] = $this->UtilModel->selectQuery('id', 'project_requests', [
                'where' => ['project_id' => $this->data['project']['project_id']]
            ]);

            /********* level data*******************/

            $permissions = $this->handleEmployeePermission([INSTALLER], ['quote_view'], base_url('home/applications'));

            $this->load->model(["ProjectLevel", "ProjectRooms"]);
            $roomCount = $this->ProjectRooms->roomCountByLevel($id);

            $projectLevels = $this->ProjectLevel->projectLevelData([
                'where' => ['project_id' => $id]
            ]);

            $this->load->helper(['project', 'db']);

            $projectLevels = level_activity_status_handler($projectLevels);

            $projectLevels =
                getDataWith($projectLevels, $roomCount, 'level', 'level', 'room_count', 'room_count');

            $this->data['projectId'] = encryptDecrypt($id);
            $this->data['permissions'] = $permissions;

            $activeLevels = array_filter($projectLevels, function ($projectLevel) {
                return (bool)$projectLevel['active'];
            });

            $activeLevels = array_column($activeLevels, 'level');
            $activeLevels = array_map(function ($level) {return (int)$level;}, $activeLevels);

            $projectLevels = array_map(function ($level) use ($activeLevels) {
                $level['level'] = (int)$level['level'];
                $level['data'] = json_encode([
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                    'project_id' => $this->data['projectId'],
                    'level' => $level['level']
                ]);
                $level['room_count'] = is_array($level['room_count']) &&
                    count($level['room_count']) &&
                    isset($level['room_count'][0]) ? (int)$level['room_count'][0] : 0;

                $level['cloneable_destinations'] = json_encode(array_values(array_filter($activeLevels, function ($activeLevel) use ($level) {
                    return (int)$activeLevel !== $level['level'] && (bool)$level['active'];
                })));
                return $level;
            }, $projectLevels);


            foreach($projectLevels as $key=>$level) {
                $projectLevels[$key]['isAllRoomPriceAdded'] = $this->ProjectLevel->isAllRoomPriceAdded($id,$level['level']);
            }
            $this->data['csrf'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                'project_id' => $this->data['projectId']
            ]);
            
            $this->data['active_levels'] = $activeLevels;
            
            $this->data['projectLevels'] = $projectLevels;
        


            $this->data['quotationRequest'] = $this->UtilModel->selectQuery('id', 'project_requests', [
                'where' => ['project_id' => $id]
            ]);

            $this->data['all_levels_done'] = is_bool(array_search(0, array_column($projectLevels, 'status')));

            $this->data['hasAddedAllPrice'] = false;
            $this->data['projectRoomPrice'] = [];
            $this->data['hasAddedFinalPrice'] = false;
            $this->data['request_id'] = $request_id;
            $request_id= encryptDecrypt($request_id, "decrypt");
            

            $this->data['request_status'] = $this->getRequestStatus($request_id);

            //$this->data['request_status']=2;
            
            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                
                $this->load->helper(['utility']);
                $this->data['hasAddedAllPrice'] = $this->projectCheckPrice($id);
                $this->data['projectRoomPrice'] = (array)$this->originalQuotationPrice((int)$this->userInfo['company_id'], $id);
                $this->data['hasAddedFinalPrice'] = $this->hasTechnicianAddedFinalPrice($id);

                
                $this->data['hasFinalQuotePriceAdded'] = $this->isFinalQuotePriceAdded($request_id);
            }

            $this->data['company_discount'] = 0;
            
            if ((int)$this->userInfo['is_owner'] === ROLE_OWNER && (int)$this->userInfo['user_type'] === INSTALLER) {
                $companyDiscount = $this->UtilModel->selectQuery('company_discount', 'company_master', [
                    'where' => ['company_id' => $this->userInfo['company_id']], 'single_row' => true
                ]);
                $this->data['company_discount'] = $companyDiscount['company_discount'];
            }
            
            
          // pr($this->data);
            
            website_view('quotes/project_details', $this->data);
        } catch (Exception $ex) {
        }
    }

    private function isFinalQuotePriceAdded($request_id)
    {
        $requestData = $this->UtilModel->selectQuery('id', 'project_quotations', [
            'where' => ['request_id' => $request_id], 'single_row' => true
        ]);

        if(!empty($requestData)) {
            return true;
        } else {
            return false;
        }
    }

    private function getRequestStatus($request_id)
    {
        $requestData = $this->UtilModel->selectQuery('id,status', 'project_quotations', [
            'where' => ['request_id' => $request_id], 'single_row' => true
        ]);

        
        if(!empty($requestData)) {
            return $requestData['status'];
        } else {
            return false;
        }
    }

    /**
     * Project Room Listing from quotes
     *
     * @param string $projectId
     * @param string $level
     * @return void
     */
    public function projectCreateRoomListing($projectId, $level)
    {
        try {
            require_once('ProjectRoomsController.php');
            $obj = new ProjectRoomsController();
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('project-level-room-listing');
            $this->data['js'] = 'project-level-room-listing';

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");

            $obj->validationData = ['project_id' => $projectId, 'level' => $level];

            $obj->validateRoomsListing();

           

            $status = $obj->validationRun();

            if (!$status) { 
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER], ['quote_view'], base_url('home/applications'));
            $this->load->model(['UtilModel', 'ProjectRooms', 'ProjectRoomProducts']);
            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            $levelCheck = $this->UtilModel->selectQuery('id, status', 'project_levels', [
                'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);

           

            if (empty($projectData)) { 
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            if (empty($levelCheck)) { 
                show404($this->lang->line('bad_request'), base_url(''));
            }

            //pr($this->userInfo);
            
            // if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
            //     (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
            //     (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                    
            //     show404($this->lang->line('forbidden_action'), base_url(''));
            // }

            $page = $this->input->get('page');

            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = 0;
            if (is_numeric($page) && (int)$page > 0) {
                $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            }
            $params['where']['project_id'] = $projectId;
            $params['where']['language_code'] = $languageCode;
            $params['where']['level'] = $level;

            $roomData = $this->ProjectRooms->get($params);
            $this->load->helper(['db', 'utility']);

            if (!empty($roomData['data'])) {
                $roomIds = array_column($roomData['data'], 'project_room_id');
                $roomProducts = $this->UtilModel->selectQuery('project_room_id', 'project_room_products', [
                    'where_in' => ['project_room_id' => $roomIds]
                ]);

                $roomData['data'] = getDataWith($roomData['data'], $roomProducts, 'project_room_id', 'project_room_id', 'products');
            }

            $roomData['data'] = array_map(function ($room) {
                $room['room_count_data'] = json_encode([
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                    'project_room_id' => encryptDecrypt($room['project_room_id'])
                ]);
                return $room;
            }, $roomData['data']);

            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true) && !empty($roomData['data'])) {
                $this->load->model('ProjectRoomQuotation');
                $roomPrice = $this->UtilModel->selectQuery(
                    'project_room_id, price_per_luminaries, installation_charges, discount_price',
                    'project_room_quotations',
                    [
                        'where_in' => ['project_room_id' => $roomIds]
                    ]
                );

                $roomData['data'] = getDataWith($roomData['data'], $roomPrice, 'project_room_id', 'project_room_id', 'price');

                $roomData['data'] = array_map(function ($room) {
                    $room['price'] = isset($room['price'][0]) ? $room['price'][0] : [];
                    if (!empty($room['price']) && isset($room['price']['price_per_luminaries'], $room['price']['installation_charges'], $room['price']['discount_price'])) {
                        $room['price']['subtotal'] = sprintf("%.2f", $room['price']['price_per_luminaries'] + $room['price']['installation_charges']);
                        $room['price']['total'] = sprintf("%.2f", get_percentage($room['price']['price_per_luminaries'] + $room['price']['installation_charges'], $room['price']['discount_price']));
                    }
                    $room['price_data'] = is_array($room['price']) && count($room['price']) > 0 ? $room['price'] : (object)[];
                    $room['price_data'] = json_encode($room['price_data']);
                    return $room;
                }, $roomData['data']);
            }

            $this->data['levelCheck'] = $levelCheck;
            $this->data['rooms'] = $roomData['data'];
            $this->data['projectId'] = encryptDecrypt($projectData['id']);
            $this->data['level'] = $level;
            $this->data['levelData'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                'project_id' => $this->data['projectId'],
                'level' => $level
            ]);

            $this->data['csrf'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash()
            ]);

            $this->data['quotationRequest'] = $this->UtilModel->selectQuery('id', 'project_requests', [
                'where' => ['project_id' => $projectId]
            ]);

            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), (int)$roomData['count'], $params['limit']);

            $this->data['hasAddedFinalPrice'] = false;
            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->load->helper(['utility']);
                $this->data['hasAddedFinalPrice'] = $this->hasTechnicianAddedFinalPrice($projectId);
            }

            
            website_view('quotes/levels_room_list', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('/home/applications'));
        }
    }

    /**
     * edit project from quotes
     *@param string projectId
     * @return void
     */
    public function editProject($projectId,$requestId)
    { 
        require_once('ProjectController.php');
        $obj = new ProjectController();

        $this->activeSessionGuard();
        $this->data['userInfo'] = $this->userInfo;
        $this->load->config('css_config');
        $this->data['css'] = $this->config->item('create-project');

        
        $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));
        
        $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_add'], base_url('home/applications'));

        
        $languageCode = "en";

        $this->data['projectId'] = $projectId;

        $projectId = encryptDecrypt($projectId, 'decrypt');
        $this->load->model("UtilModel");

        if (empty($projectId) || !is_numeric($projectId)) { 
            show404($this->lang->line('bad_request'), base_url('/home/applications'));
        }

       

        $projectData = $this->UtilModel->selectQuery('*', 'projects', [
            'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
        ]);

        if (empty($projectData)) {
            show404($this->lang->line('project_not_found'), base_url('/home/applications'));
        }

        
        if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
            (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
            (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
            show404($this->lang->line('forbidden_action'), base_url(''));
        }

        $this->data['employees'] = [];

        if ((int)$this->userInfo['user_type'] === INSTALLER && (int)$this->userInfo['is_owner'] === ROLE_OWNER) {
            $this->load->model('Employee');
            $employees = $this->Employee->employees([
                'where' => ['users.company_id' => $this->userInfo['company_id'], 'is_owner' => ROLE_EMPLOYEE]
            ]);

            $employees = array_map(function ($employee) {
                $employee['user_id'] = encryptDecrypt($employee['user_id']);
                return $employee;
            }, $employees);

            $this->data['employees'] = $employees;
        }

        if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true) && !empty($projectData['installer_id'])) {
            $projectData['installer_id'] = encryptDecrypt($projectData['installer_id']);
        }
        
        $this->data['projectData'] = $projectData;

        try {
            $post = $this->input->post();
            
            if (isset($post) and ! empty($post)) { 
                $this->load->library('form_validation');
                $this->form_validation->CI = & $this;
                $rules                     =$obj->setEditValidationRule();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run()) {
                    $update = [
                        "number"        => trim($post['project_number']),
                        "name"          => trim($post['project_name']),
                        "address"       => trim($post['address']),
                        "lat"       => trim($post['address_lat']),
                        "lng"       => trim($post['address_lng']),
                        "updated_at"    => $this->datetime,
                        'updated_at_timestamp' => $this->timestamp,
                    ];

                    if ((int)$this->userInfo['user_type'] === INSTALLER &&
                     (int)$this->userInfo['is_owner'] === ROLE_OWNER
                     && strlen(trim($post['installers'])) > 0) {
                        $update['installer_id'] = (int)encryptDecrypt(trim($post['installers']), 'decrypt');
                    }

                    $this->db->trans_begin();
                    // $projectId = $this->Project->save_project($insert);
                    $this->UtilModel->updateTableData($update, 'projects', [
                        'id' => $projectId 
                    ]);

                    

                    $this->load->model("UtilModel");
            
                    if ($this->db->trans_status() === true) {
                        $this->db->trans_commit();
                        $this->session->set_flashdata("flash-message", $this->lang->line('project_updated'));
                        $this->session->set_flashdata("flash-type", "success");
                        $projectId = encryptDecrypt($projectId, 'encrypt');
                        redirect(base_url("home/quotes/projects/".$projectId.'/'.$requestId));
                    } else {
                        throw new Exception("Something Went Wrong", 500);
                    }
                }
            }
            $this->data['js'] = 'project_edit';
            $this->data['request_id'] = $requestId;

            
            website_map_modal_view("quotes/edit_project", $this->data);
        } catch (Exception $ex) {
            $this->db->trans_rollback();
        }
    }

    /**
     * Displays selected from quotes
     *
     * @param string projectId
     * @param string level
     * @param string roomId
     * @param string projectRoomId
     * @return void
     */
    public function selectedProjectProducts($projectId,$requestId, $level, $roomId, $projectRoomId)
    {
        try {

            require_once('ProjectProductController.php');
            $obj = new ProjectProductController();


            $this->activeSessionGuard();

            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('basic-with-font-awesome');

            $projectId = encryptDecrypt($projectId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");

            $languageCode = $this->languageCode;

            $obj->validationData = ['project_id' => $projectId, 'level' => $level, 'room_id' => $roomId, 'project_room_id' => $projectRoomId];

            
            $obj->validateAccessoryProduct();

            $status = $obj->validationRun();

            

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER], ['project_view', 'project_edit', 'project_add'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'ProjectRoomProducts']);

            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            $levelCheck = $this->UtilModel->selectQuery('id', 'project_levels', [
                'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            if (empty($levelCheck)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $projectRoom = $this->UtilModel->selectQuery('id, room_id', 'project_rooms', [
                'where' => ['id' => $projectRoomId], 'single_row' => true
            ]);

            if (empty($projectRoom)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            // if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
            //     (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
            //     (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
            //     show404($this->lang->line('forbidden_action'), base_url(''));
            // }

            $get = $this->input->get();

            $params['project_room_id'] = $projectRoomId;

            $this->data['search'] = '';
            if (isset($get['search']) && strlen(trim($get['search'])) > 0) {
                $params['search'] = trim($get['search']);
                $this->data['search'] = $params['search'];
            }

            $data = $this->ProjectRoomProducts->selectedProducts($params);

            $this->load->model('Product');

            $params['room_id'] = $roomId;

            //$this->data['assessory_products'] = $this->Product->roomProducts($params);

            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['roomId'] = encryptDecrypt($roomId);
            $this->data['projectRoomId'] = encryptDecrypt($projectRoomId);
            $this->data['level'] = $level;

            $data = array_map(function ($product) {
                $product['remove_data'] = json_encode([
                    'product_id' => encryptDecrypt($product['product_id']),
                    'article_code' => $product['articlecode'],
                    'project_room_id' => $this->data['projectRoomId'],
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash()
                ]);
                return $product;
            }, $data);

            $this->data['products'] = $data;


            

            $this->data['quotationRequest'] = [];
            $this->data['hasAddedFinalPrice'] = false;
            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->load->helper(['utility']);
                $this->data['hasAddedFinalPrice'] = $this->hasTechnicianAddedFinalPrice($projectId);
            }
            
            if (in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $this->data['quotationRequest'] = $this->UtilModel->selectQuery('id', 'project_requests', [
                    'where' => ['project_id' => $projectId]
                ]);
            }

            $this->data['request_id'] = $requestId;

            $requestId= encryptDecrypt($requestId, "decrypt");

            $this->data['request_status'] = $this->getRequestStatus($requestId);


            //pr($this->data);
            website_view('quotes/project_selected_products', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    public function projectResultRoomListing($projectId, $request_id,$level)
    { 
        try {
            require_once('ProjectRoomsController.php');
            $obj = new ProjectRoomsController();

            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('project-level-room-listing');
            $this->data['js'] = 'project-level-room-listing';

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");

            $obj->validationData = ['project_id' => $projectId, 'level' => $level];

            $obj->validateRoomsListing();

            $status = $obj->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER], ['project_view'], base_url('home/applications'));

            $this->data['permission'] = $permissions;

            
            $this->load->model(['UtilModel', 'ProjectRooms', 'ProjectRoomProducts']);
            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            $levelCheck = $this->UtilModel->selectQuery('id, status', 'project_levels', [
                'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            if (empty($levelCheck)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            // if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
            //     (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
            //     (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
            //     show404($this->lang->line('forbidden_action'), base_url(''));
            // }

            $page = $this->input->get('page');

            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = 0;
            if (is_numeric($page) && (int)$page > 0) {
                $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            }
            $params['where']['project_id'] = $projectId;
            $params['where']['language_code'] = $languageCode;
            $params['where']['level'] = $level;

            $roomData = $this->ProjectRooms->get($params);
            $this->load->helper(['db', 'utility']);

            if (!empty($roomData['data'])) {
                $roomIds = array_column($roomData['data'], 'project_room_id');
                $roomProducts = $this->UtilModel->selectQuery('project_room_id', 'project_room_products', [
                    'where_in' => ['project_room_id' => $roomIds]
                ]);

                $roomData['data'] = getDataWith($roomData['data'], $roomProducts, 'project_room_id', 'project_room_id', 'products');
            }

            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true) && !empty($roomData['data'])) {
                $this->load->model('ProjectRoomQuotation');
                $roomPrice = $this->UtilModel->selectQuery(
                    'project_room_id, price_per_luminaries, installation_charges, discount_price',
                    'project_room_quotations',
                    [
                        'where_in' => ['project_room_id' => $roomIds]
                    ]
                );

                $roomData['data'] = getDataWith($roomData['data'], $roomPrice, 'project_room_id', 'project_room_id', 'price');

                $roomData['data'] = array_map(function ($room) {
                    $room['price'] = isset($room['price'][0]) ? $room['price'][0] : [];
                    if (!empty($room['price']) && isset($room['price']['price_per_luminaries'], $room['price']['installation_charges'], $room['price']['discount_price'])) {
                        $room['price']['subtotal'] = sprintf("%.2f", $room['price']['price_per_luminaries'] + $room['price']['installation_charges']);
                        $room['price']['total'] = sprintf("%.2f", get_percentage($room['price']['price_per_luminaries'] + $room['price']['installation_charges'], $room['price']['discount_price']));
                    }
                    $room['price_data'] = is_array($room['price']) && count($room['price']) > 0 ? $room['price'] : (object)[];
                    $room['price_data'] = json_encode($room['price_data']);
                    return $room;
                }, $roomData['data']);
            }

            $this->data['levelCheck'] = $levelCheck;
            $this->data['rooms'] = $roomData['data'];
            $this->data['projectId'] = encryptDecrypt($projectData['id']);
            $this->data['level'] = $level;
            $this->data['levelData'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                'project_id' => $this->data['projectId'],
                'level' => $level
            ]);

            $this->data['csrf'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash()
            ]);

            $this->data['quotationRequest'] = $this->UtilModel->selectQuery('id', 'project_requests', [
                'where' => ['project_id' => $projectId]
            ]);

            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), (int)$roomData['count'], $params['limit']);

            $this->data['hasAddedFinalPrice'] = false;
            $this->data['projectRoomPrice'] = [];
            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->load->helper(['utility']);
                $this->data['projectRoomPrice'] = (array)$this->quotationTotalPrice((int)$this->userInfo['user_type'], $projectId, $level);
                $this->data['hasAddedFinalPrice'] = $this->hasTechnicianAddedFinalPrice($projectId);
            }
            $this->data['request_id'] = $request_id;

            $request_id= encryptDecrypt($request_id, "decrypt");

            $this->data['request_status'] = $this->getRequestStatus($request_id);

            
           
           //pr($this->data);
            website_view('quotes/result_room_list', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('/home/applications'));
        }
    }

    /**
     * Displays selected from quotes
     *
     * @param string projectRoomId
     * @return void
     */

    public function view_result($project_id,$request_id,$project_room_id)
    { 
        try {
            $this->activeSessionGuard();
            if (isset($this->userInfo, $this->userInfo['user_id']) && ! empty($this->userInfo['user_id'])) {
                $this->data['userInfo'] = $this->userInfo;
            }
            $id = encryptDecrypt($project_room_id, "decrypt");

            $this->load->model(['ProjectRooms']);

            $projectAndRoomData = $this->ProjectRooms->projectAndRoomData([
                'where' => ['pr.id' => $id]
            ]);

            if (empty($projectAndRoomData)) {
                show404($this->lang->line('room_data_not_found'), base_url());
            }

            // if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
            //     (int)$this->userInfo['user_id'] !== (int)$projectAndRoomData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
            //     (int)$this->userInfo['company_id'] !== (int)$projectAndRoomData['company_id'])) {
            //     show404($this->lang->line('forbidden_action'), base_url(''));
            // }

            $this->load->model(["UtilModel", "ProjectRoomProducts", "ProductSpecification"]);
            $this->data['room_data'] = $this->UtilModel->selectQuery("*", "project_rooms", ["single_row" => true, "where" => ["id" => $id]]);

            if (empty($this->data['room_data'])) {
                show404($this->lang->line('no_data_found'), base_url('home/projects'));
            }
            $this->data['projectId'] = encryptDecrypt($this->data['room_data']['project_id']);
            $roomProductData = $this->ProjectRoomProducts->get([
                'where' => ['project_room_id' => $id]
            ]);
            
            $roomProductData = $roomProductData['data'][0];
            $productSpecifications = $this->ProductSpecification->getch([
                'product_id' => $roomProductData['product_id'],
                'where' => ['articlecode' => $roomProductData['articlecode']],
                'single_row' => true
            ]);
            $this->data['product_data'] = $roomProductData;
            $this->data['product_specification_data'] = $productSpecifications;
            $this->data['request_id'] = $request_id;

            /*******room view result**************/


            $this->data['room_data']['working_plane_height'] / 100;

            // pd($this->data['room_data']);

            $quickCalcData = $this->fetchQuickCalcData($this->data['room_data'], $productSpecifications['uld']);

            $quickCalcData = json_decode($quickCalcData, true);

            //pr($quickCalcData);

            if (!isset($quickCalcData['projectionFront'], $quickCalcData['projectionTop'], $quickCalcData['projectionSide'])) {
                $this->session->set_flashdata("flash-message", $this->lang->line('unable_to_calculate'));
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url('home/quotes/projects/' .$project_id.'/'.$request_id.'/'. '/levels/' . $this->data['room_data']['level'] . '/rooms'));
            }

            $this->data['room_data']['front_view'] = $quickCalcData['projectionFront'];
            $this->data['room_data']['top_view'] = $quickCalcData['projectionTop'];
            $this->data['room_data']['side_view'] = $quickCalcData['projectionSide'];

            $this->data['product_data'] = $roomProductData;
            $this->data['product_specification_data'] = $productSpecifications;
     

            //pr($this->data);
            website_view('quotes/view_result', $this->data);
        } catch (Exception $ex) {
        }
    }

    /**
     * Level lisitng in project create flow
     *
     * @return void
     */
    public function levelsListing($projectId,$requestId)
    { 
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('project-levels');
            $this->data['js'] = 'level-listing';

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));

            $languageCode = "en";

            $projectId = encryptDecrypt($projectId, 'decrypt');
            $this->load->model("UtilModel");

            if (empty($projectId) || !is_numeric($projectId)) {
                show404($this->lang->line('bad_request'), base_url('/home/applications'));
            }

            $projectData = $this->UtilModel->selectQuery('id, user_id, company_id', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url('/home/applications'));
            } 

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) ||
                (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $this->load->model(["ProjectLevel", "ProjectRooms"]);
            $roomCount = $this->ProjectRooms->roomCountByLevel($projectId);

            $projectLevels = $this->ProjectLevel->projectLevelData([
                'where' => ['project_id' => $projectId]
            ]);

            $this->load->helper(['project', 'db']);

            $projectLevels = level_activity_status_handler($projectLevels);

            $projectLevels =
                getDataWith($projectLevels, $roomCount, 'level', 'level', 'room_count', 'room_count');

            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['permissions'] = $permissions;

            $activeLevels = array_filter($projectLevels, function ($projectLevel) {
                return (bool)$projectLevel['active'];
            });

            $activeLevels = array_column($activeLevels, 'level');
            $activeLevels = array_map(function ($level) {return (int)$level;}, $activeLevels);

            $projectLevels = array_map(function ($level) use ($activeLevels) {
                $level['level'] = (int)$level['level'];
                $level['data'] = json_encode([
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                    'project_id' => $this->data['projectId'],
                    'level' => $level['level']
                ]);
                $level['room_count'] = is_array($level['room_count']) &&
                    count($level['room_count']) &&
                    isset($level['room_count'][0]) ? (int)$level['room_count'][0] : 0;

                $level['cloneable_destinations'] = json_encode(array_values(array_filter($activeLevels, function ($activeLevel) use ($level) {
                    return (int)$activeLevel !== $level['level'] && (bool)$level['active'];
                })));
                return $level;
            }, $projectLevels);

            $this->data['csrf'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                'project_id' => $this->data['projectId']
            ]);
            
            $this->data['active_levels'] = $activeLevels;
            
            $this->data['projectLevels'] = $projectLevels;

            $this->data['quotationRequest'] = $this->UtilModel->selectQuery('id', 'project_requests', [
                'where' => ['project_id' => $projectId]
            ]);

            $this->data['all_levels_done'] = is_bool(array_search(0, array_column($projectLevels, 'status')));

            $this->data['hasAddedAllPrice'] = false;
            $this->data['projectRoomPrice'] = [];
            $this->data['hasAddedFinalPrice'] = false;
            $this->data['permission'] = $permissions;
            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->load->helper(['utility']);
                $this->data['hasAddedAllPrice'] = $this->projectCheckPrice($projectId);
                $this->data['projectRoomPrice'] = (array)$this->quotationTotalPrice((int)$this->userInfo['user_type'], $projectId);
                $this->data['hasAddedFinalPrice'] = $this->hasTechnicianAddedFinalPrice($projectId);
            }

            $this->data['request_id'] = $requestId;

            website_view('quotes/levels-listing', $this->data);
        } catch (\Exception $error) {
        }
    }

    /**
     * Tco data from room listing 
     *
     * @return void
     */
    public function tco($projectId, $requestId,$level, $projectRoomId)
    { 
        try {
            $obj = require('TcoController.php');
            $obj = new TcoController();
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('basic-with-font-awesome');
            $this->data['js'] = 'tco';

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");

            $obj->validationData = ['project_id' => $projectId, 'level' => $level, 'project_room_id' => $projectRoomId];

            
            $obj->validateTco();

            $status = $obj->validationRun();

           

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER], ['project_add'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'ProjectRoomProducts']);
            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            $levelCheck = $this->UtilModel->selectQuery('id, status', 'project_levels', [
                'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);

           

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            if (empty($levelCheck)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            

            $roomData = $this->UtilModel->selectQuery('*', 'project_rooms', [
                'where' => ['id' => $projectRoomId], 'single_row' => true
            ]);

            $tcoData = $this->UtilModel->selectQuery('*', 'project_room_tco_values', [
                'where' => ['project_room_id' => $projectRoomId], 'single_row' => true
            ]);

            
            if ((int)$roomData['project_id'] !== (int)$projectData['id']) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $this->requestData = $this->input->post();

            $this->requestData = trim_input_parameters($this->requestData, false);
            if (!empty($this->requestData)) {
                $this->tcoFormHandler($this->requestData, (bool)empty($tcoData), $projectRoomId, $projectId, $level,$requestId);
            }

            $productData = $this->UtilModel->selectQuery('lifetime_hours, wattage, system_wattage', 'project_room_products as prp', [
                'where' => ['project_room_id' => $roomData['id']], 
                'join' => ['product_specifications as ps' => 'prp.product_id=ps.product_id AND prp.article_code=ps.articlecode'],
                'single_row' => true
            ]);

            $this->data['tcoData'] = $tcoData;
            $this->data['roomData'] = $roomData;
            $this->data['productData'] = $productData;
            $this->data['request_id'] = $requestId;
            $requestId = encryptDecrypt($requestId, "decrypt");
            
            $this->data['request_status'] = $this->getRequestStatus($requestId);
            

            
            //pr($this->data);
            
            website_view('tco/tco_quotes', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    /**
     * delete quote
     *
     * @return void
     */
    public function delete($requestId)
    {
        $this->load->helper('json_helper');
        
        $this->activeSessionGuard();
        $this->data['userInfo'] = $this->userInfo;
        $this->load->config('css_config');
        $this->data['css'] = $this->config->item('create-project');

        $this->userTypeHandling([INSTALLER], base_url('home/applications'));

        $this->handleEmployeePermission([INSTALLER], ['quote_delete'], base_url('home/applications'));

        $languageCode = "en";


        $requestId = encryptDecrypt($requestId, 'decrypt');
        $this->load->model("UtilModel");

        if (empty($requestId) || !is_numeric($requestId)) {
            show404($this->lang->line('bad_request'), base_url('/home/applications'));
        }

        $quotationData = $this->UtilModel->selectQuery('*', 'project_quotations', [
            'where' => ['request_id' => $requestId, 'language_code' => $languageCode], 'single_row' => true
        ]);

        if (empty($quotationData)) {
            show404($this->lang->line('quote_not_found'), base_url('/home/applications'));
        }

        
        if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
            (int)$this->userInfo['user_id'] !== (int)$quotationData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
            (int)$this->userInfo['company_id'] !== (int)$quotationData['company_id'])) {
            show404($this->lang->line('forbidden_action'), base_url(''));
        }

        

        $this->data['quotesData'] = $quotationData;

        try {
                    $this->load->model("UtilModel");

                    $this->UtilModel->deleteData('project_quotations', [
                        'where' => ['request_id' => $requestId]
                    ]);

                    if ($this->db->trans_status() === true) {
                        $this->db->trans_commit();
                        $this->session->set_flashdata("flash-message", $this->lang->line('quote_deleted'));
                        $this->session->set_flashdata("flash-type", "success");
                        json_dump([
                            'success' => true,
                            'message' => $this->lang->line('quote_deleted')
                        ]);
                        //redirect(base_url("home/projects"));
                    } else {
                        throw new Exception("Something Went Wrong", 500);
                    }
            
            website_map_modal_view("projects/main", $this->data);
        } catch (Exception $ex) {
            $this->db->trans_rollback();
        }
    }

    

    private function tcoFormHandler($requestData, $toInsert, $projectRoomId, $projectId, $level,$requestId)
    {
        $this->tco->setTcoParams($requestData);
        $roi = $this->tco->returnOnInvestment();

        $this->validateTcoForm();

        $status = $this->validationRun();
        
        $tcoData = $requestData;
        $tcoData['company_id'] = $this->userInfo['company_id'];
        
        if ((bool)$status) {
            if ($toInsert) {
                $tcoData['project_room_id']  = $projectRoomId;
                $tcoData['roi'] = $roi;
                
                $this->load->model("ProjectRoomTcoValue");
                $this->ProjectRoomTcoValue->insert($tcoData);
                $this->session->set_flashdata("flash-message", $this->lang->line("tco_done"));
                $this->session->set_flashdata("flash-type", "success");
                
                redirect(base_url('home/quotes/projects/' . encryptDecrypt($projectId).'/'.$requestId . '/levels/' . $level . '/rooms'));
            } else {
                $tcoData['roi'] = $roi;
                $tcoData['updated_at'] = $this->datetime;
                $tcoData['updated_at_timestamp'] = $this->timestamp;
                $this->UtilModel->updateTableData($tcoData, 'project_room_tco_values', [
                    'project_room_id' => $projectRoomId
                ]);
                $this->session->set_flashdata("flash-message", $this->lang->line("tco_done"));
                $this->session->set_flashdata("flash-type", "success");
                redirect(base_url('home/quotes/projects/' . encryptDecrypt($projectId).'/'.$requestId . '/levels/' . $level . '/rooms'));
            }
        }
    }

    public function validateTcoForm()
    {
        $this->form_validation->reset_validation();

        

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'existing_number_of_luminaries',
                'label' => 'Existing number of luminaries',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_wattage',
                'label' => 'Existing wattage',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_led_source_life_time',
                'label' => 'Existing led source life time',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_hours_per_year',
                'label' => 'Existing hours per year',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_energy_price_per_kw',
                'label' => 'Existing energy price per kw',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_number_of_light_source',
                'label' => 'Existing number of light source',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_price_per_light_source',
                'label' => 'Existing price per light source',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_price_to_change_light_source',
                'label' => 'Existing price to change light source',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_number_of_luminaries',
                'label' => 'New number of luminaries',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_wattage',
                'label' => 'New wattageD',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_led_source_life_time',
                'label' => 'New led source life time',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_hours_per_year',
                'label' => 'New hours per year',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_energy_price_per_kw',
                'label' => 'New energy price per kw',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_number_of_light_source',
                'label' => 'New number of light source',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_price_per_light_source',
                'label' => 'New price per light source',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_price_to_change_light_source',
                'label' => 'New price to change light source',
                'rules' => 'trim|required'
            ]
        ]);
    }

    
}
