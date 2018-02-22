<?php 
defined("BASEPATH") OR exit("No direct script access allowed");
/**
 * DD - Dump & Die
 * @param mixed $mixed data
 * @param bool $exit if it should exit default true 
 */
if ( ! function_exists( "dd" ) ) {
    function dd($mixed, $exit = true) {
        var_dump($mixed);
        if ( $exit ) {
            exit; 
        }
    }
}

/**
 * PD - Print & Die
 * @param mixed $mixed data
 * @param bool $exit if it should exit default true 
 */
if ( ! function_exists( "pd" ) ) {
    function pd($mixed, $exit = true) {
        echo "<pre>";
        print_r($mixed);
        echo "</pre>";
        if ( $exit ) {
            exit; 
        }
    }
}
