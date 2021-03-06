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
   
    protected $datetime;
    protected $data;
    protected $userInfo;
    private $user_query_fields;
    protected $session_data;
    protected $timestamp;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'input_data', 'common', 'json','debuging']);
        $this->load->model(['Common_model', 'UtilModel']);
        $this->load->library('session');
        $this->lang->load('common', "english");
        $this->lang->load('rest_controller', "english");
        $this->lang->load('xhttp', "english");
        $this->userInfo = [];
        $this->user_query_fields = 'status,user_id,first_name,image,email,user_type,company_id';
        $this->session_data = $this->session->userdata('sg_userinfo');
        $this->datetime = date("Y-m-d H:i:s");
        $this->timestamp = time();
        $is_ajax_request = $this->input->is_ajax_request();
        if (! $is_ajax_request ) {
            exit("Only XHR request allowed");
        }
    }

    protected function activeSessionGuard()
    {
        if(!empty($this->session_data) && ($this->session_data != '')) { 
            $sg_userinfo = $this->session_data;
            $this->userInfo = $this->Common_model->fetch_data('ai_user', $this->user_query_fields, array('where' => array('user_id' => $sg_userinfo['user_id'])), true);
            if ((int)$this->userInfo['status'] === BLOCKED ) {
                $this->session->unset_userdata("sg_userinfo");
                json_dump(
                    [
                        "success" => false,
                        "error" => $this->lang->line('account_blocked'),
                    ]
                );
            }
        } else {
            json_dump(
                [
                    "success" => false,
                    "error" => $this->lang->line('forbidden_action'),
                ]
            );
        }
    }

    protected function inactiveSessionGuard()
    {
        if(isset($this->session_data) && !empty($this->session_data)) { 
            redirect(base_url());
        }
    }

    protected function neutralGuard()
    {
        if(!empty($this->session_data) && ($this->session_data != '')) { 
            $sg_userinfo = $this->session_data;
            $this->userInfo = $this->Common_model->fetch_data('ai_user', $this->user_query_fields, array('where' => array('user_id' => $sg_userinfo['user_id'],'status'=>1)), true);
        }
    }

}