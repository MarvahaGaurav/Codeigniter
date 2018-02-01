<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Login extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
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
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="Password",
     *     required=true,
     *     type="string"
     *   ),
     *    @SWG\Parameter(
     *     name="device_id",
     *     in="formData",
     *     description="Unique Device Id",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="device_token",
     *     in="formData",
     *     description="Device Token required to send push",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="platform",
     *     in="formData",
     *     description="1: Android and 2: iOS",
     *     type="string"
     *   ),
     *   @SWG\Response(response=101, description="Account Blocked"),
     *   @SWG\Response(response=200, description="Login Success"),
     *   @SWG\Response(response=206, description="Unauthorized request"),     
     *   @SWG\Response(response=207, description="Header is missing"),       
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function index_post() {

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
                $userInfo = $this->Common_model->fetch_data('ai_user', 'company_id, user_id,first_name,email,IF(image !="",CONCAT("' . IMAGE_PATH . '","",image),"") as image,IF(image_thumb !="",CONCAT("' . THUMB_IMAGE_PATH . '","",image_thumb),"") as image_thumb,status,user_type,is_owner', array('where' => array('email' => $email, 'password' => $encrypt_pass)), true);
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
                            if (!empty($isExist)) {
                                $isSuccess = $this->Common_model->update_single('ai_session', $sessionArr, $whereArr);
                            } else {
                                $isSuccess = $this->Common_model->insert_single('ai_session', $sessionArr);
                            }
                            if (!$isSuccess) {
                                throw new Exception($this->lang->line('try_again'));
                            }
                        }
                        if ($this->db->trans_status() === TRUE) {
                            $this->db->trans_commit();
                            $userInfo['accesstoken'] = $accessToken['public_key'] . '||' . $accessToken['private_key'];
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

}
