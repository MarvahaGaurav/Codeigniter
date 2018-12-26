<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";
require_once APPPATH . "/libraries/Traits/ProjectDelete.php";
class ProjectController extends BaseController
{
    use ProjectDelete;
    /**
     * Request Data
     *
     * @var array
     */
    private $requestData;

    public function __construct()
    {
        parent::__construct();
    }

    public function projectClone()
    {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            if (isset($this->requestData['project_id'])) {
                $this->requestData['project_id'] = encryptDecrypt($this->requestData['project_id'], 'decrypt');
            }

            $this->validateProjectClone();

            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
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
            $this->load->helper(['db']);
            $insertProjectQuotations = (int)$this->userInfo['user_type'] === INSTALLER && !empty($roomsData);
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
                $this->ProjectRoomQuotation->cloneQuotation($projectRooms, $filterQuotation, $roomQuotations, $this->userInfo);
            }

            $this->db->trans_commit();
            $this->session->set_flashdata("flash-message", $this->lang->line('project_cloned_success'));
            $this->session->set_flashdata("flash-type", "success");

            json_dump([
                'success' => true,
                'message' => $this->lang->line('project_cloned_success')
            ]);
        } catch (\Exception $error) {
            json_dump(
                [
                    "success" => false,
                    "error" => $this->lang->line('internal_server_error'),
                ]
            );
        }
    }

    /**
     * delete project
     *
     * @return void
     */
    public function delete()
    {
        $this->activeSessionGuard();

        $languageCode = "en";

        $this->requestData = $this->input->post();

        if (isset($this->requestData['project_id'])) {
            $this->requestData['project_id'] = encryptDecrypt($this->requestData['project_id'], 'decrypt');
        }

        $this->validateProjectDelete();

        $projectId = $this->requestData['project_id'];

        $projectData = $this->UtilModel->selectQuery('*', 'projects', [
            'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
        ]);

        if (empty($projectData)) {
            json_dump([
                'success' => false,
                'msg' => $this->lang->line('no_project_found')
            ]);
        }

        if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
            (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
            (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
            json_dump([
                'success' => false,
                'msg' => $this->lang->line('forbidden_action')
            ]);
        }

        try {
            $this->projectId = $this->requestData['project_id'];
            $this->deleteProject();

            $this->load->model("UtilModel");

            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();

                $this->session->set_flashdata("flash-message", $this->lang->line('project_deleted'));
                $this->session->set_flashdata("flash-type", "success");

                json_dump([
                    'success' => true,
                    'message' => $this->lang->line('project_deleted')
                ]);
            } else {
                throw new Exception("Something Went Wrong", 500);
            }
        } catch (Exception $ex) {
            json_dump(
                [
                    "success" => false,
                    "error" => $this->lang->line('internal_server_error'),
                ]
            );
        }
    }

    /**
     * Validates project clone
     *
     * @return void
     */
    private function validateProjectClone()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project',
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
     * Validates project clone
     *
     * @return void
     */
    private function validateProjectDelete()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project',
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
