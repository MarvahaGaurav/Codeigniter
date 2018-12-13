<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";
require_once APPPATH . "/libraries/Traits/TechnicianChargesCheck.php";

class ProjectPriceController extends BaseController
{
    use TechnicianChargesCheck;

    private $requestData;

    public function __construct()
    {
        parent::__construct();
    }

    public function installerFinalPrice()
    {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            if (isset($this->requestData['project_id'])) {
                $this->requestData['project_id'] = encryptDecrypt($this->requestData['project_id'], 'decrypt');
            }

            $this->validateAddCharges();

            $projectId = $this->requestData['project_id'];
            $additionalProductCharges = isset($this->requestData['additional_product_charges'])?$this->requestData['additional_product_charges']:0.00;
            $discount = isset($this->requestData['discount'])?$this->requestData['discount']:0.00;

            $projectData = $this->UtilModel->selectQuery('id, user_id, company_id', 'projects', [
                'where' => ['id' => $projectId], 'single_row' => true
            ]);

            if ((int)$projectData['company_id'] !== (int)$this->userInfo['company_id']) {
                json_dump([
                    'success' => false,
                    'error' => $this->lang->line('forbidden_action')
                ]);
            }

            $this->handleTechnicianChargesCheck($projectId, 'xhr');

            $insertData = [
                'project_id' => (int)$projectData['id'],
                'company_id' => $this->userInfo['company_id'],
                'user_id' => $this->userInfo['user_id'],
                'language_code' => 'en',
                'additional_product_charges' => isset($this->requestData['additional_product_charges'])?$this->requestData['additional_product_charges']:0.00,
                'discount' => isset($this->requestData['discount'])?$this->requestData['discount']:0.00,
                'created_at' => $this->datetime,
                'created_at_timestamp' => $this->timestamp,
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => $this->timestamp,
            ];

            $this->UtilModel->insertTableData($insertData, 'project_technician_charges');
            
            $this->session->set_flashdata("flash-message", $this->lang->line('technician_charges_added'));
            $this->session->set_flashdata("flash-type", "success");
            json_dump([
                'success' => true,
                'message' => $this->lang->line('technician_charges_added')
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


    public function installerFinalQuotePrice()
    {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            //pr($this->requestData);

            if (isset($this->requestData['project_id'])) {
                $this->requestData['project_id'] = encryptDecrypt($this->requestData['project_id'], 'decrypt');
            }

            if (isset($this->requestData['request_id'])) {
                $this->requestData['request_id'] = encryptDecrypt($this->requestData['request_id'], 'decrypt');
            }

            

            $this->validateAddCharges();

            $projectId = $this->requestData['project_id'];
            $additionalProductCharges = isset($this->requestData['additional_product_charges'])?$this->requestData['additional_product_charges']:0.00;
            $discount = isset($this->requestData['discount'])?$this->requestData['discount']:0.00;

            $projectData = $this->UtilModel->selectQuery('id, user_id, company_id', 'projects', [
                'where' => ['id' => $projectId], 'single_row' => true
            ]);

            
            $this->handleTechnicianChargesCheck($projectId, 'xhr');

            $insertData = [
                'request_id' => (int)$this->requestData['request_id'],
                'company_id' => $this->userInfo['company_id'],
                'user_id' => $this->userInfo['user_id'],
                'language_code' => 'en',
                'additional_product_charges' => isset($this->requestData['additional_product_charges'])?$this->requestData['additional_product_charges']:0.00,
                'discount' => isset($this->requestData['discount'])?$this->requestData['discount']:0.00,
                'created_at' => $this->datetime,
                'created_at_timestamp' => $this->timestamp,
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => $this->timestamp,
            ];

           

            $this->UtilModel->insertTableData($insertData, 'project_quotations');
            
            $this->session->set_flashdata("flash-message", $this->lang->line('final_quote_price_added'));
            $this->session->set_flashdata("flash-type", "success");
            json_dump([
                'success' => true,
                'message' => $this->lang->line('final_quote_price_added')
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

    private function validateAddCharges()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'additional_product_charges',
                'label' => 'Additional product charges',
                'rules' => 'trim|numeric|greater_than_equal_to[0]'
            ],
            [
                'field' => 'discount',
                'label' => 'Discount',
                'rules' => 'trim|numeric|greater_than_equal_to[0]|less_than_equal_to[100]'
            ]
        ]);

        $status = $this->form_validation->run();
        
        if (!$status) {
            $errorMessage = $this->form_validation->error_array();
            json_dump(
                [
                    "success" => false,
                    "error" => array_shift($errorMessage),
                ]
            );
        }
    }
}
