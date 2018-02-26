<?php 
defined("BASEPATH") or exit("No direct script access allowed");
require_once "BaseController.php";


class ProjectController extends BaseController
{   
    
    public function __construct()
    {
        parent::__construct();
        $this->neutral_session();
    }

    public function index()
    {
        $this->data['userInfo'] = $this->userInfo;
        $this->session->set_flashdata("flash-message", "");
        $this->session->set_flashdata("flash-type", "");
        if ( ! empty($this->userInfo) &&
            isset($this->userInfo['status']) &&
            $this->userInfo['status'] != BLOCKED
        ) {
            load_alternate_views("projects/main", $this->data);
        } else {
            load_alternate_views("projects/main_inactive_session", $this->data);
        }

    }

    public function create()
    {

    }
}