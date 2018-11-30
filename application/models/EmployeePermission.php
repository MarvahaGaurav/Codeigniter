<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';


class EmployeePermission extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "user_employee_permission";
    }

    public function get()
    {
        
    }

}