<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("session");
    }

    /* Admin Dashboard */

    protected function loadAdmin($page, $data) {
        $this->load->view("admin/adminPanel/template/login_header", $data);
        $this->load->view("admin/adminPanel/{$page}", $data);
//                $this->load->view("admin/adminPanel/template/footer_1", $data);
        $this->load->view("admin/adminPanel/template/footer", $data);
    }

    /* Admin Dashboard */

    protected function loadAdminDashboard($page, $data) {
        $this->load->view("admin/adminPanel/template/header_1", $data);
        $this->load->view("admin/adminPanel/template/leftmenu_1", $data);
        $this->load->view("admin/adminPanel/template/navbar", $data);
        $this->load->view("admin/adminPanel/{$page}", $data);
//                $this->load->view("admin/adminPanel/template/footer_1", $data);
        $this->load->view("admin/adminPanel/template/ending_footer", $data);
    }

    /* Add merchant */

    protected function loadvendorDashboard($page, $data) {
        $this->load->view("admin/adminPanel/template/header_1", $data);
        $this->load->view("admin/adminPanel/template/leftmenu_1", $data);
        $this->load->view("admin/adminPanel/template/navbar", $data);
        $this->load->view("admin/Vendor_Management/{$page}", $data);
//                $this->load->view("admin/adminPanel/template/footer_1", $data);
        $this->load->view("admin/adminPanel/template/ending_footer", $data);
    }

}
