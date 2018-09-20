<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{

    function __construct() 
    {        
        parent::__construct();
        $this->load->helper(['url', 'form', 'custom_cookie']);
        $this->load->model('Common_model');
        $this->load->library('session');
        $this->lang->load('common', "english"); 
        $this->userInfo = [];
        if(!empty($this->session->userdata('sg_userinfo')) && ($this->session->userdata('sg_userinfo') != '')) { 
            $sg_userinfo = $this->session->userdata('sg_userinfo');
            $this->userInfo = $this->Common_model->fetch_data('ai_user', 'user_id,first_name,image,email', array('where' => array('user_id' => $sg_userinfo['user_id'],'status'=>1)), true);
        }
        
    }

    /*
     * @function:index
     * @param:no param     
     * @description:Home page of the website
     */

    public function index() 
    {    
        $data = [];        
        $data['userInfo'] = $this->userInfo;
        load_views('/home/index', $data);
    }

}

?>