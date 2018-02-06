<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

if ( ! function_exists( "dd" ) ) {
    function dd($mixed) {
        var_dump($mixed);
        exit; 
    }
}
if ( ! function_exists( "pd" ) ) {
    function pd($mixed) {
        print_r($mixed);
        exit;
    }
}
