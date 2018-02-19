<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Signup extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->load->library('commonfn');
    }

    /**
     * @SWG\Post(path="/user/signup",
     *   tags={"User"},
     *   summary="Singup Information",
     *   description="Singup Information",
     *   operationId="signup_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="first_name",
     *     in="formData",
     *     description="Architech",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="last_name",
     *     in="formData",
     *     description="employe",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="architectemployee@yopmail.com",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="123456",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="device_id",
     *     in="formData",
     *     description="6516516265265",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="device_token",
     *     in="formData",
     *     description="sdvdsdsfsdadc wv zsd zv56z5s ad ad ad35165a1 6as asv5as1v6asd5 1",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="Platform",
     *     in="formData",
     *     description="1 //1=Android, 2=IOS, 3-Web",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="User_type",
     *     in="formData",
     *     description="3 //1=Private User, 2=Technician, 3=Architect, 4=Electrical Planner, 5=Wholesaler,6=Business User",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="Is_owner",
     *     in="formData",
     *     description="1 //0=user, 1 =  employee , 2 = owner",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="profile_image",
     *     in="formData",
     *     description="image",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="company_name",
     *     in="formData",
     *     description="Architech Company",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="company_reg_number",
     *     in="formData",
     *     description="IT51651651",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="company_image",
     *     in="formData",
     *     description="company image",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="prm_user_countrycode",
     *     in="formData",
     *     description="91",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="phone",
     *     in="formData",
     *     description="9015417310",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="alt_user_countrycode",
     *     in="formData",
     *     description="91",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="alt_userphone",
     *     in="formData",
     *     description="9654379323",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="country",
     *     in="formData",
     *     description="DK, NL, SW, NO, etc..",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="state",
     *     in="formData",
     *     description="2184",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="city",
     *     in="formData",
     *     description="1210455",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="zipcode",
     *     in="formData",
     *     description="22215",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="company_id",
     *     in="formData",
     *     description="4",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Signup Success"),
     *   @SWG\Response(response=206, description="Unauthorized request"),     
     *   @SWG\Response(response=207, description="Header is missing"),     
     *   @SWG\Response(response=421, description="File Upload Failed"),     
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function index_post() {

        $postDataArr = $this->post();

        /*
         *   Singup form Validation
         */
        $required_fields_arr = array(
            array(
                'field' => 'first_name',
                'label' => 'First Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'device_id',
                'label' => 'Device Id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'user_type',
                'label' => 'User Type',
                'rules' => 'trim|required'
            )
        );
        
        if($postDataArr['user_type']!='1'){
            $required_fields_arr[] = array('field' => 'is_owner','label' => 'Owner','rules' => 'trim|required');
       
            if($postDataArr['is_owner'] == '2'){
                $required_fields_arr[] = array('field' => 'company_name','label' => 'Company Name','rules' => 'trim|required');
                $required_fields_arr[] = array('field' => 'company_reg_number','label' => 'Company Registration Number','rules' => 'trim|required');
                //$required_fields_arr[] = array('field' => 'company_address','label' => 'Company Address','rules' => 'trim|required');
                //$required_fields_arr[] = array('field' => 'latitude','label' => 'Latitude','rules' => 'trim|required');
                //$required_fields_arr[] = array('field' => 'longitude','label' => 'Longitude','rules' => 'trim|required');
                $required_fields_arr[] = array('field' => 'country','label' => 'Country','rules' => 'trim|required');
                // $required_fields_arr[] = array('field' => 'state','label' => 'State','rules' => 'trim|required');
                $required_fields_arr[] = array('field' => 'city','label' => 'City','rules' => 'trim|required');
                $required_fields_arr[] = array('field' => 'zipcode','label' => 'Zipcode','rules' => 'trim|required');
            }/*else{
                $required_fields_arr[] = array('field' => 'company_id','label' => 'Company','rules' => 'trim|required');
            }*/
        }
        
        //print_r($required_fields_arr);die;
        /*
         * Validate phone number
         */
        /*if (isset($postDataArr['phone']) && !empty($postDataArr['phone'])) {
            $this->validate_phone($postDataArr['phone']);
        }*/
        /*
         * Validate dob
         */
        /*if (isset($postDataArr['dob']) && !empty($postDataArr['dob'])) {
            $this->validate_dob($postDataArr['dob']);
        }*/

        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_rules($required_fields_arr);
        $this->form_validation->set_message('is_unique', 'The %s is already registered with us');
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {
            //print_r($required_fields_arr);die;
            try {
                /*
                 *  Check if email if already registered and is it blocked
                 */
                $whereArr = [];
                $whereArr['where'] = ['email' => $postDataArr['email']];
                $user_info = $this->Common_model->fetch_data('ai_user', ['email', 'status'], $whereArr, true);
                if (!empty($user_info) && $user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                } else if (!empty($user_info) && $user_info['status'] == 1) {
                    $this->response(array('code' => EMAIL_ALREADY_EXIST, 'msg' => $this->lang->line('account_exist'), 'result' => (object)[]));
                }
                $signupArr = [];
                $signupArr["first_name"] = trim($postDataArr["first_name"]);
                $signupArr["middle_name"] = isset($postDataArr["middle_name"]) ? trim($postDataArr["middle_name"]) : "";
                $signupArr["last_name"] = isset($postDataArr["last_name"]) ? trim($postDataArr["last_name"]) : "";
                $signupArr["email"] = $postDataArr["email"];
                $signupArr['language'] = isset($postDataArr['language'])&&preg_match('/^(en|da|nb|sv|fi|fr|nl|de)$/', $postDataArr['language'])?$postDataArr['language']:"en";
                //$signupArr["gender"] = isset($postDataArr["gender"]) ? trim($postDataArr["gender"]) : 0;
                //$signupArr["dob"] = isset($postDataArr["dob"]) ? date('Y-m-d', strtotime($postDataArr["dob"])) : "";
                //$signupArr["age"] = isset($postDataArr["age"]) ? trim($postDataArr["age"]) : "";
                $signupArr["prm_user_countrycode"] = isset($postDataArr["prm_user_countrycode"]) ? trim($postDataArr["prm_user_countrycode"]) : "";
                $signupArr["phone"] = isset($postDataArr["phone"]) ? trim($postDataArr["phone"]) : "";
                $signupArr["alt_user_countrycode"] = isset($postDataArr["alt_user_countrycode"]) ? trim($postDataArr["alt_user_countrycode"]) : "";
                $signupArr["alt_userphone"] = isset($postDataArr["alt_userphone"]) ? trim($postDataArr["alt_userphone"]) : "";
                //$signupArr["address"] = isset($postDataArr["address"]) ? trim($postDataArr["address"]) : "";
                //$signupArr["user_lat"] = isset($postDataArr["user_lat"]) ? $postDataArr["user_lat"] : "";
                //$signupArr["user_long"] = isset($postDataArr["user_long"]) ? $postDataArr["user_long"] : "";
                $signupArr["country_id"] = isset($postDataArr['country']) ? $postDataArr['country'] : "";
                // $signupArr["state_id"] = isset($postDataArr['state']) ? $postDataArr['state'] : "";
                $signupArr["city_id"] = isset($postDataArr['city']) ? $postDataArr['city'] : "";
                $signupArr["zipcode"] = isset($postDataArr['zipcode']) ? $postDataArr['zipcode'] : "";
                $signupArr["password"] = encrypt($postDataArr["password"]);
                $signupArr["registered_date"] = date('Y-m-d H:i:s');
                $signupArr["image"] = isset($postDataArr['profile_image']) ? $postDataArr['profile_image'] : "";;
                $signupArr["image_thumb"] = isset($postDataArr['profile_image_thumb']) ? $postDataArr['profile_image_thumb'] : "";;

                /*
                 *  upload profile pic option 
                 */
                /*if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image'])) {
                    $config = [];
                    $config = getConfig(UPLOAD_IMAGE_PATH, 'jpeg|jpg|png', 3000, 1024, 768);
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('profile_image')) {
                        $upload_data = $this->upload->data();
                        $imageName = $upload_data['file_name'];
                        $thumbFileName = $upload_data['file_name'];
                        $fileSource = UPLOAD_IMAGE_PATH . $thumbFileName;
                        $targetPath = UPLOAD_THUMB_IMAGE_PATH;
                        $isSuccess = $this->commonfn->thumb_create($thumbFileName, $fileSource, $targetPath);
                        if ($isSuccess) {
                            $thumbName = $imageName;
                        }
                    } else {
                        $this->response(array('code' => ERROR_UPLOAD_FILE, 'msg' => strip_tags($this->upload->display_errors()), 'result' => $signupArr));
                    }
                    $signupArr["image"] = $imageName;
                    $signupArr["image_thumb"] = $thumbName;
                }*/
                
                $this->db->trans_begin();
                                
                $signupArr["is_owner"] = $postDataArr['is_owner'];
                $signupArr["user_type"] = $postDataArr['user_type'];

                if($postDataArr['is_owner'] == '2'){
                    $companyArr['company_name'] = isset($postDataArr['company_name']) ? $postDataArr['company_name'] : "";
                    $companyArr['company_reg_number'] = isset($postDataArr['company_reg_number']) ? $postDataArr['company_reg_number'] : "";
                    //$companyArr['prm_country_code'] = isset($postDataArr['prm_country_code']) ? $postDataArr['prm_country_code'] : "";
                    //$companyArr['prm_contact_number'] = isset($postDataArr['prm_contact_number']) ? $postDataArr['prm_contact_number'] : "";
                    //$companyArr['alt_country_code'] = isset($postDataArr['alt_country_code']) ? $postDataArr['alt_country_code'] : "";
                    //$companyArr['alt_contact_number'] = isset($postDataArr['alt_contact_number']) ? $postDataArr['alt_contact_number'] : "";
                    //$companyArr['company_address'] = isset($postDataArr['company_address']) ? $postDataArr['company_address'] : "";
                    //$companyArr['latitude'] = isset($postDataArr['latitude']) ? $postDataArr['latitude'] : "";
                    //$companyArr['longitude'] = isset($postDataArr['longitude']) ? $postDataArr['longitude'] : "";
                    $companyArr['country'] = isset($postDataArr['country']) ? $postDataArr['country'] : "";
                    // $companyArr['state'] = isset($postDataArr['state']) ? $postDataArr['state'] : "";
                    $companyArr['city'] = isset($postDataArr['city']) ? $postDataArr['city'] : "";
                    $companyArr['zipcode'] = isset($postDataArr['zipcode']) ? $postDataArr['zipcode'] : "";
                    $companyArr['company_image'] = isset($postDataArr['company_image']) ? $postDataArr['company_image'] : "";
                    $companyArr['company_image_thumb'] = isset($postDataArr['company_image_thumb']) ? $postDataArr['company_image_thumb'] : "";
                    $companyArr["owner_type"] = $postDataArr['user_type'];
                    $companyArr['insert_date'] = date('Y-m-d H:i:s');

                    /*if (isset($_FILES['company_image']) && !empty($_FILES['company_image'])) {                        
                        $config = [];
                        $config = getConfig(UPLOAD_IMAGE_PATH, 'jpeg|jpg|png', 3000, 1024, 768);
                        $this->load->library('upload', $config);
                        if ($this->upload->do_upload('company_image')) {
                            $upload_data = $this->upload->data();
                            $companuyImageName = $upload_data['file_name'];
                            $companuyThumbFileName = $upload_data['file_name'];
                            $fileSource = UPLOAD_IMAGE_PATH . $companuyThumbFileName;
                            $targetPath = UPLOAD_THUMB_IMAGE_PATH;
                            $isSuccess = $this->commonfn->thumb_create($companuyThumbFileName, $fileSource, $targetPath);
                            if ($isSuccess) {
                                $compthumbName = $companuyImageName;
                            }
                        } else {
                            $this->response(array('code' => ERROR_UPLOAD_FILE, 'msg' => strip_tags($this->upload->display_errors()), 'result' => $signupArr));
                        }
                        $companyArr["company_image"] = $companuyImageName;
                        $companyArr["company_image_thumb"] = $compthumbName;
                    }*/
                    $companyId = $this->Common_model->insert_single('company_master', $companyArr);
                    if($companyId){
                        $signupArr["company_id"] = $companyId;
                    }else{
                        throw new Exception($this->lang->line('try_again'));
                    }
                }else{                    
                    $signupArr["company_id"] = isset($postDataArr['company_id']) ? $postDataArr['company_id'] : ""; 
                }
                //print_r($companyArr); die;
                
                //print_r($signupArr);die;
                $userId = $this->Common_model->insert_single('ai_user', $signupArr);
                if (!$userId) {
                    throw new Exception($this->lang->line('try_again'));
                }
                $postDataArr['user_id'] = $userId;
                
                if($postDataArr['is_owner'] == '1'){
                    $whereArr['where'] = ['is_owner' => 2, 'company_id' => $signupArr['company_id']];
                    $companyowner_info = $this->Common_model->fetch_data('ai_user', ['user_id'], $whereArr, true);
                    if($companyowner_info){
                        // adding in notification master table
                        $requestedbyname = $postDataArr['first_name'].' '.$postDataArr['middle_name'].' '.$postDataArr['last_name'];
                        $msg = "" . $requestedbyname . " has requested to join your company as employee.";
                        $notifArr['sender_id'] = $userId;
                        $notifArr['reciever_id'] = $companyowner_info['user_id'];
                        $notifArr['message'] = $msg;
                        $notifArr['msg_type'] = 1;
                        $notifArr['user_type'] = $postDataArr['user_type'];
                        $notificationId = $this->Common_model->insert_single('users_notification_master', $notifArr);

                        // adding in employee request tabel
                        $requestfArr['requested_by'] = $userId;
                        $requestfArr['requested_to'] = $companyowner_info['user_id'];
                        $requestfArr['request_time'] = date('Y-m-d H:i:s');
                        $requestfArr['company_id'] = $signupArr["company_id"];
                        $requestid = $this->Common_model->insert_single('employee_request_master', $requestfArr);
                    }
                }
                /*
                 * Generate Public and Private Access Token
                 */
                $accessToken = create_access_token($userId, $signupArr['email']);

                $signupArr['accesstoken'] = $accessToken['public_key'] . '||' . $accessToken['private_key'];

                $signupArr["image"] = isset($signupArr['image']) ? $signupArr['image'] : "";
                $signupArr["image_thumb"] = isset($signupArr['image_thumb']) ? $signupArr['image_thumb'] : "";
                /*
                 * 
                 */
                $sessionArr = setSessionVariables($postDataArr, $accessToken);

                /*
                 * Insert Session Data
                 */
                $whereArr = [];
                $device_id = isset($postDataArr['device_id']) ? $postDataArr['device_id'] : "";
                $whereArr['where'] = ['device_id' => $device_id];
                $isExist = $this->Common_model->fetch_data('ai_session', array('session_id'), $whereArr, true);
                /*
                 * If user has logged in previously with same device then update his detail
                 * or insert as a new row
                 */
                $sessionArr['login_status'] = 1;
                if (!empty($isExist)) {
                    $sessionId = $this->Common_model->update_single('ai_session', $sessionArr, $whereArr);
                } else {
                    $sessionId = $this->Common_model->insert_single('ai_session', $sessionArr);
                }
                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                    if($postDataArr['is_owner'] == '1'){
                        $whereArr['where'] = ['is_owner' => 2, 'company_id' => $postDataArr['company_id']];
                        $companyowner_info = $this->Common_model->fetch_data('ai_user', ['user_id'], $whereArr, true);
                        if($companyowner_info){
                            /*
                            * Create Android Payload
                            */
                            $requestedbyname = $postDataArr['first_name'].' '.$postDataArr['middle_name'].' '.$postDataArr['last_name'];
                            $msg = "" . $requestedbyname . " has requested to join your company as employee.";
                            $androidPayload = [];
                            $androidPayload['message'] = $msg;
                            $androidPayload['user_id'] = $userId;
                            $androidPayload['type'] = 1;
                            $androidPayload['time'] = time();
                            /*
                            * Create Ios Payload
                            */
                            $iosPayload = [];
                            $iosPayload['alert'] = array('title' => $msg, 'user_id' => $userId);
                            $iosPayload['badge'] = 0;
                            $iosPayload['type'] = 1;
                            $iosPayload['sound'] = 'beep.mp3';
                            
                            $pushData = [];
                            $pushData['receiver_id'] = $companyowner_info['user_id'];
                            $pushData['androidPayload'] = $androidPayload;
                            $pushData['iosPayload'] = $iosPayload;

                            $this->load->library('commonfn');
                            //$this->commonfn->sendPush($pushData);
                            
                           
                        }
                    }
                    
                    if (!empty($sessionId) && !empty($userId)) {
                        unset($signupArr['password']);
                        $signupArr['full_name'] = $signupArr['first_name'];
                        unset($signupArr['first_name']);
                        unset($signupArr['middle_name']);
                        unset($signupArr['last_name']);
                        $mailData = [];
                        $mailData['name'] = $postDataArr['full_name'];
                        $mailData['email'] = $postDataArr['email'];
                        $this->sendWelcomeMail($mailData);
                        $signupArr['quote_view'] = 0;
                        $signupArr['quote_add'] = 0;
                        $signupArr['quote_edit'] = 0;
                        $signupArr['quote_delete'] = 0;
                        $signupArr['insp_view'] = 0;
                        $signupArr['insp_add'] = 0;
                        $signupArr['insp_edit'] = 0;
                        $signupArr['insp_delete'] = 0;
                        $signupArr['project_view'] = 0;
                        $signupArr['project_add'] = 0;
                        $signupArr['project_edit'] = 0;
                        $signupArr['project_delete'] = 0;

                        if ( ROLE_EMPLOYEE === (int)$signupArr["is_owner"] ) {
                            $this->load->library("PushNotification");
                            $this->load->model("UtilModel");
                            
                            $user_data = $this->UtilModel->selectQuery(
                                "device_token, platform, ai_user.user_id",
                                "ai_user",
                                [
                                    "where" => ["ai_user.company_id" => $signupArr["company_id"], "is_owner" => ROLE_OWNER, "ai_user.status" => 1],
                                    "join" => ["ai_session" => "ai_user.user_id=ai_session.user_id"]
                                ]
                            );
                            $ios_user_data = array_filter($user_data, function($data){
                                return IPHONE === (int)$data["platform"]?true:false;
                            });
                            $android_user_data = array_filter($user_data, function($data){
                                return ANDROID === (int)$data["platform"]?true:false;
                            });

                            $android_tokens = array_map(function($data){
                                return $data['device_token'];
                            }, $android_user_data);

                            if ( $android_tokens ) {
                                $android_payload_data = [
                                    'badge' => 1,
                                    'sound' => 'default',
                                    'status' => 1,
                                    'type' => "new_employee_request",
                                    'message' => "{$signupArr['full_name']} has requested your approval",
                                    'time' => strtotime('now')
                                ];
                                $this->pushnotification->androidMultiplePush($android_tokens, $android_payload_data);
                            }
                            if ( $ios_user_data ) {
                                $ios_payload_data = [
                                    'badge' => 1,
                                    'alert' => "New Employee Request",
                                    'sound' => 'default',
                                    'status' => 1,
                                    'type' => "new_employee_request",
                                    'message' => "{$signupArr['full_name']} has requested your approval",
                                    'time' => strtotime('now')
                                ];

                                $this->pushnotification->sendMultipleIphonePush($ios_user_data, $ios_payload_data);
                            }
                        }
                        $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('registration_success'), 'result' => $signupArr));
                    }
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $error, 'result' => (object)[]));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }

    
    
    
    public function sendWelcomeMail($mailData) {

        $this->load->helper('url');
        $data = [];
        $data['url'] = base_url() . 'request/welcomeMail?email=' . $mailData['email'] . '&name=' . urlencode($mailData['name']);
        sendGetRequest($data);
    }

    /*
     * Custom Rule Validate Phone
     * @param: Phone number
     */

    public function validate_phone($phone) {

        if (isset($phone) && !preg_match("/^[0-9]{10}$/", $phone) && !empty($phone)) {
            $this->response(array('code' => PARAM_REQ, 'msg' => $this->lang->line('invalid_phone'), 'result' => (object)[]));
        } else {
            return true;
        }
    }

    /*
     * Custom Rule Validate Dob
     * @param: user dob
     */

    public function validate_dob($dob) {
        if (!(isValidDate($dob, 'm-d-Y'))) {
            $this->response(array('code' => PARAM_REQ, 'msg' => $this->lang->line('invalid_dob'), 'result' => (object)[]));
        } else {
            return true;
        }
    }

}
