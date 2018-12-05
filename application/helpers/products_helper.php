<?php
defined("BASEPATH") or exit("No direct script access allowed");

if (! function_exists('products')) {
    function products($languageCode = 'en') {
        $ci = &get_instance();
        $ci->load->model('UtilModel');
        $products = $ci->UtilModel->selectQuery('*', 'products', ['where' => ['language_code' => $languageCode]]);

        return $products;
    }
}
