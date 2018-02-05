<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class Application extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'applications';
    }

    public function get()
    {

    }

}