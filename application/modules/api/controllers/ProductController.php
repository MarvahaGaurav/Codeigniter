<?php
defined("BASEPATH") OR exit("No direct script access allowed");

require 'BaseController.php';

class ProductController extends BaseController 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Product");
    }

    /**
     * @SWG\Get(path="/applications/{application_id}/products",
     *   tags={"Products"},
     *   summary="Product Lists according to application",
     *   description="",
     *   operationId="application_products_get",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="application_id",
     *     in="query",
     *     description="1-Residential and 2-Professional",
     *     type="string",
     *     required = true 
     *   ),  
     *   @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="offset paramters to paginate",
     *     type="string"
     *   ),  
     *   @SWG\Response(response=200, description="OK"),
     *   @SWG\Response(response=401, description="Unauthorize"),
     *   @SWG\Response(response=202, description="No data found"), 
     * )
     */
    public function application_products_get()
    {   
        $language_code = $this->langcode_validate();

        $request_data = $this->get();
        $request_data = trim_input_parameters($request_data);

        $mandatory_fields = ['application_id'];

        $check = check_empty_parameters($request_data, $mandatory_fields);

        if ( $check['error'] ) {
            $this->response([
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('missing_parameter'),
                'extra_info' => [
                    "missing_parameter" => $check['parameter']
                ]
            ]);
        }

        $offset = isset($request_data['offset'])?(int)$request_data['offset']:0;
        $link = "";
        $alt_link = "";
        $params['offset'] = $offset;
        $params['application_id'] = $request_data['application_id'];
        
        $products = $this->Product->get($params);
        $count = (int)$products['count'];
        $next_count = $offset + RECORDS_PER_PAGE;

        if ( ! $products['result'] ) {
            $this->response([
                "code" => NO_DATA_FOUND,
                "api_code_result" => "NO_DATA_FOUND",
                "msg" => $this->lang->line("no_records_found")
            ]);
        }

        if ( $next_count < $count ) {
            $offset = $next_count;
            $link = "/api/v1/applications/{$request_data['application_id']}/products?offset={$next_count}";
            $alt_link = "/applications/{$request_data['application_id']}/products?offset={$next_count}";
        } else {
            $offset = -1;
        }
        // print_r($count);die;

        $response = [
            "code" => HTTP_OK,
            "api_code_result" => "OK",
            "msg" => $this->lang->line("products_found"),
            "offset" => $offset,
            "data" => $products['result'],
            "links" => [
                "url" => $link,
                "alternate_link" => $alt_link
            ]
        ];

        $this->response($response);
        
    }

    /**
     * @SWG\Get(path="/products/{product_id}",
     *   tags={"Products"},
     *   summary="Application",
     *   description="",
     *   operationId="application_get",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="product_id",
     *     in="query",
     *     description="Product id",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     description="Search key",
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="OK"),
     *   @SWG\Response(response=401, description="Unauthorize"),
     *   @SWG\Response(response=202, description="No data found"), 
     * )
     */
    public function products_get()
    {
        $language_code = $this->langcode_validate();
        
        $request_data = $this->get();
        $request_data = trim_input_parameters($request_data);
        // $mandatory_fields = ['product_id'];

        // $check = check_empty_parameters($request_data, $mandatory_fields);

        // if ( $check['error'] ) {
        //     $this->response([
        //         'code' => HTTP_UNPROCESSABLE_ENTITY,
        //         'api_code_result' => 'UNPROCESSABLE_ENTITY',
        //         'msg' => $this->lang->line('missing_parameter'),
        //         'extra_info' => [
        //             "missing_parameter" => $check['parameter']
        //         ]
        //     ]);
        // }

        $product_listing = false;
        if ( isset($request_data['product_id']) ) {
            $params['product_id'] = $request_data['product_id'];
        } else {
            $product_listing = true;
            $params['product_listing'] = $product_listing;
            $offset = isset($request_data['offset'])&&!empty((int)$request_data['offset'])?(int)$request_data['offset']:0;
            $params['offset'] = $offset;
            $params['search'] = isset($request_data['search'])&&!empty($request_data['search'])?$request_data['search']:"";
            $params['language_code'] = $language_code;
        }

        $products = $this->Product->get($params);
        $response = [
            "code" => HTTP_OK,
            "api_code_result" => "OK",
            "msg" => $this->lang->line("products_found")
        ];

        if ( $product_listing ) {
            $count = (int)$products['count'];
            $next_count = $offset + RECORDS_PER_PAGE;
           
            if ( $next_count < $count ) {
                $offset = $next_count;
                $link = "/api/v1/products?offset={$next_count}";
                $alt_link = "/products?offset={$next_count}";
            } else {
                $offset = -1;
            }
            $response['offset'] = $offset;
            $response["data"] = $products['result'];
            $response['links'] = [
                "url" => $link,
                "alternate_link" => $alt_link
            ];
            
        } else {
            $response["data"] = $products;
        }

        $this->response($response);
    }

}