<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/Authentication.php';

class Forgot extends Authentication {

    function __construct() {
        parent::__construct();

        $this->load->model('Common_model');
        $this->load->library('form_validation');
        $this->load->library('commonfn');
        $this->load->library('encrypt');
        $this->load->helper('url');
        $this->load->database();
    }

    /*
     * public function : forgotpass
     * description : function to 
     * 
     */

    public function index_post() {

        $post_data = $this->post();

        if (!empty($post_data)) {

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
                    $where = [];
                    $where['where'] = array('email' => $post_data['email']);
                    $userInfo = $this->Common_model->fetch_data('ai_user', array('CONCAT(first_name," ",middle_name) as name', 'email'), $where, true);
                    /*
                     * Encrypt the user-email
                     */
                    $ciphertext = $this->encrypt->encode($userInfo['email']);
                    $mailInfoArr = array();
                    $mailInfoArr['subject'] = 'Reset Password';
                    $mailInfoArr['mailerName'] = 'reset.php';
                    $mailInfoArr['email'] = $userInfo['email'];
                    $mailInfoArr['name'] = $userInfo['name'];
                    $mailInfoArr['link'] = base_url() . 'reset?token=' . base64_encode($ciphertext);

                    $isSuccess = $this->commonfn->sendEmailToUser($mailInfoArr);
                    if (!$isSuccess) {
                        throw new Exception($this->email->print_debugger());
                    }
                    $updatearr = array('isreset_link_sent' => 1);
                    $where = array('where' => array('email' => $userInfo['email']));
                    $isSuccess = $this->Common_model->update_single('ai_user', $updatearr, $where);
                    if (!empty($isSuccess)) {
                        $this->response_data([], EMAIL_SEND_SUCCESS, 'email_activate_link');
                    }
                } catch (Exception $e) {

                    $err_msg = $e->getMessage();
                    $code = EMAIl_SEND_FAILED;
                    $this->response_data([], $code, $err_msg);
                }
            } else {

                $err = $this->form_validation->error_array();
                $message = array_values($err);
                $this->response_data([], PARAM_REQ, '', $message[0]);
            }
        } else {
            $this->response_data([], PARAM_REQ, 'REQUIRED_PARAMETER_MISSING');
        }
    }

}
