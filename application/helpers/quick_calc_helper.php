<?php 
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('hitCulrQuickCal')) {
    function hitCulrQuickCal($data)
    {
        $request_data = json_encode($data);
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => "https://www.dialux-plugins.com/FastCalc/api/arrangement",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "$request_data",
                CURLOPT_HTTPHEADER => ["Content-Type: application/json", "cache-control: no-cache"],
            ]
        );
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}

if (!function_exists('quickCalcSuggestions')) {
    function quickCalcSuggestions($data)
    {
        $request_data = json_encode($data);
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => "https://www.dialux-plugins.com/FastCalc/api/suggestion",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "$request_data",
                CURLOPT_HTTPHEADER => ["Content-Type: application/json", "cache-control: no-cache"],
            ]
        );
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}
