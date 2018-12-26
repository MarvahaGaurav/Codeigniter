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
    protected $languageCode;

    public function __construct()
    {
        error_reporting(-1);
        ini_set('display_errors', 1);
        parent::__construct();
        $this->load->helper(['url', 'form', 'custom_cookie', 'common', 'debuging', 'datetime']);
        $this->load->model('Common_model');
        $this->load->library('session');
        $this->lang->load('common', "english");
        $this->lang->load('rest_controller', "english");
        $this->userInfo                    = [];
        $this->datetime                    = date("Y-m-d H:i:s");
        $this->timestamp                   = time();
        $this->user_query_fields           = 'status,user_id,first_name,image,email, user_type, is_owner, company_id';
        $this->session_data                = $this->session->userdata('sg_userinfo');
        $this->languageCode = "en";
        $this->employeePermission = [];
        $this->data['activePage'] = '';
        // $this->employeePermission          = retrieveEmployeePermission($this->session->userdata('sg_userinfo')['user_id']);
        // $this->data['employee_permission'] = $this->employeePermission;
    }

    private function getNotifications($userId)
    {
        $this->load->model(['Notification']);
        $params['user_id'] = $userId;
        $params['is_read'] = "0";
        $params['limit'] = 5;
        $notifications = $this->Notification->getNotifications($params);
        $notifications['data'] = $this->processNotifications($notifications['data']);
        return $notifications;
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
            $this->data['userInfo'] = $this->userInfo;
            $this->data['permission'] = [];

            $notifications = $this->getNotifications($this->userInfo['user_id']);
            $this->data['siteNotifications'] = $notifications['data'];
            $this->data['notificationCount'] = $notifications['count'];

            if ((int)$this->userInfo['is_owner'] === ROLE_EMPLOYEE) {
                $this->employeePermission = retrieveEmployeePermission($this->userInfo['user_id']);
                $this->data['permission'] = $this->employeePermission;
            }
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
            $this->data['userInfo'] = $this->userInfo;
            $this->data['permission'] = [];

            $notifications = $this->getNotifications($this->userInfo['user_id']);
            $this->data['siteNotifications'] = $notifications['data'];
            $this->data['notificationCount'] = $notifications['count'];

            if ((int)$this->userInfo['is_owner'] === ROLE_EMPLOYEE) {
                $this->employeePermission = retrieveEmployeePermission($this->userInfo['user_id']);
                $this->data['permission'] = $this->employeePermission;
            }
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
        
        if (!is_array($userTypesToCheck) || !is_array($permissionsToCheck)) {
            show404($this->lang->line('internal_server_error'), $redirectUrl);
        }
        if (in_array((int)$this->userInfo['user_type'], $userTypesToCheck, true) &&
        (int)$this->userInfo['is_owner'] === ROLE_EMPLOYEE
        ) { 
            $this->load->helper('common');
            $this->data['permissions'] = $this->employeePermission;
            
            if (empty($this->employeePermission)) { 
                show404($this->lang->line('adequate_permission_required'), $redirectUrl);
            }

            $permissionKeys = array_keys($this->employeePermission); 
            
            foreach ($permissionsToCheck as $permissionToCheck) { 
                if (!in_array($permissionToCheck, $permissionKeys)) { 
                    show404($this->lang->line('adequate_permission_required'), $redirectUrl);
                }
                    
                if (!(bool)$this->employeePermission[$permissionToCheck]) { 
                    show404($this->lang->line('adequate_permission_required'), $redirectUrl);
                }
            }
        }

        return $this->employeePermission;
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

    protected function processNotifications($notifications)
    {
        $notifications = array_map(function ($notification) {
            $notification['name'] = isset($notification['sender']['full_name'])? $notification['sender']['full_name']:'';
            $notification['message'] = sprintf($this->notificationMessagesHandler($notification['type']), $notification['name']);
            if ((int)$notification['type'] === NOTIFICATION_PERMISSION_GRANTED) {
                $notification['message'] = sprintf(
                    $this->lang->line('notification_permission_granted'),
                    isset($notification['messages'], $notification['messages']['message'])? $notification['messages']['message']: ''
                );
                unset($notification['messages']);
            } else if ((int)$notification['type'] === NOTIFICATION_ADMIN_NOTIFICATION && isset($notification['admin_messages'], $notification['admin_messages']['title'])) {
                $notification['message'] = $notification['admin_messages']['title'];
            }
            if ((int)$notification['type'] === NOTIFICATION_SEND_QUOTES) {
                $notification['redirection'] = sprintf($this->notificationRedirectionHandler()[NOTIFICATION_SEND_QUOTES], encryptDecrypt($notification['project_id']));
            } else {
                $notification['redirection'] = $this->notificationRedirectionHandler()[$notification['type']];
            }
            $notification['redirection'] = !empty($notification['redirection'])?base_url($notification['redirection']):null;
            return $notification;
        }, $notifications);

        return $notifications;
    }

    protected function notificationRedirectionHandler()
    {
        return [
            NOTIFICATION_EMPLOYEE_REQUEST_RECEIVED => "home/technicians/requests",
            NOTIFICATION_RECEIVED_QUOTES => "home/quotes/awaiting",
            NOTIFICATION_PERMISSION_GRANTED => null,
            NOTIFICATION_SEND_QUOTES => "/home/projects/%s/quotations",
            NOTIFICATION_ACCEPT_QUOTE => "home/quotes/approved",
            NOTIFICATION_EDIT_QUOTE_PRICE => null,
            NOTIFICATION_EMPLOYEE_APPROVED => null,
            NOTIFICATION_ADMIN_NOTIFICATION => null
        ];
    }

    /**
     * Get message based on the type of notifications
     *
     * @param int $type
     * @return string
     */
    private function notificationMessagesHandler($type)
    {
        $typeMessageMapping = [
            NOTIFICATION_EMPLOYEE_REQUEST_RECEIVED => $this->lang->line('notification_employee_request_received'),
            NOTIFICATION_RECEIVED_QUOTES => $this->lang->line('notification_received_quotes'),
            NOTIFICATION_PERMISSION_GRANTED => $this->lang->line('notification_permission_granted'),
            NOTIFICATION_SEND_QUOTES => $this->lang->line('notification_send_quotes'),
            NOTIFICATION_ACCEPT_QUOTE => $this->lang->line('notification_accept_quote'),
            NOTIFICATION_EDIT_QUOTE_PRICE => $this->lang->line('notification_edit_quote_price'),
        ];

        return isset($typeMessageMapping[$type])?$typeMessageMapping[$type]:'';
    }
}
