<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

/**
 * Checks for empty parameters
 * @param array $data
 * @param array $mandatoryFields
 * @return array error status
 */
if ( ! function_exists('convert_date_time_format') ) {
    function convert_date_time_format($dateFormat, $dateTime, $requriedFormat = "Y-m-d H:i:s", $timezoneFrom = "UTC", $timezoneTo = "UTC") {
        try {            
            //echo $dateTime;die;
            $date = DateTime::createFromFormat($dateFormat, $dateTime, new DateTimeZone($timezoneFrom));
        } catch (Exception $error) {
            $date = DateTime::createFromFormat($dateFormat, $dateTime, new DateTimeZone("UTC"));
        }

        try {
            //pr($date);
            $date->setTimezone(new DateTimeZone($timezoneTo));
        } catch (Exception $error) {
            $date->setTimezone(new DateTimeZone("utc"));
        }

        return $date->format($requriedFormat);
    }
}

