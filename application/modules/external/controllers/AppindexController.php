<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require_once APPPATH . "libraries/REST_Controller.php";

use DatabaseExceptions\InsertException;

class AppindexController extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    
    public function index_get()
    {
        
    }

    
}