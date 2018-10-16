<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class ProductController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Product");
    }

    /**
     * @SWG\Get(path="/products/mounting-types",
     *   tags={"Products"},
     *   summary="Product Mounting types",
     *   description="Lists all the product mounting types",
     *   operationId="mountingTypes_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function mountingTypes_get()
    {
        try {
            $language_code = $this->langcode_validate();

            $this->lang->load(['sg']);

            $mountingTypes = [
                MOUNTING_SUSPENDED => $this->lang->line('mounting_suspended'),
                MOUNTING_RECESSED => $this->lang->line('mounting_recessed'),
                MOUNTING_SURFACE => $this->lang->line('mounting_surface'),
                MOUNTING_DOWNLIGHT => $this->lang->line('mounting_downlight'),
                MOUNTING_DOWNLIGHT_ISOSAFE => $this->lang->line('mounting_downlight_isosafe'),
                MOUNTING_PENDANT => $this->lang->line('mounting_pendant'),
                MOUNTING_TRACKS => $this->lang->line('mounting_tracks'),
            ];

            $mountingTypesData = [];
            foreach ($mountingTypes as $mountingTypeId => $mountingType) {
                $mountingTypesData[] = [
                    'id' => $mountingTypeId,
                    'mounting_type' => $mountingType
                ];
            }

            $this->response([
                'code' => HTTP_OK,
                'api_code_result' => 'OK',
                'msg' => $this->lang->line('mounting_types'),
                'data' => $mountingTypesData
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' =>  $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * @SWG\Get(path="/rooms/{room_id}/mounting-types/{mounting_type_id}/products",
     *   tags={"Products"},
     *   summary="Product Mounting types",
     *   description="Lists all the product mounting types",
     *   operationId="mountingTypes_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="room_id",
     *     in="path",
     *     description="room_id",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="mounting_type",
     *     in="path",
     *     description="Mounting Type",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="other_products",
     *     in="query",
     *     description="Optional pass value 1 to retrieve other products",
     *     type="string",
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="No data found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function roomProducts_get()
    {
        try {
            $language_code = $this->langcode_validate();

            $get = $this->get();

            $this->load->library(['form_validation']);

            $this->form_validation->set_data($get);
            $this->form_validation->set_rules([
                [
                    'label' =>  'room_id',
                    'field' => 'room_id',
                    'rules' => 'trim|required|is_natural_no_zero',
                ],
                [
                    'label' =>  'mounting_type',
                    'field' => 'mounting_type',
                    'rules' => 'trim|required|is_natural_no_zero',
                ]
            ]);
            
            if (!(bool)$this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => $this->lang->line('something_went_wrong'),
                    'extra' => array_shift($errorMessage),
                ]);
            }

            $this->load->model("Product");

            $params['where'] = [
                'rp.type' => $get['mounting_type'],
                'room_id' => $get['room_id'],
                'EXISTS(SELECT id FROM product_specifications WHERE product_id = rp.product_id AND CHAR_LENGTH(uld) > 0)' => null
            ];

            if ($this->get('other_products') == 1) {
                $params['where'] = [
                    'rp.type !=' => $get['mounting_type'],
                    'room_id' => $get['room_id'],
                    'EXISTS(SELECT id FROM product_specifications WHERE product_id = rp.product_id AND CHAR_LENGTH(uld) > 0)' => null
                ];
            }

            $data = $this->Product->productByMountingType($params);

            if (empty($data['data'])) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'api_code_result' => 'NOT_FOUND',
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $this->load->helper('utility');
            $data['data'] = array_strip_tags($data['data'], ['body']);

            $this->response([
                'code' => HTTP_OK,
                'api_code_result' => 'OK',
                'msg' => $this->lang->line('product_fetched'),
                'data' => $data['data']
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' =>  $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * @SWG\Get(path="/products/{product_id}",
     *   tags={"Products"},
     *   summary="",
     *   description="",
     *   operationId="mountingTypes_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="product_id",
     *     in="path",
     *     description="product_id",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="No data found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function details_get()
    {
        try {
            $language_code = $this->langcode_validate();

            $get = $this->get();

            $this->load->library(['form_validation']);

            $this->form_validation->set_data($get);
            $this->form_validation->set_rules([
                [
                    'label' =>  'product_id',
                    'field' => 'product_id',
                    'rules' => 'trim|required|is_natural_no_zero',
                ],
            ]);
            
            if (!(bool)$this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => $this->lang->line('something_went_wrong'),
                    'extra' => array_shift($errorMessage),
                ]);
            }
            $params = [
                'product_id' => $get['product_id']
            ];
            $this->load->model('Product');
            $this->load->model('ProductTechnicalData');
            $this->load->model('ProductSpecification');
            $this->load->model('ProductRelated');

            $productData = $this->Product->details($params);
            $productTechnicalData = $this->ProductTechnicalData->get($params);
            $productSpecifications = $this->ProductSpecification->get($params);
            $relatedProducts = $this->ProductRelated->get($params);

            if (empty($productData)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'api_code_result' => 'NOT_FOUND',
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }
            $this->load->helper('utility');
            $productSpecifications = array_strip_tags($productSpecifications, ['title']);
            $productTechnicalData = array_strip_tags($productTechnicalData, ['title', 'info']);
            $productData['body'] = trim(strip_tags($productData['body']));
            $productData['how_to_specity'] = trim(strip_tags($productData['how_to_specity']));

            $productSpecifications = array_map(function ($specification) {
                $specification['image'] = preg_replace("/^\/home\/forge\//", "https://", $specification['image']);
                return $specification;
            }, $productSpecifications);

            $data = $productData;
            $data['technical_data'] = $productTechnicalData;
            $data['specifications'] = $productSpecifications;
            $data['related_products'] = $relatedProducts;

            $this->response([
                'code' => HTTP_OK,
                'api_code_result' => 'OK',
                'msg' => $this->lang->line('product_fetched'),
                'data' => $data
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' =>  $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * @SWG\Get(path="/rooms/{room_id}/products",
     *   tags={"Products"},
     *   summary="",
     *   description="",
     *   operationId="mountingTypes_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="room_id",
     *     in="path",
     *     description="room_id",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="No data found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function accessoryProducts_get()
    {
        try {
            $language_code = $this->langcode_validate();

            $this->requestData = $this->get();
            $this->load->library('form_validation');

            $this->validateAccessoryProduct();

            if (!(bool)$this->form_validation->run()) {
                $errorMessage = $this->form_validation->error_array();
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($errorMessage),
                ]);
            }

            $params['room_id'] = $this->requestData['room_id'];
            
            $data = $this->Product->roomProducts($params);

            $this->load->helper('utility');
            $data = array_strip_tags($data, ['body']);

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('related_products_fetched'),
                'data' => $data
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' =>  $this->lang->line("internal_server_error")
            ]);
        }
    }

    private function validateAccessoryProduct()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'room_id',
                'label' => 'Room',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    /**
     * @SWG\Get(path="/applications/{application_id}/products",
     *   tags={"Products"},
     *   summary="Product Lists according to application",
     *   description="",
     *   operationId="application_products_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="application_id",
     *     in="query",
     *     description="1-Residential and 2-Professional",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="offset paramters to paginate",
     *     type="string"
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=202, description="No data found"),
     * )
     */
    public function application_products_get()
    {
        $language_code = $this->langcode_validate();

        $request_data = $this->get();
        $request_data = trim_input_parameters($request_data);

        $mandatory_fields = ['application_id'];

        $check = check_empty_parameters($request_data, $mandatory_fields);

        if ($check['error']) {
            $this->response(
                [
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('missing_parameter'),
                'extra_info' => [
                    "missing_parameter" => $check['parameter']
                ]
                ]
            );
        }

        $offset = isset($request_data['offset'])?(int)$request_data['offset']:0;
        $link = "";
        $alt_link = "";
        $params['offset'] = $offset;
        $params['application_id'] = $request_data['application_id'];
        
        $products = $this->Product->get($params);
        $count = (int)$products['count'];
        $next_count = $offset + RECORDS_PER_PAGE;

        if (! $products['result']) {
            $this->response(
                [
                "code" => NO_DATA_FOUND,
                "api_code_result" => "NO_DATA_FOUND",
                "msg" => $this->lang->line("no_records_found")
                ]
            );
        }

        if ($next_count < $count) {
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

    /*
     * @SWG\Get(path="/products/{product_id}",
     *   tags={"Products"},
     *   summary="Application",
     *   description="",
     *   operationId="application_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="product_id",
     *     in="query",
     *     description="Product id",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     description="Search key",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     description="1-Residential, 2-Professional",
     *     type="string"
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=202, description="No data found"),
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
        if (isset($request_data['product_id'])) {
            $params['product_id'] = $request_data['product_id'];
        } else {
            $product_listing = true;
            $params['product_listing'] = $product_listing;
            $offset = isset($request_data['offset'])&&!empty((int)$request_data['offset'])?(int)$request_data['offset']:0;
            $params['offset'] = $offset;
            $params['search'] = isset($request_data['search'])&&!empty($request_data['search'])?$request_data['search']:"";
            $params['language_code'] = $language_code;
        }

        if (isset($request_data['type'])) {
            $validTypes = [APPLICATION_RESIDENTIAL,APPLICATION_PROFESSIONAL];
            $params['type'] = in_array((int)$request_data['type'], $validTypes)?(int)$request_data['type']:APPLICATION_RESIDENTIAL;
            $products = $this->Product->productByType($params);
        } else {
            $products = $this->Product->get($params);
        }
        $response = [
            "code" => HTTP_OK,
            "api_code_result" => "OK",
            "msg" => $this->lang->line("products_found")
        ];

        if ($product_listing) {
            $count = (int)$products['count'];
            $next_count = $offset + RECORDS_PER_PAGE;
           
            if ($next_count < $count) {
                $offset = $next_count;
                $type = isset($request_data['type'])&&in_array((int)$type, $validTypes)?"&type={$request_data['type']}":'';
                $link = "/api/v1/products?offset={$next_count}" . $type;
                $alt_link = "/products?offset={$next_count}" . $type;
            } else {
                $offset = -1;
                $link = "";
                $alt_link = "";
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

    /**
     * Rooms related products
     *
     * @return void
     */
    public function roomRelatedProducts()
    {
        try {

        } catch (\Exception $error) {

        }
    }
}
