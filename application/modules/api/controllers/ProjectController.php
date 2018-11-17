<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class ProjectController extends BaseController
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

    public function __construct()
    {
        error_reporting(-1);
        ini_set('display_errors', 1);
        parent::__construct();
        $this->load->library('form_validation');
    }

    /**
     * Save project created by user
     *
     * @return string
     */
    /**
     * @SWG\Post(path="/projects",
     *   tags={"Projects"},
     *   summary="Add project",
     *   description="Add projects",
     *   operationId="projects_post",
     *   consumes ={"multipart/form-data"},
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
     * @SWG\Parameter(
     *     name="number",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="installer_id",
     *     in="formData",
     *     description="Required for Installer Owner type",
     *     type="string",
     *   ),
     * @SWG\Parameter(
     *     name="levels",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="address",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="lat",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="lng",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function index_post()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_add']);

            $this->requestData = $this->post();

            $this->validateProject();

            $this->validationRun();

            $this->requestData = trim_input_parameters($this->requestData, false);

            $project = [
                'language_code' => $language_code,
                'user_id' => $user_data['user_id'],
                'number' => $this->requestData['number'],
                'name' => $this->requestData['name'],
                'levels' => $this->requestData['levels'],
                'address' => $this->requestData['address'],
                'lat' => $this->requestData['lat'],
                'lng' => $this->requestData['lng'],
                'created_at' => $this->datetime,
                'created_at_timestamp' => time(),
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => time()
            ];

            if (in_array((int)$user_data['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true)) {
                $project['company_id'] = $user_data['company_id'];
            }
            
            if ((int)$user_data['user_type'] === INSTALLER && (int)$user_data['is_owner'] === ROLE_OWNER && isset($this->requestData['installer_id'])) {
                $project['installer_id'] = $this->requestData['installer_id'];
            }

            $this->db->trans_begin();
            $projectId = $this->UtilModel->insertTableData($project, 'projects', true);
            
            $levelsCount = (int)$this->requestData['levels'];
            $levelsData = [];
            foreach (range(1, $levelsCount) as $key => $level) {
                $levelsData[$key] = [
                    'project_id' => $projectId,
                    'level' => $level
                ];
            }
            
            $this->UtilModel->insertBatch('project_levels', $levelsData);
            
            $this->db->trans_commit();
            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_added'),
                'data' => [
                    'project_id' => $projectId
                ]
            ]);
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }


    /**
     * Save project created by user
     *
     * @return string
     */
    /**
     * @SWG\Put(path="/projects",
     *   tags={"Projects"},
     *   summary="Edit project",
     *   description="Edit projects",
     *   operationId="projects_put",
     *   consumes ={"multipart/form-data"},
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
     * @SWG\Parameter(
     *     name="project_id",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="number",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="levels",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="address",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="lat",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="lng",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function index_put()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);
            
            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_edit']);

            $this->requestData = $this->put();

            $this->validateProject(true);

            if (! (bool) $this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($errorMessage),
                ]);
            }

            $this->requestData = trim_input_parameters($this->requestData, false);

            $projectData = $this->UtilModel->selectQuery('user_id, company_id', 'projects', [
                'where' => ['id' => $this->requestData['project_id']],
                'single_row' => true
            ]);

            if (empty($projectData)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }
            
            $isOwnProject = false;
            if (in_array((int)$user_data['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $isOwnProject = (int)$user_data['user_id'] === (int)$projectData['user_id'];
            } else {
                $isOwnProject = (int)$user_data['company_id'] === (int)$projectData['company_id'];
            }

            if (!$isOwnProject) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('forbidden_action')
                ]);
            }

            $project = [
                'user_id' => $user_data['user_id'],
                'number' => $this->requestData['number'],
                'name' => $this->requestData['name'],
                'levels' => $this->requestData['levels'],
                'address' => $this->requestData['address'],
                'lat' => $this->requestData['lat'],
                'lng' => $this->requestData['lng'],
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => time()
            ];

            if ((int)$user_data['user_type'] === INSTALLER && (int)$user_data['is_owner'] === ROLE_OWNER && isset($this->requestData['installer_id'])) {
                $project['installer_id'] = $this->requestData['installer_id'];
            }

            $projectId = $this->UtilModel->updateTableData($project, 'projects', [
                'id' => $this->requestData['project_id']
            ]);

            $project['project_id'] = $this->requestData['project_id'];

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_updated'),
                'data' => $project
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
     * Add project rooms
     *
     * @return string
     */
    public function projectRooms_post()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);
            
            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_add']);

            $this->requestData = $this->post();

            if (empty($this->requestData)) {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('bad_request'),
                ]);
            }

            $this->validateRooms();

            if (! (bool) $this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($errorMessage),
                ]);
            }
            
            $this->products = array_column($this->requestData, 'calcProduct');

            $this->validateRoomProducts();

            if (! (bool) $this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($errorMessage),
                ]);
            }

            $projectIds = array_column($this->requestData, 'projectId');

            $check = $this->UtilModel->selectQuery('id', 'projects', [
                'where_in' => ['id' => $projectIds],
                'single_row' => true
            ]);

            if (empty($check)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('project_not_found')
                ]);
            }

            $this->requestData = trim_input_parameters($this->requestData, false);
            $this->products = trim_input_parameters($this->products, false);

            $roomsData = array_map(function ($room) use ($language_code) {
                $data['language_code'] = $language_code;
                $data['project_id'] = $room['projectId'];
                $data['suspension_height'] = isset($room['suspensionHeight'])?round((double)$room['suspensionHeight'],2):0.00;
                $data['level'] = $room['level'];
                // $data['application_id'] = $room['applicationId'];
                $data['room_id'] = $room['roomId'];
                $data['name'] = $room['name'];
                $data['length'] = $room['length'];
                $data['width'] = $room['width'];
                $data['height'] = $room['height'];
                $data['maintainance_factor'] = $room['maintainanceFactor'];
                $data['shape'] = isset($room['shape'])?$room['shape']:'';
                $data['working_plane_height'] = isset($room['workingPlaneHeight'])?$room['workingPlaneHeight']:0.00;
                $data['rho_wall'] = isset($room['rhoWall'])?$room['rhoWall']:0.00;
                $data['rho_ceiling'] = isset($room['rhoCeiling'])?$room['rhoCeiling']:0.00;
                $data['rho_floor'] = isset($room['rhoFloor'])?$room['rhoFloor']:0.00;
                $data['lux_value'] = isset($room['luxValue'])?$room['luxValue']:0.00;
                $data['luminaries_count_x'] = $room['luminariesCountX'];
                $data['luminaries_count_y'] = $room['luminariesCountY'];
                $data['fast_calc_response'] = isset($room['fastCalcResponse'])?$room['fastCalcResponse']:'';
                $fastCalcResponse = json_decode($data['fast_calc_response'], true);
                if (json_last_error() == JSON_ERROR_NONE || is_array($fastCalcResponse)) {
                    $data['side_view'] = $fastCalcResponse['projectionSide'];
                    $data['top_view'] = $fastCalcResponse['projectionTop'];
                    $data['front_view'] = $fastCalcResponse['projectionFront'];
                }
                $data['created_at'] = $this->datetime;
                $data['created_at_timestamp'] = time();
                $data['updated_at'] = $this->datetime;
                $data['updated_at_timestamp'] = time();
                return $data;
            }, $this->requestData);

            // $this->UtilModel->insertBatch('project_rooms', $roomsData);
            $productData = [];
            $this->db->trans_begin();
            foreach ($roomsData as $room) {
                $roomId = $this->UtilModel->insertTableData($room, 'project_rooms', true);
                $product = array_shift($this->products);
                $productData[] = [
                    'project_room_id' => $roomId,
                    'article_code' => $product['articleCode'],
                    'product_id' => $product['productId'],
                    'type' => ROOM_MAIN_PRODUCT
                ];
            }
            $this->UtilModel->insertBatch('project_room_products', $productData);

            $this->db->trans_commit();
            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('room_added')
            ]);
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    /**
     * @SWG\Post(path="/projects/quotation-request",
     *   tags={"Projects"},
     *   summary="Send Quotation Request",
     *   description="On selection user sends his project for quotation request",
     *   operationId="projects_quotation_request_post",
     *   consumes ={"multipart/form-data"},
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
     * @SWG\Parameter(
     *     name="project_id",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="company_id[]",
     *     in="formData",
     *     description="Company Ids",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function sendQuotationRequest_post()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([PRIVATE_USER, BUSINESS_USER]);
            
            $this->requestData = $this->post();
            
            $this->validateSendQuotation();

            $this->validationRun();

            if (!is_array($this->requestData['company_id']) ||
                empty($this->requestData['company_id']) ||
                count($this->requestData['company_id']) > MAXIMUM_REQUEST_COUNTS_PER_PROJECT
            ) {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('invalid_request')
                ]);
            }

            $this->load->model(['ProjectLevel', 'ProjectRooms']);

            $projectLevel = $this->ProjectLevel->projectLevelData([
                'where' => ['project_id' => $this->requestData['project_id']]
            ], 'level');
            $projectRooms = $this->ProjectRooms->roomsData([
                'where' => ['project_id' => $this->requestData['project_id']],
                'group_by' => ['level']
            ], 'level');

            $projectLevel = array_column($projectLevel, 'level');
            $projectRooms = array_column($projectRooms, 'level');

            $pendingLevels = array_diff($projectLevel, $projectRooms);

            if (!empty($pendingLevels)) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('add_rooms_to_all_levels')
                ]);
            }

            $this->db->trans_begin();
            $requestId = $this->UtilModel->insertTableData([
                'language_code' => $language_code,
                'project_id' => $this->requestData['project_id'],
                'created_at' => $this->datetime,
                'created_at_timestamp' => time(),
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => time()
            ], 'project_requests', true);

            $requestData = array_map(function ($companyId) use ($requestId) {
                $data['request_id'] = $requestId;
                $data['company_id'] = $companyId;
                $data['created_at'] = $this->datetime;
                $data['created_at_timestamp'] = time();
                return $data;
            }, $this->requestData['company_id']);

            $this->UtilModel->insertBatch('project_request_installers', $requestData);

            $this->db->trans_commit();
            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('quotation_sent')
            ]);
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            $this->response([
            'code' => HTTP_INTERNAL_SERVER_ERROR,
            'api_code_result' => 'INTERNAL_SERVER_ERROR',
            'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Save project created by user
     *
     * @return string
     */
    /**
     * @SWG\Get(path="/projects",
     *   tags={"Projects"},
     *   summary="List project",
     *   description="List projects posted by current user",
     *   operationId="projects_get",
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
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string",
     *     required = true
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
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $permissions = $this->handleEmployeePermission([WHOLESALER, ELECTRICAL_PLANNER], ['project_view']);

            $this->load->model("Project");
            $get = $this->get();
            $params['offset'] =
                isset($get['offset'])&&is_numeric($get['offset'])&&(int)$get['offset'] > 0 ? (int)$get['offset']: 0;
            $params['limit'] = API_RECORDS_PER_PAGE;

            if ((int)$user_data['user_type'] === INSTALLER && (int)$user_data['is_owner'] === ROLE_EMPLOYEE) {
                $this->load->helper('common');
                $permissions = retrieveEmployeePermission($this->user['user_id']);
            }
            
            if (in_array((int)$user_data['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true)) {
                if ((int)$user_data['user_type'] === INSTALLER &&
                 (int)$user_data['is_owner'] === ROLE_EMPLOYEE &&
                 isset($permissions['project_view']) &&
                 (int)$permissions['project_view'] === 1
                ) {
                    $params['where']['company_id'] = $user_data['company_id'];
                } if ((int)$user_data['user_type'] === INSTALLER &&
                (int)$user_data['is_owner'] === ROLE_EMPLOYEE &&
                isset($permissions['project_view']) &&
                (int)$permissions['project_view'] === 0
                ) {
                    $params['where']['installer_id'] = $user_data['user_id'];
                } else {
                    $params['where']['company_id'] = $user_data['company_id'];
                }
            } else {
                $params['where']['user_id'] = $user_data['user_id'];
            }

            $params['where']['language_code'] = $language_code;
            $projects = $this->Project->get($params);

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$projects['count'] > ($params['offset'] + API_RECORDS_PER_PAGE)) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + API_RECORDS_PER_PAGE;
            }

            $data = $projects['data'];

            if (empty($data)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_listed'),
                'data' => $data,
                'next_count' => $nextCount,
                'has_more_pages' => $hasMorePages,
                'per_page_count' => $params['limit'],
                'total' => $projects['count']
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
     * @SWG\Get(path="/projects/{project_id}",
     *   tags={"Projects"},
     *   summary="List project",
     *   description="List projects posted by current user",
     *   operationId="projects_get",
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
     *     in="path",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="Data not found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function details_get()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view']);

            $this->requestData = $this->get();
            
            $this->validateProductDetails();

            if (! (bool) $this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($errorMessage),
                ]);
            }
            
            $this->load->model("Project");

            $params['project_id'] = $this->requestData['project_id'];

            $projectData = $this->Project->details($params);
            
            if (empty($projectData)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $this->load->model("ProjectRooms");
            $roomParams['where']['project_id'] = $this->requestData['project_id'];
            $roomParams['limit'] = 5;
            $roomData = $this->ProjectRooms->get($roomParams);
            
            $rooms = $roomData['data'];
            $roomCount = (int)$roomData['count'];
            if (!empty($rooms)) {
                $roomIds = array_column($rooms, 'project_room_id');
                $this->load->model('ProjectRoomProducts');
                $roomProductParams['where']['project_room_id'] = $roomIds;
                $roomProducts = $this->ProjectRoomProducts->get($roomProductParams);
                $roomProducts = $roomProducts['data'];
                $roomProducts = array_map(function ($product) {
                    $product['article_image'] =
                        preg_replace("/^\/home\/forge\//", "https://", $product['article_image']);
                    return $product;
                }, $roomProducts);
                $this->load->helper('db');
                $rooms = getDataWith($rooms, $roomProducts, 'project_room_id', 'project_room_id', 'products');
            }

            $projectData['rooms'] = $rooms;
            $projectData['room_count'] = $roomCount;
            $projectData['has_more_rooms'] = $roomCount > 4;
            $projectData['page_room_count'] = $roomParams['limit'];

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('product_details_fetched'),
                'data' => $projectData
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
     * @SWG\Get(path="/projects/{project_id}/rooms",
     *   tags={"Projects"},
     *   summary="List project rooms",
     *   description="List rooms for a given project",
     *   operationId="projectsRooms_get",
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
     *     in="path",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     *  @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="Data not found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    /**
     * @SWG\Get(path="/projects/{project_id}/levels/{level}/rooms",
     *   tags={"Projects"},
     *   summary="List project rooms by project level",
     *   description="List rooms for a given project",
     *   operationId="projectsRooms_get",
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
     *     in="path",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     *  @SWG\Parameter(
     *     name="levels",
     *     in="path",
     *     description="",
     *     type="string",
     *   ),
     *  @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="Data not found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function projectRoomsFetch_get()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view']);

            $this->requestData = $this->get();

            $this->requestData = trim_input_parameters($this->requestData, false);

            $this->validateProductDetails();

            if (! (bool) $this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($errorMessage),
                ]);
            }
            $params['offset'] =
                isset($this->requestData['offset'])&&is_numeric($this->requestData['offset'])&&(int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset']: 0;

            $this->load->model("ProjectRooms");
            $levelsSet = false;
            if (isset($this->requestData['levels'])) {
                if (strlen($this->requestData['levels']) < 1) {
                    $this->response([
                        'code' => HTTP_UNPROCESSABLE_ENTITY,
                        'msg' => $this->lang->line('levels_required')
                    ]);
                }
                $levelsSet = true;
                $params['where']['level'] = $this->requestData['levels'];
            }
            $params['where']['project_id'] = $this->requestData['project_id'];
            $params['where']['language_code'] = $language_code;
            $params['limit'] = API_RECORDS_PER_PAGE;
            $roomData = $this->ProjectRooms->get($params);
            
            $rooms = $roomData['data'];
            $roomCount = (int)$roomData['count'];
            $totalPrice = (object)[];
            $this->load->helper('utility');
            if (!empty($rooms)) {
                $roomIds = array_column($rooms, 'project_room_id');
                $this->load->model('ProjectRoomProducts');
                $roomProductParams['where']['project_room_id'] = $roomIds;
                $roomProducts = $this->ProjectRoomProducts->get($roomProductParams);
                $roomProducts = $roomProducts['data'];
                $roomProducts = array_map(function ($product) {
                    $product['article_image'] =
                        preg_replace("/^\/home\/forge\//", "https://", $product['article_image']);
                    return $product;
                }, $roomProducts);
                $this->load->helper('db');
                $rooms = getDataWith($rooms, $roomProducts, 'project_room_id', 'project_room_id', 'products');

                if ((int)$user_data['user_type'] === INSTALLER) {
                    $this->load->model('ProjectRoomQuotation');
                    $roomIds = array_column($rooms, 'project_room_id');

                    $conditions = [
                        'where' => ['company_id' => $user_data['company_id']],
                        'where_in' => ['project_room_id' => $roomIds]
                    ];

                    $priceData = $this->ProjectRoomQuotation->quotationInfo($conditions);
                    

                    $rooms = getDataWith($rooms, $priceData, 'project_room_id', 'project_room_id', 'price');

                    $rooms = array_map(function ($room) {
                        $room['has_price'] =
                            (bool)((isset($room['price']) && is_array($room['price'])&&count($room['price']) > 0));
                        $room['price'] = is_array($room['price'])&&count($room['price']) > 0 ? array_pop($room['price']) : (object)[];
                        if ($room['has_price']) {
                            $room['price']['total'] = get_percentage(
                                $room['price']['price_per_luminaries'] + $room['price']['installation_charges'],
                                $room['price']['discount_price']
                            );
                        }
                        return $room;
                    }, $rooms);

                    $projectParams['company_id'] = $user_data['company_id'];
                    $projectParams['project_id'] = $this->requestData['project_id'];

                    if ($levelsSet) {
                        $projectParams['where']['level'] = $this->requestData['levels'];
                    }

                    $this->load->model(['ProjectQuotation', 'ProjectRoomProducts']);
                    
                    $totalRoomQuotationPrice =
                        $this->ProjectQuotation->getProjectQuotationPriceByInstaller($projectParams);
                    $totalProductCharges = $this->ProjectRoomProducts->totalProductCharges(
                        ['project_id' => $this->requestData['project_id']]
                    );

                    // if (!empty($totalProductCharges)) {
                        $totalPrice->main_product_charge = 0.00;
                        $totalPrice->accessory_product_charge = 0.00;
                    // }
                    
                    $totalPrice->price_per_luminaries =
                            !empty($totalRoomQuotationPrice)&&
                            isset($totalRoomQuotationPrice['price_per_luminaries'])&&
                            !empty($totalRoomQuotationPrice['price_per_luminaries'])?
                            $totalRoomQuotationPrice['price_per_luminaries']:0.00;
                    $totalPrice->installation_charges =
                            !empty($totalRoomQuotationPrice)&&
                            isset($totalRoomQuotationPrice['installation_charges'])&&
                            !empty($totalRoomQuotationPrice['installation_charges'])?
                            $totalRoomQuotationPrice['installation_charges']:0.00;
                    $totalPrice->discount_price =
                            !empty($totalRoomQuotationPrice)&&
                            isset($totalRoomQuotationPrice['discount_price'])&&
                            !empty($totalRoomQuotationPrice['discount_price'])?
                            $totalRoomQuotationPrice['discount_price']:0.00;
                    $totalPrice->total = $totalPrice->main_product_charge +
                    $totalPrice->accessory_product_charge + get_percentage(
                        $totalPrice->price_per_luminaries +
                        $totalPrice->installation_charges,
                        $totalPrice->discount_price
                    );
                }
                // $totalPrice = $this->handleTotalPrice($user_data['user_type'], $projectParams);
            } else {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$roomCount > ($params['offset'] + API_RECORDS_PER_PAGE)) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + API_RECORDS_PER_PAGE;
            }

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_rooms_fetched_successfully'),
                'price' => $totalPrice,
                'data' => $rooms,
                'total' => $roomCount,
                'next_count' => $nextCount,
                'has_more_pages' => $hasMorePages,
                'per_page_count' => $params['limit']
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
     * Delete Rooms for project
     *
     * @return void
     */
     /**
     * @SWG\Delete(path="/projects/rooms",
     *   tags={"Projects"},
     *   summary="Delete Project Rooms",
     *   description="Delete project room for a current user",
     *   operationId="projects_rooms_delete",
     *   consumes ={"multipart/form-data"},
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
     * @SWG\Parameter(
     *     name="project_room_id",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function projectRooms_delete()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_delete']);

            $this->requestData = $this->delete();

            $this->validateRoomDelete();

            if (! (bool) $this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($errorMessage),
                ]);
            }

            $projectRoomData = $this->UtilModel->selectQuery('user_id, company_id, project_id', 'project_rooms', [
                'where' => ['project_rooms.id' => $this->requestData['project_room_id']],
                'join' => [
                    'projects' => 'projects.id=project_rooms.project_id'
                ],
                'single_row' => true
            ]);

            if (empty($projectRoomData)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            if (((int)$projectRoomData['user_id'] !== (int)$user_data['user_id']) &&
                ($projectRoomData['company_id'] != (int)$user_data['company_id'])
                /** @todo or doesnt have permission check */
            ) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('forbidden_action')
                ]);
            }

            $this->UtilModel->deleteData('project_rooms', [
                'where' => ['id' => $this->requestData['project_room_id']]
            ]);

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('room_deleted')
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
     * Project Rooms Edit
     *
     * @return void
     */
    public function projectRooms_put()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_edit']);

            $this->requestData = $this->put();

            $this->validateRoomEdit();

            if (! (bool) $this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($errorMessage),
                ]);
            }

            if (isset($this->requestData['calcProduct'])) {
                if (!is_array($this->requestData['calcProduct'])) {
                    $response['code'] = HTTP_UNPROCESSABLE_ENTITY;
                    $response['msg'] = $this->lang->line('invalid_product_data');
                    $this->response($response);
                }
            }

            $this->products = [];
            if (isset($this->requestData['calcProduct'])) {
                $this->products = [$this->requestData['calcProduct']];

                $this->validateRoomProducts();

                if (! (bool) $this->form_validation->run()) {
                    $errorMessage = $this->form_validation->error_array();
                    $this->response([
                        'code' => HTTP_UNPROCESSABLE_ENTITY,
                        'msg' => array_shift($errorMessage),
                    ]);
                }
            }

            $roomData = $this->UtilModel->selectQuery('id, project_id, room_id', 'project_rooms', [
                'where' => [
                    'id' => $this->requestData['projectRoomId']
                ],
                'single_row' => true
            ]);

            if (empty($roomData)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $this->requestData = trim_input_parameters($this->requestData, false);
            if (isset($this->requestData['calcProduct'])) {
                $this->products = trim_input_parameters($this->products, false);
            }
            $updateData = [];
            if (isset($this->requestData["name"])) {
                $updateData["name"] =  $this->requestData['name'];
            }
            if (isset($this->requestData["count"])) {
                $updateData["count"] =  $this->requestData['count'];
            }
            if (isset($this->requestData["length"])) {
                $updateData["length"] =  $this->requestData['length'];
            }
            if (isset($this->requestData["width"])) {
                $updateData["width"] =  $this->requestData['width'];
            }
            if (isset($this->requestData["height"])) {
                $updateData["height"] =  $this->requestData['height'];
            }
            if (isset($this->requestData["suspensionHeight"])) {
                $updateData["suspension_height"] =  $this->requestData['suspensionHeight'];
            }
            if (isset($this->requestData["maintainanceFactor"])) {
                $updateData["maintainance_factor"] =  $this->requestData['maintainanceFactor'];
            }
            if (isset($this->requestData["shape"])) {
                $updateData["shape"] =  $this->requestData['shape'];
            }
            if (isset($this->requestData["workingPlaneHeight"])) {
                $updateData["working_plane_height"] =  $this->requestData['workingPlaneHeight'];
            }
            if (isset($this->requestData["rhoWall"])) {
                $updateData["rho_wall"] =  $this->requestData['rhoWall'];
            }
            if (isset($this->requestData["rhoCeiling"])) {
                $updateData["rho_ceiling"] =  $this->requestData['rhoCeiling'];
            }
            if (isset($this->requestData["rhoFloor"])) {
                $updateData["rho_floor"] =  $this->requestData['rhoFloor'];
            }
            if (isset($this->requestData["luxValue"])) {
                $updateData["lux_value"] =  $this->requestData['luxValue'];
            }
            if (isset($this->requestData["luminariesCountX"])) {
                $updateData["luminaries_count_x"] =  $this->requestData['luminariesCountX'];
            }
            if (isset($this->requestData["luminariesCountY"])) {
                $updateData["luminaries_count_y"] =  $this->requestData['luminariesCountY'];
            }
            if (isset($this->requestData["fastCalcResponse"])) {
                $updateData["fast_calc_response"] =  $this->requestData['fastCalcResponse'];
                $fastCalcResponse = json_decode($updateData['fast_calc_response'], true);
                if (json_last_error() == JSON_ERROR_NONE || is_array($fastCalcResponse)) {
                    $data['side_view'] = $fastCalcResponse['projectionSide'];
                    $data['top_view'] = $fastCalcResponse['projectionTop'];
                    $data['front_view'] = $fastCalcResponse['projectionFront'];
                }
            }
                
            if (empty($updateData) && empty($this->products)) {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('nothing_to_update')
                ]);
            }
            
            $updateData['updated_at'] = $this->datetime;
            $updateData['updated_at_timestamp'] = time();

            $this->db->trans_begin();
            if (!empty($updateData)) {
                $this->UtilModel->updateTableData($updateData, 'project_rooms', [
                    'id' => $this->requestData['projectRoomId']
                ]);
            }

            if (!empty($this->products)) {
                $this->UtilModel->deleteData('project_room_products', [
                    'where' => ['project_room_id' => $this->requestData['projectRoomId'], 'type' => 1]
                ]);

                $productData = [
                    'project_room_id' => $this->requestData['projectRoomId'],
                    'article_code' => $this->products[0]['articleCode'],
                    'product_id' => $this->products[0]['productId'],
                    'type' => ROOM_MAIN_PRODUCT
                ];

                $this->UtilModel->insertTableData($productData, 'project_room_products');
            }

            $updateData['project_room_id'] = $this->requestData['projectRoomId'];
            $updateData['room_id'] = $roomData['room_id'];
            $updateData['project_id'] = $roomData['project_id'];
            
            $this->db->trans_commit();

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('room_updated'),
                'data' => $updateData
            ]);
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Add project Rooms
     *
     * @return void
     */
    public function projectRoomProducts_post()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_add']);

            $this->requestData = $this->post();

            $this->validateProjectRoomProducts();

            $this->validationRun();

            $productData = array_map(function ($product) {
                $data['project_room_id'] = $product['projectRoomId'];
                $data['product_id'] = $product['productId'];
                $data['article_code'] = isset($product['article_code'])?$product['article_code']:'';
                $data['type'] = PROJECT_ROOM_ACCESSORY_PRODUCT;
                return $data;
            }, $this->requestData);

            $this->UtilModel->insertBatch('project_room_products', $productData);

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('accessory_product_added')
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    private function validateProjectRoomProducts()
    {
        $validationData = [
            'products' => $this->requestData
        ];

        $this->form_validation->set_data($validationData);

        foreach ($this->requestData as $id => $products) {
            $this->form_validation->set_rules('products['. $id .'][projectRoomId]', 'Project Room', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('products['. $id .'][productId]', 'Product', 'trim|required|is_natural_no_zero');
        }
    }

    /**
     * Validate project
     *
     * @return void
     */
    private function validateProject($isEdit = false)
    {
        $this->form_validation->set_data($this->requestData);

        $validationRules = [
            [
                'label' => 'Project Number',
                'field' => 'number',
                'rules' => 'trim|required'
            ],
            [
                'label' => 'Project Name',
                'field' => 'name',
                'rules' => 'trim|required'
            ],
            [
                'label' => 'Project Levels',
                'field' => 'levels',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'label' => 'Address',
                'field' => 'address',
                'rules' => 'trim|required'
            ],
            [
                'label' => 'Location',
                'field' => 'lat',
                'rules' => 'trim|required|numeric'
            ],
            [
                'label' => 'Location',
                'field' => 'lng',
                'rules' => 'trim|required|numeric'
            ],
        ];

        if ($isEdit) {
            array_unshift($validationRules, [
                'label' => 'Project',
                'field' => 'project_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ]);
        }

        $this->form_validation->set_rules($validationRules);

        if ((int)$this->user['user_type'] === INSTALLER && (int)$this->user['is_owner'] === ROLE_OWNER) {
            $this->form_validation->set_rules('installer_id', 'Installer ID', 'trim|is_natural_no_zero');
        }
    }

    /**
     * Validate Room Data
     *
     * @return void
     */
    private function validateRooms()
    {
        $validationData = [
            'rooms' => $this->requestData
        ];

        $this->form_validation->set_data($validationData);

        foreach ($this->requestData as $id => $room) {
            $this->form_validation->set_rules('rooms['. $id .'][projectId]', 'Project id', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('rooms['. $id .'][level]', 'Level', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('rooms['. $id .'][suspensionHeight]', 'Suspension Height', 'trim|is_natural_no_zero');
            // $this->form_validation->set_rules('rooms['. $id .'][applicationId]', 'Application id', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('rooms['. $id .'][roomId]', 'Room id', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('rooms['. $id .'][name]', 'Name', 'trim|required');
            $this->form_validation->set_rules('rooms['. $id .'][length]', 'Length', 'trim|required|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][width]', 'Width', 'trim|required|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][height]', 'Height', 'trim|required|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][maintainanceFactor]', 'Maintainance factor', 'trim|required|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][shape]', 'Shape', 'trim');
            $this->form_validation->set_rules('rooms['. $id .'][workingPlaneHeight]', 'Working plane height', 'trim|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][rhoWall]', 'Rho wall', 'trim|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][rhoCeiling]', 'Rho ceiling', 'trim|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][rhoFloor]', 'Rho floor', 'trim|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][luxValue]', 'Lux value', 'trim|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][calcProduct]', 'Calculation Products', 'required');
            $this->form_validation->set_rules('rooms['. $id .'][luminariesCountX]', 'Luminaries count x', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('rooms['. $id .'][luminariesCountY]', 'Luminaries count y', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('rooms['. $id .'][fastCalcResponse]', 'Fast calc response', 'trim');
        }
    }

    private function validateRoomEdit()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules('projectRoomId', 'Project Room id', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('suspensionHeight', 'Suspension Height', 'trim|numeric');
        $this->form_validation->set_rules('name', 'Name', 'trim');
        $this->form_validation->set_rules('length', 'Length', 'trim|numeric');
        $this->form_validation->set_rules('width', 'Width', 'trim|numeric');
        $this->form_validation->set_rules('height', 'Height', 'trim|numeric');
        $this->form_validation->set_rules('maintainanceFactor', 'Maintainance factor', 'trim|numeric');
        $this->form_validation->set_rules('shape', 'Shape', 'trim');
        $this->form_validation->set_rules('workingPlaneHeight', 'Working plane height', 'trim|numeric');
        $this->form_validation->set_rules('rhoWall', 'Rho wall', 'trim|numeric');
        $this->form_validation->set_rules('rhoCeiling', 'Rho ceiling', 'trim|numeric');
        $this->form_validation->set_rules('rhoFloor', 'Rho floor', 'trim|numeric');
        $this->form_validation->set_rules('luxValue', 'Lux value', 'trim|numeric');
        $this->form_validation->set_rules('luminariesCountX', 'Luminaries count x', 'trim|is_natural_no_zero');
        $this->form_validation->set_rules('luminariesCountY', 'Luminaries count y', 'trim|is_natural_no_zero');
        $this->form_validation->set_rules('fastCalcResponse', 'Fast calc response', 'trim');
    }

    /**
     * Validate room products
     *
     * @return void
     */
    private function validateRoomProducts()
    {
        $this->form_validation->reset_validation();

        $validationData = [
            "products" => $this->products
        ];

        $this->form_validation->set_data($validationData);

        foreach ($this->products as $id => $product) {
            $this->form_validation->set_rules('products['. $id .'][articleCode]', 'Article code', 'trim|required');
            $this->form_validation->set_rules('products['. $id .'][productId]', 'Product', 'trim|required|is_natural_no_zero');
        }
    }

    /**
     * Validate send quotation
     *
     * @return void
     */
    private function validateSendQuotation()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'label' => 'Project',
                'field' => 'project_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'label' => 'Company',
                'field' => 'company_id[]',
                'rules' => 'required'
            ]
        ]);
    }

    /**
     * Validate send project details
     *
     * @return void
     */
    private function validateProductDetails()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'label' => 'Project',
                'field' => 'project_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'label' => 'Level',
                'field' => 'level',
                'rules' => 'trim|is_natural_no_zero'
            ],
        ]);
    }

    /**
     * Validate room delete
     *
     * @return void
     */
    private function validateRoomDelete()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'label' => 'Project Room',
                'field' => 'project_room_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    /**
     * Handle total price data
     *
     * @param string $userData
     * @param array $projectParams
     * @return Object
     */
    private function handleTotalPrice($userType, $projectParams)
    {
        $this->load->model("ProjectQuotation");
        $totalPrice = (object)[];

        if ((int)$userType === INSTALLER) {
            $totalPrice->main_product_charge = 0.00;
            $totalPrice->accessory_product_charge = 0.00;
            $totalPriceData =  $this->ProjectQuotation->getProjectQuotationPriceByInstaller($projectParams);
            $quotationPrice = $this->ProjectQuotation->quotationChargesByInstaller($projectParams);

            $totalPrice->price_per_luminaries = isset($totalPriceData['price_per_luminaries'])?(double)$totalPriceData['price_per_luminaries']:0.00;
            $totalPrice->installation_charges = isset($totalPriceData['installation_charges'])?(double)$totalPriceData['installation_charges']:0.00;
            $totalPrice->discount_price = isset($totalPriceData['discount_price'])?(double)$totalPriceData['discount_price']:0.00;
            $totalPrice->additional_product_charges = isset($quotationPrice['additional_product_charges'])?(double)$quotationPrice['additional_product_charges']:0.00;
            $totalPrice->discount = isset($quotationPrice['discount'])?(double)$quotationPrice['discount']:0.00;
           
            
            $totalPrice->total = get_percentage(($totalPrice->main_product_charge
                                        + $totalPrice->accessory_product_charge
                                        + $totalPrice->price_per_luminaries
                                        + $totalPrice->installation_charges
                                        + $totalPrice->additional_product_charges), $totalPrice->discount_price);
            $totalPrice->total = round($totalPrice->total, 2);
        } elseif (in_array((int)$userType, [BUSINESS_USER, PRIVATE_USER], true)) {
            $totalPrice->main_product_charge = 0.00;
            $totalPrice->accessory_product_charge = 0.00;
            $totalPriceData = $this->ProjectQuotation->approvedProjectQuotationPrice($projectParams);
            $totalPrice->price_per_luminaries = isset($totalPriceData['price_per_luminaries'])?(double)$totalPriceData['price_per_luminaries']:0.00;
            $totalPrice->installation_charges = isset($totalPriceData['installation_charges'])?(double)$totalPriceData['installation_charges']:0.00;
            $totalPrice->discount_price = isset($totalPriceData['discount_price'])?(double)$totalPriceData['discount_price']:0.00;
            $totalPrice->additional_product_charges = isset($totalPriceData['additional_product_charges'])?(double)$totalPriceData['additional_product_charges']:0.00;
            $totalPrice->discount = isset($totalPriceData['discount'])?(double)$totalPriceData['discount']:0.00;

            $totalPrice->total = get_percentage(($totalPrice->price_per_luminaries
                                        + $totalPrice->installation_charges
                                        + $totalPrice->additional_product_charges), $totalPrice->discount_price);
            $totalPrice->total = round($totalPrice->total, 2);
        }

        return $totalPrice;
    }
}
