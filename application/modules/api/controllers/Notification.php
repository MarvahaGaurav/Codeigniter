<?php

require APPPATH . 'libraries/REST_Controller.php';

class Notification extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->library('form_validation');
    }

    /**
     * @SWG\Post(path="/Notification",
     *   tags={"Notification"},
     *   summary="Notification List",
     *   description="Notification List",
     *   operationId="notification_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="accesstoken",
     *     in="formData",
     *     description="Access Token",
     *     required=true,
     *     type="string"
     *   ),  
     *   @SWG\Response(response=200, description="Profile Update Success"),
     *   @SWG\Response(response=206, description="Unauthorized request"),     
     *   @SWG\Response(response=207, description="Header is missing"),        
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     *   @SWG\Response(response=490, description="Invalid User"),
     *   @SWG\Response(response=501, description="Please try again"),
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
                $respArr = $this->Common_model->getUserInfo($postDataArr['accesstoken'], ['u.user_id','login_time','login_status','status','first_name','middle_name','last_name','email','prm_user_countrycode','phone','alt_user_countrycode','alt_userphone','company_id','is_owner','user_type','IF(image !="",CONCAT("' . IMAGE_PATH . '","",image),"") as image','IF(image_thumb !="",CONCAT("' . THUMB_IMAGE_PATH . '","",image_thumb),"") as image_thumb']);
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
                    $whereArr['where'] = ['reciever_id'=>$user_info['user_id']];
                    $whereArr['order_by'] = ['notify_time'=>'DESC'];
                    $notificationlist =  $this->Common_model->fetch_data('users_notification_master', '*', $whereArr, false);                   
                    $this->response(array('CODE' => SUCCESS_CODE, 'MESSAGE' => $this->lang->line('process_success'), 'result' => $notificationlist));
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
    
    
    public function readnotification_post() {

        $postDataArr = $this->post();
        $config = [];

        $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            ), 
            array(
                'field' => 'n_id',
                'label' => 'Notification ID',
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
                $respArr = $this->Common_model->getUserInfo($postDataArr['accesstoken'], ['u.user_id','login_time','login_status','status','first_name','middle_name','last_name','email','prm_user_countrycode','phone','alt_user_countrycode','alt_userphone','company_id','is_owner','user_type','IF(image !="",CONCAT("' . IMAGE_PATH . '","",image),"") as image','IF(image_thumb !="",CONCAT("' . THUMB_IMAGE_PATH . '","",image_thumb),"") as image_thumb']);
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
                
                $whereArr['where'] = ['n_id'=>$user_info['n_id']];                
                $notificationlist =  $this->Common_model->update_single('users_notification_master', ['is_read'=>1], $whereArr);        
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();                                
                    $this->response(array('CODE' => SUCCESS_CODE, 'MESSAGE' => $this->lang->line('process_success'), 'result' => $notificationlist));
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
