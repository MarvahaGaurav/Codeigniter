<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class ApplicationController extends BaseController
{   
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("location");
    }

    public function fetch($application_type = 0) 
    {
        $where = [];
        if (!empty($application_type) &&
            in_array($application_type, [APPLICATION_RESIDENTIAL, APPLICATION_PROFESSIONAL])) {
            $where['type'] = $application_type;
        }

        $where['language_code'] = "en";
        $this->load->model("UtilModel");
        $data = $this->UtilModel->selectQuery("id, title as text", "applications", ["where" => $where]);
        if ( empty($data) ) {
            json_dump([
                "success" => false,
                "message" => $this->lang->line("no_records_found")
            ]);
        }

        $data = array_map(function($application) {
            $application['id'] = encryptDecrypt($application['id']);
            return $application;
        }, $data);

        json_dump([
            "success" => true,
            "message" => $this->lang->line("application_data_fetched"),
            "data" => $data
        ]);
    }
}