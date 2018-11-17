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
}
