<?php

require APPPATH . 'libraries/REST_Controller.php';

class Changepassword extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->library('form_validation');
    }

    /**
     * @SWG\Post(path="/Changepassword",
     *   tags={"User"},
     *   summary="Change Password",
     *   description="Change Password",
     *   operationId="changepassword_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="oldpassword",
     *     in="formData",
     *     description="oldpassword",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="accesstoken",
     *     in="formData",
     *     description="Access Token",
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
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     *   @SWG\Response(response=490, description="Old password not matched"),
     *   @SWG\Response(response=491, description="New and Old password are same"),
     *   @SWG\Response(response=501, description="Please try again"),
     * )
     */
    public function index_post() {
        $postDataArr = $this->post();
        $config = [];

        $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required'
            ),
            array(
                'field' => 'oldpassword',
                'label' => 'Old Password',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {

            try {
                $this->load->library('commonfn');
                $respArr = $this->Common_model->getUserInfo($postDataArr['accesstoken'], ['u.user_id', 'password', 'status']);
                $user_info = [];
                /*
                 * Response is not success if session expired or invalid access token
                 */
                if ($respArr['code'] != SUCCESS_CODE) {
                    $this->response($respArr);
                } else {
                    $user_info = $respArr['userinfo'];
                }
                /*
                 * Validate if user is not blocked
                 */
                if ($user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                }
                $oldpassword = encrypt($postDataArr['oldpassword']);
                $newpassword = encrypt($postDataArr['password']);

                if ($user_info['password'] != $oldpassword) { // match old password and entered current password
                    $this->response(array('CODE' => OLD_PASSWORD_MISMATCH, 'MESSAGE' => $this->lang->line('old_password_wrong'), 'result' => (object)[]));
                } else if ($oldpassword == $newpassword) {
                    $this->response(array('CODE' => NEW_PASSWORD_SAME, 'MESSAGE' => $this->lang->line('password_exist'), 'result' => (object)[]));
                } else {  // update new password)
                    $this->db->trans_begin();
                    $newdata = array('password' => $newpassword, 'updated_at' => datetime());
                    $isSuccess = $this->Common_model->update_single('ai_user', $newdata, array('where' => array('user_id' => $user_info['user_id'])));
                    if (!$isSuccess) {
                        throw new Exception($this->lang->line('try_again'));
                    }
                }
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    $this->response(array('CODE' => SUCCESS_CODE, 'MESSAGE' => $this->lang->line('password_change_success'), 'result' => (object)[]));
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $msg, 'result' => (object)[]));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }

}
