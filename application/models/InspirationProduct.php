<?php
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class InspirationProduct extends BaseModel {

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'inspiration_products';
    }

}