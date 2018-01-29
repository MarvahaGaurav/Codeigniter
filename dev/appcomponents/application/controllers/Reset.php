<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends CI_Controller {

    public function index() {
        $token = $this->input->get('token');
        $this->load->helper('email');
        $this->load->library('encrypt');
        $this->load->model('Common_model');
        if (isset($token) && !empty($token)) {
            $email = $this->encrypt->decode(base64_decode($token));
            $where = array('where' => array('email' => $email));
            if (valid_email($email)) {
                $userinfo = $this->Common_model->fetch_data('ai_user', array('user_id', 'isreset_link_sent'), $where, true);
                if (!empty($userinfo) && $userinfo['isreset_link_sent'] != 0) {
                    $data = array();
                    $data['token'] = $token;
                    $data['userId'] = $userinfo['user_id'];
                    $this->load->view('reset/index', $data);
                } else {
                    echo "Invalid Email Entered or Link Expired";
                    die;
                }
            } else {
                echo "Invalid token error";
                die;
            }
        } else {
            echo "Invalid request please try again";
            die;
        }
    }

}
