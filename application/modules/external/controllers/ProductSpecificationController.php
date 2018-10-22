<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

use GuzzleHttp\Client as GuzzleClient;

class ProductSpecificationController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Product");
        $this->load->helper(["url", "array_util"]);
        error_reporting(-1);
        ini_set('display_errors', 1);
    }

    public function index_post()
    {
        try {
            $products = $this->UtilModel->selectQuery('product_id, language_code', 'products');

            foreach ($this->language_code as $language) {
                $productByLanguage = array_filter($products, function ($product) use ($language) {
                    return $product['language_code'] === $language;
                });
                $specificationData = [];
                $productIds = array_column($productByLanguage, 'product_id');
                foreach ($productIds as $product) {
                    $response = get_sg_data("{$language}/products/{$product}/specifications");
                    $data = json_decode($response, true);
                    $insertData = array_map(function ($specification) use ($product, $language) {
                        return $this->mapSpecifications($product, $specification, $language);
                    }, $data);
                    $specificationData = array_merge($specificationData, $insertData);
                }
                $this->UtilModel->insertBatch('product_specifications', $specificationData);
            }

            $this->response([
                'code' => HTTP_OK,
                'message' => 'Success'
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
        $data['image'] = $specification_data['image'];
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
