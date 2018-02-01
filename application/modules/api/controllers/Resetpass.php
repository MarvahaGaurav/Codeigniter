<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Resetpass extends REST_Controller {
    function __construct() {
        parent::__construct();

        $this->load->model('Common_model');
        $this->load->library('form_validation');
        $this->load->library('commonfn');
        $this->load->library('encrypt');
    }

    /**
     * @SWG\Post(path="/user/password/reset",
     *   tags={"User"},
     *   summary="Reset Password",
     *   description="Reset Password",
     *   operationId="reset_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="user_id",
     *     in="formData",
     *     description="userId",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="New password",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Reset Password Success"),
     *   @SWG\Response(response=206, description="Unauthorized request"),     
     *   @SWG\Response(response=207, description="Header is missing"),   
     *   @SWG\Response(response=301, description="Password already set"),       
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     *   @SWG\Response(response=501, description="Please try again"),
     * )
     */
    public function index_post() {

        $postDataArr = $this->post();

        $config = array(
            array(
                'field' => 'user_id',
                'label' => 'user Id',
                'rules' => 'required'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {

            $userId = $postDataArr['user_id'];
            $password = $postDataArr['password'];

            $resparr = array();

            $where = array('where' => array('user_id' => $userId));
            $userInfo = $this->Common_model->fetch_data('ai_user', 'user_id,is_otp_verified', $where, true);
            
            if (!empty($userInfo) && $userInfo['is_otp_verified'] == '1') {
                
                /*
                 * Encrypt the password
                 */
                $password = encrypt($password);
                $updatearr = array('password' => $password);
                $where = array('where' => array('user_id' => $userId));
                try {
                    $issuccess = $this->Common_model->update_single('ai_user', $updatearr, $where);
                } catch (Exception $ex) {
                    $resparr = array('code' => TRY_AGAIN_CODE, 'msg' => $ex->getMessage());
                }
                if ($issuccess) {
                    $resparr = array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('password_reset_success'), 'result' => (object)[]);
                } else {
                    $resparr = array('code' => TRY_AGAIN_CODE, 'msg' => $this->lang->line('password_exist'), 'result' => (object)[]);
                }
            } else {
                $resparr = array('code' => OTP_NOT_VERIFIED, 'msg' => $this->lang->line('otp_not_verified'), 'result' => (object)[]);
            }
            $this->response($resparr);
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }

}
