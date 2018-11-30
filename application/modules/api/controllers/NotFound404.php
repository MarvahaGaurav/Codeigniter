<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'BaseController.php';

class NotFound404 extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        $this->response(
            [
                "code" => HTTP_NOT_FOUND,
                "api_code_result" => "NOT_FOUND",
                "msg" => $this->lang->line("invalid_url")
            ],
            HTTP_NOT_FOUND
        );
    }
    
    public function index_get()
    {
        $this->response(
            [
                "code" => HTTP_NOT_FOUND,
                "api_code_result" => "NOT_FOUND",
                "msg" => $this->lang->line("invalid_url")
            ],
            HTTP_NOT_FOUND
        );
    }

    public function index_delete()
    {
        $this->response(
            [
                "code" => HTTP_NOT_FOUND,
                "api_code_result" => "NOT_FOUND",
                "msg" => $this->lang->line("invalid_url")
            ],
            HTTP_NOT_FOUND
        );
    }

    public function index_put()
    {
        $this->response(
            [
                "code" => HTTP_NOT_FOUND,
                "api_code_result" => "NOT_FOUND",
                "msg" => $this->lang->line("invalid_url")
            ],
            HTTP_NOT_FOUND
        );
    }
}
