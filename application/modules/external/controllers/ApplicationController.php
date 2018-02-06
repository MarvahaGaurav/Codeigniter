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
        $this->load->model("ProductTechicalData");
        $this->load->model("ProductSpecification");
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

                foreach ( $response as $data ) {
                    $this->Application->image = $data['image']['url'];
                    if ( $data['type'] == 'residential' ) {
                        $this->Application->type = 1;
                    } else if ($data['type'] == 'proffesional') {
                        $this->Application->type = 2;
                    } else {
                        $this->Application->type = 0;
                    }
                    $this->Application->title = $data['title'];
                    $this->Application->language_code = $data['language_code'];
                    $this->Application->slug = preg_replace("/\s+/", "-" ,strtolower(convert_accented_characters($data['title']))) . "-" . $data['language_code'];
                    $this->Application->subtitle = $data['subTitle'];
                    $this->Application->created_at = $this->datetime;
                    $this->Application->updated_at = $this->datetime;
                    $this->Application->application_id = $data['id'];

                    $application_id = $this->Application->save();

                    $product_response = get_request_handler("{$data['language_code']}/applications/{$application_id}/products");
                    $product_response = json_decode($product_response, true);

                    $product_data = $this->Product->fetch();

                    $product_ids = array_map(function($data) {
                        return $data['product_id'];
                    }, $product_data);

                    $batch_data = [];
                    foreach( $product_response as $product ) {
                        if ( in_array($product['id'], $product_ids) ) {
                            continue;
                        }

                        $product_details = get_request_handler("{$data['language_code']}/product/{$product['id']}");
                        $product_details = json_decode($product_details, true);

                        $this->Product->product_id = $product['id'];
                        $this->Product->title = $product['title'];
                        $this->Product->subtitle = $product['subTitle'];
                        $this->Product->slug = preg_replace("/\s+/", "-" ,strtolower(convert_accented_characters($product['title']))) . "-" . $data['language_code'];;
                        $this->Product->product_id = $product['id'];
                        $this->Product->image = $product['image'];

                        $product_technical_data = $product_details["technicalData"];

                        $product_technical_data = array_map(function($data){
                            $data['slug'] = preg_replace("/\s+/", "-" ,strtolower(convert_accented_characters($data['title']))) . "-" . $data['language_code'];
                            $data['created_at'] = $this->datetime;
                            $data['updated_at'] = $this->datetime;
                            return $data;
                        }, $product_technical_data);

                        $this->ProductTechicalData->batch_data = $product_technical_data;

                        $this->ProductTechicalData->batch_save();

                    }
                }

            }

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