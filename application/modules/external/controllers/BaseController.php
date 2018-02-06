<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once APPPATH . "libraries/REST_Controller.php";

use GuzzleHttp\Client as GuzzleClient;

class BaseController extends REST_Controller
{
    protected $language_code;
    protected $datetime;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['external_api', 'debuging', 'text']);
        $this->language_code = ["en", "da", "nb", "sv", "fi", "fr", "nl", "de"];
        $this->datetime = date("Y-m-d H:i:s");
    }

}