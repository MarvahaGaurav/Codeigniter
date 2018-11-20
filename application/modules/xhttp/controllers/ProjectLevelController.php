<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

/**
 * @property array $data  array of values for view
 * @property array $userInfo session data
 * @property array $user_query_fields - table fields for user table
 * @property array $session_data - session data
 */

class ProjectLevelController extends BaseController
{
    private $requestData;

    public function __construct()
    {
        parent::__construct();
    }

    public function markAsDone()
    {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            if (isset($this->requestData['project_id'])) {
                $this->requestData['project_id'] = encryptDecrypt($this->requestData['project_id'], 'decrypt');
            }

            $this->validateMarkAsDone();

            $projectData = $this->UtilModel->selectQuery('user_id, id, company_id', 'projects', [
                'where' => ['id' => $this->requestData['project_id']], 'single_row' => true
            ]);

            if (empty($projectData)) {
                json_dump(
                    [
                        "success" => false,
                        "error" => $this->lang->line('no_data_found')
                    ]
                );
            }

            $roomData = $this->UtilModel->selectQuery('id', 'project_rooms', [
                'where' => ['project_id' => $this->requestData['project_id'], 'level' => $this->requestData['level']],
                'single_row' => true
            ]);

            if (empty($roomData)) {
                json_dump(
                    [
                        "success" => false,
                        "error" => $this->lang->line('add_rooms_to_mark_as_done')
                    ]
                );
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                json_dump(
                    [
                        "success" => false,
                        "error" => $this->lang->line('forbidden_action')
                    ]
                );
            }

            $this->UtilModel->updateTableData([
                'status' => 1
            ], 'project_levels', [
                'project_id' => $this->requestData['project_id'], 'level' => $this->requestData['level']
            ]);


            json_dump(
                [
                    "success" => true,
                    "message" => $this->lang->line('level_marked_done'),
                    'button_message' => $this->lang->line('done')
                ]
            );
        } catch (\Exception $error) {
            json_dump(
                [
                    "success" => false,
                    "error" => "Internal Server Error",
                ]
            );
        }
    }

    /**
     * Clone Levels
     *
     * @return void
     */
    public function levelClone()
    {
        error_reporting(-1);
        ini_set('display_errors', 1);
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            if (isset($this->requestData['project_id'])) {
                $this->requestData['project_id'] = encryptDecrypt($this->requestData['project_id'], 'decrypt');
            }

            $this->validateMakeClone();

            $projectData = $this->UtilModel->selectQuery('user_id, company_id', 'projects', [
                'where' => ['id' => $this->requestData['project_id']],
                'single_row' => true
            ]);

            if (empty($projectData)) {
                json_dump([
                    'success' => false,
                    'msg' => $this->lang->line('project_not_found')
                ]);
            }

            $isOwnProject = false;
            if (in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $isOwnProject = (int)$this->userInfo['user_id'] === (int)$projectData['user_id'];
            } else {
                $isOwnProject = (int)$this->userInfo['company_id'] === (int)$projectData['company_id'];
            }

            if (!$isOwnProject) {
                json_dump([
                    'success' => false,
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
                'where_in' => ['level' => [$this->requestData['destination_levels']]],
                'where' => ['project_id' => $this->requestData['project_id']]
            ]);

            $this->ProjectRooms->cloneLevelRooms($roomsData, [$this->requestData['destination_levels']], $time);

            $cloneRoomParams = [
                'where' => ['project_id' => $this->requestData['project_id']],
                'where_in' => ['level' => [$this->requestData['destination_levels']]]
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
            
            $this->session->set_flashdata("flash-message", $this->lang->line('level_clone_successful'));
            $this->session->set_flashdata("flash-type", "success");
            json_dump([
                'success' => true,
                'msg' => $this->lang->line('level_clone_successful')
            ]);
        } catch (\Exception $error) {
            json_dump(
                [
                    "success" => false,
                    "error" => "Internal Server Error",
                ]
            );
        }
    }
    /**
     * Validate mark as done
     *
     * @return void
     */
    private function validateMarkAsDone()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);

        $status = $this->form_validation->run();

        if (!$status) {
            json_dump(
                [
                    "success" => false,
                    "error" => $this->lang->line('bad_request'),
                ]
            );
        }
    }

    /**
     * Validate make clone
     *
     * @return void
     */
    private function validateMakeClone()
    {
        $this->load->library('form_validation');

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
                'field' => 'destination_levels',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    
        $status = $this->form_validation->run();

        if (!$status) {
            json_dump(
                [
                    "success" => false,
                    "msg" => $this->lang->line('bad_request'),
                ]
            );
        }
    }
}
