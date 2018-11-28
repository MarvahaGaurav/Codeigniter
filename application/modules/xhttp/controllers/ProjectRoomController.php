<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class ProjectRoomController extends BaseController
{
    private $requestData;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Increments room count
     *
     * @return void
     */
    public function incrementRoomCount()
    {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            if (isset($this->requestData['project_room_id'])) {
                $this->requestData['project_room_id'] = encryptDecrypt($this->requestData['project_room_id'], 'decrypt');
            }

            $this->validateRoomCount();

            $this->load->model(['ProjectRooms']);

            $params['where']['pr.id'] = $this->requestData['project_room_id'];
            $projectData = $this->ProjectRooms->projectAndRoomData($params);

            if (empty($projectData)) {
                json_dump([
                    'success' => false,
                    'error' => $this->lang->line('no_data_found')
                ]);
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

            $updatedCount = $projectData['count'] + 1;
            $this->UtilModel->updateTableData([
                'count' => $updatedCount
            ], 'project_rooms', [
                'id' => $this->requestData['project_room_id']
            ]);

            json_dump([
                'success' => true,
                'count' => $updatedCount
            ]);
        } catch (\Exception $error) {
            json_dump([
                'success' => false,
                'msg' => $this->lang->line('internal_server_error')
            ]);
        }
    }

    /**
     * decrement room count
     *
     * @return void
     */
    public function decrementRoomCount()
    {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            if (isset($this->requestData['project_room_id'])) {
                $this->requestData['project_room_id'] = encryptDecrypt($this->requestData['project_room_id'], 'decrypt');
            }

            $this->validateRoomCount();

            $this->load->model(['ProjectRooms']);

            $params['where']['pr.id'] = $this->requestData['project_room_id'];
            $projectData = $this->ProjectRooms->projectAndRoomData($params);

            if (empty($projectData)) {
                json_dump([
                    'success' => false,
                    'error' => $this->lang->line('no_data_found')
                ]);
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

            if ((int)$projectData['count'] === 1) {
                json_dump([
                    'success' => true,
                    'count' => (int)$projectData['count']
                ]);
            }

            $updatedCount = $projectData['count'] - 1;
            $this->UtilModel->updateTableData([
                'count' => $updatedCount
            ], 'project_rooms', [
                'id' => $this->requestData['project_room_id']
            ]);

            json_dump([
                'success' => true,
                'count' => $updatedCount
            ]);
        } catch (\Exception $error) {
            json_dump([
                'success' => false,
                'msg' => $this->lang->line('internal_server_error')
            ]);
        }
    }


    private function validateRoomCount()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_room_id',
                'label' => 'Project Room',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);

        $status = $this->form_validation->run();

        if (!(bool)$status) {
            $errorMessage = $this->form_validation->error_array();
            json_dump([
                'success' => false,
                'error' => $this->lang->line('bad_request')
            ]);
        }

    }

    /**
     * Add price
     *
     * @return void
     */
    public function addRoomQuotation()
    {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            if (isset($this->requestData['project_room_id'])) {
                $this->requestData['project_room_id'] = encryptDecrypt($this->requestData['project_room_id'], 'decrypt');
            }

            $this->validateAddRoomQuotation();

            $projectRoomData = $this->UtilModel->selectQuery('*', 'project_rooms', [
                'where' => ['id' => $this->requestData['project_room_id']], 'single_row' => true
            ]);

            if (empty($projectRoomData)) {
                json_dump([
                    'success' => false,
                    'error' => $this->lang->line('no_data_found')
                ]);
            }

            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectRoomData['project_id']], 'single_row' => true
            ]);

            if (empty($projectData)) {
                json_dump([
                    'success' => false,
                    'error' => $this->lang->line('no_data_found')
                ]);
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

            $projectRoomQuotation = $this->UtilModel->selectQuery('id', 'project_room_quotations', [
                'where' => ['project_room_id' => $this->requestData['project_room_id']], 'single_row' => true
            ]);

            $projectRoomQuotationData = [
                "price_per_luminaries" => $this->requestData['price_per_luminaries'],
                "installation_charges" => $this->requestData['installation_charges'],
                "discount_price" => $this->requestData['discount_price'],
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => $this->timestamp
            ];

            if (empty($projectRoomQuotation)) {
                $projectRoomQuotationData["project_room_id"] = $this->requestData['project_room_id'];
                $projectRoomQuotationData['created_at'] = $this->datetime;
                $projectRoomQuotationData['created_at_timestamp'] = $this->timestamp;
                if (in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                    $projectRoomQuotationData['user_id'] = $this->userInfo['user_id'];
                } else {
                    $projectRoomQuotationData['user_id'] = $this->userInfo['user_id'];
                    $projectRoomQuotationData['company_id'] = $this->userInfo['company_id'];
                }

                $this->UtilModel->insertTableData($projectRoomQuotationData, 'project_room_quotations');
                $message = $this->lang->line('room_quotation_added');
            } else {
                $this->UtilModel->updateTableData($projectRoomQuotationData, 'project_room_quotations', [
                    'project_room_id' => $this->requestData['project_room_id']
                ]);
                $message = $this->lang->line('room_quotation_updated');
            }

            $this->load->helper('utility');
            json_dump([
                'success' => true,
                'msg' => $message,
                'data' => [
                    "price_per_luminaries" => $this->requestData['price_per_luminaries'],
                    "installation_charges" => $this->requestData['installation_charges'],
                    "discount_price" => $this->requestData['discount_price'],
                    "subtotal" => $this->requestData['price_per_luminaries'] + $this->requestData['installation_charges'],
                    'total' => get_percentage($this->requestData['price_per_luminaries'] + $this->requestData['installation_charges'], $this->requestData['discount_price'])
                ]
            ]);
        } catch (\Exception $error) {
            json_dump([
                'success' => false,
                'error' => $this->lang->line('internal_server_error')
            ]);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function validateAddRoomQuotation()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_room_id',
                'label' => 'Project Room Id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'price_per_luminaries',
                'label' => 'Price per luminaries',
                'rules' => 'trim|required|numeric|greater_than[0]'
            ],
            [
                'field' => 'installation_charges',
                'label' => 'Installation charges',
                'rules' => 'trim|required|numeric|greater_than[0]'
            ],
            [
                'field' => 'discount_price',
                'label' => 'Discount price',
                'rules' => 'trim|required|numeric|greater_than_equal_to[0]'
            ]
        ]);

        $status = $this->form_validation->run();

        if (!$status) {
            json_dump(
                [
                    "success" => false,
                    "msg" => $this->lang->line('bad_request'),
                    'error' => $this->form_validation->error_array()
                ]
            );
        }
    }
}