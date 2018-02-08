<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Profile extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Common_model');
        $this->load->library('form_validation');
        $this->load->helper("encrypt_openssl");
        $this->load->helper(array('form', 'url', 'date'));
        $this->load->library('session');
        $this->lang->load('common', "english");
        $this->data = [];
        $this->admininfo = [];
        $this->admininfo = $this->session->userdata('admininfo');
        if (empty($this->admininfo)) {
            redirect(base_url() . 'admin/Admin');
        }
        $this->data['admininfo'] = $this->admininfo;
    }

    /**
     * @name admin_profile
     * @description Admin pofile view
     * @access public.
     */
    public function admin_profile() {

        $this->data["csrfName"] = $this->security->get_csrf_token_name();
        $this->data["csrfToken"] = $this->security->get_csrf_hash();
        $this->data['editdata'] = $this->admininfo;
        load_views("profile/my-profile", $this->data);
    }

    /**
     * @name admin_change_password
     * @description This method is used to change admin password.
     */
    public function admin_change_password() {

        $postdata = $this->input->post();

        if (isset($postdata) && !empty($postdata)) {

            $this->form_validation->set_rules('oldpassword', $this->lang->line('old_pass'), 'trim|required');
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required');
            $this->form_validation->set_rules('confirm_password', $this->lang->line('con_pass'), 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                load_views("profile/change-password", $this->data);
            } else {
                $isExists = $this->Common_model->fetch_data('admin', 'admin_password', ['where' => ['admin_password' => hash("sha256", $postdata["oldpassword"]), 'admin_id' => $this->admininfo['admin_id']]], true);

                if ($isExists != []) {
                    /*
                     * updating admin password 
                     */
                    $userdata['admin_password'] = (isset($postdata['password']) && !empty($postdata['password'])) ? hash("sha256", $postdata["password"]) : '';
                    $where = array("where" => array('admin_id' => $this->admininfo['admin_id']));
                    $this->Common_model->update_single("admin", $userdata, $where);

                    $this->session->set_flashdata('message', $this->lang->line('success_prefix') . $this->lang->line('password_updated') . $this->lang->line('success_suffix'));
                    redirect(base_url() . "admin/profile");
                } else {
                    $this->data['error_message'] = $this->lang->line('old_password_mismatch');
                    load_views("profile/change-password", $this->data);
                }
            }
        } else {
            load_views("profile/change-password", $this->data);
        }
    }

    /**
     * @name Admin Profile
     * @description This method is used to edit admin profile.
     */
    public function edit_profile() {
        $postdata = $this->input->post();
        $get = $this->input->get();

        if (isset($postdata) && !empty($postdata)) {
            $this->form_validation->set_rules('Admin_Name', $this->lang->line('name_missing'), 'trim|required');
            /* Client side validation false it will redirect to form */
            if ($this->form_validation->run() == FALSE) {
                /* Csrf token check */
                $this->data["csrfName"] = $this->security->get_csrf_token_name();
                $this->data["csrfToken"] = $this->security->get_csrf_hash();
                $this->data['editdata'] = $this->Common_model->fetch_data('admin', array('admin_id', 'admin_email', 'admin_profile_pic'), array('where' => array('admin_id' => $id)), true);
                load_views("profile/admin_profile_edit", $this->data);
            }

            if (isset($_FILES['admin_image']) && !empty($_FILES['admin_image'])) {
                $this->load->library('commonfn');
                $config = [];
                //echo UPLOAD_IMAGE_PATH; die;
                $config = getConfig(UPLOAD_IMAGE_PATH, 'jpeg|jpg|png', 3000, 1024, 768);
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('admin_image')) {
                    $upload_data = $this->upload->data();
                    //print_r($upload_data);die;
                    $imageName = $upload_data['file_name'];
                    $thumbFileName = $upload_data['file_name'];
                    $fileSource = UPLOAD_IMAGE_PATH . $thumbFileName;
                    $targetPath = UPLOAD_THUMB_IMAGE_PATH;
                    //$isSuccess = $this->commonfn->thumb_create($thumbFileName, $fileSource, $targetPath);
                    if ($isSuccess) {
                        $thumbName = $imageName;
                    }
                } else { 
                    $this->data['imageErr'] = strip_tags($this->upload->display_errors());
                    //print_r($this->data); die('asdfs');
                    //load_views("profile/admin_profile_edit", $this->data);
                    $this->session->set_flashdata('message', $this->lang->line('error_prefix') .strip_tags($this->upload->display_errors()). $this->lang->line('error_suffix'));
                    redirect(base_url() . "admin/edit-profile");                    
                }
            }
            $adminData = [];
            $adminData['admin_name'] = $postdata['Admin_Name'];
            if (isset($imageName) && !empty($imageName)) {
                $adminData['admin_profile_pic'] = $imageName;
                $adminData['admin_profile_thumb'] = $thumbName;
            }
            $where = array("where" => array('admin_id' => $this->admininfo['admin_id']));
            $isSuccess = $this->Common_model->update_single("admin", $adminData, $where);

            if ($isSuccess) {
                $newAdminInfo = $this->admininfo;
                $newAdminInfo['admin_name'] = $adminData['admin_name'];
                if(!empty($adminData['admin_profile_pic'])){
                    $newAdminInfo['admin_profile_pic'] = $adminData['admin_profile_pic'];
                    $newAdminInfo['admin_profile_thumb'] = $adminData['admin_profile_thumb'];
                }
                
                $this->session->set_userdata('admininfo', $newAdminInfo);
                $this->session->set_flashdata('message', $this->lang->line('success_prefix') . $this->lang->line('profile_update') . $this->lang->line('success_suffix'));
                redirect(base_url() . "admin/profile");
            } else {
                $this->session->set_flashdata('message', $this->lang->line('error_prefix') . $this->lang->line('error_suffix'));
                load_views("profile/admin_profile_edit", $this->data);
            }
        }

        $this->data["csrfName"] = $this->security->get_csrf_token_name();
        $this->data["csrfToken"] = $this->security->get_csrf_hash();
        $this->data['editdata'] = $this->admininfo;
        load_views("profile/admin_profile_edit", $this->data);
    }

}
