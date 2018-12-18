<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';
require APPPATH . '/libraries/Traits/ProjectDelete.php';
require APPPATH . '/libraries/Traits/ProjectLevelEdit.php';
require APPPATH . '/libraries/Traits/Notifier.php';


class ProjectController extends BaseController
{

    use ProjectDelete, ProjectLevelEdit, Notifier;
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
     * Delete project
     *
     * @return void
     */
    public function index_delete()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_delete']);

            $this->requestData = $this->delete();

            $this->validateDeleteProject();

            $this->validationRun();

            $this->projectId = $this->requestData['project_id'];

            $project = $this->fetchProduct();

            if (empty($project)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_project_found')
                ]);
            }

            $this->deleteProject();

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_deleted')
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

            if (!(bool)$this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($errorMessage),
                ]);
            }

            $this->requestData = trim_input_parameters($this->requestData, false);

            $projectData = $this->UtilModel->selectQuery('id, user_id, company_id, levels', 'projects', [
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
                // 'levels' => $this->requestData['levels'],
                'address' => $this->requestData['address'],
                'lat' => $this->requestData['lat'],
                'lng' => $this->requestData['lng'],
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => time()
            ];

            $this->db->trans_begin();
            if (isset($this->requestData['levels'])) {
                $project['levels'] = (int)$this->requestData['levels'];
                $this->editLevel((int)$projectData['levels'], (int)$this->requestData['levels'], (int)$projectData['id'], (int)$user_data['user_type']);
            }

            if ((int)$user_data['user_type'] === INSTALLER && (int)$user_data['is_owner'] === ROLE_OWNER && isset($this->requestData['installer_id'])) {
                $project['installer_id'] = $this->requestData['installer_id'];
            }

            $projectId = $this->UtilModel->updateTableData($project, 'projects', [
                'id' => $this->requestData['project_id']
            ]);

            $project['project_id'] = $this->requestData['project_id'];
            $this->db->trans_commit();

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_updated'),
                'data' => $project
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
                count($this->requestData['company_id']) > MAXIMUM_REQUEST_COUNTS_PER_PROJECT) {
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

            $this->notifyQuotationRequest($user_data['user_id'], $this->requestData['company_id'], $this->requestData['project_id']);

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

    public function fetchRoomproducts_get()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view']);

            $this->requestData = $this->get();

            $this->validateFetchRoomProducts();

            $this->validationRun();

            $params['offset'] =
                isset($this->requestData['offset']) && is_numeric($this->requestData['offset']) && (int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset'] : 0;
            $params['limit'] = API_RECORDS_PER_PAGE;

            $this->load->model(['ProjectRoomProducts', 'ProductMountingTypes']);
            $params['where']['project_room_id'] = $this->requestData['project_room_id'];
            $roomProducts = $this->ProjectRoomProducts->get($params);
            $productCount = (int)$roomProducts['data'];
            $roomProducts = $roomProducts['data'];

            $productIds = array_column($roomProducts, 'product_id');
            $productMountingTypeData = $this->ProductMountingTypes->get($productIds);
            $roomProducts = getDataWith($roomProducts, $productMountingTypeData, 'product_id', 'product_id', 'mounting_type', 'type');

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$productCount > ($params['offset'] + API_RECORDS_PER_PAGE)) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + API_RECORDS_PER_PAGE;
            }

            $response = [
                'code' => HTTP_OK,
                'msg' => $this->lang->line('room_products_fetched'),
                'data' => $roomProducts,
                'total' => $productCount,
                'has_more_pages' => $hasMorePages,
                'per_page_count' => $params['limit'],
                'next_count' => $nextCount
            ];
            
            $this->response($response);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    private function validateFetchRoomProducts()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'label' => 'Project Room Id',
                'field' => 'project_room_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
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
                isset($get['offset']) && is_numeric($get['offset']) && (int)$get['offset'] > 0 ? (int)$get['offset'] : 0;
            $params['limit'] = API_RECORDS_PER_PAGE;

            if (isset($get['search']) && is_string($get['search']) && strlen(trim($get['search'])) > 0) {
                $search = trim($get['search']);
                $params['where']['name LIKE'] = "%{$search}%";
            }

            if ((int)$user_data['user_type'] === INSTALLER && (int)$user_data['is_owner'] === ROLE_EMPLOYEE) {
                $this->load->helper('common');
                $permissions = retrieveEmployeePermission($this->user['user_id']);
            }

            if (in_array((int)$user_data['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true)) {
                if ((int)$user_data['user_type'] === INSTALLER &&
                    (int)$user_data['is_owner'] === ROLE_EMPLOYEE &&
                    isset($permissions['project_view']) &&
                    (int)$permissions['project_view'] === 1) {
                    $params['where']['company_id'] = $user_data['company_id'];
                }
                if ((int)$user_data['user_type'] === INSTALLER &&
                    (int)$user_data['is_owner'] === ROLE_EMPLOYEE &&
                    isset($permissions['project_view']) &&
                    (int)$permissions['project_view'] === 0) {
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

            if (!(bool)$this->form_validation->run()) {
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
                $this->load->model(['ProjectRoomProducts', 'ProductMountingTypes']);
                $roomProductParams['where']['project_room_id'] = $roomIds;
                $roomProducts = $this->ProjectRoomProducts->get($roomProductParams);
                $roomProducts = $roomProducts['data'];
                $roomProducts = array_map(function ($product) {
                    $product['article_image'] =
                        preg_replace("/^\/home\/forge\//", "https://", $product['article_image']);
                    return $product;
                }, $roomProducts);
                $productIds = array_column($roomProducts, 'product_id');
                $productMountingTypeData = $this->ProductMountingTypes->get($productIds);
                $roomProducts = getDataWith(
                    $roomProducts,
                    $productMountingTypeData,
                    'product_id',
                    'product_id',
                    'mounting_type',
                    'type'
                );
                $this->load->helper('db');
                $rooms = getDataWith($rooms, $roomProducts, 'project_room_id', 'project_room_id', 'products');
                if ((int)$user_data['user_type'] === INSTALLER) {
                    $this->load->model(['ProjectRoomQuotation', 'ProjectRoomTcoValue']);
                    $projectRoomIds = array_column($rooms, 'project_room_id');
                    $roomPrice = $this->ProjectRoomQuotation->quotationInfo([
                        'where_in' => ['project_room_id' => $projectRoomIds]
                    ]);
                    $tcoData = $this->ProjectRoomTcoValue->get($projectRoomIds);
                    $this->load->helper('utility');
                    $rooms = getDataWith($rooms, $roomPrice, 'project_room_id', 'project_room_id', 'price');
                    $rooms = getDataWith($rooms, $tcoData, 'project_room_id', 'project_room_id', 'tco');
                    $rooms = array_map(function ($room) {
                        if (empty($room['price'])) {
                            $room['has_price'] = false;
                            $room['price'] = (object)[];
                        } else {
                            $room['has_price'] = true;
                            $room['price'] = $room['price'][0];
                            $room['price']['total'] = get_percentage(
                                $room['price']['price_per_luminaries'] + $room['price']['installation_charges'],
                                $room['price']['discount_price']
                            );
                        }
                        if (empty($room['tco'])) {
                            $room['tco'] = (object)[];
                        } else {
                            $room['tco'] = $room['tco'][0];
                        }
                        return $room;
                    }, $rooms);
                }
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

            $articleCheck = $this->UtilModel->selectQuery('id', 'product_specifications', [
                'where' => ['product_id' => $this->requestData['productId'], 'articlecode' => $this->requestData['articleCode']]
            ]);

            if (empty($articleCheck)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('product_not_found')
                ]);
            }

            $productCheck = $this->UtilModel->selectQuery('id', 'project_room_products', [
                'where' => ['product_id' => $this->requestData['productId'], 'article_code' => $this->requestData['articleCode'], 'project_room_id' => $this->requestData['projectRoomId']]
            ]);

            if (!empty($productCheck)) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('product_already_added')
                ]);
            }

            $productData['project_room_id'] = $this->requestData['projectRoomId'];
            $productData['product_id'] = $this->requestData['productId'];
            $productData['article_code'] = $this->requestData['articleCode'];
            $productData['type'] = PROJECT_ROOM_ACCESSORY_PRODUCT;
            $productData['created_at'] = $this->datetime;
            $productData['created_at_timestamp'] = $this->timestamp;


            $this->UtilModel->insertTableData($productData, 'project_room_products');

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

    /**
     * Accessory Product Remove
     *
     * @return void
     */
    public function projectRoomProducts_delete()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_add']);

            $this->requestData = $this->delete();

            $this->validateProjectRoomProductsDelete();

            $this->validationRun();

            $articleCheck = $this->UtilModel->selectQuery('id', 'product_specifications', [
                'where' => ['product_id' => $this->requestData['productId'], 'articlecode' => $this->requestData['articleCode']]
            ]);

            if (empty($articleCheck)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('product_not_found')
                ]);
            }

            $productCheck = $this->UtilModel->selectQuery('id', 'project_room_products', [
                'where' => ['product_id' => $this->requestData['productId'], 'article_code' => $this->requestData['articleCode'], 'project_room_id' => $this->requestData['projectRoomId']]
            ]);

            if (empty($productCheck)) {
                $this->response([
                    'code' => HTTP_OK,
                    'msg' => $this->lang->line('product_removed')
                ]);
            }

            $this->UtilModel->deleteData('project_room_products', [
                'where' => ['product_id' => $this->requestData['productId'], 'article_code' => $this->requestData['articleCode'], 'project_room_id' => $this->requestData['projectRoomId']]
            ]);

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('product_removed')
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
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules('projectRoomId', 'Project Room', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('productId', 'Product', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('articleCode', 'Article', 'trim|required|is_natural_no_zero');
    }

    private function validateProjectRoomProductsDelete()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules('projectRoomId', 'Project Room', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('productId', 'Product', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('articleCode', 'Article', 'trim|required|is_natural_no_zero');
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
            array_unshift($validationRules, [
                'label' => 'Project Levels',
                'field' => 'levels',
                'rules' => 'trim|is_natural_no_zero'
            ]);
        } else {
            array_unshift($validationRules, [
                'label' => 'Project Levels',
                'field' => 'levels',
                'rules' => 'trim|required|is_natural_no_zero'
            ]);
        }

        $this->form_validation->set_rules($validationRules);

        if ((int)$this->user['user_type'] === INSTALLER && (int)$this->user['is_owner'] === ROLE_OWNER) {
            $this->form_validation->set_rules('installer_id', 'Installer ID', 'trim|is_natural_no_zero');
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
     * Validate send project details
     *
     * @return void
     */
    private function validateDeleteProject()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'label' => 'Project',
                'field' => 'project_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

}
