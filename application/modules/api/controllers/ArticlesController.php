<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class ArticlesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Application");
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function articles_get()
    {
        
    }
}
