<?php 
defined("BASEPATH") or exit("No direct script access allowed");
/**
 * @property array $data  array of values for view
 * @property array $userInfo session data
 * @property array $user_query_fields - table fields for user table
 * @property array $session_data - session data
 */
class BaseController extends MY_Controller
{   
    
    protected $data;
    protected $userInfo;
    private $user_query_fields;
    protected $session_data;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'input_data', 'common', 'json','debuging']);
        $this->load->model('Common_model');
        $this->load->library('session');
        $this->lang->load('common', "english");
        $this->lang->load('xhttp', "english");
        $this->userInfo = [];
        $this->user_query_fields = 'status,user_id,first_name,image,email';
        $this->session_data = $this->session->userdata('sg_userinfo');
        $is_ajax_request = $this->input->is_ajax_request();
        if ( ! $is_ajax_request ) {
            exit("Only XHTTP request allowed");
        }
    }

    protected function active_session_required()
    {
        if(!empty($this->session_data) && ($this->session_data != '')) { 
            $sg_userinfo = $this->session_data;
            if ( $sg_userinfo['status'] == BLOCKED ) {
                $this->session->unset_userdata("sg_userinfo");
                redirect(base_url());
            }
            $this->userInfo = $this->Common_model->fetch_data('ai_user', $this->user_query_fields, array('where' => array('user_id' => $sg_userinfo['user_id'],'status'=>1)), true);
        } else {
            redirect(base_url("login"));
        }
    }

    protected function inactive_session_required()
    {
        if(isset($this->session_data) && !empty($this->session_data)) { 
            redirect(base_url());
        }
    }

    protected function neutral_session()
    {
        if(!empty($this->session_data) && ($this->session_data != '')) { 
            $sg_userinfo = $this->session_data;
            $this->userInfo = $this->Common_model->fetch_data('ai_user', $this->user_query_fields , array('where' => array('user_id' => $sg_userinfo['user_id'],'status'=>1)), true);
        }
    }

}