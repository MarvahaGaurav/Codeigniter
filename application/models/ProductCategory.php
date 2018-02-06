<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';


class ProductCategory extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "product_categories";
    }

    public function get()
    {
        
    }

}