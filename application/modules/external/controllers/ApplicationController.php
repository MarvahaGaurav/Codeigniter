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
        $this->load->model("ProductTechnicalData");
        $this->load->model("ProductGallery");
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
            
            
            foreach ( $language_code as $language ) {
                $response = get_request_handler("{$language}/applications");
                $response = json_decode($response, true);

                $response = array_map(function($data) use ($language) {
                    $data['language_code'] = $language;
                    return $data;
                }, $response);

                foreach ( $response as $data ) {
                    $this->db->trans_begin();
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
                    $this->Application->slug = preg_replace("/\s+/", "-" ,trim(strtolower(convert_accented_characters($data['title'])))). "-" . $data['language_code'];
                    $this->Application->subtitle = $data['subTitle'];
                    $this->Application->created_at = $this->datetime;
                    $this->Application->updated_at = $this->datetime;
                    $this->Application->application_id = $data['id'];

                    $application_id = $this->Application->save();

                    $product_response = get_request_handler("{$data['language_code']}/applications/{$data['id']}/products");
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

                        $product_details = get_request_handler("{$data['language_code']}/products/{$product['id']}");
                        $product_details = json_decode($product_details, true);

                        $this->Product->title = $product_details['title'];
                        $this->Product->language_code = $data['language_code'];
                        $this->Product->subtitle = $product_details['subTitle'];
                        $this->Product->slug = preg_replace("/\s+/", "-" ,trim(strtolower(convert_accented_characters($product_details['title'])))). "-" . $data['language_code'];;
                        $this->Product->product_id = $product_details['id'];
                        $this->Product->how_to_specity = $product_details['howToSpecity'];
                        $this->Product->image = $product['image']['url'];
                        $this->Product->created_at = $this->datetime;
                        $this->Product->updated_at = $this->datetime;

                        $product_id = $this->Product->save();
                        $product_technical_data = $product_details["technicalData"];
                        $product_gallery = $product_details['images'];
                        
                        $product_gallery = array_map(function($gallery) use($product_id){
                            $gallery['product_id'] = $product_id;
                            $gallery['image'] = $gallery['url'];
                            $gallery['created_at'] = $this->datetime;
                            $gallery['updated_at'] = $this->datetime;
                            unset($gallery['url']);
                            return $gallery;
                        }, $product_gallery);
                        
                        $product_technical_data = array_map(function($technical) use ($product_id, $data) {
                            $technical['info'] = $technical['text'];
                            $technical['product_id'] = $product_id;
                            $technical['slug'] = preg_replace("/\s+/", "-" ,trim(strtolower(convert_accented_characters($technical['title'])))). "-" . $data['language_code'];
                            $technical['created_at'] = $this->datetime;
                            $technical['updated_at'] = $this->datetime;
                            unset($technical['text']);
                            return $technical;
                        }, $product_technical_data);

                        $this->ProductTechnicalData->batch_data = $product_technical_data;
                        $this->ProductTechnicalData->batch_save();

                        $this->ProductGallery->batch_data = $product_gallery;
                        $this->ProductGallery->batch_save();

                    }
                    $this->db->trans_commit();
                }

            }

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['code' => 200, 'code_result' => 'OK']));
        } catch ( InsertException $error ) {
            $this->db->trans_rollback();
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['code' => 500, 'code_result' => 'INTERNAL_SERVER_ERROR', 'query' => $this->db->last_query()]));
        }
    }
}