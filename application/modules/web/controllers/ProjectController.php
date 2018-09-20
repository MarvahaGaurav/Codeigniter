<?php 
defined("BASEPATH") or exit("No direct script access allowed");
require_once "BaseController.php";


class ProjectController extends BaseController
{
   
    
    public function __construct()
    {
        parent::__construct();
        $this->neutralGuard();
    }

    public function index()
    {
        $this->data['userInfo'] = $this->userInfo;
        if (! empty($this->userInfo) 
            && isset($this->userInfo['status']) 
            && $this->userInfo['status'] != BLOCKED
        ) {
            load_website_views("projects/main", $this->data);
        } else {
            load_website_views("projects/main_inactive_session", $this->data);
        }

    }

    public function create()
    {

    }
}