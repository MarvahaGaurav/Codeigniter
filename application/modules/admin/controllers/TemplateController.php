<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TemplateController extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'custom_cookie', 'form', 'encrypt_openssl']);
        $this->load->model('Common_model');
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
        $this->load->model("Template");
    }

     public function index() 
     {
        
        load_views("project_templates/index", $this->data);
     }

     public function add() 
     {
         
        load_views("project_templates/add", $this->data);
     }

     public function edit() 
     {
        load_views("project_templates/edit", $this->data);
     }

     public function details($template_id = "") 
     {
        load_views("project_templates/details", $this->data);
     }

}