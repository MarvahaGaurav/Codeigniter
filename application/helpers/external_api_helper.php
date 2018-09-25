<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

if (! function_exists("get_sg_data")) {
    function get_sg_data($url)
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

if (! function_exists("mounting_type_str_to_num")) {
    function mounting_type_str_to_num($type)
    {
        $mountingMap = [
            'Suspended' => MOUNTING_SUSPENDED,
            'Recessed' => MOUNTING_RECESSED,
            'Surface' => MOUNTING_SURFACE,
            'Downlight' => MOUNTING_DOWNLIGHT,
            'Downlight Isosafe' => MOUNTING_DOWNLIGHT_ISOSAFE,
            'Pendant' => MOUNTING_PENDANT,
            'Tracks' => MOUNTING_TRACKS
        ];

        if (in_array($type, array_keys($mountingMap))) {
            return $mountingMap[$type];
        }

        return '';
    }
}
