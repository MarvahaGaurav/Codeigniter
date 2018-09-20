<?php
defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Checks for empty parameters
 * @param array $data
 * @param array $mandatoryFields
 * @return array error status
 */
if (! function_exists('check_empty_parameters')) {
    function check_empty_parameters($data, $mandatoryFields)
    {
        foreach ($mandatoryFields as $value) {
            if (!isset($data[$value]) || empty(trim($data[$value]))) {
                return [
                    "error" => true,
                    "parameter" => $value
                ];
            }
        }

        return [
            "error" => false
        ];
    }
}

/**
 * Trim input parameters
 * @param array $data
 * @return array trimmed $data array
 */
if (! function_exists('trim_input_parameters')) {

    function trim_input_parameters($data, $unsetEmptyValue = true)
    {
        $output = array_map(function ($value) use ($unsetEmptyValue) {
            if (! $unsetEmptyValue) {
                return is_array($value)?trim_input_parameters($value, false):htmlentities(trim($value), ENT_NOQUOTES);
            }
            return is_array($value)?trim_input_parameters($value):htmlentities(trim($value), ENT_NOQUOTES);
        }, $data);

        if ($unsetEmptyValue) {
            $output = array_filter($output, function ($value) {
                if ((! is_array($value) && empty(trim($value))) || ( is_array($value) && empty($value) )) {
                    return false;
                } else {
                    return true;
                }
            });
        }
        return $output;
    }
    

}
