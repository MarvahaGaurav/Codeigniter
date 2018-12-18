<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';
require_once APPPATH . "/libraries/Traits/LevelRoomCheck.php";
require_once APPPATH . "/libraries/Traits/TotalProjectPrice.php";
require_once APPPATH . "/libraries/Traits/InstallerPriceCheck.php";

class ProjectLevelsController extends BaseController
{

    use LevelRoomCheck, InstallerPriceCheck, TotalProjectPrice;

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
     * @SWG\Get(path="/projects/{project_id}/levels",
     *   tags={"Projects"},
     *   summary="List project Levels",
     *   description="List projects levels posted by current user",
     *   operationId="projectLevels_get",
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
    public function projectLevels_get()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER]);

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view']);

            $this->requestData = $this->get();

            $this->validateLevelsGet();

            $this->validationRun();

            $this->load->model('ProjectLevel');

            $params['project_id'] = $this->requestData['project_id'];
            $projectId = $this->requestData['project_id'];
            $data = $this->ProjectLevel->get($params);
            $projectLevels = $data['data'];
            $totalPrice = (object)[];

            if (empty($projectLevels)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $this->load->helper(['project', 'db']);
            //add activity key to all project level data
            $projectLevels = level_activity_status_handler($projectLevels);

            $totalPrice = $this->handleTotalPrice(
                (int)$user_data['user_type'],
                ['project_id' => $this->requestData['project_id'], 'company_id' => $user_data['company_id']]
            );

            $this->load->model(['ProjectRooms']);
            $roomCount = $this->ProjectRooms->roomCountByLevel($this->requestData['project_id']);

            $projectLevels = getDataWith($projectLevels, $roomCount, 'level', 'level', 'room_count', 'room_count');
            $projectLevels = array_map(function ($project) {
                $project['room_count'] = is_array($project['room_count'])&&
                    count($project['room_count'])&&
                    isset($project['room_count'][0])?(int)$project['room_count'][0]:0;
                return $project;
            }, $projectLevels);

            $projectLevelStatus = is_bool(array_search(0, array_column($projectLevels, 'status')));

            $response = [
                'code' => HTTP_OK,
                'msg' => $this->lang->line('success'),
                'data' => $projectLevels,
                'project_level_status' => $projectLevelStatus,
                'total_price' => $totalPrice,
                'has_added_all_rooms' => $this->isAllRoomsAdded($projectId)
            ];

            if (in_array((int)$user_data['user_type'], [INSTALLER], true)) {
                $params['company_id'] = (int)$user_data['company_id'];

                $response['has_added_all_price'] = $this->projectCheckPrice($projectId);
                $companyData = $this->UtilModel->selectQuery('company_discount', 'company_master', [
                    'where' => ['company_id' => $user_data['company_id']], 'single_row' => true
                ]);

                $response['company_discount'] = $companyData['company_discount'];
                $technicianCharges = $this->UtilModel->selectQuery('id', 'project_technician_charges', [
                    'where' => ['project_id' => $projectId]
                ]);
                $response['is_technician_final_price_added'] = !empty($technicianCharges);
            }
            
            $this->response($response);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

     /**
     * @SWG\Put(path="/projects/levels",
     *   tags={"Projects"},
     *   summary="Update project levels status",
     *   description="Update project level status",
     *   operationId="levelDone_put",
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
     *     name="level",
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

            $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_add']);

            $this->requestData = $this->put();

            $projectId = (int)$this->requestData['project_id'];
            $level = (int)$this->requestData['level'];

            $this->validateLevelsDone();

            $this->validationRun();

            $projectData = $this->UtilModel->selectQuery('user_id, id as project_id, company_id', 'projects', [
            'where' => ['id' => $projectId], 'single_row' => true
            ]);

            if (empty($projectData)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            

            if (((int)$projectData['user_id'] !== (int)$user_data['user_id']) && ((int)$projectData['company_id'] !== (int)$user_data['company_id'])) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('forbidden_action')
                ]);
            }

            $projectRooms = $this->UtilModel->selectQuery('project_id', 'project_rooms', [
            'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);
            
            if (empty($projectRooms)) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('rooms_required')
                ]);
            }

            $this->UtilModel->updateTableData([
                'status' => 1
                ], 'project_levels', [
                'project_id' => $projectId,
                'level' => $level
                ]);

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line("level_marked_done")
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
     * @SWG\Post(path="/projects/levels",
     *   tags={"Projects"},
     *   summary="Post Project Level resource - (clone)",
     *   description="inserts to resource based on tasks (eg. clone)",
     *   operationId="levelDone_post",
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
     *     name="reference_level",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="destination_levels[]",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="task",
     *     in="formData",
     *     description="task = clone (for cloning  levels)",
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

            $this->validateMakeClone();

            $this->validationRun();

            if (!is_array($this->requestData['destination_levels'])) {
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => $this->lang->line('invalid_request'),
                    'info' => 'destination_levels'
                ]);
            }

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

            $this->load->model(['ProjectRooms', 'ProjectRoomProducts']);

            $roomsData = $this->ProjectRooms->roomsData([
                'where' => [
                    'project_id' => $this->requestData['project_id'],
                    'level' => $this->requestData['reference_level']
                ]
            ]);
            
            $time = [
                'time' => $this->datetime,
                'timestamp' => $this->timestamp
            ];

            $this->UtilModel->deleteData('project_rooms', [
                'where_in' => ['level' => $this->requestData['destination_levels']],
                'where' => ['project_id' => $this->requestData['project_id']]
            ]);
            
            $this->ProjectRooms->cloneLevelRooms($roomsData, $this->requestData['destination_levels'], $time);

            $cloneRoomParams = [
                'where' => ['project_id' => $this->requestData['project_id']],
                'where_in' => ['level' => $this->requestData['destination_levels']]
            ];

            $productsData = $this->ProjectRoomProducts->projectRoomProducts([
                'where' => [
                    'project_id' => $this->requestData['project_id'],
                    'level' => $this->requestData['reference_level']
                ]
            ]);

            $clonedRooms = $this->ProjectRooms->roomsData($cloneRoomParams);

            $sourceDestinationRoomIdMap = [];
            foreach ($roomsData as $key => $value) {
                $sourceDestinationRoomIdMap[$value['id']] = $clonedRooms[$key]['id'];
            }

            $this->ProjectRoomProducts->cloneProjectRoomProducts($productsData, $sourceDestinationRoomIdMap);
            
            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('level_clone_successful')
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
     * Validate make clone
     *
     * @return void
     */
    private function validateMakeClone()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'label' => 'Project',
                'field' => 'project_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'label' => 'Reference Level',
                'field' => 'reference_level',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'label' => 'Destination Levels',
                'field' => 'destination_levels[]',
                'rules' => 'trim|required'
            ],
            [
                'label' => 'Task',
                'field' => 'task',
                'rules' => 'trim|required|regex_match[/^(clone)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_task_supplied')
                ]
            ],
        ]);
    }

    /**
     * Validate Levels Listing
     *
     * @return void
     */
    private function validateLevelsGet()
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

    /**
     * Validate levels mark as done api
     *
     * @return void
     */
    private function validateLevelsDone()
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
                'rules' => 'trim|required|is_natural_no_zero'
            ],
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
        $this->load->helper('utility');
        $totalPrice = (object)[];

        if ((int)$userType === INSTALLER) {
            $totalPrice->main_product_charge = 0.00;
            $totalPrice->accessory_product_charge = 0.00;
            $totalPriceData =  $this->ProjectQuotation->getProjectQuotationPriceByInstaller($projectParams);
            $quotationPrice = $this->ProjectQuotation->quotationChargesByInstaller($projectParams);

            $totalPrice->price_per_luminaries = isset($totalPriceData['price_per_luminaries'])?
                                                    (double)$totalPriceData['price_per_luminaries']:0.00;
            $totalPrice->installation_charges = isset($totalPriceData['installation_charges'])?
                                                    (double)$totalPriceData['installation_charges']:0.00;
            $totalPrice->discount_price = isset($totalPriceData['discount_price'])?
                                                    (double)$totalPriceData['discount_price']:0.00;
            $totalPrice->additional_product_charges = isset($quotationPrice['additional_product_charges'])?
                                                    (double)$quotationPrice['additional_product_charges']:0.00;
            $totalPrice->discount = isset($quotationPrice['discount'])?
                                                    (double)$quotationPrice['discount']:0.00;
           
            
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
