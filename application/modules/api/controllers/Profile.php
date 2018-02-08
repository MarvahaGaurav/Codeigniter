<?php

require APPPATH . 'libraries/REST_Controller.php';

class Profile extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->library('form_validation');
    }
    
    /**
     * @SWG\Get(path="/user/profile",
     *   tags={"User"},
     *   summary="Profile Detail",
     *   description="Profile Detail",
     *   operationId="profile_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
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
    public function index_get() {
        $postDataArr = $this->get();
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
        try {
            $this->load->library('commonfn');
            $respArr = $this->Common_model->getUserInfoWithAddress($head['accesstoken'], ['u.user_id', 'u.country_id as u_country_id', 'u.city_id as u_city_id', 'u.zipcode', 'cl.name as u_country_name', 'cyl.name as u_city_name', 'login_time', 'login_status', 'status', 'first_name', 'middle_name', 'last_name', 'email', 'prm_user_countrycode', 'phone', 'alt_user_countrycode', 'alt_userphone', 'company_id', 'is_owner', 'user_type', 'IF(image !="",image,"") as image', 'IF(image_thumb !="",image_thumb,"") as image_thumb']);
            //echo $this->db->last_query(); die;
            $user_info = [];

            /*
                * Response is not success if session expired or invalid access token
                */
            if ($respArr['code'] != SUCCESS_CODE) {
                //die('on');
                $this->response($respArr);
            } else {
                //die('out');
                $user_info = $respArr['userinfo'];
            }
            /*
                * Validate if user is not blocked
                */
            //echo $this->db->last_query();
            //pr($user_info);
            if ($user_info['status'] == BLOCKED) {
                $this->response(array('code' => HTTP_FORBIDDEN, 'msg' => $this->lang->line('account_blocked'), 'result' => (object) []));
            }
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                $whereArr['where'] = ['company_id' => $user_info['company_id']];
                $companyDetail = $this->Common_model->fetch_data('company_master', 'company_id,company_name,company_reg_number,company_image,company_image_thumb', $whereArr, true);
                if ($companyDetail) {
                    $newresArr = array_merge($companyDetail, $user_info);
                } else {
                    $companyDetail['company_name'] = "";
                    $companyDetail['company_reg_number'] = "";
                    $companyDetail['company_image'] = "";
                    $companyDetail['company_image_thumb'] = "";
                    $newresArr = array_merge($companyDetail, $user_info);
                }

                if ($newresArr['user_type'] != '1' && $newresArr['user_type'] != '6' && $newresArr['is_owner'] == '1') {
                    $whereArr['where'] = ['employee_id' => $newresArr['user_id']];
                    $empPermissionDetail = $this->Common_model->fetch_data('user_employee_permission', 'quote_view,quote_add,quote_edit,quote_delete,insp_view,insp_add,insp_edit,insp_delete,project_view,project_add,project_edit,project_delete', $whereArr, true);
                    //pr($empPermissionDetail);
                    if ($empPermissionDetail) {
                        $newresArr = array_merge($newresArr, $empPermissionDetail);
                    } else {
                        $empPermissionDetail['quote_view'] = 0;
                        $empPermissionDetail['quote_add'] = 0;
                        $empPermissionDetail['quote_edit'] = 0;
                        $empPermissionDetail['quote_delete'] = 0;
                        $empPermissionDetail['insp_view'] = 0;
                        $empPermissionDetail['insp_add'] = 0;
                        $empPermissionDetail['insp_edit'] = 0;
                        $empPermissionDetail['insp_delete'] = 0;
                        $empPermissionDetail['project_view'] = 0;
                        $empPermissionDetail['project_add'] = 0;
                        $empPermissionDetail['project_edit'] = 0;
                        $empPermissionDetail['project_delete'] = 0;
                        $empPermissionDetail['pr_id'] = 0;
                        //$empPermissionDetail['user_id'] = 0;
                        $empPermissionDetail['employee_id'] = 0;
                        $newresArr = array_merge($newresArr, $empPermissionDetail);
                    }
                } else {
                    $empPermissionDetail['quote_view'] = 0;
                    $empPermissionDetail['quote_add'] = 0;
                    $empPermissionDetail['quote_edit'] = 0;
                    $empPermissionDetail['quote_delete'] = 0;
                    $empPermissionDetail['insp_view'] = 0;
                    $empPermissionDetail['insp_add'] = 0;
                    $empPermissionDetail['insp_edit'] = 0;
                    $empPermissionDetail['insp_delete'] = 0;
                    $empPermissionDetail['project_view'] = 0;
                    $empPermissionDetail['project_add'] = 0;
                    $empPermissionDetail['project_edit'] = 0;
                    $empPermissionDetail['project_delete'] = 0;
                    $empPermissionDetail['pr_id'] = 0;
                    //$empPermissionDetail['user_id'] = 0;
                    $empPermissionDetail['employee_id'] = 0;
                    $newresArr = array_merge($newresArr, $empPermissionDetail);
                }
                //pr($newresArr);
                $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('process_success'), 'result' => $newresArr));
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $error = $e->getMessage();
            $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $msg, 'result' => (object) []));
        }
    }

    public function companylist_get() {

        $getDataArr = $this->input->get();
        
        $this->load->model('Company_model');
        try {
            /*
             * Get user id with public and private key
             */
            if (isset($getDataArr['accesstoken']) && !empty($getDataArr['accesstoken'])) {
                $respArr = $this->Common_model->getUserInfo($getDataArr['accesstoken'], ['u.user_id', 'status']);
                
                $user_info = [];
                /*
                 * Response is not success if session expired or invalid access token
                 */
                if ($respArr['code'] != SUCCESS_CODE) {
                    $this->response($respArr);
                } else {
                    $user_info = $respArr['userinfo'];
                }
            }
            $user_id = isset($user_info['user_id']) ? $user_info['user_id'] : 0;
            $page = isset($getDataArr['page']) ? $getDataArr['page'] : "";
            $searchlike = isset($getDataArr['searchlike']) ? $getDataArr['searchlike'] : "";

            if (!empty($page)) {
                $limit = 10;
                $offset = ($page - 1) * $limit;
            }
            $params = [];
            $params['user_id'] = $user_id;
            $params['limit'] = isset($limit) ? $limit : "";
            $params['offset'] = isset($offset) ? $offset : "";
            $params['searchlike'] = $searchlike;
            
            $companyList = $this->Company_model->getCompanyList($params);
            /*
             * fetching recieved pending requests
             */

            if (!empty($page)) {
                if (($companyList['count'] > ($page * $limit))) {
                    $page++;
                } else {
                    $page = 0;
                }
            }
            
            if (!empty($companyList)) {
                $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('process_success'), 'next_page' => $page, 'total_rows' => $companyList['count'], 'result' => $companyList['result']));
            } else {
                $this->response(array('code' => NO_DATA_FOUND, 'msg' => $this->lang->line('no_data_found'), 'result' => (object) []));
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response(array('code' => $code, 'msg' => $msg, 'result' => (object) []));
        }
    }

    /**
     * @SWG\PUT(path="/user/profile",
     *   tags={"User"},
     *   summary="Profile Update",
     *   description="Profile Update",
     *   operationId="profile_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access Token",
     *     required=true,
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="first_name",
     *     in="formData",
     *     description="First name",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="prm_user_countrycode",
     *     in="formData",
     *     description="Prm user countrycode",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="phone",
     *     in="formData",
     *     description="Phone",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="alt_user_countrycode",
     *     in="formData",
     *     description="Alt user countrycode",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="alt_userphone",
     *     in="formData",
     *     description="Alt userphone",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="image",
     *     in="formData",
     *     description="Image",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="image_thumb",
     *     in="formData",
     *     description="Image thumb",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="country",
     *     in="formData",
     *     description="Country",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="country",
     *     in="formData",
     *     description="Country",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="state",
     *     in="formData",
     *     description="State",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="state",
     *     in="formData",
     *     description="State",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="city",
     *     in="formData",
     *     description="City",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="city",
     *     in="formData",
     *     description="City",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="zipcode",
     *     in="formData",
     *     description="Zipcode",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="zipcode",
     *     in="formData",
     *     description="Zipcode",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="company_id",
     *     in="formData",
     *     description="Company id",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="company_name",
     *     in="formData",
     *     description="Company name",
     *     type="string"
     *   ),  
     *   @SWG\Parameter(
     *     name="company_image",
     *     in="formData",
     *     description="Company image",
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
    public function profileupdate_put() {
        $postDataArr = $this->put();
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
        try {
            $this->load->library('commonfn');
            $respArr = $this->Common_model->getUserInfoWithAddress($head['accesstoken'], ['u.user_id', 'u.country_id as u_country_id', 'u.city_id as u_city_id', 'u.zipcode', 'cl.name as u_country_name', 'cyl.name as u_city_name', 'login_time', 'login_status', 'status', 'first_name', 'middle_name', 'last_name', 'email', 'prm_user_countrycode', 'phone', 'alt_user_countrycode', 'alt_userphone', 'company_id', 'is_owner', 'user_type', 'IF(image !="",CONCAT("' . IMAGE_PATH . '","",image),"") as image', 'IF(image_thumb !="",CONCAT("' . THUMB_IMAGE_PATH . '","",image_thumb),"") as image_thumb']);
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
            if ($user_info['status'] == BLOCKED) {
                $this->response(array('code' => HTTP_FORBIDDEN, 'msg' => $this->lang->line('account_blocked'), 'result' => (object) []));
            }
            //pr($user_info);
            if (isset($postDataArr['first_name']) && !empty($postDataArr['first_name'])) {
                $updateArr['first_name'] = $postDataArr['first_name'];
            }
            if (isset($postDataArr['prm_user_countrycode']) && !empty($postDataArr['prm_user_countrycode'])) {
                $updateArr['prm_user_countrycode'] = $postDataArr['prm_user_countrycode'];
            }
            if (isset($postDataArr['phone']) && !empty($postDataArr['phone'])) {
                $updateArr['phone'] = $postDataArr['phone'];
            }
            if (isset($postDataArr['alt_user_countrycode']) && !empty($postDataArr['alt_user_countrycode'])) {
                $updateArr['alt_user_countrycode'] = $postDataArr['alt_user_countrycode'];
            }
            if (isset($postDataArr['alt_userphone']) && !empty($postDataArr['alt_userphone'])) {
                $updateArr['alt_userphone'] = $postDataArr['alt_userphone'];
            }
            if (isset($postDataArr['image']) && !empty($postDataArr['image'])) {
                $updateArr['image'] = $postDataArr['image'];
            }
            if (isset($postDataArr['image_thumb']) && !empty($postDataArr['image_thumb'])) {
                $updateArr['image_thumb'] = $postDataArr['image_thumb'];
            }
            if (isset($postDataArr['country']) && !empty($postDataArr['country'])) {
                $updateArr['country_id'] = $postDataArr['country'];
                $compupdateArr['country'] = $postDataArr['country'];
            }
            if (isset($postDataArr['state']) && !empty($postDataArr['state'])) {
                $updateArr['state_id'] = $postDataArr['state'];
                $compupdateArr['state'] = $postDataArr['state'];
            }
            if (isset($postDataArr['city']) && !empty($postDataArr['city'])) {
                $updateArr['city_id'] = $postDataArr['city'];
                $compupdateArr['city'] = $postDataArr['city'];
            }
            if (isset($postDataArr['prm_country_code']) && !empty($postDataArr['prm_country_code'])) {
                $updateArr['prm_user_countrycode'] = $postDataArr['prm_country_code'];
            }
            if (isset($postDataArr['zipcode']) && !empty($postDataArr['zipcode'])) {
                $updateArr['zipcode'] = $postDataArr['zipcode'];
                $compupdateArr['zipcode'] = $postDataArr['zipcode'];
            }

            if (isset($postDataArr['company_id']) && !empty($postDataArr['company_id'])) {
                $company_id = $postDataArr['company_id'];

                if (isset($postDataArr['company_name']) && !empty($postDataArr['company_name'])) {
                    $compupdateArr['company_name'] = $postDataArr['company_name'];
                }
                if (isset($postDataArr['company_image']) && !empty($postDataArr['company_image'])) {
                    $compupdateArr['company_image'] = $postDataArr['company_image'];
                }
                
                // pr($compupdateArr);
                if ($compupdateArr) {
                    $comwhereArr['where'] = ['company_id' => $postDataArr['company_id']];
                    $updaterequesr = $this->Common_model->update_single('company_master', $compupdateArr, $comwhereArr);
                }
            }

            $whereArr['where'] = ['user_id' => $user_info['user_id']];
            $updaterequesr = $this->Common_model->update_single('ai_user', $updateArr, $whereArr);
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();

                $userdata = $this->Common_model->getUserInfoWithAddress($head['accesstoken'], ['u.user_id', 'u.country_id as u_country_id', 'u.city_id as u_city_id', 'u.zipcode', 'cl.name as u_country_name', 'cyl.name as u_city_name', 'login_time', 'login_status', 'status', 'first_name', 'middle_name', 'last_name', 'email', 'prm_user_countrycode', 'phone', 'alt_user_countrycode', 'alt_userphone', 'company_id', 'is_owner', 'user_type', 'IF(image !="",image,"") as image', 'IF(image_thumb !="",image_thumb,"") as image_thumb']);
                unset($userdata['code']);
                //$whereArr['where'] = ['company_id'=>$user_info['company_id']];
                //$companyDetail =  $this->Common_model->fetch_data('company_master', 'company_id,company_name,company_reg_number,company_image,company_image_thumb', $whereArr, true);
                //$newresArr = array_merge($companyDetail,$userdata);
                //pr($userdata);
                $whereArr['where'] = ['company_id' => $user_info['company_id']];
                $companyDetail = $this->Common_model->fetch_data('company_master', 'company_id,company_name,company_reg_number,company_image,company_image_thumb', $whereArr, true);
                if ($companyDetail) {
                    $newresArr = array_merge($companyDetail, $userdata['userinfo']);
                } else {
                    $companyDetail['company_name'] = "";
                    $companyDetail['company_reg_number'] = "";
                    $companyDetail['company_image'] = "";
                    $companyDetail['company_image_thumb'] = "";
                    $newresArr = array_merge($companyDetail, $userdata['userinfo']);
                    //$newresArr =$user_info;
                }

                if ($newresArr['user_type'] != '1' && $newresArr['user_type'] != '6' && $newresArr['is_owner'] == '1') {
                    $whereArr['where'] = ['employee_id' => $newresArr['user_id']];
                    $empPermissionDetail = $this->Common_model->fetch_data('user_employee_permission', 'quote_view,quote_add,quote_edit,quote_delete,insp_view,insp_add,insp_edit,insp_delete,project_view,project_add,project_edit,project_delete', $whereArr, true);
                    //pr($empPermissionDetail);
                    if ($empPermissionDetail) {
                        $newresArr = array_merge($newresArr, $empPermissionDetail);
                    } else {
                        $empPermissionDetail['quote_view'] = 0;
                        $empPermissionDetail['quote_add'] = 0;
                        $empPermissionDetail['quote_edit'] = 0;
                        $empPermissionDetail['quote_delete'] = 0;
                        $empPermissionDetail['insp_view'] = 0;
                        $empPermissionDetail['insp_add'] = 0;
                        $empPermissionDetail['insp_edit'] = 0;
                        $empPermissionDetail['insp_delete'] = 0;
                        $empPermissionDetail['project_view'] = 0;
                        $empPermissionDetail['project_add'] = 0;
                        $empPermissionDetail['project_edit'] = 0;
                        $empPermissionDetail['project_delete'] = 0;
                        $empPermissionDetail['pr_id'] = 0;
                        //$empPermissionDetail['user_id'] = 0;
                        $empPermissionDetail['employee_id'] = 0;
                        $newresArr = array_merge($newresArr, $empPermissionDetail);
                    }
                } else {
                    $empPermissionDetail['quote_view'] = 0;
                    $empPermissionDetail['quote_add'] = 0;
                    $empPermissionDetail['quote_edit'] = 0;
                    $empPermissionDetail['quote_delete'] = 0;
                    $empPermissionDetail['insp_view'] = 0;
                    $empPermissionDetail['insp_add'] = 0;
                    $empPermissionDetail['insp_edit'] = 0;
                    $empPermissionDetail['insp_delete'] = 0;
                    $empPermissionDetail['project_view'] = 0;
                    $empPermissionDetail['project_add'] = 0;
                    $empPermissionDetail['project_edit'] = 0;
                    $empPermissionDetail['project_delete'] = 0;
                    $empPermissionDetail['pr_id'] = 0;
                    //$empPermissionDetail['user_id'] = 0;
                    $empPermissionDetail['employee_id'] = 0;
                    $newresArr = array_merge($newresArr, $empPermissionDetail);
                }

                //pr($newresArr);
                $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('profile_update_success'), 'result' => $newresArr));
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $error = $e->getMessage();
            $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $msg, 'result' => (object) []));
        }
    } 

}
