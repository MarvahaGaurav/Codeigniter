<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Forgot extends REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('Common_model');
        $this->load->library('form_validation');
        $this->load->library('commonfn');
        $this->load->helper('url');
    }

    /**
     * @SWG\Post(path="/user/password/forgot",
     *   tags={"User"},
     *   summary="Forgot Password",
     *   description="Forgot Password",
     *   operationId="index_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="Email",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Forgot Email Send Success"),
     *   @SWG\Response(response=206, description="Unauthorized request"),     
     *   @SWG\Response(response=207, description="Header is missing"),   
     *   @SWG\Response(response=211, description="Email Send failed"),   
     *   @SWG\Response(response=302, description="Email in not registered"),       
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function index_post() {

        $post_data = $this->post();

        $config = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            )
        );
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run()) {

            try {
                $this->db->trans_begin();

                $where = [];
                $where['where'] = array('email' => $post_data['email']);
                $userInfo = $this->Common_model->fetch_data('ai_user', array('user_id,CONCAT(first_name," ",middle_name) as name', 'email', 'status'), $where, true);
                if (!empty($userInfo) && $userInfo['status'] == 1) {
                    /*
                     * Encrypt the user-email
                     */
                    $otpcode = $this->Common_model->RandomString(6);
                    $updArr = array(
                        'otp_code' => $otpcode,
                        'is_otp_verified' => '0',
                        'otp_sent_time' => date('Y-m-d H:i:s'),
                    );
                    $whereArr['where'] = ['user_id' => $userInfo['user_id']];
                    $this->Common_model->update_single('ai_user', $updArr, $where);

                    //$token = $userInfo['name'] . uniqid();
                    //$ciphertext = encryptDecrypt($token);
                    /*$mailInfoArr = array();
                    $mailInfoArr['subject'] = 'Reset Password';
                    $mailInfoArr['mailerName'] = 'reset.php';
                    $mailInfoArr['email'] = $userInfo['email'];
                    $mailInfoArr['name'] = $userInfo['name'];
                    //$mailInfoArr['link'] = base_url() . 'reset?token=' . $ciphertext;
                    $mailInfoArr['otp_code'] = $otpcode;*/
                    /*
                     * Send Email to user with above mentioned detail
                     */
                    $this->load->helper('url');
                    $data = [];
                    $data['url'] = base_url().'request/sendotpMail?email='.$userInfo['email'].'&name='.urlencode($userInfo['name']).'&otp_code='.$otpcode;
                    sendGetRequest($data);
                    
                    /*$isSuccess = $this->commonfn->sendEmailToUser($mailInfoArr);
                    
                    if (!$isSuccess) {
                        throw new Exception($this->email->print_debugger());
                    }*/
                    if ($this->db->trans_status() === TRUE) {
                        $this->db->trans_commit();
                        $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('email_otp_sent'), 'result' => ['otp_code' => $otpcode, 'user_id' => $userInfo['user_id']]));
                    }
                } else if (!empty($userInfo) && $userInfo['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                } else {
                    $this->response(array('code' => EMAIL_NOT_EXIST, 'msg' => $this->lang->line('email_not_exists'), 'result' => (object)[]));
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

    /**
     * @SWG\Post(path="/user/otp/verify",
     *   tags={"User"},
     *   summary="Verify OTP",
     *   description="Verify OTP",
     *   operationId="verifyotp_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="otp",
     *     in="formData",
     *     description="863666",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="User_id",
     *     in="formData",
     *     description="1",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="OTP Verify Success"),
     *   @SWG\Response(response=206, description="Unauthorized request"),     
     *   @SWG\Response(response=207, description="Header is missing"),   
     *   @SWG\Response(response=211, description="Email Send failed"),   
     *   @SWG\Response(response=424, description="OTP Expired/Invalid OTP"),       
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */

    public function verifyotp_post() {

        $post_data = $this->post();

        $config = array(
            array(
                'field' => 'otp',
                'label' => 'OTP',
                'rules' => 'trim|required'
            )
        );
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run()) {

            try {
                $this->db->trans_begin();

                $where = [];
                $where['where'] = array('otp_code' => $post_data['otp'], 'user_id' => $post_data['user_id']);
                $userInfo = $this->Common_model->fetch_data('ai_user', 'user_id,otp_code,otp_sent_time,status', $where, true);
                if (!empty($userInfo) && $userInfo['status'] == 1) {
                   
                    // checking otp expired or not
                    $curtime = date('Y-m-d H:i:s');
                    $prevTime = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -10 minutes"));
                    $otpsendtime = strtotime($userInfo['otp_sent_time']);
                    if($otpsendtime < strtotime($prevTime)){
                         $this->response(array('code' => INVALID_OTP, 'msg' => $this->lang->line('expired_otp'), 'result' => (object)[]));
                    }
                                                            
                    $updArr = array(
                        'otp_code' => '0',
                        'is_otp_verified' => '1',
                        'otp_sent_time' => '',
                    );
                    $whereArr['where'] = ['user_id' => $userInfo['user_id']];
                    $isSuccess = $this->Common_model->update_single('ai_user', $updArr, $where);

                    if (!$isSuccess) {
                        throw new Exception($this->lang->line('try_again'));
                    }

                    if ($this->db->trans_status() === TRUE) {
                        $this->db->trans_commit();
                        $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('valid_otp'), 'result' => ['user_id'=>$userInfo['user_id']]));
                    }
                } else if (!empty($userInfo) && $userInfo['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                } else {
                    $this->response(array('code' => INVALID_OTP, 'msg' => $this->lang->line('invalid_otp'), 'result' => (object)[]));
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
    
    /**
     * @SWG\Post(path="/user/otp/resend",
     *   tags={"User"},
     *   summary="Resend OTP",
     *   description="Resend OTP",
     *   operationId="resendotp_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="User_id",
     *     in="formData",
     *     description="1",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="OTP Sent Success"),
     *   @SWG\Response(response=206, description="Unauthorized request"),     
     *   @SWG\Response(response=207, description="Header is missing"),   
     *   @SWG\Response(response=424, description="Invalid OTP"),       
     * )
     */
    public function resendotp_post() {

        $post_data = $this->post();        
        $config = array(
            array(
                'field' => 'user_id',
                'label' => 'User ID',
                'rules' => 'trim|required'
            )
        );
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run()) {

            try {
                $this->db->trans_begin();

                $where = [];
                $where['where'] = array('user_id' => $post_data['user_id']);
                $userInfo = $this->Common_model->fetch_data('ai_user',  array('user_id,CONCAT(first_name," ",middle_name) as name', 'email', 'status'), $where, true);
                if (!empty($userInfo) && $userInfo['status'] == 1) {                                       
                                                            
                    $otpcode = $this->Common_model->RandomString(6);
                    $updArr = array(
                        'otp_code' => $otpcode,
                        'is_otp_verified' => '0',
                        'otp_sent_time' => date('Y-m-d H:i:s'),
                    );
                    $whereArr['where'] = ['user_id' => $userInfo['user_id']];
                    $isSuccess = $this->Common_model->update_single('ai_user', $updArr, $where);

                    /*$mailInfoArr = array();
                    $mailInfoArr['subject'] = 'Reset Password';
                    $mailInfoArr['mailerName'] = 'reset.php';
                    $mailInfoArr['email'] = $userInfo['email'];
                    $mailInfoArr['name'] = $userInfo['name'];                    
                    $mailInfoArr['otp_code'] = $otpcode;*/

                    /*
                     * Send Email to user with above mentioned detail
                     */
                    /*$isSuccess = $this->commonfn->sendEmailToUser($mailInfoArr);
                    if (!$isSuccess) {
                        throw new Exception($this->email->print_debugger());
                    } */                 
                    
                    $this->load->helper('url');
                    $data = [];
                    $data['url'] = base_url().'request/sendotpMail?email='.$userInfo['email'].'&name='.urlencode($userInfo['name']).'&otp_code='.$otpcode;
                    sendGetRequest($data);
                    

                    if ($this->db->trans_status() === TRUE) {
                        $this->db->trans_commit();
                        $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('email_otp_sent'), 'result' => ['otp_code' => $otpcode, 'user_id' => $userInfo['user_id']]));
                    }
                } else if (!empty($userInfo) && $userInfo['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                } else {
                    $this->response(array('code' => INVALID_USERID, 'msg' => $this->lang->line('invalid_userid'), 'result' => (object)[]));
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
