<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

class Category extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "categories";
    }

    public function get()
    {
        
    }
}