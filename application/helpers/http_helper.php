<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

if ( ! function_exists("async_curl_post") ) {
    function async_curl_post($url, $data, $headers = [], $buildQuery = true) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
        if (count($headers) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);   // Always ensure the connection is fresh				
        curl_setopt($curl, CURLOPT_HEADER, false);         // Don't retrieve headers				
        // curl_setopt($curl, CURLOPT_NOBODY, false);          // Don't retrieve the body				
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
        if ($buildQuery) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        } else {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        ignore_user_abort(true);

        $response = curl_exec($curl);
        
        curl_close($curl);

        return $response;
    }
}