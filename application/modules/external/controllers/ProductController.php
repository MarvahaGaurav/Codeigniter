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
        $this->load->model("ProductRelated");
        $this->load->helper(["url", "array_util"]);
        error_reporting(-1);
        ini_set('display_errors', 1);
    }

    public function product_post()
    {
        $productInsert = [];
        $productApplicationInsert = [];
        $productGalleryInsert = [];
        $productCategoryInsert = [];
        $productMountingTypeInsert = [];

        try {
            foreach ($this->language_code as $language) {
                $response = get_sg_data("{$language}/products");
                $data = json_decode($response, true);

                $products = array_map(function ($product) use ($language) {
                    $data['product_id'] = $product['id'];
                    $data['title'] = $product['title'];
                    $data['subtitle'] = $product['subTitle'];
                    $data['slug'] = url_title($product['title']);
                    $data['language_code'] = $language;
                    $data['images'] = array_map(function ($image) use ($product) {
                        $data['product_id'] = $product['id'];
                        $data['image'] = $image;
                        $data['created_at'] = $this->datetime;
                        $data['updated_at'] = $this->datetime;
                        return $data;
                    }, $product['images']);
                    $data['categories'] = array_map(function ($categoryId) use ($product) {
                        $data['category_id'] = $categoryId;
                        $data['product_id'] = $product['id'];
                        $data['created_at'] = $this->datetime;
                        $data['updated_at'] = $this->datetime;
                        return $data;
                    }, $product['category_ids']);
                    $data['applications'] = array_map(function ($applicationId) use ($product) {
                        $data['application_id'] = $applicationId;
                        $data['product_id'] = $product['id'];
                        $data['created_at'] = $this->datetime;
                        $data['updated_at'] = $this->datetime;
                        return $data;
                    }, $product['application_ids']);
                    $data['mounting_types'] = array_map(function ($type) use ($product) {
                        $data['product_id'] = $product['id'];
                        $data['type'] = mounting_type_str_to_num($type);
                        $data['type_string'] = $type;
                        $data['created_at'] = $this->datetime;
                        $data['updated_at'] = $this->datetime;
                        return $data;
                    }, $product['mounting_types']);
                    $data['created_at'] = $this->datetime;
                    $data['updated_at'] = $this->datetime;
                    return $data;
                }, $data);

                array_flatten_d(array_column($products, 'images'), $productGalleryInsert);
                array_flatten_d(array_column($products, 'categories'), $productCategoryInsert);
                array_flatten_d(array_column($products, 'applications'), $productApplicationInsert);
                array_flatten_d(array_column($products, 'mounting_types'), $productMountingTypeInsert);
                $products = array_map(function ($data) {
                    unset($data['images'], $data['categories'], $data['applications'], $data['mounting_types']);
                    return $data;
                }, $products);
                $productInsert = array_merge($productInsert, $products);
            }
            $this->db->trans_begin();
            $this->UtilModel->insertBatch('products', $productInsert);
            $this->UtilModel->insertBatch('product_gallery', $productGalleryInsert);
            $this->UtilModel->insertBatch('product_categories', $productCategoryInsert);
            $this->UtilModel->insertBatch('product_applications', $productApplicationInsert);
            $this->UtilModel->insertBatch('product_mounting_types', $productMountingTypeInsert);
            $this->db->trans_commit();
            $this->response([
                'success' => true,
                'code' => 200
            ]);
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            $this->response([
                'message' => 'some error has occured, please try again'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function productDetail_post()
    {
        try {
            $products = $this->UtilModel->selectQuery('product_id, language_code', 'products');
            $productUpdate = [];
            $productTechnicalData = [];
            $productArticles = [];
            foreach ($this->language_code as $language) {
                $productByLanguage = array_filter($products, function ($data) use ($language) {
                    return $data['language_code'] === $language;
                });

                $productIds = array_column($productByLanguage, 'product_id');

                foreach ($productIds as $productId) {
                    $response = get_sg_data("{$language}/products/{$productId}");
                    $data = json_decode($response, true);

                    $product = [
                        'product_id' => $data['id'],
                        'body' => $data['body'],
                        'how_to_specity' => $data['howToSpecity']
                    ];

                    $productTechnical = array_map(function ($data) use ($productId) {
                        $output['product_id'] = $productId;
                        $output['title'] = $data['title'];
                        $output['slug'] = url_title($data['title']);
                        $output['info'] = $data['text'];
                        $output['created_at'] = $this->datetime;
                        $output['updated_at'] = $this->datetime;
                        return $output;
                    }, $data['technicalData']);
                    $productSpecification = array_map(function ($data) use ($productId, $language) {
                        $output['product_id'] = $productId;
                        $output['language_code'] = $language;
                        $output['articlecode'] = $data['articlecode'];
                        $output['ean'] = $data['ean'];
                        $output['created_at'] = $this->datetime;
                        $output['updated_at'] = $this->datetime;
                        return $output;
                    }, $data['specifications']);

                    $productUpdate = array_merge($productUpdate, [$product]);
                    $productTechnicalData = array_merge($productTechnicalData, $productTechnical);
                    $productArticles = array_merge($productArticles, $productSpecification);
                }
            }
            $this->db->trans_begin();
            $this->UtilModel->updateBatch('products', $productUpdate, 'product_id');
            $this->UtilModel->insertBatch('product_technical_data', $productTechnicalData);
            $this->UtilModel->insertBatch('product_specifications', $productArticles);
            $this->db->trans_commit();
            $this->response([
                'success' => true,
                'code' => 200
            ]);
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            $this->response([
                'message' => 'some error has occured, please try again'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function specification_post()
    {
        try {
            $articleData = $this->UtilModel->selectQuery(
                'product_id, articlecode, language_code',
                'product_specifications'
            );
            $articleUpdate = [];
            foreach ($articleData as $article) {
                $url = "{$article['language_code']}/specifications/{$article['product_id']}/{$article['articlecode']}";
                $response = get_sg_data($url);
                $data = json_decode($response, true);
                $articleData = $this->mapSpecifications($article['product_id'], $data, $article['language_code']);
                $articleUpdate = array_merge($articleUpdate, [$articleData]);
            }
            $this->UtilModel->updateBatch('product_specifications', $articleUpdate, 'product_id');
            $this->response([
                'success' => true,
                'code' => 200
            ]);
        } catch (\Exception $error) {
            $this->response([
                'message' => 'some error has occured, please try again'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function mapSpecifications($product_id, $specification_data, $language_code)
    {
        $data['product_id'] = $product_id;
        $data["articlecode"]= $specification_data["articlecode"];
        $data["ean"]= $specification_data["ean"];
        $data["title"]= $specification_data["title"];
        $data["title"]= $specification_data["title"];
        $data["slug"]= url_title(preg_replace("/\s+/", "-", trim(strtolower(convert_accented_characters($specification_data['title'])))). "-" . $language_code);
        $data["uld"]= $specification_data["uld"];
        $data["ldt"]= $specification_data["ldt"];
        $data["price"]= $specification_data["data"]["price"];
        $data["currency"]= $specification_data["data"]["currency"];
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
