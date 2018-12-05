<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Technician extends MY_Controller
{

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
        $this->data['admininfo'] = $this->admininfo;
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
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['viewp', 'blockp', 'deletep'], $whereArr, true);
        }

        $this->data['accesspermission'] = ($role_id == 2) ? $access_detail : $defaultPermission;
        $this->load->library('commonfn');

        /* Fetch List of users */

        $get = $this->input->get();
        $get = is_array($get) ? $get : array();
        $validSortBy = ['asc', 'desc'];

        $limit = (isset($get['limit']) && !empty($get['limit'])) ? $get['limit'] : 10;
        $page = (isset($get['page']) && !empty($get['page'])) ? $get['page'] : 1;
        $startDate = isset($get['startDate']) ? $get['startDate'] : '';
        $endDate = isset($get['endDate']) ? $get['endDate'] : '';
        $searchlike = (isset($get['searchlike']) && !empty($get['searchlike'])) ? (trim($get['searchlike'])) : "";
        $status = (isset($get['status']) && !empty($get['status'])) ? (trim($get['status'])) : "";
        $country = (isset($get['country']) && !empty($get['country'])) ? $get['country'] : "";
        $isExport = (isset($get['export']) && !empty($get['export'])) ? $get['export'] : "";

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
        $params['user_type'] = 2;   
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
        $this->data['totalrows'] = $totalrows;

        $this->session->set_flashdata('message', $this->lang->line('success_prefix') . $this->lang->line('login_success') . $this->lang->line('success_suffix'));
        load_views("technician/index", $this->data);
    }

    public function detail() 
    {

        $get = $this->input->get();
        $userId = (isset($get['id']) && !empty($get['id'])) ? encryptDecrypt($get['id'], 'decrypt') : show_404();
        $this->data['user_id'] = $userId;
        $this->data['profile'] = $profile = $this->Common_model->fetch_data('ai_user', array(), ['where' => ['user_id' => $userId]], true);
        $this->data['companydetail'] = $this->Common_model->fetch_data('company_master', array(), ['where' => ['company_id' => $profile['company_id']]], true);
        if (empty($this->data['profile'])) {
            show_404();
        }
        
        /* CSRF token */
        $this->data["csrfName"] = $this->security->get_csrf_token_name();
        $this->data["csrfToken"] = $this->security->get_csrf_hash();

        load_views("technician/user-detail", $this->data);
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
        foreach ($userData AS $res) {

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
