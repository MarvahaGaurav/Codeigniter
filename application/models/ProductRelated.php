<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';


class ProductRelated extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "products_related";
    }

    public function get()
    {
        
    }

}