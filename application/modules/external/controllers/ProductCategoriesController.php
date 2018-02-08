<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

use GuzzleHttp\Client as GuzzleClient;

class ProductCategoriesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("ProductCategory");
        $this->load->model("Category");
    }

    /**
     * External API - Inserts Product information  
     */
    public function product_category_post()
    {
        $final_array = [];
        $language_code = $this->language_code;

        $category_data = $this->Category->fetch();

        foreach ($category_data as $category) {
            $response = get_request_handler("{$category['language_code']}/productCategories/{$category['category_id']}/products");
            $response = json_decode($response, true);
            $language = $category['language_code'];
            $response = array_map(function ($data) use ($category) {
                $product_data = $this->UtilModel->selectQuery(
                    "id",
                    "products",
                    ["single_row" => true, "where" => ['product_id' => $data['id']]]
                );
                $data['category_id'] = $category['id'];
                $data['product_id'] = $product_data['id'];
                $data['primary_category_id'] = $category['category_id'];
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
            $this->ProductCategory->batch_data[] = $data;
        }

        $this->ProductCategory->batch_save();

        pd($final_array);

    }


}