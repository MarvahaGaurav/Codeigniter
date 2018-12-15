<?php
defined("BASEPATH") or exit("No direct script access allowed");

if (!function_exists('getDataWith')) {
    /**
     * combines a parent and child related table results that are fetched from the DB
     *
     * @param array $parentArray
     * @param array $childArray
     * @param string $parentMatchField
     * @param string $childMatchField
     * @param string $outputField
     * @param string $childField
     * @return array
     */
    function getDataWith($parentArray, $childArray, $parentMatchField, $childMatchField, $outputField = '', $childField = '', $getFirstElement = false)
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
                if ((bool)$getFirstElement) {
                    $parentArray[$key][$outputField] = array_shift($parentArray[$key][$outputField]);
                }
            } else {
                $parentArray[$key][$outputField] = [];
            }
        }
        
        return $parentArray;
    }
}
