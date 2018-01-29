<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/Authentication.php';

class Reset extends Authentication {

    function __construct() {
        parent::__construct();

        $this->load->model('Common_model');
        $this->load->library('form_validation');
        $this->load->library('commonfn');
        $this->load->library('encrypt');
        $this->load->database();
    }

    /*
     * public function : forgotpass
     * description : function to 
     * 
     */

    public function index_post() {

        $postDataArr = $this->post();

        if (!empty($postDataArr)) {

            $config = array(
                array(
                    'field' => 'userId',
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

                $userId = $postDataArr['userId'];
                $password = $postDataArr['password'];

                $resparr = array();

                $where = array('where' => array('user_id' => $userId));
                $userInfo = $this->Common_model->fetch_data('ai_user', array('isreset_link_sent'), $where, true);

                if (!empty($userInfo) && $userInfo['isreset_link_sent'] != 0) {
                    /*
                     * Encrypt the password
                     */
                    $password = $this->encrypt($password);
                    $updatearr = array('password' => $password, 'isreset_link_sent' => 0);
                    $where = array('where' => array('user_id' => $userId));
                    try {
                        $issuccess = $this->Common_model->update_single('ai_user', $updatearr, $where);
                    } catch (Exception $ex) {
                        $resparr = array('code' => TRY_AGAIN, 'msg' => $ex->getMessage());
                    }
                    if ($issuccess) {
                        $resparr = array('code' => SUCCESS, 'msg' => $this->lang->line('password_reset_success'));
                    } else {
                        $resparr = array('code' => TRY_AGAIN, 'msg' => $this->lang->line('try_again'));
                    }
                } else {
                    $resparr = array('code' => PASSWORD_ALREADY_SET, 'msg' => $this->lang->line('password_already_reset'));
                }
                $this->response($resparr);
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
