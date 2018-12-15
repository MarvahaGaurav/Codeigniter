<?php 
defined("BASEPATH") or exit("No direct script access allowed");
require 'BaseController.php';

use DatabaseExceptions\InsertException;

class EmployeeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @SWG\Get(path="/employee",
     *   tags={"Employee"},
     *   summary="Employee List",
     *   description="Employee List",
     *   operationId="employee_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="employee_id",
     *     in="query",
     *     description="passing employee Id will fetch employee details",
     *     type="string"
     *   ),
     * @SWG\Response(response=200, description="Employee List"),
     * @SWG\Response(response=101, description="Account Blocked"),     
     * @SWG\Response(response=201, description="Header is missing"),        
     * @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function employee_get()
    {
        $language_code = $this->langcode_validate();
        $userData = $this->accessTokenCheck("company_id,is_owner,user_type,status");

        $getData = $this->get();
        $getData = trim_input_parameters($getData);

        $search = isset($getData['search']) && is_string($getData['search']) && strlen(trim($getData['search'])) > 0 ? trim($getData['search']) : '';

        if ($userData['status'] == 2) {
            $this->response(
                [
                    'code' => HTTP_FORBIDDEN,
                    'api_code_result' => 'FORBIDDEN',
                    'msg' => $this->lang->line('account_blocked')
                ],
                HTTP_FORBIDDEN
            );
        }

        if ((int)$userData['company_id'] <= 0 && (int)$userData['is_owner'] !== 2) {
            $this->response(
                [
                    'code' => NO_DATA_FOUND,
                    'api_code_result' => 'NO_DATA_FOUND',
                    'msg' => $this->lang->line("no_data_found")
                ]
            );
        }

        $this->load->model('Common_model');

        if (isset($getData['employee_id']) && !empty((int)$getData['employee_id'])) {
            $whereArr['where'] = ['user_id' => $getData['employee_id'], 'is_owner' => '1'];

            $myEmployeeDetail = $this->Common_model->fetch_data('ai_user', 'user_id,first_name as full_name,email,company_id,IF(image !="",CONCAT("' . IMAGE_PATH . '","",image),"") as image,IF(image_thumb !="",CONCAT("' . THUMB_IMAGE_PATH . '","",image_thumb),"") as image_thumb', $whereArr, true);
            if ($userData['company_id'] > 0 && $userData['is_owner'] == '2' && $userData['company_id'] == $myEmployeeDetail['company_id']) {
                $this->response(
                    [
                        'code' => HTTP_OK,
                        'api_code_result' => "OK",
                        'msg' => $this->lang->line('process_success'),
                        'data' => $myEmployeeDetail
                    ]
                );
            } else {
                $myEmployeeDetail = [];
                $this->response(
                    [
                        'code' => HTTP_NOT_FOUND,
                        'api_code_result' => "NO_DATA_FOUND",
                        'msg' => $this->lang->line('no_data_found')
                    ]
                );
            }
        } else {
            $offset = isset($getData['offset']) && !empty((int)$getData['offset']) ? $getData['offset'] : 0;
            $this->load->model('Employee');
            $params['company_id'] = $userData['company_id'];
            $params['limit'] = RECORDS_PER_PAGE;
            $params['offset'] = $offset;
            if (strlen($search) > 0) {
                $params['where']["(u.first_name LIKE '%{$search}%' OR u.email LIKE '%{$search}%')"] = null;
            }
            $myEmployeeList = $this->Employee->employeeList($params);
            $offset = $myEmployeeList['count'] + RECORDS_PER_PAGE;
            if ((int)$myEmployeeList['count'] <= (int)$offset) {
                $offset = -1;
            }
            if (empty($myEmployeeList['data'])) {
                $this->response(
                    [
                        'code' => HTTP_NOT_FOUND,
                        'api_code_result' => 'NO_DATA_FOUND',
                        'msg' => $this->lang->line("no_data_found")
                    ]
                );
            }

            $this->response(
                [
                    'code' => HTTP_OK,
                    'api_code_result' => "OK",
                    'data' => $myEmployeeList['data'],
                    'offset' => $offset
                ]
            );
        }
    }

    /**
     * @SWG\Get(path="/employee/request",
     *   tags={"Employee"},
     *   summary="List all employee requests",
     *   description="List all employee requests",
     *   operationId="employee_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Response(response=200, description="Employee List"),
     * @SWG\Response(response=101, description="Account Blocked"),     
     * @SWG\Response(response=201, description="Header is missing"),        
     * @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function request_get()
    {
        $language_code = $this->langcode_validate();
        $userData = $this->accessTokenCheck("company_id,is_owner,user_type,status");

        $getData = $this->get();
        $getData = trim_input_parameters($getData);

        if ($userData['status'] == 2) {
            $this->response(
                [
                    'code' => HTTP_FORBIDDEN,
                    'api_code_result' => 'FORBIDDEN',
                    'msg' => $this->lang->line('account_blocked')
                ],
                HTTP_FORBIDDEN
            );
        }

        if ((int)$userData['company_id'] <= 0 && (int)$userData['is_owner'] !== 2) {
            $this->response(
                [
                    'code' => NO_DATA_FOUND,
                    'api_code_result' => 'NO_DATA_FOUND',
                    'msg' => $this->lang->line("no_data_found")
                ]
            );
        }

        $this->load->model('Common_model');

        $offset = isset($getData['offset']) && !empty((int)$getData['offset']) ? $getData['offset'] : 0;



        $fields = 'SQL_CALC_FOUND_ROWS erm.*,u.user_id,u.first_name as full_name,email,user_type,is_owner,IF(image !="",image,"") as image,IF(image_thumb !="",image_thumb,"") as image_thumb';
        $myemployeerequests = $this->Common_model->EemployeeRequestsbyUser($fields, $userData['user_id'], $userData['company_id'], 0, $offset);
        $offset = $myemployeerequests['count'] + RECORDS_PER_PAGE;
        if ((int)$myemployeerequests['count'] <= (int)$offset) {
            $offset = -1;
        }

        if (empty($myemployeerequests['result'])) {
            $this->response(
                [
                    'code' => NO_DATA_FOUND,
                    'api_code_result' => 'NO_DATA_FOUND',
                    'msg' => $this->lang->line("no_data_found")
                ]
            );
        }

        $this->response(
            [
                'code' => HTTP_OK,
                'api_code_result' => "OK",
                'data' => $myemployeerequests['result'],
                'offset' => $offset
            ]
        );

    }
    /**
     * @SWG\Get(path="/employee/permission",
     *   tags={"Employee"},
     *   summary="List Employee permissions",
     *   description="List Employee permissions",
     *   operationId="employeePermissions_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="employee_id",
     *     in="query",
     *     description="Employee Id",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Response(response=200, description="Employee List"),
     * @SWG\Response(response=101, description="Account Blocked"),     
     * @SWG\Response(response=201, description="Header is missing"),        
     * @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function employeePermissions_get()
    {
        $language_code = $this->langcode_validate();
        $userData = $this->accessTokenCheck('company_id');

        $requestData = $this->get();
        $requestData = trim_input_parameters($requestData);
        $mandatoryFields = ['employee_id'];
        $check = check_empty_parameters($requestData, $mandatoryFields);
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

        $this->load->model("Permission");
        $options['employee_id'] = $requestData['employee_id'];
        // $options['user_id'] = $userData['user_id'];
        $options['company_id'] = $userData['company_id'];

        $data = $this->Permission->get($options);

        if (empty($data)) {
            $this->response(
                [
                    'code' => NO_DATA_FOUND,
                    'api_code_result' => 'NO_DATA_FOUND',
                    'msg' => $this->lang->line("no_data_found")
                ]
            );
        }

        $this->response(
            [
                'code' => HTTP_OK,
                'api_code_result' => "OK",
                'data' => $data
            ]
        );
    }

    public function updateEmployeeCompany_put()
    {
        try {
            $userData = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->requestData = $this->put();

            $this->requestData = trim_input_parameters($this->requestData, false);

            if ((int)$userData['is_owner'] !== ROLE_EMPLOYEE) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('forbidden_action')
                ]);
            }

            $this->validateUpdateEmployee();

            $this->validationRun();

            $this->load->model(['Company']);

            $companyInfo = $this->Company->companyDetails($this->requestData['company_id']);

            if (empty($companyInfo)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_company_found')
                ]);
            }

            $this->db->trans_begin();

            $this->UtilModel->updateTableData([
                'requested_to' => $companyInfo->user_id,
                'company_id' => $companyInfo->company_id,
                'status' => EMPLOYEE_REQUEST_PENDING,
                'request_time' => $this->datetime
            ], 'employee_request_master', ['requested_by' => $userData['user_id']]);

            $this->UtilModel->updateTableData([
                'user_id' => $companyInfo->user_id,
                'quote_view' => 1,
                'quote_add' => 0,
                'quote_edit' => 0,
                'quote_delete' => 0,
                'insp_view' => 1,
                'insp_add' => 0,
                'insp_edit' => 0,
                'insp_delete' => 0,
                'project_view' => 1,
                'project_add' => 0,
                'project_edit' => 0,
                'project_delete' => 0,
            ], 'user_employee_permission', ['employee_id' => $userData['user_id']]);

            $this->db->trans_commit();

            $this->response([
                'code' => HTTP_OK,
                'msg' => sprintf($this->lang->line('request_sent_to_company'), $companyInfo->company_name)
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

    private function validateUpdateEmployee()
    {
        $this->load->library(['form_validation']);
        $this->form_validation->set_data($this->requestData);
        $this->form_validation->set_rules([
            [
                'field' => 'company_id',
                'label' => 'Company',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }
}