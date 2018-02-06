<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

use GuzzleHttp\Client as GuzzleClient;

class ProductController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Product");
        $this->load->model("Application");
    }

    /**
     * External API - Inserts Product information  
     */
    public function product_post()
    {   
        $final_array = [];
        $language_code = $this->language_code;

        $application_data = $this->Application->fetch();

        foreach ($application_data as $application) {
                $response = get_request_handler("{$application['language_code']}/applications/{$application['application_id']}/products");
                $response = json_decode($response, true);
                $language = $application['language_code'];
                $response = array_map(function($data) use ($language) {
                    $data['language_code'] = $language;
                    return $data;
                }, $response);

                $final_array = array_merge($final_array, $response);
        }
        $output = [];
        foreach ($final_array as $key => $data) {
            $output[$data['id']] = $data;
        }

        $output = array_map(function($data) {
            $data['product_id'] = $data['id'];
            $data['subtitle'] = $data['subTitle'];
            $data['image'] = $data['image']['url'];
            $data['created_at'] = $this->datetime;
            $data['updated_at'] = $this->datetime;
            unset($data['id']);
            unset($data['subTitle']);
            return $data;
        }, $output);

        foreach ( $output as $data ) {
            $this->Product->batch_data[] = $data;
        }
        
        $this->Product->batch_save();

        pd($output);
        
    }

    
}