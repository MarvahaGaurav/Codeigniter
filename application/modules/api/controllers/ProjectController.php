<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class ProjectController extends BaseController
{

    /**
     * Request Data
     *
     * @var array
     */
    private $request;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Product");
    }

    /**
     * Save project created by user
     *
     * @return string
     */
    public function index_post()
    {
        try {
            $this->request = $this->post();
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' =>  $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Validate project
     *
     * @return void
     */
    private function validateProject()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules();
    }
}
