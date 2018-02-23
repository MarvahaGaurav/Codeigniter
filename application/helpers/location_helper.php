<?php
defined("BASEPATH") OR exit("No direct script access allowed");
/**
 * Fetches country list 
 * @return array 
  */
if ( ! function_exists("fetch_countries") ) {
    function fetch_countries() 
    {
        $ci = &get_instance();
        $ci->load->model("UtilModel");
        $countries = $ci->UtilModel->selectQuery("*", "country_list");

        return $countries;
    }
}

/**
 * Fetches cities based on country code
 * @param string $country_code 
 * @return array 
  */
if ( ! function_exists("fetch_cities") ) {
    function fetch_cities($country_code) 
    {
        $ci = &get_instance();
        $ci->load->model("UtilModel");
        $cities = $ci->UtilModel->selectQuery("*", "city_list", ["where" => ["country_code" => $country_code]]);

        return $cities;
    }
}