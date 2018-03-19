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
    function fetch_cities($country_code, $opt=[]) 
    {
        $ci = &get_instance();
        $ci->load->model("UtilModel");
        $options = ["where" => ["country_code" => $country_code]];
        if ( isset($opt['limit']) && !empty((int)$opt['limit']) ) {
            $options['limit'] = $opt['limit'];
        }
        if ( isset($opt['where']) && !empty($opt['where']) && is_array($opt['where']) ) {
            foreach ( $opt['where'] as $column_field => $value ) {
                $options['where'][$column_field] = $value;
            }
        }
        $cities = $ci->UtilModel->selectQuery("*", "city_list", $options);

        return $cities;
    }
}