<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

use GuzzleHttp\Client as GuzzleClient;

class ApplicationController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Application");
    }

    /**
     * External API - Inserts Application information  
     */
    public function application_post()
    {   
        $final_array = [];
        $language_code = $this->language_code;
        
        foreach (  $language_code as $language ) {
            $response = get_request_handler("{$language}/applications");
            $response = json_decode($response, true);
            $response = array_map(function($data) use ($language) {
                $data['language_code'] = $language;
                return $data;
            }, $response);
            $final_array = array_merge($final_array, $response);
        }

        $final_array = array_map(function($data){
            $data['image'] = $data['image']['url'];
            if ( $data['type'] == 'residential' ) {
                $data['type'] = 1;
            } else if ($data['type'] == 'proffesional') {
                $data['type'] = 2;
            } else {
                $data['type'] = 0;
            }

            $data['subtitle'] = $data['subTitle'];
            $data['created_at'] = $this->datetime;
            $data['updated_at'] = $this->datetime;
            $data['application_id'] = $data['id'];
            unset($data['subTitle']);
            unset($data['id']);
            return $data;
        }, $final_array);

        foreach ( $final_array as $data ) {
            $this->Application->batch_data[] = $data;
        }

        $this->Application->batch_save();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($final_array));
    }
}