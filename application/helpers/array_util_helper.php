<?php
defined("BASEPATH") or exit("No direct script access allowed");

/**
 * Flatten a multi-dimensional array into a one dimensional array.
 *
 * @param  array   $array         The array to flatten
 * @param  boolean $preserveKeys  Whether or not to preserve array keys. Keys from deeply nested arrays will
 *                                overwrite keys from shallow nested arrays
 * @return array
 */
if (!function_exists("array_flatten")) {
    function array_flatten($array, $preserveKeys = true)
    {
        $flattened = [];

        array_walk_recursive($array, function ($value, $key) use (&$flattened, $preserveKeys) {
            if ($preserveKeys && !is_int($key)) {
                $flattened[$key] = $value;
            } else {
                $flattened[] = $value;
            }
        });

        return $flattened;
    }
}

if (!function_exists("array_flatten_d")) {
    function array_flatten_d($array, &$outputArray, $depth = 1, $count = 0)
    {
        array_map(function ($data) use (&$outputArray, $depth, $count) {
            if ($count < $depth) {
                array_flatten_d($data, $outputArray, $depth, ++$count);
            } else {
                $outputArray[] = $data;
            }
        }, $array);
    }
}
