<?php
defined("BASEPATH") OR exit("No direct script access allowed");

if ( ! function_exists( "json_dump" ) ) {
    function json_dump($array) {
        header('Content-Type:application/json');
        echo json_encode($array);
        exit;
    }
}