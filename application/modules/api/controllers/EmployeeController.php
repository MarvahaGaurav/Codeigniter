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
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="employee_id",
     *     in="query",
     *     description="passing employee Id will fetch employee details",
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Employee List"),
     *   @SWG\Response(response=101, description="Account Blocked"),     
     *   @SWG\Response(response=201, description="Header is missing"),        
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function employee_get()
    {
        $userData = $this->accessTokenCheck("company_id,is_owner,user_type,status");

        $getData = $this->get();
        $getData = trim_input_parameters($getData);

        if ( $userData['status'] == 2 ) {
            $this->response([
                'code' => HTTP_FORBIDDEN,
                'api_code_result' => 'FORBIDDEN',
                'msg' => $this->lang->line('account_blocked')
            ], HTTP_FORBIDDEN);
        }

        if((int)$userData['company_id'] <= 0 && (int)$userData['is_owner'] !== 2){
            $this->response([
                'code' => NO_DATA_FOUND,
                'api_code_result' => 'NO_DATA_FOUND',
                'msg' => $this->lang->line("no_data_found")
            ]);
        }

        $this->load->model('Common_model');

        if ( isset($getData['employee_id']) && !empty((int)$getData['employee_id']) ) {
            $whereArr['where'] = ['user_id'=>$getData['employee_id'],'is_owner'=>'1'];
            $myEmployeeDetail =  $this->Common_model->fetch_data('ai_user', 'user_id,first_name,middle_name,last_name,email,company_id,IF(image !="",CONCAT("' . IMAGE_PATH . '","",image),"") as image,IF(image_thumb !="",CONCAT("' . THUMB_IMAGE_PATH . '","",image_thumb),"") as image_thumb', $whereArr, true);             
            if($userData['company_id'] > 0 && $userData['is_owner'] == '2' && $userData['company_id'] == $myEmployeeDetail['company_id']){
                $this->response([
                    'code' => HTTP_OK,
                    'api_code_result' => "OK",
                    'msg' => $this->lang->line('process_success'),
                    'data' => $myEmployeeDetail
                ]);
             }else{
                 $myEmployeeDetail = [];
                $this->response([
                    'code' => NO_DATA_FOUND,
                    'api_code_result' => "NO_DATA_FOUND",
                    'msg' => $this->lang->line('no_data_found')
                ]);
             }
        } else {
            $offset = isset($getData['offset'])&&!empty((int)$getData['offset'])?$getData['offset']:0;
            $myEmployeeList = $this->Common_model->getMyEmployeesList('u.user_id,u.first_name,u.middle_name,u.last_name,u.email,u.user_type,u.is_owner,IF(u.image !="",u.image,"") as image,IF(u.image_thumb !="",u.image_thumb,"") as image_thumb',$userData['user_id'],$userData['company_id'], $offset);
            $offset = $myEmployeeList['count'] + RECORDS_PER_PAGE;
            if ( (int)$myEmployeeList['count'] <= (int) $offset ) {
                $offset = -1;
            }
            if(!$myEmployeeList['result']){
                $this->response([
                    'code' => NO_DATA_FOUND,
                    'api_code_result' => 'NO_DATA_FOUND',
                    'msg' => $this->lang->line("no_data_found")
                ]);
            }
    
            $this->response([
                'code' => HTTP_OK,
                'api_code_result' => "OK", 
                'data' =>  $myEmployeeList['result'],
                'offset' => $offset
            ]);
        }
    }

    /**
     * @SWG\Get(path="/employee/request",
     *   tags={"Employee"},
     *   summary="List all employee requests",
     *   description="List all employee requests",
     *   operationId="employee_get",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Employee List"),
     *   @SWG\Response(response=101, description="Account Blocked"),     
     *   @SWG\Response(response=201, description="Header is missing"),        
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function request_get()
    {
        $userData = $this->accessTokenCheck("company_id,is_owner,user_type,status");

        $getData = $this->get();
        $getData = trim_input_parameters($getData);

        if ( $userData['status'] == 2 ) {
            $this->response([
                'code' => HTTP_FORBIDDEN,
                'api_code_result' => 'FORBIDDEN',
                'msg' => $this->lang->line('account_blocked')
            ], HTTP_FORBIDDEN);
        }

        if((int)$userData['company_id'] <= 0 && (int)$userData['is_owner'] !== 2){
            $this->response([
                'code' => NO_DATA_FOUND,
                'api_code_result' => 'NO_DATA_FOUND',
                'msg' => $this->lang->line("no_data_found")
            ]);
        }

        $this->load->model('Common_model');

        $fields = 'erm.*,u.user_id,u.first_name,u.middle_name,u.last_name,email,user_type,is_owner,IF(image !="",image,"") as image,IF(image_thumb !="",image_thumb,"") as image_thumb';
        $myemployeerequests = $this->Common_model->EemployeeRequestsbyUser($fields,$userData['user_id'], $userData['company_id']);

        if ( empty($myemployeerequests) ) {
            $this->response([
                'code' => NO_DATA_FOUND,
                'api_code_result' => 'NO_DATA_FOUND',
                'msg' => $this->lang->line("no_data_found")
            ]);
        }

        $this->response([
            'code' => HTTP_OK,
            'api_code_result' => "OK", 
            'data' =>  $myemployeerequests
        ]);
        
    }
    /**
     * @SWG\Get(path="/employee/permission",
     *   tags={"Employee"},
     *   summary="List Employee permissions",
     *   description="List Employee permissions",
     *   operationId="employeePermissions_get",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="employee_id",
     *     in="query",
     *     description="Employee Id",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Employee List"),
     *   @SWG\Response(response=101, description="Account Blocked"),     
     *   @SWG\Response(response=201, description="Header is missing"),        
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function employeePermissions_get()
    {
        $userData = $this->accessTokenCheck('company_id');

        $requestData = $this->get();
        $requestData = trim_input_parameters($requestData);
        $mandatoryFields = ['employee_id'];
        $check = check_empty_parameters($requestData, $mandatoryFields);
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

        $this->load->model("Permission");
        $options['employee_id'] = $requestData['employee_id'];
        // $options['user_id'] = $userData['user_id'];
        $options['company_id'] = $userData['company_id'];

        $data = $this->Permission->get($options);

        if ( empty($data) ) {
            $this->response([
                'code' => NO_DATA_FOUND,
                'api_code_result' => 'NO_DATA_FOUND',
                'msg' => $this->lang->line("no_data_found")
            ]);
        }

        $this->response([
            'code' => HTTP_OK,
            'api_code_result' => "OK", 
            'data' =>  $data
        ]);
    }
}