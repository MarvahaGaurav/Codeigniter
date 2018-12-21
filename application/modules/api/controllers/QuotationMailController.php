<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseController.php';

class QuotationMailController extends BaseController
{

    /**
     * Request data
     *
     * @var array
     */
    private $requestData;

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['form_validation']);
    }

    public function sendRequestQuotationMail_post()
    {
        try {
            $userData = $this->accessTokenCheck('u.is_owner, u.user_type, u.company_id, u.email');
            $languageCode = $this->langcode_validate();

            $this->user = $userData;

            $this->userTypeHandling([INSTALLER]);

            $this->handleEmployeePermission([INSTALLER], ['project_add']);

            $this->requestData = $this->post();

            $this->requestData = trim_input_parameters($this->requestData);

            $this->valiateRequestQuotationMail();

            $this->validationRun();

            $this->load->model(['Project', 'ProjectRequest', 'ProjectQuotation']);

            $request = $this->ProjectRequest->request($this->requestData['request_id']);

            if (empty($request)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_request_found')
                ]);
            }

            $projectData = $this->Project->project($request['project_id']);

            $installerRequest = $this->ProjectRequest->checkRequestForInstaller(
                $request['project_id'],
                $userData['company_id']
            );

            if (empty($installerRequest)) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('forbidden_action')
                ]);
            }

            $quotation = $this->ProjectQuotation->quotation(
                $request['project_id'],
                $userData['company_id']
            );

            if (empty($quotation)) {
                $quotationData = [
                    'language_code' => $languageCode,
                    'request_id' => $this->requestData['request_id'],
                    'company_id' => $userData['company_id'],
                    'user_id' => $userData['user_id'],
                    'additional_product_charges' =>
                        isset($this->requestData['additional_product_charges']) ? (double)$this->requestData['additional_product_charges'] : 0.00,
                    'discount' =>
                        isset($this->requestData['discount']) ? (double)$this->requestData['discount'] : 0.00,
                    'created_at' => $this->datetime,
                    'created_at_timestamp' => time(),
                    'updated_at' => $this->datetime,
                    'updated_at_timestamp' => time()
                ];

                if (isset($this->requestData['expiry_time'])) {
                    $quotationData['expire_at'] = date("Y-m-d H:i:s", $this->requestData['expiry_time']);
                    $quotationData['expire_at_timestamp'] = $this->requestData['expiry_time'];
                } else {
                    $expiryMonths = strtotime("+" . QUOTATION_EXPIRY_MONTH . " months");
                    $quotationData['expire_at'] = date("Y-m-d H:i:s", $expiryMonths);
                    $quotationData['expire_at_timestamp'] = $expiryMonths;
                }

                $quotationId = $this->UtilModel->insertTableData($quotationData, 'project_quotations', true);
            }

            $customerData = $this->UtilModel->selectQuery(
                'email, first_name as full_name',
                'ai_user',
                ['where' => ['user_id' => $projectData['user_id']], 'single_row' => true]
            );

            $this->load->library(['Generate_pdf', 'Commonfn', 'Excel']);

            $content = $this->generate_pdf->getPdf(
                $request['project_id'],
                $userData['company_id'],
                $projectData['name'],
                "string"
            );
            
            $path = $this->excel->generateXls($request['project_id'], $userData['company_id']);

            $this->commonfn->sendMailWithAttachment(
                sprintf($this->lang->line('quotations_for_project'), $projectData['name']),
                ['email' => $customerData['email']],
                'sendquote',
                [
                    [
                        "attachment" => $content,
                        "name" => sprintf($this->lang->line('quotation_file_name'), $projectData['name']),
                        "mime" => "application/pdf"
                    ],
                    [
                        "attachment" => $path,
                        "name" => sprintf($this->lang->line('quotation_file_name'), $projectData['name']),
                        "mime" => ""
                    ]
                ]
            );

            unlink($path);

            $this->response([
                'code' => HTTP_OK,
                'msg' => sprintf($this->lang->line('quotation_mail_success'), $customerData['email'])
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    public function sendProjectQuotationMail_post()
    {
        try {
            $userData = $this->accessTokenCheck('u.is_owner, u.user_type, u.company_id, u.email');
            $languageCode = $this->langcode_validate();

            $this->user = $userData;

            $this->userTypeHandling([INSTALLER]);

            $this->handleEmployeePermission([INSTALLER], ['project_add']);

            $this->requestData = $this->post();

            $this->valiateProjectQuotationMail();

            $this->validationRun();

            $this->requestData = trim_input_parameters($this->requestData, 'false');

            $this->load->model(['Project', 'ProjectTechnicianCharges']);

            $projectData = $this->Project->project($this->requestData['project_id']);

            if (empty($projectData)) {
                $this->repsonse([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_project_found')
                ]);
            }

            if ((int)$projectData['company_id'] !== (int)$userData['company_id']) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('forbidden_action')
                ]);
            }

            $quoteData = $this->ProjectTechnicianCharges->technicianProjectCharges($this->requestData['project_id']);

            if (empty($quoteData)) {
                $insertData = [
                    'project_id' => (int)$projectData['id'],
                    'company_id' => $this->user['company_id'],
                    'user_id' => $this->user['user_id'],
                    'language_code' => $languageCode,
                    'additional_product_charges' => isset($this->requestData['additional_product_charges']) ? $this->requestData['additional_product_charges'] : 0.00,
                    'discount' => isset($this->requestData['discount']) ? $this->requestData['discount'] : 0.00,
                    'created_at' => $this->datetime,
                    'created_at_timestamp' => $this->timestamp,
                    'updated_at' => $this->datetime,
                    'updated_at_timestamp' => $this->timestamp,
                ];

                if (isset($this->requestData['expiry_time'])) {
                    $insertData['expire_at'] = date("Y-m-d H:i:s", $this->requestData['expiry_time']);
                    $insertData['expire_at_timestamp'] = $this->requestData['expiry_time'];
                } else {
                    $expiryMonths = strtotime("+" . QUOTATION_EXPIRY_MONTH . " months");
                    $insertData['expire_at'] = date("Y-m-d H:i:s", $expiryMonths);
                    $insertData['expire_at_timestamp'] = $expiryMonths;
                }

                $this->UtilModel->insertTableData($insertData, 'project_technician_charges');
            }

            $this->load->library(['Generate_pdf', 'Commonfn', 'Excel']);

            $path = $this->excel->generateXls($this->requestData['project_id'], $userData['company_id']);

            $content = $this->generate_pdf->getPdf(
                $this->requestData['project_id'],
                $userData['company_id'],
                $projectData['name'],
                "string"
            );

            $this->commonfn->sendMailWithAttachment(
                sprintf($this->lang->line('quotations_for_project'), $projectData['name']),
                ['email' => $this->requestData['email']],
                'sendquote',
                [
                    [
                        "attachment" => $content,
                        "name" => sprintf($this->lang->line('quotation_file_name'), $projectData['name']) . ".pdf",
                        "mime" => 'application/pdf'
                    ],
                    [
                        "attachment" => $path,
                        "name" => sprintf($this->lang->line('quotation_file_name'), $projectData['name']) . ".xls",
                        "mime" => ""
                    ]
                ]
            );

            unlink($path);

            $this->response([
                'code' => HTTP_OK,
                'msg' => sprintf($this->lang->line('quotation_mail_success'), $this->requestData['email'])
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    private function valiateRequestQuotationMail()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'request_id',
                'label' => 'Request ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'additional_product_charges',
                'label' => 'Additional product charges',
                'rules' => 'trim|numeric'
            ],
            [
                'field' => 'discount',
                'label' => 'Discount',
                'rules' => 'trim|numeric'
            ],
            [
                'field' => 'expiry_time',
                'label' => 'Expiry Time',
                'rules' => 'trim|numeric'
            ]
        ]);
    }

    private function valiateProjectQuotationMail()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
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
            ],
            [
                'field' => 'expiry_time',
                'label' => 'Expiry Time',
                'rules' => 'trim|numeric'
            ]
        ]);
    }

}
