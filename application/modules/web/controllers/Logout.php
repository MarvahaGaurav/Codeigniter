<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller
{

    function __construct() 
    {        
        parent::__construct();
        $this->load->helper(['url', 'form', 'custom_cookie']);
        $this->load->model('Common_model');
        $this->load->library('session');
        $this->lang->load('common', "english");
        $this->load->library('form_validation');        
    }

    /*
     * @function:logout
     * @param: 
     * @description: this is used to logout the user and redirect him to home page after logout
     */

    public function index() 
    {                    
        $this->session->unset_userdata("sg_userinfo");
        redirect(base_url());
    }

}
