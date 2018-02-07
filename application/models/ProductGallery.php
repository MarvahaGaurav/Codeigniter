<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';


class ProductGallery extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "product_gallery";
    }

    public function get()
    {
        
    }
}