<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';


class ProductSpecification extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "product_specifications";
    }

    public function get()
    {
        
    }
}