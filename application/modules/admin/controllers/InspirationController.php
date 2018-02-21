<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InspirationController extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'custom_cookie', 'form', 'encrypt_openssl']);
        $this->load->model('Common_model');
        $this->load->model('User_Model');
        $this->load->model('Admin_Model');
        $this->load->library('session');
        $sessionData = validate_admin_cookie('rcc_appinventiv', 'admin');
        if ($sessionData) {
            $this->session->set_userdata('admininfo', $sessionData);
        }
        $this->admininfo = $this->session->userdata('admininfo');
        if (empty($this->admininfo)) {
            redirect(base_url() . 'admin/Admin');
        }
        $this->data = [];
        $this->data['admininfo'] = $this->admininfo;
        if($this->admininfo['role_id'] == 2){
            $whereArr = ['where'=>['admin_id'=>$this->admininfo['admin_id']]];
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['viewp', 'addp', 'editp', 'blockp', 'deletep', 'access_permission', 'admin_id', 'id'], $whereArr, false);
            $this->data['admin_access_detail'] = $access_detail;
        }
        $this->load->model("Inspiration");
    }

    public function details()
    {
        $get = $this->input->get();
        $this->load->helper(['input_data', 'datetime']);
        $get = trim_input_parameters($get);
        $inspirationId = (isset($get['id']) && !empty($get['id'])) ? encryptDecrypt($get['id'], 'decrypt') : show_404();
        $this->data['test'] = "";

        $params['inspiration_id'] = $inspirationId;
        $params['media'] = true;
        $params['poster_details'] = true;
        $data = $this->Inspiration->get($params);
        $data['media'] = json_decode("[" . $data['media'] . "]", true);
        $data['user_id'] = encryptDecrypt($data['user_id']);
        $this->data['inspiration_data'] = $data;
        load_views("inspiration/details", $this->data);
    }

}
