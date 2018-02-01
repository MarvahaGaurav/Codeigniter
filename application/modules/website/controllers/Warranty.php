<?php 
defined("BASEPATH") or exit("No direct script access allowed");

use DatabaseExceptions\InsertException;

class Warranty extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index() 
    {
        echo "Warranty Page";
    }
}