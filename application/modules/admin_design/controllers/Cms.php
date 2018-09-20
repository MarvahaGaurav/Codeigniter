<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends MY_Controller
{

    function __construct() 
    {
        parent::__construct();
        $this->load->helper(['url', 'custom_cookie', 'form', 'encrypt_openssl']);
        $this->load->model('Common_model');
        $this->load->model('Admin_Model');
        $this->load->model('Cms_Model');
        $this->load->library('session');
        $this->load->library('form_validation');

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
        $this->lang->load('common', "english");
    }

    /**
     * @name index
     * @description This method is used to list all the customers.
     */
    public function index() 
    {
        $this->load->library('commonfn');
        /* Fetch List of users */
        $get = $this->input->get();
        $get = is_array($get) ? $get : array();

        $page = (isset($get['page']) && !empty($get['page'])) ? $get['page'] : 1;
        $limit = (isset($get['pagecount']) && !empty($get['pagecount'])) ? $get['pagecount'] : 10;
        $searchlike = (isset($get['searchlike']) && !empty($get['searchlike'])) ? (trim($get['searchlike'])) : "";

        $params = [];
        $params['searchlike'] = $searchlike;
        $offset = ($page - 1) * $limit;
        $respData = $this->Cms_Model->pagelist($limit, $offset, $params);
        /*
         * Manage pagination
         */
        $pageurl = 'admin/cms';
        $totalrows = $respData['total'];
        $this->data["link"] = $this->commonfn->pagination($pageurl, $totalrows, $limit);

        $this->data['searchlike'] = $searchlike;
        $this->data['cmsData'] = $respData['result'];
        $this->data['totalrows'] = $totalrows;
        /* CSRF token */
        $this->data["csrfName"] = $this->security->get_csrf_token_name();
        $this->data["csrfToken"] = $this->security->get_csrf_hash();

        load_views("cms/index", $this->data);
    }

    /**
     * @name add
     * @description This method is used to add a new page to the cms.
     */
    public function add() 
    {

        $this->data = [];
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', $this->lang->line('title'), 'required|trim');
            $this->form_validation->set_rules('page_desc', $this->lang->line('page_desc'), 'required');
            $this->form_validation->set_rules('status', $this->lang->line('status'), 'required|trim');
            if ($this->form_validation->run() == false) {
                load_views("cms/add", $this->data);
            } else {
                $postedData = $this->input->post();

                $savedata['name'] = $postedData['title'];
                $savedata['content'] = $postedData['page_desc'];
                $savedata['status'] = $postedData['status'];
                $savedata['created_date'] = DEFAULT_DB_DATE_TIME_FORMAT;

                // calling to insert data method.
                $res = $this->saveCmsData($savedata);
                $alertMsg = [];
                if ($res) {
                    $alertMsg['text'] = $this->lang->line('page_added');
                    $alertMsg['type'] = $this->lang->line('success');
                    $this->session->set_flashdata('alertMsg', $alertMsg);
                } else {
                    $alertMsg['text'] = $this->lang->line('try_again');
                    $alertMsg['type'] = $this->lang->line('error');
                    $this->session->set_flashdata('alertMsg', $alertMsg);
                }
                redirect('/admin/cms');
            }
        } else {
            load_views("cms/add", $this->data);
        }
    }

    /**
     * @name saveCmsData
     * @descrition To insert the data form cms add page.
     * @param type $this->data
     * @return boolean
     */
    protected function saveCmsData($data, $updateId = false) 
    {

        try {
            $this->db->trans_start();
            if ($updateId) {
                $this->Common_model->update_single('page_master', $data, ['where' => ['id' => $updateId]]);
            } else {
                $this->Common_model->insert_single('page_master', $data);
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

    /**
     * @name edit
     * @description This method is used to edit the cms page.
     * @access public
     */
    public function edit() 
    {
        $get = $this->input->get();

        // $this->data['page_id'] = $pageId = (isset($get['id']) && !empty($get['id'])) ? $this->Common_model->demcrypt_data(str_replace(' ', '+', $get['id'])) : show_404();
        $this->data['page_id'] = $pageId = (isset($get['id']) && !empty($get['id'])) ? encryptDecrypt($get['id'], 'decrypt') : show_404();

        $this->data['pages'] = $this->Common_model->fetch_data('page_master', '*', ['where' => ['id' => $pageId]], true);
        if (empty($this->data['pages']) && $this->data['pages'] == array()) {
            show_404();
        }

        // print_r($this->data['pages']);die;
        if ($this->input->post()) {

            $this->form_validation->set_rules('title', $this->lang->line('title'), 'required|trim');
            $this->form_validation->set_rules('page_desc', $this->lang->line('page_desc'), 'required');
            $this->form_validation->set_rules('status', $this->lang->line('status'), 'required|trim');
            if ($this->form_validation->run() == false) {
                load_views("cms/edit", $this->data);
            } else {
                $postedData = $this->input->post();

                $savedata['name'] = $postedData['title'];
                $savedata['content'] = $postedData['page_desc'];
                $savedata['status'] = $postedData['status'];
                $savedata['created_date'] = DEFAULT_DB_DATE_TIME_FORMAT;
                // calling to update data method.
                $res = $this->saveCmsData($savedata, $pageId);
                if ($res) {
                    $alertMsg['text'] = $this->lang->line('page_updated');
                    $alertMsg['type'] = $this->lang->line('success');
                    $this->session->set_flashdata('alertMsg', $alertMsg);
                } else {
                    $alertMsg['text'] = $this->lang->line('try_again');
                    $alertMsg['type'] = $this->lang->line('error');
                    $this->session->set_flashdata('alertMsg', $alertMsg);
                }
                redirect('/admin/cms');
            }
        } else {
            load_views("cms/edit", $this->data);
        }
    }

}
