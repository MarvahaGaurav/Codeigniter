<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Version extends MY_Controller
{

    function __construct() 
    {
        parent::__construct();
        $this->load->helper(['url', 'custom_cookie', 'form', 'encrypt_openssl']);
        $this->load->model('Common_model');
        $this->load->model('Admin_Model');
        $this->load->model('Version_Model');
        $this->load->library('session');

        $sessionData = validate_admin_cookie('rcc_appinventiv', 'admin');
        if ($sessionData) {
            $this->session->set_userdata('admininfo', $sessionData);
        }
        $this->admininfo = $this->session->userdata('admininfo');
        $this->lang->load('common', "english");

        $this->admininfo = $this->session->userdata('admininfo');
        if (empty($this->admininfo)) {
            redirect(base_url() . 'admin');
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
        $defaultPermission['addp'] = 1;
        $defaultPermission['editp'] = 1;
        $defaultPermission['deletep'] = 1;
        if ($role_id != 1) {
            $whereArr = [];
            $whereArr['where'] = array('admin_id' => $this->admininfo['admin_id'], 'access_permission' => 2, 'status' => 1);
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['addp', 'editp', 'deletep'], $whereArr, true);
        }
        $this->data['accesspermission'] = ($role_id == 2) ? $access_detail : $defaultPermission;
        $this->data['admininfo'] = $this->admininfo;

        /* Fetch List of users */

        $get = $this->input->get();
        $get = is_array($get) ? $get : array();

        $page = (isset($get['per_page']) && !empty($get['per_page'])) ? $get['per_page'] : 1;
        $this->data['limit'] = $limit = (isset($get['pagecount']) && !empty($get['pagecount'])) ? $get['pagecount'] : 10;
        $searchlike = (isset($get['searchlike']) && !empty($get['searchlike'])) ? (trim($get['searchlike'])) : "";

        $params = [];
        $params['searchlike'] = $searchlike;
        $this->data['page'] = $page;
        $offset = ($page - 1) * $limit;
        $this->data['offset'] = $offset;

        $this->data['versions'] = $this->Version_Model->versionlist('', $offset, $limit, $params);
        $totalrows = $this->data['versions']['total'];
        /* paggination */
        $pageurl = 'admin/version';
        $this->data["link"] = $this->Admin_Model->paginaton_link_custom($totalrows, $pageurl, $limit, $per_page = 1);
        $this->data['searchlike'] = $searchlike;
        /* CSRF token */
        $this->data["csrfName"] = $this->security->get_csrf_token_name();
        $this->data["csrfToken"] = $this->security->get_csrf_hash();
        $this->data["totalrows"] = $totalrows;

        load_views("version/index", $this->data);
    }

    /**
     * @name add
     * @description This method is used to add a new app version to the admin.
     */
    public function add() 
    {

        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', $this->lang->line('version_name'), 'trim|required');
            $this->form_validation->set_rules('title', $this->lang->line('version_title'), 'trim|required');
            $this->form_validation->set_rules('desc', $this->lang->line('description'), 'trim|required');
            $this->form_validation->set_rules('platform', $this->lang->line('platform'), 'trim|required');
            $this->form_validation->set_rules('update_type', $this->lang->line('update_type'), 'trim|required');
            $this->form_validation->set_rules('current_version', $this->lang->line('current_version'), 'trim|required');

            if ($this->form_validation->run() == false) {
                load_views("version/add", $this->data);
            } else {
                $saveData = array(
                    'version_name' => $this->input->post('name'),
                    'versiob_title' => $this->input->post('title'),
                    'version_desc' => $this->input->post('desc'),
                    'platform' => $this->input->post('platform'),
                    'update_type' => $this->input->post('update_type'),
                    'is_cur_version' => $this->input->post('current_version'),
                    'create_date' => DEFAULT_DB_DATE_TIME_FORMAT
                );
                // call to insert data into db
                $res = $this->saveVersionData($saveData);
                $alertMsg = [];
                if ($res) {
                    $alertMsg['text'] = $this->lang->line('version_added');
                    $alertMsg['type'] = $this->lang->line('success');
                    $this->session->set_flashdata('alertMsg', $alertMsg);
                    redirect('admin/version/');
                } else {
                    $alertMsg['text'] = $this->lang->line('try_again');
                    $alertMsg['type'] = $this->lang->line('error');
                    $this->session->set_flashdata('alertMsg', $alertMsg);
                    redirect('admin/version/');
                }
            }
        } else {
            /*
             * If logged user is sub admin check for his permission
             */
            $role_id = $this->admininfo['role_id'];
            if ($role_id != 1) {
                $whereArr = [];
                $whereArr['where'] = array('admin_id' => $this->admininfo['admin_id'], 'access_permission' => 2, 'status' => 1);
                $access_detail = $this->Common_model->fetch_data('sub_admin', ['addp'], $whereArr, true);
                if (!$access_detail['addp']) {
                    redirect('admin/version');
                }
            }
            load_views("version/add", $this->data);
        }
    }

    /**
     * @name add
     * @description This method is used to add a new app version to the admin.
     */
    public function edit() 
    {

        $get = $this->input->get();
        $this->data['version_id'] = $versionId = (isset($get['id']) && !empty($get['id'])) ? encryptDecrypt($get['id'], 'decrypt') : show_404();
        $this->data['version'] = $this->Common_model->fetch_data('app_version', '*', ['where' => ['vid' => $versionId]], true);
        /*
         * If logged user is sub admin check for his permission
         */
        $role_id = $this->admininfo['role_id'];
        if ($role_id != 1) {
            $whereArr = [];
            $whereArr['where'] = array('admin_id' => $this->admininfo['admin_id'], 'access_permission' => 2, 'status' => 1);
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['editp'], $whereArr, true);
            if (!$access_detail['editp']) {
                redirect('admin/version');
            }
        }
        if (empty($this->data['version']) && $this->data['version'] == array()) {
            show_404();
        }
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', $this->lang->line('version_name'), 'trim|required');
            $this->form_validation->set_rules('title', $this->lang->line('version_title'), 'trim|required');
            $this->form_validation->set_rules('desc', $this->lang->line('description'), 'trim|required');
            $this->form_validation->set_rules('platform', $this->lang->line('platform'), 'trim|required');
            $this->form_validation->set_rules('update_type', $this->lang->line('update_type'), 'trim|required');
            $this->form_validation->set_rules('current_version', $this->lang->line('current_version'), 'trim|required');

            if ($this->form_validation->run() == false) {

                load_views("version/edit", $this->data);
            } else {
                $saveData = array(
                    'version_name' => $this->input->post('name'),
                    'versiob_title' => $this->input->post('title'),
                    'version_desc' => $this->input->post('desc'),
                    'platform' => $this->input->post('platform'),
                    'update_type' => $this->input->post('update_type'),
                    'is_cur_version' => $this->input->post('current_version'),
                    'create_date' => DEFAULT_DB_DATE_TIME_FORMAT
                );

                // call to insert data into db
                $res = $this->saveVersionData($saveData, $versionId);


                if ($res) {
                    $alertMsg['text'] = $this->lang->line('version_updated');
                    $alertMsg['type'] = $this->lang->line('success');
                    $this->session->set_flashdata('alertMsg', $alertMsg);
                } else {
                    $alertMsg['text'] = $this->lang->line('try_again');
                    $alertMsg['type'] = $this->lang->line('error');
                    $this->session->set_flashdata('alertMsg', $alertMsg);
                }
                redirect('admin/version/');
            }
        } else {
            load_views("version/edit", $this->data);
        }
    }

    /**
     * @name saveVersionData
     * @descrition To insert the data form app version add page.
     * @param type array
     * @return boolean
     */
    protected function saveVersionData($data, $updateId = false) 
    {

        try {
            $this->db->trans_start();
            if ($updateId) {
                $this->Common_model->update_single('app_version', $data, ['where' => ['vid' => $updateId]]);
            } else {
                $updateId = $this->Common_model->insert_single('app_version', $data);
            }

            if (isset($this->data['is_cur_version']) && $this->data['is_cur_version'] == YES) {

                $this->Common_model->update_single('app_version', ['is_cur_version' => NO], ['where' => ['vid !=' => $updateId, 'platform' => $this->data['platform']]]);
            }

            if ($this->db->trans_status() == true) {
                $this->db->trans_complete();
                return true;
            } else {
                $this->db->trans_rollback();
                return false;
            }
        } catch (Exception $e) {

            echo json_encode($e->getTraceAsString());
        }
    }

}
