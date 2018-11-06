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
    protected $timestamp;
    protected $user;

    public function __construct()
    {
        error_reporting(-1);
        ini_set('display_errors', 1);
        parent::__construct();
        $this->load->helper(['url', 'form', 'custom_cookie', 'common', 'debuging']);
        $this->load->model('Common_model');
        $this->load->library('session');
        $this->lang->load('common', "english");
        $this->lang->load('rest_controller', "english");
        $this->userInfo                    = [];
        $this->datetime                    = date("Y-m-d H:i:s");
        $this->timestamp                   = time();
        $this->user_query_fields           = 'status,user_id,first_name,image,email, user_type, is_owner, company_id';
        $this->session_data                = $this->session->userdata('sg_userinfo');
        // $this->employeePermission          = retrieveEmployeePermission($this->session->userdata('sg_userinfo')['user_id']);
        // $this->data['employee_permission'] = $this->employeePermission;
    }



    /**
     * Active session guard
     *
     * @return void
     */
    protected function activeSessionGuard()
    {
        if (! empty($this->session_data) && ($this->session_data != '')) {
            $sg_userinfo = $this->session_data;
            if ($sg_userinfo['status'] == BLOCKED) {
                $this->session->unset_userdata("sg_userinfo");
                redirect(base_url());
            }
            $this->userInfo = $this->Common_model->fetch_data('ai_user', $this->user_query_fields, array ('where' => array ('user_id' => $sg_userinfo['user_id'], 'status' => 1)), true);
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
        if (isset($this->session_data) && ! empty($this->session_data)) {
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
        if (! empty($this->session_data) && ($this->session_data != '')) {
            $sg_userinfo    = $this->session_data;
            $this->userInfo = $this->Common_model->fetch_data('ai_user', $this->user_query_fields, array ('where' => array ('user_id' => $sg_userinfo['user_id'], 'status' => 1)), true);
        }
    }

    /**
     * Handles employee permissions, this logic can be replaced
     * with any other abstaction which would exit the program
     * and provide a relevant message in a valid JSON
     * String format, should the given employee
     * not have adequate permissions
     *
     * @param array $userTypesToCheck
     * @param array $permissionsToCheck
     * @param string $redirectUrl
     * @return array
     */
    protected function handleEmployeePermission($userTypesToCheck, $permissionsToCheck, $redirectUrl)
    {
        $permissions = [];
        if (!is_array($userTypesToCheck) || !is_array($permissionsToCheck)) {
            show404($this->lang->line('internal_server_error'), $redirectUrl);
        }
        if (in_array((int)$this->userInfo['user_type'], $userTypesToCheck, true) &&
        (int)$this->userInfo['is_owner'] === ROLE_EMPLOYEE
        ) {
            $this->load->helper('common');
            $permissions = retrieveEmployeePermission($this->userInfo['user_id']);

            if (empty($permissions)) {
                show404($this->lang->line('bad_request'), $redirectUrl);
            }

            $permissionKeys = array_keys($permissions);
            foreach ($permissionsToCheck as $permissionToCheck) {
                if (!in_array($permissionToCheck, $permissionKeys)) {
                    show404($this->lang->line('bad_request'), $redirectUrl);
                }
                    
                if (!(bool)$permissions[$permissionToCheck]) {
                    show404($this->lang->line('adequate_permission_required'), $redirectUrl);
                }
            }
        }

        return $permissions;
    }

    /**
     * Permission checks on User Types
     *
     * @param array $userData
     * @param array $validUserTypes
     * @param string $message
     * @return void
     */
    protected function userTypeHandling(
        $validUserTypes,
        $redirectUrl,
        $message = ''
    ) {
        if (!in_array(
            (int)$this->userInfo['user_type'],
            $validUserTypes,
            true
        )) {
            $message = strlen(trim($message)) > 0 ? $message : $this->lang->line('forbidden_action');
            show404($message, $redirectUrl);
        }
    }

    /**
     * Runs form validation
     *
     * @return string
     */
    protected function validationRun()
    {
        $valid = (bool) $this->form_validation->run();
        if (!$valid) {
            // $errorMessage = $this->form_validation->error_array();
        }
        return $valid;
    }
}
