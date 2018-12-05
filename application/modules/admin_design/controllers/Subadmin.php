<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * @author     Appinventiv
 * @date       19-04-2017
 * @controller Admin 
 */
class Subadmin extends MY_Controller
{

    public function __construct() 
    {

        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->model('Common_model');
        $this->load->model('Subadmin_model');
        $this->load->library(['form_validation', 'session']);
        $this->lang->load('common', "english");
        $this->admininfo = $this->session->userdata('admininfo');
        if (isset($this->admininfo) && $this->admininfo['role_id'] != 1) {
            redirect(base_url() . 'admin/admin');
        }
        $this->data = [];
        $this->data['admininfo'] = $this->admininfo;
    }

    public function add() 
    {

        /*
         * Server Side validation
         */
        $this->form_validation->set_rules('name', 'Admin Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|is_unique[admin.admin_email]', array('is_unique' => '{field} must be unique'));
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[16]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if (!($this->form_validation->run())) {
            $this->Common_model->load_views('/subadmin/add-new', $this->data);
        } else {
            $post = $this->input->post();
            /*
             * If post request
             */
            if (isset($post) && !empty($post)) {
                $password = $post['password'];
                $adminInsertArr = [];
                $adminInsertArr = array(
                    'admin_name' => trim($post['name']),
                    'admin_email' => trim($post['email']),
                    'admin_password' => hash('sha256', trim($password)),
                    'status' => $post['status'],
                    'role_id' => 2,
                    'create_date' => datetime(),
                    'update_date' => datetime(),
                );

                try {
                    $adminid = $this->Common_model->insert_single('admin', $adminInsertArr);
                    /*
                     * Validating Permissions
                     */
                    if (!empty($post['permission'])) {
                        foreach ($post['permission'] as $key => $value) {
                            switch ($key) {
                            case 'user':
                                $perType = 1;
                                break;
                            case 'version':
                                $perType = 2;
                                break;
                            case 'notification':
                                $perType = 3;
                                break;
                            default:
                            }

                            $permArr = [];
                            $permArr = array(
                                'viewp' => isset($value['view']) ? $value['view'] : 0,
                                'deletep' => isset($value['delete']) ? $value['delete'] : 0,
                                'addp' => isset($value['add']) ? $value['add'] : 0,
                                'editp' => isset($value['edit']) ? $value['edit'] : 0,
                                'blockp' => isset($value['block']) ? $value['block'] : 0,
                                'admin_id' => $adminid,
                                'access_permission' => $perType,
                                'created_at' => datetime(),
                            );

                            /*
                             * Save the permissions of subadmin to db view,delete,add,edit etc 
                             */
                            $response = $this->Common_model->insert_single('sub_admin', $permArr);
                        }
                    }
                    if ($adminid) {
                        $alertMsg = [];
                        $alertMsg['text'] = $this->lang->line('subadmin_created');
                        $alertMsg['type'] = 'Success!';
                        $this->session->set_flashdata('alertMsg', $alertMsg);
                        redirect('/admin/subadmin');
                    } else {
                        $this->data['saveErr'] = 'Please try again';
                        load_views("/subadmin/add", $this->data);
                    }
                } catch (Exception $ex) {
                    pr($ex . getmessage());
                }
            } else {
                /*
                 * Csrf token manage
                 */
                $this->data["csrfName"] = $this->security->get_csrf_token_name();
                $this->data["csrfToken"] = $this->security->get_csrf_hash();
                load_views('/subadmin/add-new', $this->data);
            }
        }
    }

