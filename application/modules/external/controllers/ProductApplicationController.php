<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

use GuzzleHttp\Client as GuzzleClient;

class ProductApplicationController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("ProductApplication");
        $this->load->model("Application");
    }

    /**
     * External API - Inserts Product information  
     */
    public function product_application_post()
    {
        $final_array = [];
        $language_code = $this->language_code;

        $application_data = $this->Application->fetch();

        foreach ($application_data as $application) {
            $response = get_request_handler("{$application['language_code']}/applications/{$application['application_id']}/products");
            $response = json_decode($response, true);
            $language = $application['language_code'];
             
            $response = array_map(function ($data) use ($application) {
                $product_data = $this->UtilModel->selectQuery(
                    "id",
                    "products",
                    ["single_row" => true, "where" => ['product_id' => $data['id']]]
                );
                $data['application_id'] = $application['id'];
                $data['product_id'] = $product_data['id'];
                $data['primary_application_id'] = $application['application_id'];
                $data['primary_product_id'] = $data['id'];
                $data['created_at'] = $this->datetime;
                $data['updated_at'] = $this->datetime;
                unset($data['id']);
                unset($data['title']);
                unset($data['subTitle']);
                unset($data['image']);
                return $data;
            }, $response);

            $final_array = array_merge($final_array, $response);
        }

        foreach ($final_array as $data) {
            $this->ProductApplication->batch_data[] = $data;
        }

        $this->ProductApplication->batch_save();

        pd($final_array);

    }


}