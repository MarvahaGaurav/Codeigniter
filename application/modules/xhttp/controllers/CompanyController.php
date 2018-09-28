<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class CompanyController extends BaseController
{
    public function __construct($config = 'rest')
    {
        parent::__construct();
    }


    public function companyList()
    {
        try {

        } catch (\Exception $error) {
            json_dump(
                [
                "success" => true,
                "message" => "cities data found",
                "data" => $cities
                ]
            );
        }
    }
}
