<?php
// if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists("convert_units")) {
    function convert_units($value, $specification_string)
    {
        $formulat_list = [
            "yard_to_meter" => function ($yard) {
                return $yard * 0.9144;
            }, //returns meter
            "meter_to_yard" => function ($meter) {
                return $meter * 1.0936;
            }, //returns yards
            "meter_to_inches" => function ($meter) {
                return $meter * 39.37;
            }, //returns inches
            "inches_to_meter" => function ($inches) {
                return $inches * 0.0254;
            }, //returns meters
            "yard_to_inches" => function ($yard) {
                return $yard * 36;
            }, //returns inches
            "inches_to_yard" => function ($inches) {
                return $inches/36;
            } //returns yard
        ];

        if (! array_key_exists($specification_string, $formulat_list)) {
            return 0;
        }

        return (double)sprintf("%.2f", $formulat_list[$specification_string]($value));
    }
}

/**
 *
 */
if (! function_exists('array_strip_tags')) {
    function array_strip_tags($array, $fields)
    {
        $array = array_map(function ($data) use ($fields) {
            foreach ($fields as $field) {
                $data[$field] = trim((strip_tags($data[$field])));
            }
            return $data;
        }, $array);

        return $array;
    }
}
