<?php
defined("BASEPATH") OR exit("No direct script access allowed");

if ( ! function_exists( "json_dump" ) ) {
    function json_dump($array) {
        $ci = &get_instance();
        $ci->output
            ->set_content_type('application/json')
            ->set_output(json_encode($array));
        exit;
    }
}