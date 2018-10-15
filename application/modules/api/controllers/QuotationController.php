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
     *   summary="Add Quotation",
     *   description="Add quotation",
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
     *     name="price",
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
            $user_data = $this->accessTokenCheck('u.company_id');
            $language_code = $this->langcode_validate();

            $this->requestData = $this->post();

            $this->validateQuotations();

            $this->validationRun();

            $quotationData = $this->UtilModel->selectQuery(['id'], 'project_quotations', [
                'where' => ['request_id' => $this->requestData['request_id'], 'company_id' => $user_data['company_id']]
            ]);

            if (!empty($quotationData)) {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('quoted_by_company'),
                ]);
            }

            $quotationId = $this->UtilModel->insertTableData([
                'request_id' => $this->requestData['request_id'],
                'company_id' => $user_data['company_id'],
                'user_id' => $user_data['user_id'],
                'price' => $this->requestData['price'],
                'created_at' => $this->datetime
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
     *   summary="Quotation Listing",
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
     *  @SWG\Parameter(
     *     name="request_id",
     *     in="query",
     *     description="Request Id",
     *     type="string",
     *     required = true
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
     * Undocumented function
     *
     * @return void
     */

     /**
     *
     */
    /**
     * @SWG\Get(path="/quoted-requests",
     *   tags={"Requests & Quotations"},
     *   summary="Quoteed Request Listing",
     *   description="List projects posted by user which are quoted",
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
     *  @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     description="1 - Customer Quotation Listing, 2 - Technician Quotation Listing",
     *     type="string",
     *     required = true
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
    public function quotedRequests_get()
    {
        try {
            $user_data = $this->accessTokenCheck('u.company_id');
            $language_code = $this->langcode_validate();

            $this->requestData = $this->get();

            $this->validateQuotedRequests();

            $this->validationRun();

            $this->load->model("ProjectQuotation");
            $params['offset'] =
                isset($this->requestData['offset'])&&is_numeric($this->requestData['offset'])&&(int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset']: 0;
            $params['limit'] = API_RECORDS_PER_PAGE;

            if ((int)$this->requestData['type'] === QUOTED_REQUEST_CUSTOMER) {
                $params['user_id'] = $user_data['user_id'];
            } else {
                $params['company_id'] = $user_data['company_id'];
            }

            $quotedRequests = $this->ProjectQuotation->quotedRequestList($params);
            $requests = $quotedRequests['data'];
            $count = $quotedRequests['count'];

            if (empty($requests)) {
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
                'msg' => $this->lang->line('quoted_request_fetched'),
                'data' => $requests,
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
     *
     */
    /**
     * @SWG\Get(path="/requests",
     *   tags={"Requests & Quotations"},
     *   summary="Request Listing",
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
     *     name="type",
     *     in="query",
     *     description="1 - Customer Quotation Listing, 2 - Technician Quotation Listing",
     *     type="string",
     *     required = true
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
    public function requests_get()
    {
        try {
            $user_data = $this->accessTokenCheck('u.company_id, u.user_lat, u.user_long');
            $language_code = $this->langcode_validate();

            $this->requestData = $this->get();

            $this->validateRequests();

            $this->validationRun();
            
            $params['limit'] = API_RECORDS_PER_PAGE;
            $this->load->model('ProjectRequest');

            $params['offset'] =
                isset($this->requestData['offset'])&&is_numeric($this->requestData['offset'])&&(int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset']: 0;
            if ((int)$this->requestData['type'] === AWAITING_REQUEST_CUSTOMER) {
                $params['user_id'] = $user_data['user_id'];
            } else {
                $params['company_id'] = $user_data['company_id'];
                $params['type'] = AWAITING_REQUEST_TECHNICIAN;
                $params['lat'] = $user_data['user_lat'];
                $params['lng'] = $user_data['user_long'];
            }

            $requests = $this->ProjectRequest->awaitingRequest($params);

            $data = $requests['data'];
            $count = $request['count'];

            if (empty($data)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$requests['count'] > ($params['offset'] + API_RECORDS_PER_PAGE)) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + API_RECORDS_PER_PAGE;
            }


            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_listed'),
                'data' => $data,
                'next_count' => $nextCount,
                'has_more_pages' => $hasMorePages,
                'per_page_count' => $params['limit'],
                'total' => $requests['count']
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
     *
     */
    /**
     * @SWG\Put(path="/quotations",
     *   tags={"Requests & Quotations"},
     *   summary="Quotation Approve",
     *   description="Approve quotation",
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
            $this->UtilModel->updateTableData([
                'status' => QUOTATION_STATUS_APPROVED
            ], 'project_quotations', [
                'id' => $this->requestData['quotation_id']
            ]);

            $this->UtilModel->updateTableData([
                'status' => QUOTATION_STATUS_REJECTED
            ], 'project_quotations', [
                'id !=' => $this->requestData['quotation_id'],
                'request_id' => $this->requestData['request_id']
            ]);
            
            $this->db->trans_commit();
            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('quotation_accpeted')
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
                'field' => 'price',
                'label' => 'Price',
                'rules' => 'trim|required|decimal'
            ],
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
            ]
        ]);
    }
}
