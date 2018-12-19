<?php
defined("BASEPATH") or exit("No direct script access allowed");

if (!function_exists("json_dump")) {
    function json_dump($array)
    {
        header('Content-Type:application/json');
        echo json_encode($array);
        exit;
    }

    function json_repsonse($data, $code = 200)
    {
        $ci = &get_instance();
        $ci->output->set_status_header($code)
            ->set_content_type("application/json");

        echo json_encode($data);
        exit;
    }
}