    function check_email_avalibility() 
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $this->load->model("Subadmin_model");
        $respArr = array();
        if ($this->Subadmin_model->is_email_available($_POST["email"])) {
            $respArr = array('code' => 201, 'msg' => 'Email Already Registered');
        } else {
            $respArr = array('code' => 200, 'msg' => 'Email Available');
        }
        echo json_encode($respArr);
    }

    //fetch the subadmin details
    public function index() 
    {
        $this->data['admininfo'] = $this->admininfo;
        $get = $this->input->get();
        $get = is_array($get) ? $get : array();
        $status = $this->input->post['status'];
        $roleId = $this->input->post['roleId'];

        $params = array();

        $limit = (isset($get['limit']) && !empty($get['limit'])) ? $get['limit'] : 10;
        $page = (isset($get['page']) && !empty($get['page'])) ? $get['page'] : 1;
        $searchlike = (isset($get['searchlike']) && !empty($get['searchlike'])) ? (trim($get['searchlike'])) : "";
        $status = (isset($get['status']) && !empty($get['status'])) ? (trim($get['status'])) : "";

        $params = [];
        $params['searchlike'] = $searchlike;
        $params["sortfield"] = isset($get["field"]) && !empty($get["field"]) ? trim($get["field"]) : '';
        $params["sortby"] = isset($get["order"]) && !empty($get["order"]) && in_array(trim($get['order']), $validSortBy) ? trim($get["order"]) : '';
        $params["status"] = $status;

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $respdata = $this->Subadmin_model->getsubadmindata($limit, $offset, $params);
        $pageurl = 'admin/Subadmin';
        $totalrows = $respdata['totalrows'];
        $this->load->library('commonfn');
        $link = $this->commonfn->pagination($pageurl, $totalrows, $limit);
        $this->data['links'] = $link;
        $this->data['data'] = $respdata['records'];
        $this->data['allUsersCount'] = $totalrows;
        $this->data['page'] = $page;
        $this->data['totalrows'] = $totalrows;
        $this->data['searchlike'] = $searchlike;
        $this->data['limit'] = $limit;

        load_views('/subadmin/index', $this->data);
    }

    //fetch particular user detail
    public function view() 
    {
        $getDataArr = $this->input->get();
        $admin_id = encryptDecrypt($getDataArr['id'], 'decrypt');
        if (empty($admin_id)) {
            show404('Invalid request');
        }
        $whereArr = [];
        $whereArr['where'] = array('admin_id' => $admin_id);
        $adminField = ['admin_id', 'admin_name', 'status', 'admin_email', 'create_date'];
        $adminInfo = $this->Common_model->fetch_data('admin', $adminField, $whereArr, true);
        $perField = ['viewp', 'blockp', 'deletep', 'editp', 'addp', 'admin_id', 'access_permission'];
        $permissionDetails = $this->Common_model->fetch_data('sub_admin', $perField, $whereArr);
        if (empty($adminInfo)) {
            show404('Invalid request');
        }

        $permission = array();
        if (!empty($permissionDetails)) {
            foreach ($permissionDetails as $key => $users) {
                $availper = array();
                $perkey = $users['access_permission'];
                $avaiper['viewp'] = $users['viewp'];
                $avaiper['addp'] = $users['addp'];
                $avaiper['deletep'] = $users['deletep'];
                $avaiper['editp'] = $users['editp'];
                $avaiper['blockp'] = $users['blockp'];
                $permission[$perkey] = $avaiper;
            }
        }

        $this->data['admindetail'] = $adminInfo;
        $this->data['permission'] = $permission;
        load_views('/subadmin/admin-view', $this->data);
    }

    public function deleterecords() 
    {
        $get = $this->input->get();
        $userId = $get['userId'];
        $this->Subadmin_model->delete_data($userId);
        redirect('/subadmin');
    }

    public function edit() 
    {

        $getData = $this->input->get();
        $post = $this->input->post();
        if (isset($post) && !empty($post)) {
            $admin_id = (isset($post['token']) && !empty($post['token'])) ? encryptDecrypt($post['token'], 'decrypt') : "";

            $subAdminUpdateArr = [];
            $subAdminUpdateArr = array(
                'admin_name' => $post['name'],
                'admin_email' => $post['email'],
                'status' => $post['status'],
            );
            if (isset($post['newpassword']) && !empty($post['newpassword'])) {
                $subAdminUpdateArr['admin_password'] = hash('sha256', trim($post['newpassword']));
            }

            $whereArr = [];
            $whereArr['where'] = array('admin_id' => $admin_id);
            $isSuccess = $this->Common_model->update_single('admin', $subAdminUpdateArr, $whereArr);
            if ($post['status'] != 1) {
                $this->Common_model->update_single('sub_admin', ['status' => 2], $whereArr);
            }
            $permission = array();
            $this->Common_model->delete_data('sub_admin', $whereArr);

            if (!empty($post['permission'])) {
                foreach ($post['permission'] as $key => $value) {
                    switch ($key) {
                    case 'user':
                        $perType = 1;
                        break;
                    case 'version':
                        $perType = 2;
                        break;
                    case 'notification':
                        $perType = 3;
                        break;
                    default:
                    }

                    $permArr = [];
                    $permArr = array(
                        'viewp' => isset($value['view']) ? $value['view'] : 0,
                        'deletep' => isset($value['delete']) ? $value['delete'] : 0,
                        'addp' => isset($value['add']) ? $value['add'] : 0,
                        'editp' => isset($value['edit']) ? $value['edit'] : 0,
                        'blockp' => isset($value['block']) ? $value['block'] : 0,
                        'admin_id' => $admin_id,
                        'access_permission' => $perType,
                        'created_at' => datetime(),
                    );

                    /*
                     * Save the permissions of subadmin to db view,delete,add,edit etc 
                     */
                    $response = $this->Common_model->insert_single('sub_admin', $permArr);
                }
            }
            if ($isSuccess) {

                $alertMsg = [];
                $alertMsg['text'] = $this->lang->line('subadmin_updated');
                $alertMsg['type'] = 'Success!';
                $this->session->set_flashdata('alertMsg', $alertMsg);
                redirect('/admin/subadmin');
            } else {
                $this->data['msg'] = 'Please try again';
                load_views("/subadmin/edit", $this->data);
            }
        } else {

            $admin_id = (isset($getData['id']) && !empty($getData['id'])) ? encryptDecrypt($getData['id'], 'decrypt') : "";
            if (empty($admin_id)) {
                show404('Invalid request');
            }

            $whereArr = [];
            $whereArr['where'] = array('admin_id' => $admin_id);
            $adminField = ['admin_id', 'admin_name', 'status', 'admin_email', 'create_date'];
            $adminInfo = $this->Common_model->fetch_data('admin', $adminField, $whereArr, true);
            $perField = ['viewp', 'blockp', 'deletep', 'editp', 'addp', 'admin_id', 'access_permission'];
            $permissionDetails = $this->Common_model->fetch_data('sub_admin', $perField, $whereArr);

            $permission = array();
            if (!empty($permissionDetails)) {
                foreach ($permissionDetails as $key => $users) {
                    $availper = array();
                    $perkey = $users['access_permission'];
                    $avaiper['viewp'] = $users['viewp'];
                    $avaiper['addp'] = $users['addp'];
                    $avaiper['deletep'] = $users['deletep'];
                    $avaiper['editp'] = $users['editp'];
                    $avaiper['blockp'] = $users['blockp'];
                    $permission[$perkey] = $avaiper;
                }
            }

            $this->data['permission'] = $permission;
            $this->data['admindetail'] = $adminInfo;
            $this->data['admin_id'] = $admin_id;

            load_views('/subadmin/edit', $this->data);
        }
    }

    //........change status of user to block or active
    public function block() 
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        try {
            $id = $this->input->post('userId');
            $status = $this->input->post('status');
            $where = array('userId' => $id);
            $params = array('status' => $status);
            $result1 = $this->Subadmin_model->blockuser($params, $where);
            if ($result1 == true) {
                $result = array("code" => 200);
            } else {
                $result = array("code" => 201);
            }
            echo json_encode($result);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
            die;
        }
    }

}

?>