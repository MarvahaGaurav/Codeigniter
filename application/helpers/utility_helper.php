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

if (!function_exists('conver_to_meter')) {
    /**
     * Convert given unit to meter
     *
     * @param string $from
     * @param double $value
     * @return double
     */
    function convert_to_meter($from, $value)
    {
        $formulat_list = [
            "yard" => function ($yard) {
                return $yard * 0.9144;
            }, //returns meter
            "inches" => function ($inches) {
                return $inches * 0.0254;
            }, //returns meters
        ];
        
        if (! array_key_exists($from, $formulat_list)) {
            return 0;
        }

        return round((double)$formulat_list[$from]($value), 2);
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

if (! function_exists('get_percentage')) {
    /**
     * Get percentage amount
     *
     * @param double $amount
     * @param double $percentage
     * @param string $operator
     * @return double
     */
    function get_percentage($amount, $percentage, $operator = "minus")
    {
        $percentageAmount = $amount * ($percentage / 100);

        if ($operator === "minus") {
            return $amount - $percentageAmount;
        } else {
            return $amount + $percentageAmount;
        }
    }
}
