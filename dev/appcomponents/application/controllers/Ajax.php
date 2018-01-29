<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->library('encrypt');
        $this->load->library('commonfn');
        $this->lang->load('common_lang', "english");
        $this->load->helper('url');
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
    }

    public function reset() {

        $token = $this->input->post('token');
        $password = $this->input->post('password');

        $resparr = array();
        if (empty($token) || empty($password)) {
            $resparr = array('code' => 201, 'msg' => $this->lang->line('try again'));
        }

        $this->load->library('encrypt');
        /*
         * Decrypt the email
         */
        $email = $this->encrypt->decode(base64_decode($token));

        $where = array('where' => array('email' => $email));
        $userInfo = $this->Common_model->fetch_data('ai_user', array('isreset_link_sent'), $where, true);

        if (!empty($userInfo) && $userInfo['isreset_link_sent'] != 0) {
            /*
             * Encrypt the password
             */
            $password = $this->commonfn->encrypt($password);
            $updatearr = array('password' => $password, 'isreset_link_sent' => 0);
            $where = array('where' => array('email' => $email));
            try {
                $issuccess = $this->Common_model->update_single('ai_user', $updatearr, $where);
            } catch (Exception $ex) {
                $resparr = array('code' => 201, 'msg' => $ex->getMessage());
            }

            if ($issuccess) {
                $resparr = array('code' => 200, 'msg' => $this->lang->line('password_reset_success'));
            } else {
                $resparr = array('code' => 201, 'msg' => $this->lang->line('try_again'));
            }
        } else {
            $resparr = array('code' => 201, 'msg' => $this->lang->line('password_already_reset'));
        }


        echo json_encode($resparr);
        die;
    }

}
