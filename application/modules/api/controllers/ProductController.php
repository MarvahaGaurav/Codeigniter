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
     * @SWG\Parameter(
     *     name="is_uld",
     *     in="path",
     *     description="is_uld = 1 uld only specifications and is_uld = 0 all specifications",
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

            $uld = isset($get['is_uld'])&&is_numeric($get['is_uld'])&&in_array((int)$get['is_uld'], [1,0], true)?(int)$get['is_uld']:1;

            $this->load->model('Product');
            $this->load->model('ProductTechnicalData');
            $this->load->model('ProductSpecification');
            $this->load->model('ProductRelated');

            $specificationParam = $params;
            if ($uld === 1) {
                $specificationParam['where']['CHAR_LENGTH(uld) >'] = 0;
            }

            $productData = $this->Product->details($params);
            $productTechnicalData = $this->ProductTechnicalData->get($params);
            $productSpecifications = $this->ProductSpecification->getch($specificationParam);
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
     *   summary="Accessory Products",
     *   description="Fetch accessory products",
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
     *     name="project_room_id",
     *     in="query",
     *     description="Project Room Id",
     *     type="string",
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

            $this->requestData = trim_input_parameters($this->requestData);

            $params['room_id'] = $this->requestData['room_id'];
            
            $data = $this->Product->roomProducts($params);

            if (isset($this->requestData['project_room_id']) && !empty($this->requestData['project_room_id'])) {
                $projectRoomProductData = $this->UtilModel->selectQuery('product_id', 'project_room_products', [
                    'where' => ['project_room_id' => $this->requestData['project_room_id']]
                ]);

                $projectRoomProductIds = array_unique(array_column($projectRoomProductData, 'product_id'));

                $data = array_map(function ($product) use ($projectRoomProductIds) {
                    $product['is_selected'] = (bool)in_array($product['product_id'], $projectRoomProductIds);
                    return $product;
                }, $data);
            }

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
            ],
            [
                'field' => 'project_room_id',
                'label' => 'Project Room',
                'rules' => 'trim|is_natural_no_zero'
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

    /**
     * @SWG\Get(path="/products",
     *   tags={"Products"},
     *   summary="Products List",
     *   description="Fetch proudcts API",
     *   operationId="productFetch_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="uld",
     *     in="query",
     *     description="1 - to fetch only products with uld, ignore this key to fetch all products",
     *     type="string",
     *   ),
     * @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     description="Search text",
     *     type="string",
     *   ),
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string",
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="No data found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function products_get()
    {
        $language_code = $this->langcode_validate();

        $this->requestData = $this->get();

        $this->requestData = trim_input_parameters($this->requestData, false);

        $params['language_code'] = $language_code;
        $params['offset'] =
                isset($this->requestData['offset'])&&is_numeric($this->requestData['offset'])&&(int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset']: 0;
        $params['limit'] = API_RECORDS_PER_PAGE;

        if (isset($this->requestData['uld']) && (int)$this->requestData['uld'] === 1) {
            // $params['where']['(EXISTS(SELECT id FROM product_specifications WHERE product_id=products.product_id AND CHAR_LENGTH(uld) > 0))'] = null;
            $params['uld'] = true;
        }

        if (isset($this->requestData['search']) && strlen($this->requestData['search']) > 0) {
            $params['where']['title LIKE'] = "{$this->requestData['search']}%";
        }

        $params['where']['language_code'] = $language_code;
        $this->benchmark->mark('start');
        $data = $this->Product->products($params);
        $this->benchmark->mark('stop');
        
        if (isset($this->requestData['uld']) && (int)$this->requestData['uld'] === 1) {
            $hasMorePages = false;
            $nextCount = -1;
            if (count($data['data']) > $params['limit']) {
                $hasMorePages = true;
                array_pop($data['data']);
                $nextCount = $params['offset'] + $params['limit'];
            }
        } else {
            $count = $data['count'];
            $hasMorePages = false;
            $nextCount = -1;
    
            if ((int)$count > ($params['offset'] + $params['limit'])) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + $params['limit'];
            }
        }
       

        if (empty($data['data'])) {
            $this->response([
                'code' => HTTP_NOT_FOUND,
                'msg' => $this->lang->line('no_data_found')
            ]);
        }
        
        $this->load->helper('utility');

        $data['data'] = array_strip_tags($data['data'], ['body']);

        $this->response([
            'code' => HTTP_OK,
            'msg' => $this->lang->line('success'),
            'data' => $data['data'],
            'next_count' => isset($nextCount)?$nextCount:-1,
            'has_more_pages' => $hasMorePages,
            'per_page_count' => $params['limit'],
            'total' => isset($data['count'])?$data['count']:0,
            'elapsed_time' => $this->benchmark->elapsed_time('start', 'stop')
        ]);
    }

    /**
     * @SWG\Get(path="/products/articles",
     *   tags={"Products"},
     *   summary="Products Article List",
     *   description="Fetch proudcts article API",
     *   operationId="productArticleFetch_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="uld",
     *     in="query",
     *     description="1 - to fetch only products with uld, ignore this key to fetch all products",
     *     type="string",
     *   ),
     * @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     description="Search text",
     *     type="string",
     *   ),
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string",
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="No data found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function productArticles_get()
    {
        $language_code = $this->langcode_validate();

        $this->requestData = $this->get();

        $this->requestData = trim_input_parameters($this->requestData, false);

        $params['where']['language_code'] = $language_code;
        $params['offset'] =
                isset($this->requestData['offset'])&&is_numeric($this->requestData['offset'])&&(int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset']: 0;
        $params['limit'] = API_RECORDS_PER_PAGE;

        if (isset($this->requestData['uld']) && (int)$this->requestData['uld'] === 1) {
            $params['where']['CHAR_LENGTH(uld) >'] = 0;
        }

        if (isset($this->requestData['search']) && strlen(trim($this->requestData['search'])) > 0) {
            $search = trim($this->requestData['search']);
            $params['where']['title LIKE'] = "%{$search}%";
        }

        $this->load->model(['ProductSpecification', 'ProductMountingTypes']);
        $this->load->helper(['db']);

        $this->benchmark->mark('start');
        $data = $this->ProductSpecification->fetchArticles($params);
        $this->benchmark->mark('stop');
        $articlesData = $data['data'];
        $count = $data['count'];

        if (empty($articlesData)) {
            $this->response([
                'code' => HTTP_NOT_FOUND,
                'msg' => $this->lang->line('no_data_found')
            ]);
        }

        $productIds = array_unique(array_column($articlesData, 'product_id'));

        $productMountingTypeData = $this->ProductMountingTypes->get($productIds);

        $articlesData = getDataWith(
            $articlesData,
            $productMountingTypeData,
            'product_id',
            'product_id',
            'mounting_types',
            'type'
        );

        $articlesData = array_map(function ($article) {
            $article['image'] = preg_replace("/^\/home\/forge\//", "https://", $article['image']);
            $article['title'] = trim(strip_tags($article['title']));
            return $article;
        }, $articlesData);

        $hasMorePages = false;
        $nextCount = -1;

        if ((int)$count > ($params['offset'] + $params['limit'])) {
            $hasMorePages = true;
            $nextCount = $params['offset'] + $params['limit'];
        }

        $this->response([
            'code' => HTTP_OK,
            'msg' => $this->lang->line('success'),
            'data' => $articlesData,
            'next_count' => isset($nextCount)?$nextCount:-1,
            'has_more_pages' => $hasMorePages,
            'per_page_count' => $params['limit'],
            'total' => isset($count)?$count:0,
            'elapsed_time' => $this->benchmark->elapsed_time('start', 'stop')
        ]);

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
