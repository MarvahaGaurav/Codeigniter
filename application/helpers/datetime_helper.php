<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

/**
 * Checks for empty parameters
 * @return array error status
 */
if ( ! function_exists('convert_date_time_format') ) {
    function convert_date_time_format($dateFormat, $dateTime, $requriedFormat = "Y-m-d H:i:s", $timezoneFrom = "UTC", $timezoneTo = "UTC") {
        if ( (!isset($dateTime) || empty($dateTime)) || (!isset($dateFormat) || empty($dateFormat))) {
            return "";
        }
        try {            
            $date = DateTime::createFromFormat($dateFormat, $dateTime, new DateTimeZone($timezoneFrom));
        } catch (Exception $error) {
            $date = DateTime::createFromFormat($dateFormat, $dateTime, new DateTimeZone("UTC"));
        }
        if ( !isset($date) || empty($date)) {
            return "";
        }
        try {
            $date->setTimezone(new DateTimeZone($timezoneTo));
        } catch (Exception $error) {
            $date->setTimezone(new DateTimeZone("utc"));
        }

        return $date->format($requriedFormat);
    }
}

