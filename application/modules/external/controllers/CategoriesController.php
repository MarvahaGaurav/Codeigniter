<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

use GuzzleHttp\Client as GuzzleClient;

class CategoriesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Category");
    }

    /**
     * External API - Inserts Product information  
     */
    public function category_post()
    {   
        $final_array = [];
        $language_code = $this->language_code;

        foreach ($language_code as $language) {
                $response = get_request_handler("{$language}/productCategories");
                $response = json_decode($response, true);
                $response = array_map(function($data) use ($language) {
                    $data['subtitle'] = $data['subTitle'];
                    $data['category_id'] = $data['id'];
                    $data['image'] = $data['image']['url'];
                    $data['language_code'] = $language;
                    $data['slug'] = preg_replace("/\s+/", "-" ,trim(strtolower(convert_accented_characters($data['title'])))). "-" . $data['language_code'];
                    if ( $data['type'] == 'residential' ) {
                        $data['type'] = 1;
                    } else if ($data['type'] == 'proffesional') {
                        $data['type'] = 2;
                    } else {
                        $data['type'] = 0;
                    }
                    $data['created_at'] = $this->datetime;
                    $data['updated_at'] = $this->datetime;
                    unset($data['subTitle']);
                    unset($data['id']);
                    return $data;
                }, $response);

                $final_array = array_merge($final_array, $response);
        }

        
        
        foreach ($final_array as $data) {
            $this->Category->batch_data[] = $data;
        }

        $this->Category->batch_save();

        pd($final_array);

    }

    
}