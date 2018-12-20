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
    }

    public function sendRequestQuotationMail_post()
    {
        try {
            $userData = $this->accessTokenCheck('u.is_owner, u.user_type, u.company_id, u.email');
            $languageCode = $this->langcode_validate();

            $this->requestData = $this->post();

            $this->valiateRequestQuotationMail();

            $this->validationRun();

            $this->load->model(['ProjectRequest']);

            $request = $this->ProjectRequest->checkRequestForInstaller();

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('quotation_mail_success')
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

            $this->requestData = $this->post();

            $this->valiateProjectQuotationMail();

            $this->validationRun();

            $this->requestData = trim_input_parameters($this->requestData);

            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $this->requestData['project_id'] ]
            ]);

            if (empty($projectData)) {

            }


            $this->load->library(['Generate_pdf', 'Commonfn']);



            $this->generate_pdf->getPdf($this->requestData['project_id'], '');


            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('quotation_mail_success')
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
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
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
            ]
        ]);
    }

}
