<?php

if (!function_exists('fetch_company_data()')) {
    function fetch_company_data($params = [])
    {
        $ci = &get_instance();
        $ci->load->model('UtilModel');
        $companies = $ci->UtilModel->selectQuery(['company_id', 'company_name', 'company_image'], 'company_master');

        return $companies;
    }
}
