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
    protected $employeePermission;
    protected $datetime;
    public function __construct()
    {
        error_reporting(-1);
		ini_set('display_errors', 1);
        parent::__construct();
        $this->load->helper(['url', 'form', 'custom_cookie', 'common', 'debuging']);
        $this->load->model('Common_model');
        $this->load->library('session');
        $this->lang->load('common', "english");
        $this->userInfo = [];
        $this->datetime = date("Y-m-d H:i:s");
        $this->user_query_fields = 'status,user_id,first_name,image,email, user_type, is_owner, company_id';
        $this->session_data = $this->session->userdata('sg_userinfo');
        $this->employeePermission = retrieveEmployeePermission($this->session->userdata('sg_userinfo')['user_id']);
        $this->data['employee_permission'] = $this->employeePermission;
    }

    /**
     * Active session guard
     *
     * @return void
     */
    protected function activeSessionGuard()
    {
        if (!empty($this->session_data) && ($this->session_data != '')) {
            $sg_userinfo = $this->session_data;
            if ($sg_userinfo['status'] == BLOCKED) {
                $this->session->unset_userdata("sg_userinfo");
                redirect(base_url());
            }
            $this->userInfo = $this->Common_model->fetch_data('ai_user', $this->user_query_fields, array('where' => array('user_id' => $sg_userinfo['user_id'],'status'=>1)), true);
        } else {
            redirect(base_url("login"));
        }
    }

    /**
     * Inactive session guard
     *
     * @return void
     */
    protected function inactiveSessionGuard()
    {
        if (isset($this->session_data) && !empty($this->session_data)) {
            redirect(base_url());
        }
    }

    /**
     * Neutral session guard,
     * If a user is logged in, session data is fetched
     * @return void
     */
    protected function neutralGuard()
    {
        if (!empty($this->session_data) && ($this->session_data != '')) {
            $sg_userinfo = $this->session_data;
            $this->userInfo = $this->Common_model->fetch_data('ai_user', $this->user_query_fields, array('where' => array('user_id' => $sg_userinfo['user_id'],'status'=>1)), true);
        }
    }
}
