<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';


class ProductTechnicalData extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "product_technical_data";
    }

    public function get()
    {
        
    }
}