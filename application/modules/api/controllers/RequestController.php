<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class RequestController extends BaseController
{

    /**
     * Request Data
     *
     * @var array
     */
    private $requestData;

    /**
     * Products array
     *
     * @var array
     */
    private $products;

    /**
     * DB params
     */
    private $params;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    /**
     * @SWG\Get(path="/requests",
     *   tags={"Requests & Quotations"},
     *   summary="Request Listing (Awaiting Requests)",
     *   description="List projects posted by current user",
     *   operationId="requests_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     description="1 - Awaiting, 2-Submitted, 3-Approved Required only for technician types",
     *     type="string",
     *   ),
     *  @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string",
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="Data not found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function index_get()
    {
        try {
            $user_data = $this->accessTokenCheck('u.company_id, u.user_lat, u.user_long, u.user_type, u.is_owner');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->requestData = $this->get();

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER]);

            $this->handleEmployeePermission([INSTALLER], ['quote_view']);

            $this->params['offset'] =
                isset($this->requestData['offset'])&&
                is_numeric($this->requestData['offset'])&&
                (int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset']: 0;

            $this->params['limit'] = API_RECORDS_PER_PAGE;
            $this->params['language_code'] = $language_code;

            if (in_array((int)$user_data['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $this->params['user_id'] = $this->user['user_id'];
                $data = $this->customerRequestData();
            } elseif ((int)$user_data['user_type'] === INSTALLER) {
                $this->validateRequestList();

                $this->validationRun();

                $data = $this->technicianRequestData();
            } else {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('forbidden_action')
                ]);
            }

            if (empty($data['data'])) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$data['count'] > ($this->params['offset'] + $this->params['limit'])) {
                $hasMorePages = true;
                $nextCount = $this->params['offset'] + $this->params['limit'];
            }

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_listed'),
                'data' => $data['data'],
                'next_count' => $nextCount,
                'has_more_pages' => $hasMorePages,
                'per_page_count' => $this->params['limit'],
                'total' => $data['count']
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * @SWG\Get(path="/installers/companies",
     *   tags={"User"},
     *   summary="Fetch installers companies",
     *   description="Fetches list of installers companies based on users current location",
     *   operationId="installers_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="project_id",
     *     in="query",
     *     description="Project ID - search will be based on project location",
     *     type="string",
     *     required=true
     *   ),
     *  @SWG\Parameter(
     *     name="search_radius",
     *     in="query",
     *     description="Maxiumum Search Radius for which the installers will be fetched",
     *     type="string",
     *   ),
     *  @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     description="search key",
     *     type="string",
     *   ),
     *  @SWG\Parameter(
     *     name="company_id",
     *     in="query",
     *     description="search key",
     *     type="string",
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="Data not found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function installerCompanies_get()
    {
        try {
            $user_data = $this->accessTokenCheck('u.company_id, u.user_lat, u.user_long, u.user_type, u.is_owner');
            $language_code = $this->langcode_validate();

            $this->requestData = $this->get();

            $this->validateInstallerCompanies();

            $this->validationRun();

            $searchRadius =
                isset($this->requestData['search_radius'])&&(double)$this->requestData['search_radius'] > 0 ?
                (double)$this->requestData['search_radius']:REQUEST_SEARCH_RADIUS;
            
            $search = isset($this->requestData['search'])&&strlen(trim($this->requestData['search'])) > 0?
                trim($this->requestData['search']):'';

            $projectData = $this->UtilModel->selectQuery('lat, lng', 'projects', [
                'where' => ['id' => $this->requestData['project_id']],
                'single_row' => true
            ]);

            if (empty($projectData)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $this->load->model('User');

            $params['lat'] = $projectData['lat'];
            $params['lng'] = $projectData['lng'];
            $params['search_radius'] = $searchRadius;

            if (!empty($search)) {
                $params['where']["(users.first_name LIKE '%{$search}%' OR users.email LIKE '%{$search}%' OR company_name LIKE '%{$search}%')"] = null;
            }

            if (isset($this->requestData['company_id']) && is_numeric($this->requestData['company_id'])) {
                $params['where']['company.company_id'] = (int)$this->requestData['company_id'];
            }

            $data = $this->User->installers($params);

            if (empty($data)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('installers_found'),
                'data' => $data
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Technician request data
     *
     * @return array
     */
    private function technicianRequestData()
    {
        $data = [];
        $this->load->model('ProjectRequest');
        
        $this->params['offset'] =
                isset($this->requestData['offset'])&&is_numeric($this->requestData['offset'])&&(int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset']: 0;
        if ((int)$this->requestData['type'] === AWAITING_REQUEST) {
            $this->params['lat'] = $this->user['user_lat'];
            $this->params['lng'] = $this->user['user_long'];
            $this->params['company_id'] = $this->user['company_id'];

            $data = $this->ProjectRequest->awaitingRequest($this->params);
        } elseif ((int)$this->requestData['type'] === QUOTED_REQUEST) {
            $this->params['company_id'] = $this->user['company_id'];
            $data = $this->ProjectRequest->quotedRequestList($this->params);
            $data['data'] = $this->parseRequestPriceData($data['data']);
        } elseif ((int)$this->requestData['type'] === APPROVED_REQUEST) {
            $this->params['company_id'] = $this->user['company_id'];
            $data = $this->ProjectRequest->acceptedRequestList($this->params);
            $data['data'] = $this->parseRequestPriceData($data['data']);
        }

        return $data;
    }

    /**
     * Parse price data
     *
     * @param array $data
     * @return array
     */
    private function parseRequestPriceData($data)
    {
        $this->load->helper('utility');
        $data = array_map(function ($request) {
            if (empty($request['price'])) {
                $request['price'] = [];
                $request['price']['price_per_luminaries'] = 0.00;
                $request['price']['installation_charges'] = 0.00;
                $request['price']['discount_price'] = 0.00;
            } else {
                $request['price'] = json_decode($request['price'], true);
            }
            $request['price']['additional_product_charges'] = (double)$request['additional_product_charges'];
            $request['price']['discount'] = (double)$request['discount'];
            $request['price']['accessory_product_charge'] = 0.00;
            $request['price']['main_product_charge'] = 0.00;
            $request['price']['total'] = get_percentage(
                ($request['price']['main_product_charge']
                + $request['price']['accessory_product_charge']
                + $request['price']['price_per_luminaries']
                + $request['price']['installation_charges']
                + $request['price']['additional_product_charges']),
                $request['price']['discount_price']
            );
            unset($request['additional_product_charges'], $request['discount']);
            return $request;
        }, $data);

        return $data;
    }

    /**
     * Validate Levels Listing
     *
     * @return void
     */
    private function validateInstallerCompanies()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'label' => 'Project',
                'field' => 'project_id',
                'rules' => 'trim|required|is_natural_no_zero',
                'errors' => [
                    'required' => '%s is required'
                ]
            ]
        ]);
    }

    /**
     * Customer request data
     *
     * @return void
     */
    private function customerRequestData()
    {
        $this->load->model('ProjectRequest');
        $data = $this->ProjectRequest->customerRequests($this->params);

        return $data;
    }

    /**
     * Validate request
     *
     * @return void
     */
    private function validateRequestList()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules('type', 'Type', 'trim|required|regex_match[/^(1|2|3)$/]', [
            'regex_match' => $this->lang->line('invalid_request_list_type')
        ]);
    }
}
