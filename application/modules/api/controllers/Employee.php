<?php

require APPPATH . 'libraries/REST_Controller.php';

class Employee extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->library('form_validation');
    }

    public function index_post() {
        $postDataArr = $this->post();
        $config = [];

        $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            )                       
        );
        
        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {
            try {
                $this->load->library('commonfn');
                $respArr = $this->Common_model->getUserInfo($postDataArr['accesstoken'], ['u.user_id','company_id','is_owner','user_type','status']);
                //echo $this->db->last_query(); die;
                $user_info = [];
                
                /*
                 * Response is not success if session expired or invalid access token
                 */
                if ($respArr['code'] != SUCCESS_CODE) {
                    $this->response($respArr);
                } else {
                    $user_info = $respArr['userinfo'];                    
                }
                /*
                 * Validate if user is not blocked
                 */
                if ($user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                }
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit(); 
                    if($user_info['company_id'] > 0 && $user_info['is_owner'] == '2'){
                        $fields = 'erm.er_id,erm.requested_by,u.user_id';
                        $myemployeerequests = $this->Common_model->EemployeeRequestsbyUser($fields,$user_info['user_id'], $user_info['company_id'],'0');
                        //pr($myemployeerequests);
                        //echo $this->db->last_query(); die;
                        if($myemployeerequests){
                            if(is_array($myemployeerequests[0])){
                                foreach($myemployeerequests as $req){
                                    $userreq[] = $req['user_id'];
                                }                                                                
                            }else{
                                $userreq[] = $myemployeerequests['user_id'];
                            }
                            $whereArr['where_not_in'] = ['user_id'=>$userreq];
                        }
                        //pr($whereArr);
                        //$whereArr['where'] = ['company_id'=>$user_info['company_id'],'is_owner'=>'1'];
                        //$myEmployeeList =  $this->Common_model->fetch_data('ai_user', 'user_id,first_name,middle_name,last_name,email,user_type,is_owner,IF(image !="",image,"") as image,IF(image_thumb !="",image_thumb,"") as image_thumb', $whereArr, false); 
                         $myEmployeeList = $this->Common_model->getMyEmployeesList('u.user_id,u.first_name,u.middle_name,u.last_name,u.email,u.user_type,u.is_owner,IF(u.image !="",u.image,"") as image,IF(u.image_thumb !="",u.image_thumb,"") as image_thumb',$user_info['user_id'],$user_info['company_id']);
                        //echo $this->db->last_query(); die;
                         if(!$myEmployeeList){
                             $myEmployeeList = [];
                         }
                        $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('process_success'), 'result' => $myEmployeeList));
                    }else{
                        $myEmployeeList = [];
                        $this->response(array('code' => NO_DATA_FOUND, 'msg' => $this->lang->line('no_data_found'), 'result' => $myEmployeeList));
                    }
                    //pr($myEmployeeList);
                    
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $msg, 'result' => (object)[]));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }
    
    public function myemployeereuestlist_post() {
        $postDataArr = $this->post();
        $config = [];

        $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            ),                       
        );
        
        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {

            try {
                $this->load->library('commonfn');
                $respArr = $this->Common_model->getUserInfo($postDataArr['accesstoken'], ['u.user_id','company_id','is_owner','user_type','status']);
                //echo $this->db->last_query(); die;
                $user_info = [];
                
                /*
                 * Response is not success if session expired or invalid access token
                 */
                if ($respArr['code'] != SUCCESS_CODE) {
                    $this->response($respArr);
                } else {
                    $user_info = $respArr['userinfo'];                    
                }
                /*
                 * Validate if user is not blocked
                 */
                if ($user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                }
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit(); 
                    if($user_info['company_id'] > 0 && $user_info['is_owner'] == '2') {
                        $fields = 'erm.*,u.user_id,u.first_name,u.middle_name,u.last_name,email,user_type,is_owner,IF(image !="",image,"") as image,IF(image_thumb !="",image_thumb,"") as image_thumb';
                        $myemployeerequests = $this->Common_model->EemployeeRequestsbyUser($fields,$user_info['user_id'], $user_info['company_id'],'0');
                        //echo $this->db->last_query(); die;
                       // print_r($myemployeerequests);
                        if(!empty($myemployeerequests)){
                            /*echo count($myemployeerequests); ;
                            var_dump(is_array($myemployeerequests[0]));die;
                            if(is_array($myemployeerequests[0]) === FALSE){
                                $myem[]=$myemployeerequests;
                                $myemployeerequests = $myem;
                            }*/
                            $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('process_success'), 'result' => $myemployeerequests));
                        }else{
                            $myemployeerequests = [];
                            $this->response(array('code' => NO_DATA_FOUND, 'msg' => $this->lang->line('no_data_found'), 'result' => $myemployeerequests));
                        }
                    } else {
                        $myemployeerequests = [];
                        $this->response(array('code' => NO_DATA_FOUND, 'msg' => $this->lang->line('no_data_found'), 'result' =>  $myemployeerequests));
                    }
                    //pr($myEmployeeList);
                    
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $msg, 'result' => (object)[]));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }

    /**
     * @SWG\Post(path="/employee/request",
     *   tags={"Employee"},
     *   summary="Accept reject employee requests",
     *   description="Accept reject employee requests",
     *   operationId="actiononemployee_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="formData",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="employee_id",
     *     in="formData",
     *     description="Employee Id",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="action",
     *     in="formData",
     *     description="action",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="er_id",
     *     in="formData",
     *     description="er_id",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Employee List"),
     *   @SWG\Response(response=101, description="Account Blocked"),     
     *   @SWG\Response(response=201, description="Header is missing"),        
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function actiononemployee_post() {
        $postDataArr = $this->post();
        $language_code = $this->langcode_validate();
        $config = [];
        $head = $this->head();
        if ( (!isset($head['accesstoken']) || empty(trim($head['accesstoken']))) && (!isset($head['Accesstoken']) || empty(trim($head['Accesstoken']))) ) {
            $this->response([
                "code" => HTTP_UNAUTHORIZED,
                "api_code_result" => "UNAUTHORIZED",
                "msg" => $this->lang->line("invalid_access_token")
            ], HTTP_UNAUTHORIZED);
        }
        if ( isset($head['Accesstoken']) && !empty($head['Accesstoken']) ) {
            $head['accesstoken'] = $head['Accesstoken'];
        }
        $config = array(
            array(
                'field' => 'employee_id',
                'label' => 'Employee ID',
                'rules' => 'required'
            ), 
            array(
                'field' => 'er_id',
                'label' => 'Rquested ID',
                'rules' => 'required'
            ), 
            array(
                'field' => 'action',
                'label' => 'Action',
                'rules' => 'required'
            ),  
        );
        
        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {

            try {
                $this->load->library('commonfn');
                $respArr = $this->Common_model->getUserInfo($head['accesstoken'], ['u.user_id','company_id','is_owner','user_type','status']);                
                $user_info = [];
                
                /*
                 * Response is not success if session expired or invalid access token
                 */
                if ($respArr['code'] != SUCCESS_CODE) {
                    $this->response($respArr);
                } else {
                    $user_info = $respArr['userinfo'];                    
                }
                /*
                 * Validate if user is not blocked
                 */
                if ($user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                }
                //pr($user_info);
                $whereArr['where'] = ['er_id'=>$postDataArr['er_id']];
                $myEmployeedetail =  $this->Common_model->fetch_data('employee_request_master', 'requested_by,requested_to,status', $whereArr, true);                         
                //pr($myEmployeedetail);
                if(!empty($myEmployeedetail) && $myEmployeedetail['requested_by'] == $postDataArr['employee_id']) {                    
                    $whereArr['where'] = ['er_id'=>$postDataArr['er_id']];                
                    $updaterequesr =  $this->Common_model->update_single('employee_request_master', ['status'=>$postDataArr['action']], $whereArr); 
                } else {
                     $this->response(array('code' => INVALID_REQUEST_ID, 'msg' => $this->lang->line('invalid_request_id'), 'result' => (object)[]));
                }
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit(); 
                    
                    /* I have to work in this area*/                    
                    if($updaterequesr){
                        // $message = "";
                        // $alert = "";
                        // if ( $postDataArr['action'] == 1 ) {
                        //     $message = "Your Employee request has been accepted";
                        //     $alert = "Employee request accepted";
                        // } else {
                        //     $message = "Your Employee request has been rejected";
                        //     $alert = "Employee request rejected";
                        // }
                        // $this->load->model("UtilModel");
                        // $user_data = $this->UtilModel->selectQuery(
                        //     "device_token, platform, ai_user.user_id",
                        //     "ai_user",
                        //     [
                        //         "where" => ["ai_user.user_id" => $postDataArr["employee_id"]],
                        //         "join" => ["ai_session" => "ai_user.user_id=ai_session.user_id"]
                        //     ]
                        // );

                        // $ios_user_data = array_filter($user_data, function($data){
                        //     return IPHONE === (int)$data["platform"]?true:false;
                        // });

                        // $android_user_data = array_filter($user_data, function($data){
                        //     return ANDROID === (int)$data["platform"]?true:false;
                        // });

                        // $android_tokens = array_map(function($data){
                        //     return $data['device_token'];
                        // }, $android_user_data);

                        // if ( $android_tokens ) {
                        //     $android_payload_data = [
                        //         'badge' => 1,
                        //         'sound' => 'default',
                        //         'status' => 1,
                        //         'type' => "employee_request_status",
                        //         'message' => $message,
                        //         'time' => strtotime('now')
                        //     ];
                        //     $this->pushnotification->androidMultiplePush($android_tokens, $android_payload_data);
                        // }

                        // if ( $ios_user_data ) {
                        //     $ios_payload_data = [
                        //         'badge' => 1,
                        //         'alert' => $alert,
                        //         'sound' => 'default',
                        //         'status' => 1,
                        //         'type' => "employee_request_status",
                        //         'message' => $message,
                        //         'time' => strtotime('now')
                        //     ];

                        //     $this->pushnotification->sendMultipleIphonePush($ios_user_data, $ios_payload_data);
                        // }
                        $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('process_success'), 'result' => (object)[]));
                    }else{                        
                        $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $this->lang->line('process_failuare'), 'result' => (object)[]));
                    }
                    /* I have to work in this area*/
                    
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $msg, 'result' => (object)[]));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }

    public function employeedetail_post() {
        $postDataArr = $this->post();
        $config = [];

        $config = array(
            array(
                'field' => 'employee_id',
                'label' => 'Employee ID',
                'rules' => 'trim|required'
            ), 
        );
        
        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {

            try {
                $this->load->library('commonfn');
                $respArr = $this->Common_model->getUserInfo($postDataArr['accesstoken'], ['u.user_id','company_id','is_owner','user_type','status']);                
                $user_info = [];
                
                /*
                 * Response is not success if session expired or invalid access token
                 */
                if ($respArr['code'] != SUCCESS_CODE) {
                    $this->response($respArr);
                } else {
                    $user_info = $respArr['userinfo'];                    
                }
                /*
                 * Validate if user is not blocked
                 */
                if ($user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                }
                
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit(); 
                    
                    /* I have to work in this area*/
                    $whereArr['where'] = ['user_id'=>$postDataArr['employee_id'],'is_owner'=>'1'];
                    $myEmployeeDetail =  $this->Common_model->fetch_data('ai_user', 'user_id,first_name,middle_name,last_name,email,company_id,IF(image !="",CONCAT("' . IMAGE_PATH . '","",image),"") as image,IF(image_thumb !="",CONCAT("' . THUMB_IMAGE_PATH . '","",image_thumb),"") as image_thumb', $whereArr, true); 
                        
                    if($user_info['company_id'] > 0 && $user_info['is_owner'] == '2' && $user_info['company_id'] == $myEmployeeDetail['company_id']){
                       $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('process_success'), 'result' => $myEmployeeDetail));
                    }else{
                        $myEmployeeDetail = [];
                        $this->response(array('code' => NO_DATA_FOUND, 'msg' => $this->lang->line('no_data_found'), 'result' => (object)$myEmployeeDetail));
                    }
                    /* I have to work in this area*/
                    
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $msg, 'result' => (object)[]));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }
    

    /**
     * @SWG\Post(path="/employee/permission",
     *   tags={"Employee"},
     *   summary="Set Employee Permissions",
     *   description="Set Employee Permissions",
     *   operationId="setpermissopnforemp_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="employee_id",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="quote_view",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="quote_add",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="quote_edit",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="quote_delete",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="insp_view",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="insp_delete",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="insp_add",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="insp_edit",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="project_view",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="project_add",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="project_edit",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="project_delete",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Employee List"),
     *   @SWG\Response(response=101, description="Account Blocked"),     
     *   @SWG\Response(response=201, description="Header is missing"),        
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function setpermissopnforemp_post() {
        $postDataArr = $this->post();
        $langugage_code = $this->langcode_validate();
        $config = [];
        $head = $this->head();
        if ( (!isset($head['accesstoken']) || empty(trim($head['accesstoken']))) && (!isset($head['Accesstoken']) || empty(trim($head['Accesstoken']))) ) {
            $this->response([
                "code" => HTTP_UNAUTHORIZED,
                "api_code_result" => "UNAUTHORIZED",
                "msg" => $this->lang->line("invalid_access_token")
            ], HTTP_UNAUTHORIZED);
        }
        if ( isset($head['Accesstoken']) && !empty($head['Accesstoken']) ) {
            $head['accesstoken'] = $head['Accesstoken'];
        }
        $config = array(
            array(
                'field' => 'employee_id',
                'label' => 'Employee ID',
                'rules' => 'required'
            ),  
        );
        
        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {

            try {
                $this->load->library('commonfn');
                $respArr = $this->Common_model->getUserInfo($head['accesstoken'], ['u.user_id','company_id','is_owner','user_type','status']);
                $user_info = [];
                
                /*
                 * Response is not success if session expired or invalid access token
                 */
                if ($respArr['code'] != SUCCESS_CODE) {
                    $this->response($respArr);
                } else {
                    $user_info = $respArr['userinfo'];                    
                }
                /*
                 * Validate if user is not blocked
                 */
                if ($user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                }
                //pr($user_info);
                $whereArr['where'] = ['employee_id'=>$postDataArr['employee_id'],'user_id'=>$user_info['user_id']];
                $myEmployeepermissiondetail =  $this->Common_model->fetch_data('user_employee_permission', 'employee_id,user_id,pr_id', $whereArr, true);                         
                //pr($myEmployeepermissiondetail);
                $insArr['quote_view'] = $postDataArr['quote_view'];
                $insArr['quote_add'] = $postDataArr['quote_add'];
                $insArr['quote_edit'] = $postDataArr['quote_edit'];
                $insArr['quote_delete'] = $postDataArr['quote_delete'];
                $insArr['insp_view'] = $postDataArr['insp_view'];
                $insArr['insp_delete'] = $postDataArr['insp_delete'];
                $insArr['insp_add'] = $postDataArr['insp_add'];
                $insArr['insp_edit'] = $postDataArr['insp_edit'];
                $insArr['project_view'] = $postDataArr['project_view'];
                $insArr['project_add'] = $postDataArr['project_add'];
                $insArr['project_edit'] = $postDataArr['project_edit'];
                $insArr['project_delete'] = $postDataArr['project_delete'];
                if(!empty($myEmployeepermissiondetail)){ 
                    //die('update');
                    $insArr['modify_date'] = date('Y-m-d H:i:s');
                    $whereArr['where'] = ['pr_id'=>$myEmployeepermissiondetail['pr_id']];                
                    $updaterequesr =  $this->Common_model->update_single('user_employee_permission', $insArr, $whereArr); 
                }else{
                    //die('insert');
                    $insArr['user_id'] = $user_info['user_id'];
                    $insArr['employee_id'] = $postDataArr['employee_id'];
                    $insertrequesr =  $this->Common_model->insert_single('user_employee_permission', $insArr); 
                }
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit(); 

                    // $this->load->model("UtilModel");
                    // $user_data = $this->UtilModel->selectQuery(
                    //     "device_token, platform, ai_user.user_id",
                    //     "ai_user",
                    //     [
                    //         "where" => ["ai_user.user_id" => $postDataArr["employee_id"]],
                    //         "join" => ["ai_session" => "ai_user.user_id=ai_session.user_id"]
                    //     ]
                    // );

                    // $ios_user_data = array_filter($user_data, function($data){
                    //     return IPHONE === (int)$data["platform"]?true:false;
                    // });

                    // $android_user_data = array_filter($user_data, function($data){
                    //     return ANDROID === (int)$data["platform"]?true:false;
                    // });

                    // $android_tokens = array_map(function($data){
                    //     return $data['device_token'];
                    // }, $android_user_data);

                    // if ( $android_tokens ) {
                    //     $android_payload_data = [
                    //         'badge' => 1,
                    //         'sound' => 'default',
                    //         'status' => 1,
                    //         'type' => "employee_permission_updated",
                    //         'message' => $message,
                    //         'time' => strtotime('now')
                    //     ];
                    //     $this->pushnotification->androidMultiplePush($android_tokens, $android_payload_data);
                    // }

                    // if ( $ios_user_data ) {
                    //     $ios_payload_data = [
                    //         'badge' => 1,
                    //         'alert' => "Permissions updated",
                    //         'sound' => 'default',
                    //         'status' => 1,
                    //         'type' => "employee_permission_updated",
                    //         'message' => $message,
                    //         'time' => strtotime('now')
                    //     ];

                    //     $this->pushnotification->sendMultipleIphonePush($ios_user_data, $ios_payload_data);
                    // }
                    $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('process_success'), 'result' => (object)[])); 
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $msg, 'result' => (object)[]));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }

    private function langcode_validate()
    {
        $language_code = $this->head("X-Language-Code");
        $language_code = trim($language_code);
        $valid_language_codes = ["en","da","nb","sv","fi","fr","nl","de"];

        if ( empty($language_code) ) {
            $this->response([
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('header_missing'),
                'extra_info' => [
                    "missing_parameter" => "language_code"
                ]
            ]);
        }

        if ( ! in_array($language_code, $valid_language_codes) ) {
            $this->response([
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('invalid_header'),
                'extra_info' => [
                    "missing_parameter" => $this->lang->line('invalid_language_code')
                ]
            ]);
        }

        $language_map = [
            "en" => "english",
            "da" => "danish",
            "nb" => "norwegian",
            "sv" => "swedish",
            "fi" => "finnish",
            "fr" => "french",
            "nl" => "dutch",
            "de" => "german"
        ];

        $this->load->language("common", $language_map[$language_code]);
        $this->load->language("rest_controller", $language_map[$language_code]);

        return $language_code;
    }
}
