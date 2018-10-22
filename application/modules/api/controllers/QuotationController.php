<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class QuotationController extends BaseController
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
        $this->load->library('form_validation');
    }

    /**
     * @SWG\Post(path="/quotations",
     *   tags={"Requests & Quotations"},
     *   summary="Send Quotation",
     *   description="Send quotation to a request by Customer",
     *   operationId="quotaitions_post",
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
     *     name="request_id",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="additional_product_charges",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="discount",
     *     in="formData",
     *     description="",
     *     type="string"
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
            $user_data = $this->accessTokenCheck('u.company_id');
            $language_code = $this->langcode_validate();

            $this->requestData = $this->post();

            $this->validateQuotations();

            $this->validationRun();

            $this->requestData = trim_input_parameters($this->requestData, false);

            $check = $this->UtilModel->selectQuery('id', 'project_quotations', [
                'where' => ['request_id' => $this->requestData['request_id'], 'company_id' => $user_data['company_id']],
                'single_row' => true
            ]);
            
            if (!empty($check)) {
                $this->response([
                    'code' => HTTP_CONFLICT,
                    'msg' => $this->lang->line('quotation_already_provided')
                ]);
            }
            
            $this->load->model('ProjectRooms');
            $params['request_id'] = $this->requestData['request_id'];
            $params['company_id'] = $user_data['company_id'];
            $roomsData = $this->ProjectRooms->getQuotedRooms($params);
            
            $roomDataCheck = array_filter($roomsData, function ($data) {
                return $data['empty_room_quotations'] === "empty";
            });
            
            if (!empty($roomDataCheck)) {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('provide_quotations_for_all_rooms')
                ]);
            }
           
            $quotationData = $this->UtilModel->selectQuery('id', 'project_quotations', [
                'where' => ['request_id' => $this->requestData['request_id'], 'company_id' => $user_data['company_id']]
            ]);

            if (!empty($quotationData)) {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('quoted_by_company'),
                ]);
            }
            
            $quotationId = $this->UtilModel->insertTableData([
                'language_code' => $language_code,
                'request_id' => $this->requestData['request_id'],
                'company_id' => $user_data['company_id'],
                'user_id' => $user_data['user_id'],
                'additional_product_charges' =>
                     isset($this->requestData['additional_product_charges'])?(double)$this->requestData['additional_product_charges']:0.00,
                'discount' =>
                     isset($this->requestData['discount'])?(double)$this->requestData['discount']:0.00,
                'created_at' => $this->datetime,
                'created_at_timestamp' => time(),
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => time()
            ], 'project_quotations', true);

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('quotation_added'),
                'data' => [
                    'quotation_id' => $quotationId
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
     * @SWG\Get(path="/quotations",
     *   tags={"Requests & Quotations"},
     *   summary="Quotation Listing - Quotation for a given Request or Project",
     *   description="List Quotation posted by user which are quoted",
     *   operationId="projectQuoted_get",
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
     *   @SWG\Parameter(
     *     name="request_id",
     *     in="query",
     *     description="either request_id or project_id is required",
     *     type="string",
     *   ),
     *  @SWG\Parameter(
     *     name="project_id",
     *     in="query",
     *     description="either request_id or project_id is required",
     *     type="string",
     *   ),
     *  @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string",
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
            $user_data = $this->accessTokenCheck('u.company_id');
            $language_code = $this->langcode_validate();

            $this->requestData = $this->get();

            $this->validateQuotationListing();

            $this->validationRun();

            $this->load->model("ProjectQuotation");
            $params['offset'] =
                isset($this->requestData['offset'])&&is_numeric($this->requestData['offset'])&&(int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset']: 0;
            $params['limit'] = API_RECORDS_PER_PAGE;

            if (isset($this->requestData['request_id'])) {
                $params['where']['pq.request_id'] = $this->requestData['request_id'];
            } elseif (isset($this->requestData['project_id'])) {
                $params['where']['pr.project_id'] = $this->requestData['project_id'];
            } else {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('invalid_request')
                ]);
            }

            $quotationData = $this->ProjectQuotation->quotations($params);
            $quotations = $quotationData['data'];
            $count = $quotationData['count'];

            if (empty($quotations)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$count > ($params['offset'] + API_RECORDS_PER_PAGE)) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + API_RECORDS_PER_PAGE;
            }

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('quotation_fetched'),
                'data' => $quotations,
                'total' => $count,
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
     * @SWG\Put(path="/quotations",
     *   tags={"Requests & Quotations"},
     *   summary="Quotation Approve & Reject",
     *   description="Approve or Reject a quotation",
     *   operationId="quotations_put",
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
     *  @SWG\Parameter(
     *     name="quotation_id",
     *     in="query",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     *  @SWG\Parameter(
     *     name="request_id",
     *     in="query",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     *  @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     description="1-Approved, 2-Rejected",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function index_put()
    {
        try {
            $user_data = $this->accessTokenCheck('u.company_id, u.user_lat, u.user_long');
            $language_code = $this->langcode_validate();

            $this->requestData = $this->put();

            $this->validateAppoveQuotation();

            $this->validationRun();

            $this->db->trans_begin();
            $message = $this->lang->line('quotation_accpeted');
            if ((int)$this->requestData['type'] === CUSTOMER_QUOTATION_APPROVE) {
                $this->UtilModel->updateTableData([
                    'status' => QUOTATION_STATUS_APPROVED
                ], 'project_quotations', [
                    'id' => $this->requestData['quotation_id']
                ]);

                $this->UtilModel->updateTableData([
                    'approved_at' => $this->datetime,
                    'approved_at_timestamp' => time()
                ], 'project_requests', [
                    'id' => $this->requestData['request_id']
                ]);

                $this->UtilModel->updateTableData([
                    'status' => QUOTATION_STATUS_REJECTED
                ], 'project_quotations', [
                    'id !=' => $this->requestData['quotation_id'],
                    'request_id' => $this->requestData['request_id']
                ]);
            } elseif ((int)$this->requestData['type'] === CUSTOMER_QUOTATION_REJECT) {
                $message = $this->lang->line('quotation_rejected');

                $this->UtilModel->updateTableData([
                    'status' => QUOTATION_STATUS_REJECTED
                ], 'project_quotations', [
                    'id' => $this->requestData['quotation_id']
                ]);
            }
            
            $this->db->trans_commit();
            $this->response([
                'code' => HTTP_OK,
                'msg' => $message
            ]);
        } catch (\Exception $error) {
            $this->trans_rollback();
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * @SWG\Post(path="/projects/rooms/quotations",
     *   tags={"Requests & Quotations"},
     *   summary="Add Room Quotation",
     *   description="Add room quotation",
     *   operationId="roomsQuotation_post",
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
     *     name="project_room_id",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *  ),
     * @SWG\Parameter(
     *     name="price_per_luminaries",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *  ),
     * @SWG\Parameter(
     *     name="installation_charges",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *  ),
     * @SWG\Parameter(
     *     name="discount_price",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *  ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function roomsQuotation_post()
    {
        try {
            $user_data = $this->accessTokenCheck('u.company_id, u.user_type');
            $language_code = $this->langcode_validate();

            if ((int)$user_data['user_type'] !== INSTALLER) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('forbidden_action')
                ]);
            }

            $this->requestData = $this->post();

            $this->validateRoomQuotations();

            $this->validationRun();

            $check = $this->UtilModel->selectQuery('id',
             'project_room_quotations', [
                'where' => [
                    'company_id' => $user_data['company_id'],
                    'project_room_id' => $this->requestData['project_room_id']
                ],
                'single_row' => true
            ]);

            if (!empty($check)) {
                $this->response([
                    'code' => HTTP_CONFLICT,
                    'msg' => $this->lang->line('quotation_already_added')
                ]);
            }

            $quotationData = [
                'project_room_id' => $this->requestData['project_room_id'],
                'user_id' => $user_data['user_id'],
                'company_id' => $user_data['company_id'],
                'price_per_luminaries' => $this->requestData['price_per_luminaries'],
                'installation_charges' => $this->requestData['installation_charges'],
                'discount_price' => $this->requestData['discount_price'],
                'created_at' => $this->datetime,
                'created_at_timestamp' => time(),
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => time()
            ];

            $this->UtilModel->insertTableData($quotationData, 'project_room_quotations');

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('room_quotation_added')
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
     * @SWG\Put(path="/projects/rooms/quotations",
     *   tags={"Requests & Quotations"},
     *   summary="Edit Room Quotation",
     *   description="Edit room quotation",
     *   operationId="roomsQuotation_put",
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
     *     name="room_quotation_id",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *  ),
     * @SWG\Parameter(
     *     name="price_per_luminaries",
     *     in="formData",
     *     description="",
     *     type="string",
     *  ),
     * @SWG\Parameter(
     *     name="installation_charges",
     *     in="formData",
     *     description="",
     *     type="string",
     *  ),
     * @SWG\Parameter(
     *     name="discount_price",
     *     in="formData",
     *     description="",
     *     type="string",
     *  ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function roomsQuotation_put()
    {
        try {
            $user_data = $this->accessTokenCheck('u.company_id, u.user_type');
            $language_code = $this->langcode_validate();

            $this->requestData = $this->put();

            $this->validateRoomQuotationEdit();

            $this->validationRun();

            $this->requestData = trim_input_parameters($this->requestData);

            $roomQuotationData = $this->UtilModel->selectQuery('id', '  project_room_quotations', [
                'where' => ['id' => $this->requestData['room_quotation_id']],
                'single_row' => true
            ]);

            if (empty($roomQuotationData)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }
            $updateData = [];

            if (isset($this->requestData['price_per_luminaries'])) {
                $updateData['price_per_luminaries'] = $this->requestData['price_per_luminaries'];
            }

            if (isset($this->requestData['installation_charges'])) {
                $updateData['installation_charges'] = $this->requestData['installation_charges'];
            }

            if (isset($this->requestData['discount_price'])) {
                $updateData['discount_price'] = $this->requestData['discount_price'];
            }

            if (empty($updateData)) {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('nothing_to_update')
                ]);
            }

            $updateData['updated_at'] = $this->datetime;
            $updateData['updated_at_timestamp'] = time();

            $this->UtilModel->updateTableData($updateData, 'project_room_quotations', [
                'id' => $this->requestData['room_quotation_id']
            ]);
            
            $priceData = $this->UtilModel->selectQuery(
                'id as room_quotation_id, project_room_id, user_id, company_id,price_per_luminaries,
                installation_charges,discount_price,created_at, created_at_timestamp',
                'project_room_quotations', [
                'where' => [
                    'id' => $this->requestData['room_quotation_id']                      
                ],
                'single_row' => true
            ]);

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('room_quotation_added'),
                'data' => $priceData
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
     * Validate add quotations
     *
     * @return void
     */
    private function validateQuotations()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'request_id',
                'label' => 'Request',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'additional_product_charges',
                'label' => 'Additional product charges',
                'rules' => 'trim|numeric'
            ],
            [
                'field' => 'discount',
                'label' => 'Discount',
                'rules' => 'trim|numeric'
            ]
        ]);
    }

    /**
     * Valiate request List
     *
     * @return void
     */
    private function validateRequests()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'type',
                'label' => 'Type',
                'rules' => 'trim|required|regex_match[/^(1|2)$/]'
            ]
        ]);
    }

    /**
     * Valiate quoted request List
     *
     * @return void
     */
    private function validateQuotedRequests()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'type',
                'label' => 'Type',
                'rules' => 'trim|required|regex_match[/^(1|2)$/]'
            ]
        ]);
    }

    /**
     * Valiate quoted request List
     *
     * @return void
     */
    private function validateQuotationListing()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'request_id',
                'label' => 'Request',
                'rules' => 'trim|is_natural_no_zero'
            ],
            [
                'field' => 'project_id',
                'label' => 'Project',
                'rules' => 'trim|is_natural_no_zero'
            ],
        ]);
    }
    /**
     * Valiate Approve quotation
     *
     * @return void
     */
    private function validateAppoveQuotation()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'quotation_id',
                'label' => 'Quotation',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'request_id',
                'label' => 'Request',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'type',
                'label' => 'Type',
                'rules' => 'trim|required|regex_match[/^(1|2)$/]'
            ]
        ]);
    }

    /**
     * Validate Insert room quotations
     *
     * @return void
     */
    private function validateRoomQuotations()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_room_id',
                'label' => 'Project Room',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'price_per_luminaries',
                'label' => 'Price per luminaries',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'installation_charges',
                'label' => 'Installation charges',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'discount_price',
                'label' => 'Discount price',
                'rules' => 'trim|required|numeric'
            ]
        ]);
    }
    
    /**
     * Validate room quotation edit
     *
     * @return void
     */
    private function validateRoomQuotationEdit()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'room_quotation_id',
                'label' => 'Project Room',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'price_per_luminaries',
                'label' => 'Price per luminaries',
                'rules' => 'trim|numeric'
            ],
            [
                'field' => 'installation_charges',
                'label' => 'Installation charges',
                'rules' => 'trim|numeric'
            ],
            [
                'field' => 'discount_price',
                'label' => 'Discount price',
                'rules' => 'trim|numeric'
            ]
        ]);
    }
}