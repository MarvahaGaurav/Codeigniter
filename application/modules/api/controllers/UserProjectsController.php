<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class UserProjectsController extends BaseController
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
     * @SWG\Post(path="/users/projects",
     *   tags={"Projects"},
     *   summary="Post Project resource - (clone)",
     *   description="inserts to resource based on tasks (eg. clone)",
     *   operationId="clone_post",
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
     *     name="task",
     *     in="formData",
     *     description="task = clone (for cloning  projects)",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function clone_post()
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

            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
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

            // pd($projectData);

            $this->load->model(['Project', 'ProjectRooms', 'ProjectRoomProducts',
                                'ProjectLevel', 'ProjectRoomQuotation']);

            $time = [
                'datetime' => $this->datetime,
                'timestamp' => $this->timestamp
            ];

            $params = ['where' => ['project_id' => $this->requestData['project_id']]];

            $projectLevelData = $this->UtilModel->selectQuery('*', 'project_levels', $params);
            $roomsData = $this->ProjectRooms->roomsData($params);
            $productsData = $this->ProjectRoomProducts->projectRoomProducts($params);
            $insertProjectQuotations = (int)$this->user['user_type'] === INSTALLER && !empty($roomsData);
            $this->load->helper(['db']);
            $filterQuotation = [];
            if ($insertProjectQuotations) {
                $projectRoomIds = array_column($roomsData, 'id');
                $roomQuotations = $this->ProjectRoomQuotation->quotationInfo([
                    'where_in' => ['project_room_id' => $projectRoomIds]
                ]);
                $roomsData = getDataWith($roomsData, $roomQuotations, 'id', 'project_room_id', 'quotations', '', true);
                $filterQuotation = array_map(function ($room) {
                    return (bool)!empty($room['quotations']);
                }, $roomsData);
                // dd($filterQuotation);
            }

            $this->db->trans_begin();
            $projectId = $this->Project->saveProject($projectData, $time);
            $this->ProjectLevel->addProjectLevels($projectLevelData, $projectId);
            $this->ProjectRooms->cloneProjectRooms($roomsData, $projectId, $time);
            $this->db->trans_commit();

            $newProjectParams = [
                'where' => ['project_id' => $projectId]
            ];

            $this->db->trans_begin();
            $clonedRoomsData = $this->ProjectRooms->roomsData($newProjectParams);

            $sourceDestinationRoomIdMap = [];
            foreach ($roomsData as $key => $value) {
                $sourceDestinationRoomIdMap[$value['id']] = $clonedRoomsData[$key]['id'];
            }
            $this->ProjectRoomProducts->cloneProjectRoomProducts($productsData, $sourceDestinationRoomIdMap);
            if ($insertProjectQuotations) {
                $roomparams = ['where' => ['project_id' => $projectId]];
                $projectRooms = $this->ProjectRooms->fetchData($roomparams);
                $this->ProjectRoomQuotation->cloneQuotation($projectRooms, $filterQuotation, $roomQuotations, $this->user);
            }

            $this->db->trans_commit();
            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_cloned_success')
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
                'label' => 'Task',
                'field' => 'task',
                'rules' => 'trim|required|regex_match[/^(clone)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_task_supplied')
                ]
            ],
        ]);
    }
}
