<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class UserController extends BaseController
{
    private $requestData;

    public function __construct()
    {
        error_reporting(-1);
        ini_set('display_errors', 1);
        parent::__construct();
        $this->load->model(['User']);
        $this->load->library(['form_validation']);
    }


    public function checkEmail()
    {
        try {
            $this->requestData = $this->input->post();

            $status = $this->emailValidation();

            if (!$status) {
                json_repsonse([
                    'success' => false,
                    'msg' => $this->lang->line('invalid_email')
                ], HTTP_OK);
            }

            $params['where']['email'] = trim($this->requestData['email']);
            $data = $this->User->fetchUser($params, true);

            if (!empty($data)) {
                json_repsonse([
                    'success' => false,
                    'msg' => $this->lang->line('email_taken')
                ], HTTP_OK);
            }

            json_repsonse([
                'success' => true,
                'msg' => $this->lang->line('success')
            ]);
        } catch (\Exception $error) {
            json_repsonse([
                'success' => false,
                'msg' => $this->lang->line('internal_server_error')
            ], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkPrimaryPhoneNumber()
    {
        try {
            $this->requestData = $this->input->post();

            $status = $this->validatePhoneNumber();

            if (!$status) {
                json_repsonse([
                    'success' => false,
                    'msg' => $this->lang->line('error')
                ], HTTP_OK);
            }

            $params['where']['prm_user_countrycode'] = trim($this->requestData['country_code']);
            $params['where']['phone'] = trim($this->requestData['phone_number']);
            $data = $this->User->fetchUser($params, true);

            if (!empty($data)) {
                json_repsonse([
                    'success' => false,
                    'msg' => $this->lang->line('phone_number_taken')
                ], HTTP_OK);
            }

            json_repsonse([
                'success' => true,
                'msg' => $this->lang->line('success')
            ]);
        } catch (\Exception $error) {
            json_repsonse([
                'success' => false,
                'msg' => $this->lang->line('internal_server_error')
            ], HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkAlternatePhoneNumber()
    {
        try {
            $this->requestData = $this->input->post();

            $status = $this->validatePhoneNumber();

            if (!$status) {
                json_repsonse([
                    'success' => false,
                    'msg' => $this->lang->line('error')
                ], HTTP_OK);
            }

            $params['where']['alt_user_countrycode'] = trim($this->requestData['country_code']);
            $params['where']['alt_userphone'] = trim($this->requestData['phone_number']);
            $data = $this->User->fetchUser($params, true);

            if (!empty($data)) {
                json_repsonse([
                    'success' => false,
                    'msg' => $this->lang->line('phone_number_taken')
                ], HTTP_OK);
            }

            json_repsonse([
                'success' => true,
                'msg' => $this->lang->line('success')
            ]);
        } catch (\Exception $error) {
            json_repsonse([
                'success' => false,
                'msg' => $this->lang->line('internal_server_error')
            ], HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    private function emailValidation()
    {
        $this->form_validation->set_rules([
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ]
        ]);

        $status = $this->form_validation->run();

        return (bool)$status;
    }

    private function validatePhoneNumber()
    {
        $this->form_validation->set_rules([
            [
                'field' => 'country_code',
                'label' => 'Country Code',
                'rules' => 'trim|required|integer'
            ],
            [
                'field' => 'phone_number',
                'label' => 'phone_number',
                'rules' => 'trim|required|integer'
            ]
        ]);

        $status = $this->form_validation->run();

        return (bool)$status;
    }
}
