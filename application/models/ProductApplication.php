<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';


class ProductApplication extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "product_applications";
    }

    public function get()
    {
        
    }

}