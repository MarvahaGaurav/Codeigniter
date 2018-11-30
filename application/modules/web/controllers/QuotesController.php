<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once "BaseController.php";

class QuotesController extends BaseController {

    function __construct()
    {
        parent::__construct();
        $this->activeSessionGuard();

    }



    public function index()
    {
        $this->data['userInfo'] = $this->userInfo;
        if ( ! empty($this->userInfo) &&
            isset($this->userInfo['status']) &&
            $this->userInfo['status'] != BLOCKED
        ) {
            website_view("quotes/main", $this->data);
        }
        else {
            website_view("quotes/main_inactive_session", $this->data);
        }

    }



}

?>