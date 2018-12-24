<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";
require_once APPPATH . "/libraries/Traits/TechnicianChargesCheck.php";
require_once APPPATH . "/libraries/Traits/Notifier.php";

class ProjectPriceController extends BaseController
{
    use TechnicianChargesCheck,Notifier ;

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
                'expire_at' =>date("Y-m-d", strtotime($this->requestData['expiry_date'])),
                'expire_at_timestamp' => strtotime($this->requestData['expiry_date'])
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

           // pr($this->requestData);

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

            // check existing quotation

            $quotationData = $this->UtilModel->selectQuery('id','project_quotations', [
                'where' => ['request_id' => (int)$this->requestData['request_id'], 'company_id'=>$this->userInfo['company_id'],'user_id'=>$this->userInfo['user_id']], 'single_row' => true
            ]);

            

            if(empty($quotationData)) {
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
                    'expire_at' =>date("Y-m-d", strtotime($this->requestData['expiry_date'])),
                    'expire_at_timestamp' => strtotime($this->requestData['expiry_date'])
                ];         
    
                //pr($insertData);
                
                $this->UtilModel->insertTableData($insertData, 'project_quotations');

                
            } else {
                // update the quotations

                $updateData = [
                    'language_code' => 'en',
                    'additional_product_charges' => isset($this->requestData['additional_product_charges'])?$this->requestData['additional_product_charges']:0.00,
                    'discount' => isset($this->requestData['discount'])?$this->requestData['discount']:0.00,
                    'created_at' => $this->datetime,
                    'created_at_timestamp' => $this->timestamp,
                    'updated_at' => $this->datetime,
                    'updated_at_timestamp' => $this->timestamp,
                    'expire_at' =>date("Y-m-d", strtotime($this->requestData['expiry_date'])),
                    'expire_at_timestamp' => strtotime($this->requestData['expiry_date'])
                ];    
                
                //pr($updateData);
    
                $this->UtilModel->updateTableData($updateData, 'project_quotations', ['id' =>$quotationData['id'] ]);
                
            }
            
            
            
            // send push to requester

            $this->notifySendQuote($this->userInfo['user_id'], $projectData['user_id'], $this->requestData['request_id']);

            $status = 
            
            $this->session->set_flashdata("flash-message", $this->lang->line('final_quote_price_added'));
            $this->session->set_flashdata("flash-type", "success");
            json_dump([
                'success' => true,
                'message' => $this->lang->line('final_quote_price_added')
            ]);
        } catch (\Exception $error) {
            echo $error;
            json_dump(
                [
                    "success" => false,
                    "error" => "Internal Server Error",
                ]
            );
        }
    }

    public function sendMailToCustomer()
    {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            /*****************send email to user ******************/
           
            $this->load->library('Commonfn');

            $data['subject'] = 'Quote Submitted';
            $data['email'] = $this->requestData['email'];
            $data['mailerName'] = 'sendquote';
            $isSend =$this->commonfn->sendEmailToUser($data);
            
            $status = 
            
            $this->session->set_flashdata("flash-message", $this->lang->line('mail-sent-sucessfully'));
            $this->session->set_flashdata("flash-type", "success");
            json_dump([
                'success' => true,
                'message' => $this->lang->line('mail-sent-sucessfully')
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

    public function approveQuote() {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            

            $request_id = $this->requestData['quotation_id'];
            $request_id = encryptDecrypt($request_id, 'decrypt');

            $updateData['status']=  QUOTATION_STATUS_APPROVED;
            $updateData['updated_at']= $this->datetime;
            $updateData['updated_at_timestamp'] = $this->timestamp;

            
            $this->UtilModel->updateTableData($updateData, 'project_quotations', [
                    'id' => $request_id
            ]);


            $requestData = $this->UtilModel->selectQuery('request_id', 'project_quotations', [
                'where' => ['id' => $request_id], 'single_row' => true
            ]);

            $requestData['approved_at'] = $this->datetime;
            $requestData['approved_at_timestamp'] = $this->timestamp;
            
            $this->UtilModel->updateTableData($updateData, 'project_requests', [
                'id' => $requestData['request_id']
            ]);
            $projectData = $this->UtilModel->selectQuery('project_id', 'project_requests', [
                'where' => ['project_id' => $requestData['project_id']], 'single_row' => true
            ]);


            // send push to installer

            $this->notifyAcceptedQuotes($this->userInfo['user_id'], $request_id, $projectData['project_id']);

            $status = 
            
            $this->session->set_flashdata("flash-message", $this->lang->line('quote-approved'));
            $this->session->set_flashdata("flash-type", "success");
            json_dump([
                'success' => true,
                'message' => $this->lang->line('quote-approved-success')
            ]);
        } catch (\Exception $error) {
            pr($error);
            json_dump(
                [
                    "success" => false,
                    "error" => "Internal Server Error",
                ]
            );
        }
    }


    public function rejectQuote() {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            $request_id = $this->requestData['quotation_id'];
            $request_id = encryptDecrypt($request_id, 'decrypt');

            $updateData['status']=  QUOTATION_STATUS_REJECTED;
            $updateData['updated_at']= $this->datetime;
            $updateData['updated_at_timestamp'] = $this->timestamp;

            
            $this->UtilModel->updateTableData($updateData, 'project_quotations', [
                    'id' => $request_id
            ]);

            $status = 
            
            $this->session->set_flashdata("flash-message", $this->lang->line('quote-rejected'));
            $this->session->set_flashdata("flash-type", "success");
            json_dump([
                'success' => true,
                'message' => $this->lang->line('quote-rejected-success')
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
}
