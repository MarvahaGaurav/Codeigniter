<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    function __construct() 
    {
        parent::__construct();
        $this->load->helper(['url', 'custom_cookie']);
        $this->load->model('Common_model');
        $this->load->library('session');
        $sessionData = validate_admin_cookie('rcc_appinventiv', 'admin');
        if ($sessionData) {
            $this->session->set_userdata('admininfo', $sessionData);
        }
        
        $this->admininfo = $this->session->userdata('admininfo');
        if (empty($this->admininfo)) {
            redirect(base_url() . 'admin');
        }
        $this->data = [];
        $this->data['admininfo'] = $this->admininfo;
        if($this->admininfo['role_id'] == 2) {
            $whereArr = ['where'=>['admin_id'=>$this->admininfo['admin_id']]];
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['viewp', 'addp', 'editp', 'blockp', 'deletep', 'access_permission', 'admin_id', 'id'], $whereArr, false);
            $this->data['admin_access_detail'] = $access_detail;
        }

    }

    public function index() 
    {
        $where = [];
        $dataCount = [];
        $get = $this->input->get();
        $this->load->helper(["input_data", "datetime"]);
        $get = trim_input_parameters($get);
        $start_date = isset($get['start_date'])?$get['start_date']:"";
        $end_date = isset($get['end_date'])?$get['end_date']:"";
        $this->data['start_date'] = "";
        $this->data['end_date'] = "";
        if (isset($get['start_date']) && isset($get['end_date']) ) {
            $this->data['start_date'] = $start_date;
            $this->data['end_date'] = $end_date;
            $start_date = convert_date_time_format("d/m/Y", $start_date, "Y-m-d");
            $end_date = convert_date_time_format("d/m/Y", $end_date, "Y-m-d");
            
        }

        /*App users count */
        $where['where'] = ['status !=' => 3];
        $where['where_in'] = ['user_type' => array(1,6)];
        if (isset($get['start_date']) && isset($get['end_date']) ) {
            $where['where']['DATE(registered_date) >='] = $start_date;
            $where['where']['DATE(registered_date) <='] = $end_date;
        }
        $dataCount = $this->Common_model->fetch_data('ai_user', array('count(*) as userCount'), $where, true);
        $this->data['userCount'] = $dataCount['userCount'];
        /*App users count */
        
        /*Technician count */
        $where['where'] = ['status !=' => 3];
        $where['where_in'] = ['user_type' => array(2)];
        if (isset($get['start_date']) && isset($get['end_date']) ) {
            $where['where']['DATE(registered_date) >='] = $start_date;
            $where['where']['DATE(registered_date) <='] = $end_date;
        }
        $dataCount = $this->Common_model->fetch_data('ai_user', array('count(*) as userCount'), $where, true);
        $this->data['technicianCount'] = $dataCount['userCount'];
        /*Technician count */
        
        /*Wholeseller count */
        $where['where'] = ['status !=' => 3];
        $where['where_in'] = ['user_type' => array(5)];
        if (isset($get['start_date']) && isset($get['end_date']) ) {
            $where['where']['DATE(registered_date) >='] = $start_date;
            $where['where']['DATE(registered_date) <='] = $end_date;
        }
        $dataCount = $this->Common_model->fetch_data('ai_user', array('count(*) as userCount'), $where, true);
        $this->data['wholesellerCount'] = $dataCount['userCount'];
        /*wholeseller count */
        
        /*Architect count */
        $where['where'] = ['status !=' => 3];
        $where['where_in'] = ['user_type' => array(3)];
        if (isset($get['start_date']) && isset($get['end_date']) ) {
            $where['where']['DATE(registered_date) >='] = $start_date;
            $where['where']['DATE(registered_date) <='] = $end_date;
        }
        $dataCount = $this->Common_model->fetch_data('ai_user', array('count(*) as userCount'), $where, true);
        $this->data['architectCount'] = $dataCount['userCount'];
        /*Architect count */
        
        /*Electric Planner count */
        $where['where'] = ['status !=' => 3];
        $where['where_in'] = ['user_type' => array(4)];
        if (isset($get['start_date']) && isset($get['end_date']) ) {
            $where['where']['DATE(registered_date) >='] = $start_date;
            $where['where']['DATE(registered_date) <='] = $end_date;
        }
        $dataCount = $this->Common_model->fetch_data('ai_user', array('count(*) as userCount'), $where, true);
        $this->data['electricplannerCount'] = $dataCount['userCount'];
        /*Electric Planner  count */
        
        /*Project count */
        $this->data['projectCount'] = 0;
        /*Project count */
        
        load_views("dashboard/home", $this->data);
    }

}
