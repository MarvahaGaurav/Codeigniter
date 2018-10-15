<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{

    private $validUserTypes;
    private $userTypes;
    function __construct()
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
        $this->validUserTypes = [PRIVATE_USER, BUSINESS_USER];
        $this->data['admininfo'] = $this->admininfo;
        if ($this->admininfo['role_id'] == 2) {
            $whereArr = ['where'=>['admin_id'=>$this->admininfo['admin_id']]];
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['viewp', 'addp', 'editp', 'blockp', 'deletep', 'access_permission', 'admin_id', 'id'], $whereArr, false);
            $this->data['admin_access_detail'] = $access_detail;
        }

        $this->userTypes = [
            PRIVATE_USER => "Individual User",
            BUSINESS_USER => "Business User"
        ];
    }

    /**
     * @name index
     * @description This method is used to list all the customers.
     */
    public function index()
    {
        $role_id = $this->admininfo['role_id'];
        /*
         * If logged user is sub admin check for his permission
         */
        $defaultPermission['viewp'] = 1;
        $defaultPermission['blockp'] = 1;
        $defaultPermission['deletep'] = 1;
        if ($role_id != 1) {
            $whereArr = [];
            $whereArr['where'] = array('admin_id' => $this->admininfo['admin_id'], 'access_permission' => 1, 'status' => 1);
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['viewp', 'blockp', 'deletep','blockp','viewp'], $whereArr, true);
        }

        $this->data['accesspermission'] = ($role_id == 2) ? $access_detail : $defaultPermission;
        $this->load->library('commonfn');
        
        /* Fetch List of users */
        $reverseUserTypes = [
            "individual_user" => PRIVATE_USER,
            "business_user" => BUSINESS_USER
        ];

        $get = $this->input->get();
        $get = is_array($get) ? $get : array();
        $validSortBy = ['asc', 'desc'];
        $this->load->helper("input_data");
        $get = trim_input_parameters($get);

        $limit = (isset($get['limit']) && !empty($get['limit'])) ? $get['limit'] : 10;
        $page = (isset($get['page']) && !empty($get['page'])) ? $get['page'] : 1;
        $startDate = isset($get['startDate']) ? $get['startDate'] : '';
        $endDate = isset($get['endDate']) ? $get['endDate'] : '';
        $searchlike = (isset($get['searchlike']) && !empty($get['searchlike'])) ? (trim($get['searchlike'])) : "";
        $status = (isset($get['status']) && !empty($get['status'])) ? (trim($get['status'])) : "";
        $country = (isset($get['country']) && !empty($get['country'])) ? $get['country'] : "";
        $isExport = (isset($get['export']) && !empty($get['export'])) ? $get['export'] : "";
        $user_type = isset($get['user_type']) && in_array($get['user_type'], array_keys($reverseUserTypes)) ? $reverseUserTypes[$get['user_type']] : "";

        $params = [];
        $params['searchlike'] = $searchlike;
        $params["sortfield"] = isset($get["field"]) && !empty($get["field"]) ? trim($get["field"]) : '';
        $params["sortby"] = isset($get["order"]) && !empty($get["order"]) && in_array(trim($get['order']), $validSortBy) ? trim($get["order"]) : '';
        $params["startDate"] = $startDate;
        $params["endDate"] = $endDate;
        $params["status"] = $status;
        $params["country"] = $country;
        $params["export"] = $isExport;
        /*
         * If Request if Excel Export then restrict to 65000 limit
         */
        if ($isExport) {
            $params['limit'] = 65000;
            $params['offset'] = 0;
        } else {
            $offset = ($page - 1) * $limit;
            $params['limit'] = $limit;
            $params['offset'] = $offset;
        }
           $params['user_type'] = '1,6';
        if (! empty($user_type)) {
             $params['user_type'] = $user_type;
             $user_type = $get['user_type'];
        }

        $userInfo = $this->User_Model->userlist($params);
        //        pr($userInfo);die;
        /*
         * Export to Csv
         */
        if ($isExport) {
            $this->exportUser($userInfo['result']);
        }
        $totalrows = $userInfo['total'];
        $this->data['userlist'] = $userInfo['result'];

        /*
         * Manage Pagination
         */

        $pageurl = 'admin/users';
        $this->data["link"] = $this->commonfn->pagination($pageurl, $totalrows, $limit);

        $this->data["order_by"] = "asc";
        if (!empty($params['sortby'])) {
            $this->data["order_by"] = $params["sortby"] == "desc" ? "asc" : "desc";
        }
        //unset sortfields
        unset($params["sortby"]);
        unset($params["sortby"]);

        //build query to append it to sort url
        $getQuery = http_build_query(array_filter($params));

        $this->data['get_query'] = !empty($getQuery) ? "&" . $getQuery : "";
        
        /* CSRF token */
        $this->data["csrfName"] = $this->security->get_csrf_token_name();
        $this->data["csrfToken"] = $this->security->get_csrf_hash();
        $this->data['searchlike'] = $searchlike;
        $this->data['page'] = $page;
        $this->data['startDate'] = $startDate;
        $this->data['endDate'] = $endDate;
        $this->data['status'] = $status;
        $this->data['limit'] = $limit;
        $this->data['country'] = $country;
        $this->data['totalrows'] = $totalrows;
        $this->data['user_type'] = $user_type;
        
        $this->data['userlist'] = array_map(
            function ($data) {
                if (in_array((int)$data['user_type'], $this->validUserTypes)) {
                    $data['user_type'] = $this->userTypes[(int)$data['user_type']];
                } else {
                    $data['user_type'] = "Invalid user";
                }
                return $data;
            },
            $this->data['userlist']
        );
        // pr($this->data['userlist']);die;

        $this->session->set_flashdata('message', $this->lang->line('success_prefix') . $this->lang->line('login_success') . $this->lang->line('success_suffix'));
        load_views("users/index", $this->data);
    }

    public function detail()
    {

        $get = $this->input->get();
        $userId = (isset($get['id']) && !empty($get['id'])) ? encryptDecrypt($get['id'], 'decrypt') : show_404();
        $this->data['user_id'] = $userId;
        //$this->data['profile'] = $profile = $this->Common_model->fetch_data('ai_user', array(), ['where' => ['user_id' => $userId]], true);
        $profile = $this->User_Model->userdetail(['user_id' => $userId]);
        $this->data['profile'] =  $profile[0];
        //pr($profile['result'][0]);
        if (empty($this->data['profile'])) {
            show_404();
        }
        
        $this->data['profile']['user_type_numeric'] = $this->data['profile']['user_type'];
        if (in_array((int)$this->data['profile']['user_type'], $this->validUserTypes)) {
            $this->data['profile']['user_type'] = $this->userTypes[(int)$this->data['profile']['user_type']];
        } else {
            $this->data['profile']['user_type'] = 'Invalid user';
        }
        /* CSRF token */
        $this->data["csrfName"] = $this->security->get_csrf_token_name();
        $this->data["csrfToken"] = $this->security->get_csrf_hash();

        load_views("users/user-detail", $this->data);
    }

    public function exportUser($userData)
    {

        $fileName = 'userlist' . date('d-m-Y-g-i-h') . '.xls';
        // The function header by sending raw excel
        header("Content-type: application/vnd-ms-excel");
        // Defines the name of the export file
        header("Content-Disposition: attachment; filename=" . $fileName);
        $format = '<table border="1">'
                . '<tr>'
                . '<th width="25%">S.no</th>'
                . '<th>Name</th>'
                . '<th>Email</th>'
                . '<th>Registration Date</th>'
                . '</tr>';

        $coun = 1;
        foreach ($userData as $res) {
            $date = date_create($res['registered_date']);
            $Date = date_format($date, 'd/m/Y');
            $Time = date_format($date, 'g:i A');

            $fld_1 = $coun;
            $fld_2 = (isset($res['first_name']) && ($res['first_name'] != '')) ? $res['first_name'] : '';
            $fld_3 = (isset($res['email']) && ($res['email'] != '')) ? $res['email'] : '';
            $fld_4 = $Date . ' ' . $Time;

            $format .= '<tr>
                        <td>' . $fld_1 . '</td>
                        <td>' . $fld_2 . '</td>
                        <td>' . $fld_3 . '</td>
                        <td>' . $fld_4 . '</td>
                      </tr>';
            $coun++;
        }

        echo $format;
        die;
    }
}
