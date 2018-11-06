<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NotFound extends CI_Controller
{

    public function __construct()
    {
        error_reporting(-1);
        ini_set('display_errors', 1);
        parent::__construct();
    }

    /**
     * Not found response for API
     *
     * @return string
     */
    public function api()
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header(404)
            ->set_output(json_encode([
                'code' => HTTP_NOT_FOUND,
                'msg' => 'Resource does not exist'
            ]));
    }
}
