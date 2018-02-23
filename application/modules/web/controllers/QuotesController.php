<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "BaseController.php";

class QuotesController extends BaseController 
{

    function __construct() {		
        parent::__construct();
        $this->neutral_session();
    }

    public function index() {	
        $this->data['userInfo'] = $this->userInfo;
        if ( ! empty($this->userInfo) &&
            isset($this->userInfo['status']) &&
            $this->userInfo['status'] != BLOCKED
        ) {
            load_alternate_views("quotes/main", $this->data);
        } else {
            load_alternate_views("quotes/main_inactive_session", $this->data);
        }
    }

}

?>