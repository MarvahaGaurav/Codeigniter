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
        $this->load->model('Common_model');
    }

    /**
     * External API - Inserts Application information  
     * This API extracts information in layers 
     * 1) Fetch application data based on language code
     * 2) Fetch products based on application code and language code
     * 3) Fetch product details
     * 4) Fetch product speification from data recieved in product details
     * 
     * Consider the layering to begin when get_request_handler() function is called
     * which fetches the data form the URI resource
     * 
     * @return string JSON response
     */
    public function application_post()
    {   
        try {
            $final_array = [];
            $language_code = $this->language_code;
            // $this->db->trans_begin();
            $product_technical_data = [];
            $product_gallery = [];
            foreach ( $language_code as $language ) {
                //Layer 1 - fetch application data
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
                    $this->Application->slug = preg_replace("/\s+/", "-" ,trim(strtolower(convert_accented_characters($data['title'])))). "-" . $data['language_code'];
                    $this->Application->subtitle = $data['subTitle'];
                    $this->Application->created_at = $this->datetime;
                    $this->Application->updated_at = $this->datetime;
                    $this->Application->application_id = $data['id'];

                    $application_id = $this->Application->save();
                    //Layer - 2 Fetch product listing
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
                        //layer 3 - fetch product details
                        $product_details = get_request_handler("{$data['language_code']}/products/{$product['id']}");
                        $product_details = json_decode($product_details, true);

                        if ( empty($product_details) ) {
                            log_message('error', json_encode($product));
                            log_message('error', "\n-------------------------------------------------\n");
                            continue;
                        }

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
                        $technical_data = $product_details["technicalData"];
                        $gallery = $product_details['images'];
                        $specifications = $product_details['specifications'];
                        
                        $gallery = array_map(function($gallery) use ($product_id) {
                            $gallery['product_id'] = $product_id;
                            $gallery['image'] = $gallery['url'];
                            $gallery['created_at'] = $this->datetime;
                            $gallery['updated_at'] = $this->datetime;
                            unset($gallery['url']);
                            return $gallery;
                        }, $gallery);
                        
                        $technical_data = array_map(function($technical) use ($product_id, $data) {
                            $technical['info'] = $technical['text'];
                            $technical['product_id'] = $product_id;
                            $technical['slug'] = preg_replace("/\s+/", "-" ,trim(strtolower(convert_accented_characters($technical['title'])))). "-" . $data['language_code'];
                            $technical['created_at'] = $this->datetime;
                            $technical['updated_at'] = $this->datetime;
                            unset($technical['text']);
                            return $technical;
                        }, $technical_data);

                        $this->Common_model->insert_batch("product_technical_data", [], $technical_data);
                        $this->Common_model->insert_batch("product_gallery", [], $gallery);

                        $articlecodes = array_map(function($specification){
                            return $specification['articlecode'];
                        }, $specifications);
                        $specification_batch_data = [];
                        foreach ( $articlecodes as $articlecode ) {
                            //Layer 4 - Specifications Data
                            $specification_response = get_request_handler("{$data['language_code']}/specifications/{$product['id']}/{$articlecode}");
                            $specification_data = json_decode($specification_response, true);
                            $specification_batch_data[] = $this->mapSpecifictionsData($specification_data, $product_id, $data['language_code']);
                        }
                        $this->Common_model->insert_batch("product_specifications", [], $specification_batch_data);
                    }
                }
                
            }

            // $this->db->trans_commit();
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

    /**
     * Maps specification data to database keys  
     * 
     * @return array 
     */
    private function mapSpecifictionsData($specification_data, $product_id, $language_code) 
    {
        $data['product_id'] = $product_id;
        $data["articlecode"]= $specification_data["articlecode"];
        $data["ean"]= $specification_data["ean"];
        $data["title"]= $specification_data["title"];
        $data["title"]= $specification_data["title"];
        $data["slug"]= preg_replace("/\s+/", "-" ,trim(strtolower(convert_accented_characters($specification_data['title'])))). "-" . $language_code;
        $data["uld"]= $specification_data["uld"];
        $data["ldt"]= $specification_data["ldt"];
        $data["pdf"]= $specification_data["pdf"];
        $data["type"]= $specification_data["data"]["type"];
        $data["driver"]= $specification_data["data"]["driver"];
        $data["length"]= $specification_data["data"]["length"];
        $data["width"]= $specification_data["data"]["width"];
        $data["height"]= $specification_data["data"]["height"];
        $data["diameter"]= $specification_data["data"]["diameter"];
        $data["depth"]= $specification_data["data"]["depth"];
        $data["cut_out"]= $specification_data["data"]["cutOut"];
        $data["ceiling_void_depth"]= $specification_data["data"]["ceilingVoidDepth"];
        $data["distance_to_flamable_materials"]= $specification_data["data"]["distanceToFlamableMaterials"];
        $data["brutto_weight"]= $specification_data["data"]["bruttoWeight"];
        $data["netto_weight"]= $specification_data["data"]["nettoWeight"];
        $data["energy_class"]= $specification_data["data"]["energyClass"];
        $data["approval"]= $specification_data["data"]["approval"];
        $data["type_of_light_source"]= $specification_data["data"]["typeOfLightSource"];
        $data["socket"]= $specification_data["data"]["socket"];
        $data["light_source_included"]= $specification_data["data"]["lightSourceIncluded"];
        $data["wattage"]= $specification_data["data"]["wattage"];
        $data["system_wattage"]= $specification_data["data"]["systemWattage"];
        $data["luminous_flux"]= $specification_data["data"]["luminousFlux"];
        $data["efficacy"]= $specification_data["data"]["efficacy"];
        $data["voltage"]= $specification_data["data"]["voltage"];
        $data["colour_temperature"]= $specification_data["data"]["colourTemperature"];
        $data["colour_rendering"]= $specification_data["data"]["colourRendering"];
        $data["mac_adams_factor"]= $specification_data["data"]["macAdamsFactor"];
        $data["lifetime"]= $specification_data["data"]["lifetime"];
        $data["light_distribution"]= $specification_data["data"]["lightDistribution"];
        $data["beam_angle"]= $specification_data["data"]["beamAngle"];
        $data["housing"]= $specification_data["data"]["housing"];
        $data["colour"]= $specification_data["data"]["colour"];
        $data["optics"]= $specification_data["data"]["optics"];
        $data["mounting"]= $specification_data["data"]["mounting"];
        $data["module"]= $specification_data["data"]["module"];
        $data["wire_set"]= $specification_data["data"]["wireSet"];
        $data["cable"]= $specification_data["data"]["cable"];
        $data["cable_entry"]= $specification_data["data"]["cableEntry"];
        $data["plug"]= $specification_data["data"]["plug"];
        $data["wind_projected_area"]= $specification_data["data"]["windProjectedArea"];
        $data["luminaire_class"]= $specification_data["data"]["luminaireClass"];
        $data["ingress_protection_rating"]= $specification_data["data"]["ingressProtectionRating"];
        $data["vandal_class"]= $specification_data["data"]["vandalClass"];
        $data["ta_nominel"]= $specification_data["data"]["taNominel"];
        $data["fire_protection_class"]= $specification_data["data"]["fireProtectionClass"];
        $data['created_at'] = $this->datetime;
        $data['updated_at'] = $this->datetime;

        return $data;
    }
}