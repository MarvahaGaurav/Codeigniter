<?php
defined("BASEPATH") or exit("No direct script access allowed");

if (!function_exists('getDataWith')) {
    function getDataWith($parentArray, $childArray, $parentMatchField, $childMatchField, $outputField = '', $childField = '')
    {
        $tempArray = [];
        foreach ($childArray as $value) {
            if (isset($tempArray[$value[$childMatchField]])) {
                if (!empty($childField)) {
                    $tempArray[$value[$childMatchField]][] = $value[$childField];
                } else {
                    $tempArray[$value[$childMatchField]][] = $value;
                }
            } else {
                if (!empty($childField)) {
                    $tempArray[$value[$childMatchField]] = [$value[$childField]];
                } else {
                    $tempArray[$value[$childMatchField]] = [$value];
                }
            }
        }

        foreach ($parentArray as $key => $value) {
            if (isset($tempArray[$value[$parentMatchField]])) {
                $parentArray[$key][$outputField] = $tempArray[$value[$parentMatchField]];
            } else {
                $parentArray[$key][$outputField] = [];
            }
        }
        
        return $parentArray;
    }
}
