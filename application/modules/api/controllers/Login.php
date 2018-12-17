<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Login extends REST_Controller
{

    function __construct() 
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('User');
        $this->load->helper('email');
        $this->load->library('form_validation');
    }

    /**
     * @SWG\Post(path="/user/login",
     *   tags={"User"},
     *   summary="Login Information",
     *   description="Login Information",
     *   operationId="login_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="Password",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="device_id",
     *     in="formData",
     *     description="Unique Device Id",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="device_token",
     *     in="formData",
     *     description="Device Token required to send push",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="platform",
     *     in="formData",
     *     description="1: Android and 2: iOS",
     *     type="string"
     *   ),
     * @SWG\Response(response=101, description="Account Blocked"),
     * @SWG\Response(response=200, description="Login Success"),
     * @SWG\Response(response=206, description="Unauthorized request"),     
     * @SWG\Response(response=207, description="Header is missing"),       
     * @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function index_post() 
    {
        $language_code = $this->langcode_validate();
        $postDataArr = $this->post();


        $config = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'device_id',
                'label' => 'Device Id',
                'rules' => 'trim|required'
            ),
        );

        $this->form_validation->set_rules($config);
        if ($this->form_validation->run()) {
            try {
                $encrypt_pass = encrypt($postDataArr["password"]);
                $email = $postDataArr['email'];
                $userInfo = $this->User->login($email, $encrypt_pass);
                if (!empty($userInfo)) {
                    /*
                     * Check if user is not blocksed
                     */
                    if ($userInfo['status'] == ACTIVE) {

                        $accessToken = create_access_token($userInfo['user_id'], $email);

                        $postDataArr['user_id'] = $userInfo['user_id'];

                        $sessionArr = [];
                        $sessionArr = setSessionVariables($postDataArr, $accessToken);

                        $login_type = $this->config->item('LOGIN_TYPE');
                        /*
                         * If App Support Single Login
                         */
                        if (IS_SINGLE_DEVICE_LOGIN) {
                            $where = array('where' => array('user_id' => $userInfo['user_id']));
                            $this->Common_model->update_single('ai_session', $sessionArr, $where);
                        } else {
                            /*
                             * If App Support Multiple Login
                             */
                            $whereArr = [];
                            $device_id = isset($postDataArr['device_id']) ? $postDataArr['device_id'] : "";
                            $whereArr['where'] = ['device_id' => $device_id];
                            $isExist = $this->Common_model->fetch_data('ai_session', array('session_id'), $whereArr, true);
                            /*
                             * If user has logged in previously with same device then update his detail
                             * or insert as a new row
                             */
                            $this->db->trans_begin();
                            $sessionArr['login_status'] = '1';
                            $sessionArr['device_token'] = isset($postDataArr['device_token'])&&!empty(trim($postDataArr['device_token']))?$postDataArr['device_token']:"";
                            if (!empty($isExist)) {
                                $isSuccess = $this->Common_model->update_single('ai_session', $sessionArr, $whereArr);
                            } else {
                                $isSuccess = $this->Common_model->insert_single('ai_session', $sessionArr);
                            }
                            if (!$isSuccess) {
                                throw new Exception($this->lang->line('try_again'));
                            }
                        }
                        if ($this->db->trans_status() === true) {
                            $this->db->trans_commit();
                            $userInfo['accesstoken'] = $accessToken['public_key'] . '||' . $accessToken['private_key'];
                            $userInfo['is_employee_approved'] = false;
                            $userInfo['is_employee_rejected'] = false;
                            if ( ROLE_EMPLOYEE === (int)$userInfo['is_owner']) {
                                $this->load->model(['UtilModel']);
                                $employeeApprovalStatus = $this->UtilModel->selectQuery('er_id, status', 'employee_request_master', [
                                    'where' => ['requested_by' => (int)$userInfo['user_id']], 'single_row' => true
                                ]);
                                $userInfo['is_employee_approved'] = (bool)(!empty($employeeApprovalStatus)&&
                                                                (int)$employeeApprovalStatus['status'] === EMPLOYEE_REQUEST_ACCEPTED);
                                $userInfo['is_employee_rejected'] = (bool)(!empty($employeeApprovalStatus)&&
                                                                (int)$employeeApprovalStatus['status'] === EMPLOYEE_REQUEST_REJECTED);
                            }
                            $companyData = (object)[];
                            if (!empty($userInfo['company_id'])) {
                                $this->load->model(['UtilModel']);
                                $companyData = $this->UtilModel->selectQuery('company_name, company_reg_number, company_address, lat, lng, country, city, zipcode, company_image, company_image_thumb, owner_type, insert_date', 'company_master', [
                                    'where' => ['company_id' => $userInfo['company_id']], 'single_row' => true
                                ]);
                            }
                            $userInfo['company'] = $companyData;
                            $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('login_successful'), 'result' => $userInfo));
                        }
                    } else if ($userInfo['status'] == BLOCKED) {
                        $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                    } else {
                        $this->response(array('code' => ACCOUNT_INACTIVE, 'msg' => $this->lang->line('account_inactive'), 'result' => (object)[]));
                    }
                } else {
                    $this->response(array('code' => INVALID_CREDENTIALS, 'msg' => $this->lang->line('invalid_credentials'), 'result' => (object)[]));
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $error, 'result' => (object)[]));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }

    private function langcode_validate()
    {
        $language_code = $this->head("X-Language-Code");
        $language_code = trim($language_code);
        $valid_language_codes = ["en","da","nb","sv","fi","fr","nl","de"];

        if (empty($language_code) ) {
            $this->response(
                [
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('header_missing'),
                'extra_info' => [
                    "missing_parameter" => "language_code"
                ]
                ]
            );
        }

        if (! in_array($language_code, $valid_language_codes) ) {
            $this->response(
                [
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('invalid_header'),
                'extra_info' => [
                    "missing_parameter" => $this->lang->line('invalid_language_code')
                ]
                ]
            );
        }

        $language_map = [
            "en" => "english",
            "da" => "danish",
            "nb" => "norwegian",
            "sv" => "swedish",
            "fi" => "finnish",
            "fr" => "french",
            "nl" => "dutch",
            "de" => "german"
        ];

        $this->load->language("common", $language_map[$language_code]);
        $this->load->language("rest_controller", $language_map[$language_code]);

        return $language_code;
    }

}
