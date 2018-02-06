<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

use DatabaseExceptions\UpdateException;
use DatabaseExceptions\SelectException;
use DatabaseExceptions\InsertException;
use DatabaseExceptions\DeleteException;

use GuzzleHttp\Client as GuzzleClient;

class ApplicationController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Application");
        $this->load->model("Product");
    }

    /**
     * External API - Inserts Application information  
     */
    public function application_post()
    {   
        try {
            $final_array = [];
            $language_code = $this->language_code;
            
            $this->db->trans_begin();
            foreach ( $language_code as $language ) {
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
                $data['slug'] = preg_replace("/\s+/", "-" ,strtolower(convert_accented_characters($data['title']))) . "-" . $data['language_code'];
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

            $this->db->trans_commit();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['code' => 200, 'code_result' => 'OK']));
        } catch ( InsertException $error ) {
            $this->db->trans_rollback();
            echo $this->db->last_query();die;
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['code' => 200, 'code_result' => 'OK', 'query' => $this->db->last_query()]));
        }
    }
}