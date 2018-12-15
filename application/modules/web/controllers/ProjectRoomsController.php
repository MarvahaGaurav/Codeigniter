<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";
require_once APPPATH . "/libraries/Traits/ProjectRequestCheck.php";
require_once APPPATH . "/libraries/Traits/TotalProjectPrice.php";
require_once APPPATH . "/libraries/Traits/TechnicianChargesCheck.php";
require_once APPPATH . "/libraries/Traits/InstallerPriceCheck.php";

class ProjectRoomsController extends BaseController
{
    use ProjectRequestCheck, InstallerPriceCheck, TotalProjectPrice, TechnicianChargesCheck;
    
    /**
     * Post Request Data
     *
     * @var array
     */
    private $postRequest;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    /**
     * Project Room Listing
     *
     * @param string $projectId
     * @param string $level
     * @return void
     */
    public function projectCreateRoomListing($projectId, $level)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('project-level-room-listing');
            $this->data['js'] = 'project-level-room-listing';

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level];

            $this->validateRoomsListing();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));
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

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

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

            website_view('projects/levels_room_list', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('/home/applications'));
        }
    }

    /**
     * Project Room Results results
     *
     * @param string $projectId
     * @param string $level
     * @return void
     */
    public function projectResultRoomListing($projectId, $level)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('project-level-room-listing');
            $this->data['js'] = 'project-level-room-listing';

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level];

            $this->validateRoomsListing();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));
            
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

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

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

            website_view('projects/result_room_list', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('/home/applications'));
        }
    }

    /**
     * Lists Application
     *
     * @return string
     */
    public function applications($projectId, $level)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level];

            $this->validateRoomsListing();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));
            $this->load->model(['UtilModel', 'ProjectRooms']);
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

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $this->load->model('Application');

            $applicationType = $this->input->get("type");

            $params['language_code'] = 'en';
            $params['type'] = APPLICATION_RESIDENTIAL;
            $params['all_data'] = true;
            $params['where']['(EXISTS(SELECT id FROM rooms WHERE application_id=app.application_id))'] = null;

            if (is_numeric($applicationType) &&
                in_array((int)$applicationType, [APPLICATION_PROFESSIONAL, APPLICATION_RESIDENTIAL], true)) {
                $params['type'] = (int)$applicationType;
            }

            $applications = $this->Application->get($params);
            $applications = array_map(function ($application) {
                $application['application_id'] = encryptDecrypt($application['application_id']);
                return $application;
            }, $applications);

            $this->data['applicationChunks'] = array_chunk($applications, 4);
            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            $this->data['type'] = $params['type'];

            website_view('projects/application', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * List Room Type based on applications
     *
     * @param string $projectId
     * @param string $level
     * @param string $applicationId
     * @return void
     */
    public function roomType($projectId, $level, $applicationId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $applicationId = encryptDecrypt($applicationId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'application' => $applicationId];

            $this->validateRoomType();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'Application', 'Room']);

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

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $params['application_id'] = $applicationId;
            $application = $this->Application->details($params);
            if (empty($application)) {
                show404($this->lang->line('bad_request'), base_url('home/applications'));
            }

            $this->data['encrypted_application_id'] = encryptDecrypt($application['application_id']);
            $params['where']['rooms.application_id'] = $applicationId;
            $rooms = $this->Room->get($params);
            $rooms['result'] = array_map(function ($data) {
                $data['encrypted_room_id'] = encryptDecrypt($data['room_id']);
                return $data;
            }, $rooms['result']);

            $rooms['result'] = array_chunk($rooms['result'], 4);
            $this->data['application'] = $application;
            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            $this->data['applicationId'] = encryptDecrypt($applicationId);

            $this->data['roomChunks'] = $rooms['result'];

            website_view('projects/rooms_type', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * Add rooms with dimensions
     */
    public function dimensions($projectId, $level, $applicationId, $roomId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['js'] = 'room_js';

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $applicationId = encryptDecrypt($applicationId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'application' => $applicationId, 'room_id' => $roomId];

            $this->validateDimensionsPage();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view', 'project_add'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'Application', 'Room']);

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

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            if (in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $this->handleRequestCheck($projectId, 'web');
            }

            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->handleTechnicianChargesCheck($projectId, 'web');
            }

            $this->postRequest = $this->input->post();

            $this->data['validation_errors'] = [];
            $this->data['validation_error_keys'] = [];

            $this->load->helper(['cookie']);

            $option = ["room_id" => $roomId, "where" => ["application_id" => $applicationId]];
            $cookie_data = get_cookie("add_room_form_data");
            parse_str($cookie_data, $get_array);

            $this->data['room'] = $this->Room->get($option, true);
            if (!empty($this->postRequest)) {
                $this->roomDimensionPostHandler($this->data['room']);
            }

            $this->data['room_id'] = encryptDecrypt($roomId);
            $this->data['level'] = $level;
            $this->data['units'] = ["Meter", "Inch", "Yard"];
            $this->data['application_id'] = encryptDecrypt($applicationId);
            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['cookie_data'] = $get_array;

            $this->data['selected_product'] = [];

            $selectedProduct = get_cookie('selectd_room');

            $this->data['showSuspensionHeight'] = false;
            if (!empty($selectedProduct)) {
                $this->data['selected_product'] = json_decode($selectedProduct, true);
                if (isset($this->data['selected_product']['product_id'])) {
                    $mountingTypes = $this->UtilModel->selectQuery('type', 'product_mounting_types', [
                        'where' => ['product_id' => $this->data['selected_product']['product_id'], 'type !=' => 0]
                    ]);
                    $mountingTypes = array_column($mountingTypes, 'type');
                    $suspendedFilter = array_filter($mountingTypes, function($type){
                        return in_array((int)$type, [MOUNTING_SUSPENDED, MOUNTING_PENDANT], true);
                    });
                    $this->data['showSuspensionHeight'] = (bool)!empty($suspendedFilter);
                }
            }

            

            website_view('projects/add_room_dimensions', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * Room Dimension edit
     *
     * @param integer $projectId
     * @param integer $level
     * @return void
     */
    public function editDimensions($projectId, $level, $projectRoomId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['js'] = 'room_edit';

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'project_room_id' => $projectRoomId];

            $this->validateEditDimensionsPage();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view, project_edit'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'ProjectRoomProducts', 'Room']);

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

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            if (in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $this->handleRequestCheck($projectId, 'web');
            }

            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->handleTechnicianChargesCheck($projectId, 'web');
            }

            $this->postRequest = $this->input->post();

            $this->data['validation_errors'] = [];
            $this->data['validation_error_keys'] = [];

        // if (!empty($this->postRequest)) {
        //     $this->roomDimensionPostHandler($roomData);
        // }

            $this->load->helper(['cookie']);

            $option = [
                'where' => ['id' => $projectRoomId]
            ];
            $get_array = [];
            $cookie_data = get_cookie("edit_room_form_data_" + $projectRoomId);
            parse_str($cookie_data, $get_array);

            $this->data['room'] = $this->ProjectRooms->details($option);
            if (empty($this->data['room'])) {
                show404($this->lang->line('no_data_found'), base_url(''));
            }

            $roomData = $this->Room->get([
                'room_id' => $this->data['room']['room_id']
            ], true);

            $this->data['roomProducts'] = $this->ProjectRoomProducts->get([
                'where' => ['project_room_id' => $this->data['room']['project_room_id'], 'prs.type' => ROOM_MAIN_PRODUCT]
            ]);

            $this->data['roomProducts'] = isset($this->data['roomProducts']['data'], $this->data['roomProducts']['data'][0]) ?
                $this->data['roomProducts']['data'][0] : [];

            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            $this->data['units'] = ["Meter", "Inch", "Yard"];
            if (!empty($this->postRequest)) {
                // pd($this->postRequest);
                $this->editRoomDimensionPostHandler($roomData, $projectRoomId, $this->data['projectId']);
            }

            $productIdToCheck = isset($this->data['roomProducts']['product_id'])?$this->data['roomProducts']['product_id']:'';

            $this->data['cookie_data'] = $get_array;

            $this->data['selected_product'] = [];

            $selectedProduct = get_cookie('project_selected_product_' . $projectRoomId);
            if (!empty($selectedProduct)) {
                $this->data['selected_product'] = json_decode($selectedProduct, true);
                if (isset($this->data['selected_product']['product_id'])) {
                    $productIdToCheck = $this->data['selected_product']['product_id'];
                }
            }

            $mountingTypes = $this->UtilModel->selectQuery('type', 'product_mounting_types', [
                'where' => ['product_id' => $productIdToCheck, 'type !=' => 0]
            ]);
            $mountingTypes = array_column($mountingTypes, 'type');
            $suspendedFilter = array_filter($mountingTypes, function($type){
                return in_array((int)$type, [MOUNTING_SUSPENDED, MOUNTING_PENDANT], true);
            });
            $this->data['showSuspensionHeight'] = (bool)!empty($suspendedFilter);

            website_view('projects/edit_room_dimensions', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * Room Dimensions post handler
     *
     * @param [type] $projectId
     * @param [type] $level
     * @param [type] $roomId
     * @return void
     */
    private function roomDimensionPostHandler($room)
    {
        $projectId = '';
        if (isset($this->postRequest['project_id'])) {
            $projectId = $this->postRequest['project_id'];
            $this->postRequest['project_id'] = encryptDecrypt($this->postRequest['project_id'], 'decrypt');
        }
        if (isset($this->postRequest['room_id'])) {
            $this->postRequest['room_id'] = encryptDecrypt($this->postRequest['room_id'], 'decrypt');
        }
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($this->postRequest);

        $this->form_validation->set_rules($this->validateRoomDimensionForm());

        $validData = (bool)$this->form_validation->run();

        $uld = "";
        if (isset($this->postRequest['product_id'], $this->postRequest['article_code'])) {
            $uld = $this->UtilModel->selectQuery('uld', 'product_specifications', [
                'where' => [
                    'product_id' => $this->postRequest['product_id'],
                    'articlecode' => $this->postRequest['article_code']
                ],
                'single_row' => true
            ]);

            $uld = $uld['uld'];
        }
        if (empty($uld)) {
            $this->session->set_flashdata("flash-message", $this->lang->line('we_are_unable_to_get_data'));
            $this->session->set_flashdata("flash-type", "danger");
            redirect(base_url(uri_string()));
        }

        if ($validData) {
            $this->load->helper(['utility', 'input_data', 'quick_calc']);
            $length = convert_to_meter($this->postRequest['length_unit'], $this->postRequest['length']);
            $width = convert_to_meter($this->postRequest['width_unit'], $this->postRequest['width']);
            $height = convert_to_meter($this->postRequest['height_unit'], $this->postRequest['height']);
            $this->postRequest = trim_input_parameters($this->postRequest, false);
            $insert = [
                "project_id" => $this->postRequest['project_id'],
                // "application_id" => $this->postRequest['application_id'],
                "reference_number" => $this->postRequest['reference_number'],
                "reference_name" => $this->postRequest['reference_name'],
                "room_id" => $this->postRequest['room_id'],
                "name" => $this->postRequest['name'],
                "level" => $this->postRequest['level'],
                "reference_name" => $this->postRequest['reference_name'],
                "reference_number" => $this->postRequest['reference_number'],
                "length" => $length,
                "width" => $width,
                "height" => $height,
                "maintainance_factor" => isset($this->postRequest['maintainance_factor']) && !empty($this->postRequest['maintainance_factor']) ? $this->postRequest['maintainance_factor'] : $room['maintainance_factor'],
                "shape" => "Rectangular",
                "suspension_height" => isset($this->postRequest['pendant_length']) ? convert_to_meter($this->postRequest['pendant_length_unit'], $this->postRequest['pendant_length']) : 0.00,
                "working_plane_height" => isset($this->postRequest['room_plane_height']) ? $this->postRequest['room_plane_height'] / 100 : 0.00, //need to confirm
                "rho_wall" => isset($this->postRequest['rho_wall']) && !empty($this->postRequest['rho_wall']) ? $this->postRequest['rho_wall'] : $room['reflection_values_wall'],
                "rho_ceiling" => isset($this->postRequest['rho_ceiling']) && !empty($this->postRequest['rho_ceiling']) ? $this->postRequest['rho_ceiling'] : $room['reflection_values_ceiling'],
                "rho_floor" => isset($this->postRequest['rho_floor']) && !empty($this->postRequest['rho_floor']) ? $this->postRequest['rho_floor'] : $room['reflection_values_floor'],
                "lux_value" => isset($this->postRequest['lux_values']) && !empty($this->postRequest['lux_values']) ? $this->postRequest['lux_values'] : $room['lux_values'],
                "luminaries_count_x" => $this->postRequest['room_luminaries_x'],
                "luminaries_count_y" => $this->postRequest['room_luminaries_y'],
                "fast_calc_response" => '',
                "created_at" => $this->datetime,
                'created_at_timestamp' => $this->timestamp,
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => $this->timestamp
            ];

            $response = $this->fetchQuickCalcData($insert, $uld);
            $decodedResponse = json_decode($response, true);
            if (!isset($decodedResponse['projectionTop'], $decodedResponse['projectionSide'], $decodedResponse['projectionFront'])) {
                $this->session->set_flashdata("flash-message", $this->lang->line('we_are_unable_to_get_data'));
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url(uri_string()));
            }

            $insert['fast_calc_response'] = $response;
            $insert['side_view'] = $decodedResponse['projectionSide'];
            $insert['top_view'] = $decodedResponse['projectionTop'];
            $insert['front_view'] = $decodedResponse['projectionFront'];

            $projectRoomId = $this->UtilModel->insertTableData($insert, 'project_rooms', true);
            $this->UtilModel->insertTableData([
                'project_room_id' => $projectRoomId,
                'mounting_type' => $this->postRequest['type'],
                'type' => PROJECT_ROOM_MAIN_PRODUCT,
                'product_id' => $this->postRequest['product_id'],
                'article_code' => $this->postRequest['article_code']
            ], 'project_room_products');

            delete_cookie('selectd_room');
            delete_cookie('add_room_form_data');

            $this->session->set_flashdata("flash-message", $this->lang->line("room_added"));
            $this->session->set_flashdata("flash-type", "success");
            redirect(base_url('home/projects/' . $projectId . '/levels/' . $this->postRequest['level'] . '/rooms'));
        } else {
            $this->data['validation_errors'] = $this->form_validation->error_array();
            $this->data['validation_error_keys'] = array_keys($this->data['validation_errors']);
        }
    }

    /**
     * Room Dimensions post handler
     *
     * @param [type] $projectId
     * @param [type] $level
     * @param [type] $roomId
     * @return void
     */
    private function editRoomDimensionPostHandler($room, $projectRoomId, $projectId)
    {
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($this->postRequest);

        $this->form_validation->set_rules($this->validateEditRoomDimensionForm());

        $validData = (bool)$this->form_validation->run();

        $uld = "";
        if (isset($this->postRequest['product_id'], $this->postRequest['article_code'])) {
            $uld = $this->UtilModel->selectQuery('uld', 'product_specifications', [
                'where' => [
                    'product_id' => $this->postRequest['product_id'],
                    'articlecode' => $this->postRequest['article_code']
                ],
                'single_row' => true
            ]);

            $uld = $uld['uld'];
        }

        if (empty($uld)) {
            $this->session->set_flashdata("flash-message", $this->lang->line('we_are_unable_to_get_data'));
            $this->session->set_flashdata("flash-type", "danger");
            redirect(base_url(uri_string()));
        }

        if ($validData) {
            $this->load->helper(['utility', 'input_data', 'quick_calc']);
            $length = convert_to_meter($this->postRequest['length_unit'], $this->postRequest['length']);
            $width = convert_to_meter($this->postRequest['width_unit'], $this->postRequest['width']);
            $height = convert_to_meter($this->postRequest['height_unit'], $this->postRequest['height']);
            $this->postRequest = trim_input_parameters($this->postRequest, false);
            $update = [
                "name" => $this->postRequest['name'],
                "length" => $length,
                "width" => $width,
                "height" => $height,
                "reference_number" => $this->postRequest['reference_number'],
                "reference_name" => $this->postRequest['reference_name'],
                "maintainance_factor" => isset($this->postRequest['maintainance_factor']) && !empty($this->postRequest['maintainance_factor']) ? $this->postRequest['maintainance_factor'] : $room['maintainance_factor'],
                "suspension_height" => isset($this->postRequest['pendant_length']) ? convert_to_meter($this->postRequest['pendant_length_unit'], $this->postRequest['pendant_length']) : 0.00,
                "shape" => isset($this->postRequest['room_shape']) ? $this->postRequest['room_shape'] : "Rectangular",
                "working_plane_height" => isset($this->postRequest['room_plane_height']) ? $this->postRequest['room_plane_height'] / 100 : 0.00, //need to confirm
                "rho_wall" => isset($this->postRequest['rho_wall']) && !empty($this->postRequest['rho_wall']) ? $this->postRequest['rho_wall'] : $room['reflection_values_wall'],
                "rho_ceiling" => isset($this->postRequest['rho_ceiling']) && !empty($this->postRequest['rho_ceiling']) ? $this->postRequest['rho_ceiling'] : $room['reflection_values_ceiling'],
                "rho_floor" => isset($this->postRequest['rho_floor']) && !empty($this->postRequest['rho_floor']) ? $this->postRequest['rho_floor'] : $room['reflection_values_floor'],
                "lux_value" => isset($this->postRequest['lux_values']) && !empty($this->postRequest['lux_values']) ? $this->postRequest['lux_values'] : $room['lux_values'],
                "luminaries_count_x" => $this->postRequest['room_luminaries_x'],
                "luminaries_count_y" => $this->postRequest['room_luminaries_y'],
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => $this->timestamp
            ];

            $response = $this->fetchQuickCalcData($update, $uld);
            $decodedResponse = json_decode($response, true);
            if (!isset($decodedResponse['projectionTop'], $decodedResponse['projectionSide'], $decodedResponse['projectionFront'])) {
                $this->session->set_flashdata("flash-message", $this->lang->line('we_are_unable_to_get_data'));
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url(uri_string()));
            }

            $update['fast_calc_response'] = $response;
            $update['side_view'] = $decodedResponse['projectionSide'];
            $update['top_view'] = $decodedResponse['projectionTop'];
            $update['front_view'] = $decodedResponse['projectionFront'];

            $this->UtilModel->updateTableData($update, 'project_rooms', [
                'id' => $projectRoomId
            ]);

            $this->UtilModel->updateTableData([
                'product_id' => $this->postRequest['product_id'],
                'mounting_type' => $this->postRequest['type'],
                'article_code' => $this->postRequest['article_code']
            ], 'project_room_products', [
                'type' => PROJECT_ROOM_MAIN_PRODUCT, 'project_room_id' => $projectRoomId
            ]);

            delete_cookie('edit_room_form_data_' + $projectRoomId);
            delete_cookie('project_selected_product_' + $projectRoomId);

            $this->session->set_flashdata("flash-message", $this->lang->line("room_updated"));
            $this->session->set_flashdata("flash-type", "success");
            redirect(base_url('home/projects/' . $projectId . '/levels/' . $this->postRequest['level'] . '/rooms'));
        } else {
            $this->data['validation_errors'] = $this->form_validation->error_array();
            $this->data['validation_error_keys'] = array_keys($this->data['validation_errors']);
        }
    }

    /**
     * Validate room Listing
     *
     * @return void
     */
    public function validateRoomsListing()
    {
        
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    /**
     * Validate room type listing
     *
     * @return void
     */
    private function validateRoomType()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'application',
                'label' => 'Application',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    /**
     * Validate room type listing
     *
     * @return void
     */
    private function validateDimensionsPage()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'application',
                'label' => 'Application',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'room_id',
                'label' => 'Application',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }
    /**
     * Validate room type listing
     *
     * @return void
     */
    private function validateEditDimensionsPage()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'project_room_id',
                'label' => 'Project Room ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    /**
     * Validate data to be inserted into project rooms
     *
     * @return array
     */
    private function validateRoomDimensionForm()
    {
        return [
            [
                'field' => "name",
                'label' => "Name",
                'rules' => 'trim|required'
            ],
            [
                'field' => "room_id",
                'label' => "Room id",
                'rules' => 'trim|required'
            ],
            [
                'field' => "reference_name",
                'label' => "Room Name",
                'rules' => 'trim|alpha_numeric_spaces|max_length[50]'
            ],
            [
                'field' => "reference_number",
                'label' => "Room Number",
                'rules' => 'trim|alpha_numeric|max_length[50]'
            ],
            [
                'field' => "length",
                'label' => "Length",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "length_unit",
                'label' => "Length unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "width",
                'label' => "Width",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "width_unit",
                'label' => "Width unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "height",
                'label' => "Height",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "height_unit",
                'label' => "Height unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "room_plane_height",
                'label' => "Room plane height",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "room_plane_height_unit",
                'label' => "Room plane height unit",
                'rules' => 'trim|required|regex_match[/^(cms)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "room_luminaries_x",
                'label' => "Room luminaries x",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "room_luminaries_y",
                'label' => "Room luminaries y",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "pendant_length",
                'label' => "Pendant length",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "pendant_length_unit",
                'label' => "Pendant length unit",
                'rules' => 'trim|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "rho_wall",
                'label' => "Rho wall",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "rho_ceiling",
                'label' => "Rho ceiling",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "rho_floor",
                'label' => "Rho floor",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "maintainance_factor",
                'label' => "Maintainance factor",
                'rules' => 'trim|required|numeric'
            ], [
                'field' => "lux_values",
                'label' => "Lux values",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "project_id",
                'label' => "Project id",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "room_id",
                'label' => "Room id",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            // [
            //     'field' => "application_id",
            //     'label' => "Application id",
            //     'rules' => 'trim|required|is_natural_no_zero'
            // ],
            [
                'field' => "level",
                'label' => "Level",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "article_code",
                'label' => "Article code",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "type",
                'label' => "Type",
                'rules' => 'trim|required|regex_match[/^(1|2|3|4|5|6|7)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line("bad_request")
                ]
            ],
            [
                'field' => "product_id",
                'label' => "Product id",
                'rules' => 'trim|required'
            ],
        ];
    }

    /**
     * Validate data to be inserted into project rooms
     *
     * @return array
     */
    private function validateEditRoomDimensionForm()
    {
        return [
            [
                'field' => "name",
                'label' => "Name",
                'rules' => 'trim|required'
            ],
            [
                'field' => "length",
                'label' => "Length",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "reference_name",
                'label' => "Room Name",
                'rules' => 'trim|alpha_numeric_spaces|max_length[50]'
            ],
            [
                'field' => "reference_number",
                'label' => "Room Number",
                'rules' => 'trim|alpha_numeric|max_length[50]'
            ],
            [
                'field' => "length_unit",
                'label' => "Length unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "width",
                'label' => "Width",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "width_unit",
                'label' => "Width unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "height",
                'label' => "Height",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "height_unit",
                'label' => "Height unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "room_plane_height",
                'label' => "Room plane height",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "room_plane_height_unit",
                'label' => "Room plane height unit",
                'rules' => 'trim|required|regex_match[/^(cms)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "room_luminaries_x",
                'label' => "Room luminaries x",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "room_luminaries_y",
                'label' => "Room luminaries y",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "pendant_length",
                'label' => "Pendant length",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "pendant_length_unit",
                'label' => "Pendant length unit",
                'rules' => 'trim|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "rho_wall",
                'label' => "Rho wall",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "rho_ceiling",
                'label' => "Rho ceiling",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "rho_floor",
                'label' => "Rho floor",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "maintainance_factor",
                'label' => "Maintainance factor",
                'rules' => 'trim|required|numeric'
            ], [
                'field' => "lux_values",
                'label' => "Lux values",
                'rules' => 'trim|numeric'
            ],
            // [
            //     'field' => "application_id",
            //     'label' => "Application id",
            //     'rules' => 'trim|required|is_natural_no_zero'
            // ],
            [
                'field' => "level",
                'label' => "Level",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "article_code",
                'label' => "Article code",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "type",
                'label' => "Type",
                'rules' => 'trim|required|regex_match[/^(1|2|3|4|5|6|7)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line("bad_request")
                ]
            ],
            [
                'field' => "product_id",
                'label' => "Product id",
                'rules' => 'trim|required'
            ],
        ];
    }


    /**
     * Fetch quick calc
     *
     * @return void
     */
    private function fetchQuickCalcData($data, $uld)
    {
        $request_data = [
            "authToken" => "28c129e0aca88efb6f29d926ac4bab4d",
            "roomLength" => floatval($data['length']),
            "roomWidth" => floatval($data['width']),
            "roomHeight" => floatval($data['height']),
            "roomType" => $data['name'],
            "workingPlaneHeight" => floatval($data['working_plane_height']),
            "suspension" => isset($data['suspension_height']) ? floatval($data['suspension_height']) : 0,
            "illuminance" => $data['lux_value'],
            "luminaireCountInX" => floatval($data['luminaries_count_x']),
            "luminaireCountInY" => floatval($data['luminaries_count_y']),
            "rhoCeiling" => floatval($data['rho_ceiling']),
            "rhoWall" => floatval($data['rho_wall']),
            "rhoFloor" => floatval($data['rho_floor']),
            "maintenanceFactor" => floatval($data['maintainance_factor']),
            "uldUri" => $uld
        ];

        $response = hitCulrQuickCal($request_data);

        return $response;
    }
}
