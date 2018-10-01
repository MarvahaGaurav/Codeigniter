<?php
defined("BASEPATH") or exit("No direct script access allowed");

if (!function_exists('getDataWith')) {
    function getDataWith($parentArray, $childArray, $field, $outputField = '', $childField = '')
    {
        $tempArray = [];
        foreach ($childArray as $value) {
            if (isset($tempArray[$value[$field]])) {
                if (!empty($childField)) {
                    $tempArray[$value[$field]][] = $value[$childField];
                } else {
                    $tempArray[$value[$field]][] = $value;
                }
            } else {
                if (!empty($childField)) {
                    $tempArray[$value[$field]] = [$value[$childField]];
                } else {
                    $tempArray[$value[$field]] = [$value];
                }
            }
        }

        foreach ($parentArray as $key => $value) {
            if (isset($tempArray[$value[$field]])) {
                $parentArray[$key][$outputField] = $tempArray[$value['product_id']];
            } else {
                $parentArray[$key][$outputField] = [];
            }
        }

        return $parentArray;
    }
}
