<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class ProjectController extends BaseController
{

    /**
     * Request Data
     *
     * @var array
     */
    private $requestData;

    /**
     * Products array
     *
     * @var array
     */
    private $products;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Product");
    }

    /**
     * Save project created by user
     *
     * @return string
     */
    /**
     * @SWG\Post(path="/projects",
     *   tags={"Projects"},
     *   summary="Add project",
     *   description="Add projects",
     *   operationId="projects_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="number",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="levels",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="address",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="lat",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="lng",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function index_post()
    {
        try {
            $user_data = $this->accessTokenCheck();
            $language_code = $this->langcode_validate();

            $this->requestData = $this->post();

            $this->validateProject();

            if (! (bool) $this->form_validation->run()) {
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($this->form_validation->error_array()),
                ]);
            }

            $this->requestData = trim_input_parameters($this->requestData);

            $project = [
                'language_code' => $language_code,
                'user_id' => $user_data['user_id'],
                'number' => $this->requestData['number'],
                'name' => $this->requestData['name'],
                'levels' => $this->requestData['levels'],
                'address' => $this->requestData['address'],
                'lat' => $this->requestData['lat'],
                'lng' => $this->requestData['lng'],
                'created_at' => $this->datetime
            ];

            $projectId = $this->UtilModel->insertTableData($project, 'projects', true);

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_added'),
                'data' => [
                    'project_id' => $projectId
                ]
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }


    /**
     * Add project rooms
     *
     * @return string
     */
    public function projectRooms_post()
    {
        try {
            $user_data = $this->accessTokenCheck();
            $language_code = $this->langcode_validate();

            $this->requestData = $this->post();

            if (empty($this->requestData)) {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('bad_request'),
                ]);
            }

            $this->validateRooms();

            if (! (bool) $this->form_validation->run()) {
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($this->form_validation->error_array()),
                ]);
            }
            
            $this->products = array_column($this->requestData, 'calc_product');

            $this->validateRoomProducts();

            if (! (bool) $this->form_validation->run()) {
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($this->form_validation->error_array()),
                ]);
            }

            $this->requestData = trim_input_parameters($this->requestData);
            $this->products = trim_input_parameters($this->products);

            $roomsData = array_map(function ($room) {
                $data['project_id'] = $room['project_id'];
                $data['name'] = $room['name'];
                $data['length'] = $room['length'];
                $data['width'] = $room['width'];
                $data['height'] = $room['height'];
                $data['maintainance_factor'] = $room['maintainance_factor'];
                $data['shape'] = isset($room['shape'])?$room['shape']:'';
                $data['working_plane_height'] = $room['working_plane_height'];
                $data['rho_wall'] = isset($room['rho_wall'])?$room['rho_wall']:0.00;
                $data['rho_ceiling'] = isset($room['rho_ceiling'])?$room['rho_ceiling']:0.00;
                $data['rho_floor'] = isset($room['rho_floor'])?$room['rho_floor']:0.00;
                $data['lux_value'] = isset($room['lux_value'])?$room['lux_value']:0.00;
                $data['luminaries_count_x'] = $room['luminaries_count_x'];
                $data['luminaries_count_y'] = $room['luminaries_count_y'];
                $data['fast_calc_response'] = isset($room['fast_calc_response'])?$room['fast_calc_response']:'';
                $data['created_at'] = $this->datetime;
                return $data;
            }, $this->requestData);

            // $this->UtilModel->insertBatch('project_rooms', $roomsData);
            $productData = [];
            $this->db->trans_begin();
            foreach ($roomsData as $room) {
                $roomId = $this->UtilModel->insertTableData($room, 'project_rooms', true);
                $product = array_shift($this->products);
                $productData[] = [
                    'project_room_id' => $roomId,
                    'article_code' => $product['article_code'],
                    'product_id' => $product['product_id'],
                    'type' => ROOM_MAIN_PRODUCT
                ];
            }
            $this->UtilModel->insertBatch('project_room_products', $productData);

            $this->db->trans_commit();
            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('room_added')
            ]);
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    /**
     * @SWG\Post(path="/projects/quotation-request",
     *   tags={"Projects"},
     *   summary="Send Quotation Request",
     *   description="On selection user sends his project for quotation request",
     *   operationId="projects_quotation_request_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="project_id",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function sendQuotationRequest_post()
    {
        try {
            $user_data = $this->accessTokenCheck();
            $language_code = $this->langcode_validate();

            $this->requestData = $this->post();

            if (empty($this->requestData)) {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('bad_request'),
                ]);
            }

            $this->validateSendQuotation();

            if (! (bool) $this->form_validation->run()) {
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($this->form_validation->error_array()),
                ]);
            }

            $this->UtilModel->insertTableData([
                'project_id' => $this->requestData['project_id'],
                'created_at' => $this->datetime
            ], 'project_requests');
            
            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('quotation_sent')
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Save project created by user
     *
     * @return string
     */
    /**
     * @SWG\Get(path="/projects",
     *   tags={"Projects"},
     *   summary="List project",
     *   description="List projects posted by current user",
     *   operationId="projects_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="Data not found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function index_get()
    {
        try {
            $user_data = $this->accessTokenCheck();
            $language_code = $this->langcode_validate();

            $this->load->model("Project");
            $get = $this->get();
            $params['offset'] =
                isset($get['offset'])&&is_numeric($get['offset'])&&(int)$get['offset'] > 0 ? (int)$get['offset']: 0;
            $params['limit'] = API_RECORDS_PER_PAGE;
            $params['where']['user_id'] = $user_data['user_id'];

            $projects = $this->Project->get($params);

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$projects['count'] > ($params['offset'] + API_RECORDS_PER_PAGE)) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + API_RECORDS_PER_PAGE;
            }

            $data = $projects['data'];

            if (empty($data)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_listed'),
                'data' => $data,
                'next_count' => $nextCount,
                'has_more_pages' => $hasMorePages,
                'per_page_count' => $params['limit']
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * @SWG\Get(path="/projects/{project_id}",
     *   tags={"Projects"},
     *   summary="List project",
     *   description="List projects posted by current user",
     *   operationId="projects_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="project_id",
     *     in="path",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="Data not found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function details_get()
    {
        try {
            $user_data = $this->accessTokenCheck();
            $language_code = $this->langcode_validate();

            $this->requestData = $this->get();
            
            $this->validateProductDetails();

            if (! (bool) $this->form_validation->run()) {
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($this->form_validation->error_array()),
                ]);
            }
            
            $this->load->model("Project");

            $params['project_id'] = $this->requestData['project_id'];

            $projectData = $this->Project->details($params);
            
            if (empty($projectData)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }
            $this->load->model("ProjectRooms");
            $roomParams['where']['project_id'] = $this->requestData['project_id'];
            $roomData = $this->ProjectRooms->get($roomParams);
            
            $rooms = $roomData['data'];
            if (!empty($rooms)) {
                $roomIds = array_column($rooms, 'id');
                $this->load->model('ProjectRoomProducts');
                $roomProductParams['where']['project_room_id'] = $roomIds;
                $roomProducts = $this->ProjectRoomProducts->get($roomProductParams);
                $roomProducts = $roomProducts['data'];
                $this->load->helper('db');
                $rooms = getDataWith($rooms, $roomProducts, 'id', 'project_room_id', 'products');
            }

            $projectData['rooms'] = $rooms;

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('product_details_fetched'),
                'data' => $projectData
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Validate project
     *
     * @return void
     */
    private function validateProject()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules([
            [
                'label' => 'Project Number',
                'field' => 'number',
                'rules' => 'trim|required'
            ],
            [
                'label' => 'Project Name',
                'field' => 'name',
                'rules' => 'trim|required'
            ],
            [
                'label' => 'Project Levels',
                'field' => 'levels',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'label' => 'Address',
                'field' => 'address',
                'rules' => 'trim|required'
            ],
            [
                'label' => 'Location',
                'field' => 'lat',
                'rules' => 'trim|required|numeric'
            ],
            [
                'label' => 'Location',
                'field' => 'lng',
                'rules' => 'trim|required|numeric'
            ],
        ]);

        $this->form_validation->set_data($this->requestData);
    }

    /**
     * Validate Room Data
     *
     * @return void
     */
    private function validateRooms()
    {
        $this->load->library('form_validation');

        foreach ($this->requestData as $id => $room) {
            $this->form_validation->set_rules('rooms['. $id .'][project_id]', 'Project id', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('rooms['. $id .'][name]', 'Name', 'trim|required');
            $this->form_validation->set_rules('rooms['. $id .'][length]', 'Length', 'trim|required|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][width]', 'Width', 'trim|required|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][height]', 'Height', 'trim|required|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][maintainance_factor]', 'Maintainance factor', 'trim|required|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][shape]', 'Shape', 'trim');
            $this->form_validation->set_rules('rooms['. $id .'][working_plane_height]', 'Working plane height', 'trim|required|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][rho_wall]', 'Rho wall', 'trim|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][rho_ceiling]', 'Rho ceiling', 'trim|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][rho_floor]', 'Rho floor', 'trim|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][lux_value]', 'Lux value', 'trim|numeric');
            $this->form_validation->set_rules('rooms['. $id .'][calc_product]', 'Luminaries count x', 'trim|required');
            $this->form_validation->set_rules('rooms['. $id .'][luminaries_count_x]', 'Luminaries count x', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('rooms['. $id .'][luminaries_count_y]', 'Luminaries count y', 'trim|required|is_natural_no_zero');
            $this->form_validation->set_rules('rooms['. $id .'][fast_calc_response]', 'Fast calc response', 'trim');
        }

        $validationData = [
            'rooms' => $this->requestData
        ];

        $this->form_validation->set_data($validationData);
    }

    /**
     * Validate room products
     *
     * @return void
     */
    private function validateRoomProducts()
    {
        $this->form_validation->reset_validation();

        foreach ($this->products as $id => $product) {
            $this->form_validation->set_rules('products['. $id .'][article_code]', 'Article code', 'trim|required');
            $this->form_validation->set_rules('products['. $id .'][product_id]', 'Product', 'trim|required|is_natural_no_zero');
        }

        $validationData = [
            "products" => $this->products
        ];

        $this->form_validation->set_data($validationData);
    }

    /**
     * Validate send quotation
     *
     * @return void
     */
    private function validateSendQuotation()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules([
            [
                'label' => 'Project',
                'field' => 'project_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);

        $this->form_validation->set_data($this->requestData);
    }
    /**
     * Validate send project details
     *
     * @return void
     */
    private function validateProductDetails()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'label' => 'Project',
                'field' => 'project_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }
}
