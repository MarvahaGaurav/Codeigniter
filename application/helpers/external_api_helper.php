<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

if (! function_exists("get_request_handler")) {
    function get_request_handler($url)
    {
        $curl = curl_init();
        $url = "https://sg-as.com/api/v1/" . $url;

        $header[] = "authorization:n6dypPhIi7Gv4l2o2qFf4yPwLIQo2cqo";
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return false;
        } else {
            $response = trim($response);
            return $response;
        }
    }
}
