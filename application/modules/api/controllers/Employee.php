<?php

require APPPATH . 'libraries/REST_Controller.php';

class Employee extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->library('form_validation');
    }

    /**
     * @SWG\Post(path="/Employee",
     *   tags={"Employee"},
     *   summary="Employee List",
     *   description="Employee List",
     *   operationId="employee_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="formData",
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
    public function index_post() {
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
    
    /**
     * @SWG\Post(path="/Employee/myemployeereuestlist",
     *   tags={"Employee"},
     *   summary="My Employee List",
     *   description="My Employee List",
     *   operationId="myemployeereuestlist_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="formData",
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
                    if($user_info['company_id'] > 0 && $user_info['is_owner'] == '2'){
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
                    }else{
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
     * @SWG\Post(path="/Employee/actiononemployee",
     *   tags={"Employee"},
     *   summary="My Employee List",
     *   description="My Employee List",
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
        $config = [];

        $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            ),  
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
                //pr($user_info);
                $whereArr['where'] = ['er_id'=>$postDataArr['er_id']];
                $myEmployeedetail =  $this->Common_model->fetch_data('employee_request_master', 'requested_by,requested_to,status', $whereArr, true);                         
                //pr($myEmployeedetail);
                if(!empty($myEmployeedetail) && $myEmployeedetail['requested_by'] == $postDataArr['employee_id']){                    
                    $whereArr['where'] = ['er_id'=>$postDataArr['er_id']];                
                    $updaterequesr =  $this->Common_model->update_single('employee_request_master', ['status'=>$postDataArr['action']], $whereArr); 
                }else{
                     $this->response(array('code' => INVALID_REQUEST_ID, 'msg' => $this->lang->line('invalid_request_id'), 'result' => (object)[]));
                }
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit(); 
                    
                    /* I have to work in this area*/                    
                    if($updaterequesr){                        
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
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            ), 
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
    

        
    public function setpermissopnforemp_post() {
        $postDataArr = $this->post();
        $config = [];

        $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            ),  
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
}
