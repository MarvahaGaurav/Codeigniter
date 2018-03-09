<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "BaseController.php";

class Index extends BaseController {
    public function __construct() {		
        parent::__construct();
        $this->load->library('form_validation');      
        //$this->load->helper("images");
        //$this->load->helper("s3");
        $this->inactive_session_required();       
    }

    /*
     * @function:index
     * @param:username:email
     * @param:password:password
     * @description:if email and password are correct then he can login
     */

    public function index() {		
        $data = [];
        
        // if form is posted ..
        if ($this->input->post()) {
            
            $postDataArr = $this->input->post();
            // echo '<pre>'; print_r($postDataArr);die('safs');
            $this->session->set_flashdata("flash-message", '');
            $this->session->set_flashdata("flash-type", "");
                    
            $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required');
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required');
            
            if ($this->form_validation->run() == FALSE) {
                load_outerweb_views('/index/login', $data);
            } else {
                $email = $postDataArr['email'];
                $password = $postDataArr['password'];
                //$pass = hash('sha256', $password);
                $pass = encrypt($postDataArr["password"]);

                /*
                 * Matched the Credentials
                 */
                try {
                    $sg_userinfo = $this->Common_model->fetch_data('ai_user', array('user_id', 'first_name', 'email', 'status'), array('where' => array('email' => $email, 'password' => $pass, 'status' => 1)), true);
                    //echo $this->db->last_query(); die;
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                /*
                 * If credentials are matched set the session
                 */
                if (!empty($sg_userinfo)) {
                    
                    //$sg_userinfo = array();
                    $sg_userinfo = array(
                        "user_id" => $sg_userinfo['user_id'],
                        "first_name" => $sg_userinfo['first_name'],
                        "email" => $sg_userinfo['email'],
                        'status' => $sg_userinfo['status']
                    );
                    //pr($sg_userinfo);
                    //SETS COOKIE DATA
                    if (isset($postDataArr["remember_me"]) && $postDataArr["remember_me"] == "on") {
                        $this->load->helper(["cookie", "string"]);
                        $cookieData["cookie_validator"] = random_string('alnum', 12);
                        $cookieData["cookie_selector"] = hash("sha256", date("Y-m-d H:i:s") . $postDataArr["email"]);

                        $cookieExpiryTime = time() + COOKIE_EXPIRY_TIME;

                        set_cookie(
                                "sg_user", "{$cookieData['cookie_selector']}:{$cookieData['cookie_validator']}", $cookieExpiryTime
                        );

                        //$cookieData["cookie_validator"] = hash("sha256", $cookieData["cookie_validator"] . $sg_userinfo["create_date"]);

                       // $this->Common_model->update_single("ai_user", $cookieData, ["where" => ["user_id" => $sg_userinfo["user_id"]]]);
                    }
                    //$this->session->set_flashdata("greetings", "Welcome!");
                    //$this->session->set_flashdata("message", "You have successfully logged in");
                    $this->session->set_flashdata("flash-message", 'You have successfully logged in');
                    $this->session->set_flashdata("flash-type", "success");
                                
                    $this->session->set_userdata('sg_userinfo', $sg_userinfo);
                    redirect(base_url("home/projects"));
                } else {
                    $data['email'] = $email;
                    $data['password'] = $password;
                    //$data['error'] = $this->lang->line('invalid_email_password');
                    $this->session->set_flashdata("flash-message", $this->lang->line('invalid_email_password'));
                    $this->session->set_flashdata("flash-type", "danger");
                    //load_outerweb_views('/index/login', $data);
                }
            }
        }
        load_outerweb_views('/index/login', $data);
    }

    /*
     * @function:forget
     * @param:email:web email
     * @param:name:web name
     * @description:if web forget the password then he can reset his password
     */

    public function forgot() {
        try {
            $data = array();
            $this->load->library("commonfn");
            $this->session->set_flashdata("flash-message", '');
            $this->session->set_flashdata("flash-type", "");
            $data['additional_js'] = [
                base_url("public/js/web/forgot-password.js")
            ];
            if ($this->input->post()) {

                $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    load_outerweb_views('/index/forgotpassword', $data);
                } else {
                    
                    $dataArr = $this->input->post();                  
                    $sg_userinfo = $this->Common_model->fetch_data('ai_user', '*', array('where' => array('email' => trim($dataArr['email']))), true);
                    //echo '<pre>'; echo $this->db->last_query(); //print_r($sg_userinfo); die;
                    if (!empty($sg_userinfo) && is_array($sg_userinfo)) {

                        $name = $sg_userinfo['first_name'];
                        $email = $sg_userinfo['email'];

                        $subject = "RESET PASSWORD";
                        $reset_token = hash('sha256', date("Y-m-d h:i:s"));
                        //$timeexpire = time() + (24 * 60 * 60);
                        $timeexpire = date("Y-m-d H:i:s",strtotime('+24 hour'));

                        $dataArr['name'] = $name;
                        $insert['reset_token'] = $reset_token;
                        $insert['isreset_link_sent'] = 1;
                        $insert['reset_link_time'] = $timeexpire;
                        $condition = ['email' => $email];
                        $where = array("where" => array('email' => $email));
                        //echo '<pre>'; print_r($insert); die;
                        $update = $this->Common_model->update_single('ai_user', $insert, $where);

                        $mailinfoarr = [];
                        $mailinfoarr['link'] = base_url() . 'web/index/resetpassword?token=' . $reset_token;
                        $mailinfoarr['email'] = $email;
                        $mailinfoarr['subject'] = $subject;
                        $mailinfoarr['name'] = $name;
                        $mailinfoarr['mailerName'] = 'reset';

                        $isSuccess = $this->commonfn->sendEmailToUser($mailinfoarr);

                        if ($isSuccess) {
                            //$this->session->set_flashdata('Success', $this->lang->line('success_prefix') . $this->lang->line('reset_email') . $this->lang->line('success_suffix'));
                            $this->session->set_flashdata("flash-message", $this->lang->line('reset_email'));
                            $this->session->set_flashdata("flash-type", "success");
                            
                            //$data['success'] = $this->lang->line('success_prefix') . $this->lang->line('reset_email') . $this->lang->line('success_suffix');
                            //redirect(base_url() . 'web/index/forgot');
                            redirect('/web/index/forgotsuccess');
                        } else {
                            load_outerweb_views('/index/forgotpassword', $data);
                        }
                    } else {
                        $this->session->set_flashdata("flash-message", $this->lang->line('invalid_email'));
                        $this->session->set_flashdata("flash-type", "danger");   
                        //$data['error'] = $this->lang->line('invalid_email');
                        load_outerweb_views('/index/forgotpassword', $data);
                    }
                }
            } else {
                load_outerweb_views('/index/forgotpassword', $data);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

   
     /*
     * @function:signup
     * @param: all user information 
     * @description: saving all user information 
     */

    public function signup() {	        
        try {
            $data = array();
            $this->load->library("commonfn");
            $this->session->set_flashdata("flash-message", '');
            $this->session->set_flashdata("flash-type", "");
            
            $this->load->helper("location");
            $data['countries'] = $countries = fetch_countries();
            $data['companies'] = $this->Common_model->fetch_data('company_master', '*', array());
        
            if ($this->input->post()) {
                
                $dataArr = $this->input->post();
                
                $this->form_validation->set_rules('user_type', 'User type', 'trim|required');
                $this->form_validation->set_rules('fullname', 'Full name', 'trim|required');
                //$this->form_validation->set_rules('email', 'Email', 'trim|required|callback_valid_email('.$dataArr['email'].')');
                $this->form_validation->set_rules('email', 'Email', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[30]|alpha_numeric');
                $this->form_validation->set_rules('cnfpassword', 'Confirm Password', 'trim|required|matches[password]|min_length[8]|max_length[30]|alpha_numeric');                                

                $this->form_validation->set_rules('country', 'Country', 'trim|required');
                $this->form_validation->set_rules('cities', 'City', 'trim|required');
                $this->form_validation->set_rules('zipcode', 'Zip Code', 'trim|required|min_length[3]|max_length[7]');
                
                if($dataArr['user_type'] != '1'){
                    if($dataArr['isowner'] == '2'){
                        $this->form_validation->set_rules('comp_reg_number', 'Company Registration number', 'trim|required');
                        $this->form_validation->set_rules('company_name', 'Company name', 'trim|required');
                        //$this->form_validation->set_rules('company_logo', 'Company Logo', 'trim|required');
                    }else{
                        $this->form_validation->set_rules('company_id', 'Company', 'trim|required');
                    }                
                }
                if(!empty($dataArr['phone'])){
                    //$this->form_validation->set_rules('prmccode', 'Country Code', 'min_length[2]|max_length[4]');
                    $this->form_validation->set_rules('phone', 'Phone Number', 'min_length[6]|max_length[15]');
                }
                if(!empty($dataArr['altphone'])){
                    //$this->form_validation->set_rules('altccode', 'Country Code', 'min_length[2]|max_length[4]');
                    $this->form_validation->set_rules('altphone', 'Alternate Phone Number', 'min_length[6]|max_length[15]');
                }
                
                if ($this->form_validation->run() == FALSE) {                    
                    if(!empty(set_value('country')) &&  !empty(set_value('cities'))){
                        $data['allcities'] = $this->Common_model->fetch_data('city_list','*',array('where'=>array('country_code' => set_value('country'))));
                    }                    
                    load_outerwebcropper_views('/index/signup', $data);
                } else {
                    //echo '<pre>'; print_r($dataArr); echo '</pre>'; die('total data in post method');
                    
                    $sg_userinfo = $this->Common_model->fetch_data('ai_user', '*', array('where' => array('email' => trim($dataArr['email']))), true);
                    if (!empty($sg_userinfo) && is_array($sg_userinfo)) {
                        
                        if (!empty($sg_userinfo) && $sg_userinfo['status'] == 2) {
                            $data['error'] = $this->lang->line('account_blocked');
                            $this->session->set_flashdata("flash-message", $this->lang->line('account_blocked'));
                            $this->session->set_flashdata("flash-type", "danger");
                        } else if (!empty($user_info) && $user_info['status'] == 1) {
                            $data['error'] = $this->lang->line('email_exists');
                            $this->session->set_flashdata("flash-message", $this->lang->line('email_exists'));
                            $this->session->set_flashdata("flash-type", "danger");
                        }

                        
                        load_outerwebcropper_views('/index/signup', $data);
                    } else {
                        //echo '<pre>'; print_r($dataArr); echo '</pre>'; die('now just need to insert the data');
                        
                        $signupArr = [];
                        $signupArr["first_name"] = trim($dataArr["fullname"]);                        
                        $signupArr["email"] = $dataArr["email"];
                        //$signupArr['language'] = isset($dataArr['language'])&&preg_match('/^(en|da|nb|sv|fi|fr|nl|de)$/', $dataArr['language'])?$dataArr['language']:"en";                        
                        $signupArr["prm_user_countrycode"] = isset($dataArr["prmccode"]) ? trim($dataArr["prmccode"]) : "";
                        $signupArr["phone"] = isset($dataArr["phone"]) ? trim($dataArr["phone"]) : "";
                        $signupArr["alt_user_countrycode"] = isset($dataArr["altccode"]) ? trim($dataArr["altccode"]) : "";
                        $signupArr["alt_userphone"] = isset($dataArr["altphone"]) ? trim($dataArr["altphone"]) : "";                       
                        $signupArr["country_id"] = isset($dataArr['country']) ? $dataArr['country'] : "";
                        $signupArr["city_id"] = isset($dataArr['cities']) ? $dataArr['cities'] : "";
                        $signupArr["zipcode"] = isset($dataArr['zipcode']) ? $dataArr['zipcode'] : "";
                        $signupArr["password"] = encrypt($dataArr["password"]);
                        $signupArr["registered_date"] = date('Y-m-d H:i:s');
                        if (isset($dataArr['imgurl']) && !empty($dataArr['imgurl'])) {
                            $this->load->helper("images");
                            try
                            {
                                $signupArr['image'] = $imageName=s3_image_uploader(ABS_PATH.$dataArr['imgurl'],$dataArr['imgurl']);
                            } catch (Exception $e) {                                
                                $data['error'] = strip_tags($this->upload->display_errors());                                
                                $this->session->set_flashdata("flash-message", $e->getMessage());
                                $this->session->set_flashdata("flash-type", "danger");
                                load_outerwebcropper_views('/index/signup', $data);
                            }                
                        }else{
                            $signupArr['image'] = $imageName = '';
                        }
                        //$signupArr["image"] = isset($dataArr['profile_image']) ? $dataArr['profile_image'] : "";;
                        //$signupArr["image_thumb"] = isset($dataArr['profile_image_thumb']) ? $dataArr['profile_image_thumb'] : "";;

                        $this->db->trans_begin();
                        //pr($dataArr);
                        $signupArr["is_owner"] = isset($dataArr['isowner']) ? $dataArr['isowner'] : "1";
                        $signupArr["user_type"] = $dataArr['user_type'];

                        if($dataArr['isowner'] == '2'){
                            $companyArr['company_name'] = isset($dataArr['company_name']) ? $dataArr['company_name'] : "";
                            $companyArr['company_reg_number'] = isset($dataArr['comp_reg_number']) ? $dataArr['comp_reg_number'] : "";
                            $companyArr['country'] = isset($dataArr['country']) ? $dataArr['country'] : "";                    
                            $companyArr['city'] = isset($dataArr['cities']) ? $dataArr['cities'] : "";
                            $companyArr['zipcode'] = isset($dataArr['zipcode']) ? $dataArr['zipcode'] : "";
                            
                            if (isset($dataArr['company_logo']) && !empty($dataArr['company_logo'])) {
                                $this->load->helper("images");
                                try
                                {
                                    $companyArr['company_image'] = $imageName=s3_image_uploader(ABS_PATH.$dataArr['company_logo'],$dataArr['company_logo']);
                                } catch (Exception $e) {                                
                                    $data['error'] = strip_tags($this->upload->display_errors());                                
                                    $this->session->set_flashdata("flash-message", $e->getMessage());
                                    $this->session->set_flashdata("flash-type", "danger");
                                    load_outerwebcropper_views('/index/signup', $data);
                                }                
                            }else{
                                $companyArr['company_image'] = $imageName = '';
                            }

                            

                            //$companyArr['company_image'] = isset($dataArr['company_logo']) ? $dataArr['company_logo'] : "";
                            //$companyArr['company_image_thumb'] = isset($dataArr['company_logo']) ? $dataArr['company_logo'] : "";
                            $companyArr["owner_type"] = $dataArr['isowner'];
                            $companyArr['insert_date'] = date('Y-m-d H:i:s');
                            //echo '<pre>'; print_r($companyArr);die;
                            $companyId = $this->Common_model->insert_single('company_master', $companyArr);
                            if($companyId){
                                $signupArr["company_id"] = $companyId;
                            }else{                                
                                $error = $this->lang->line('try_again');
                                $this->session->set_flashdata("flash-message", $error);
                                $this->session->set_flashdata("flash-type", "danger");
                                load_outerwebcropper_views('/index/signup', $data);
                            }
                        }else{                    
                            $signupArr["company_id"] = isset($dataArr['company_id']) ? $dataArr['company_id'] : ""; 
                        }
                        //echo '<pre>'; print_r($signupArr);die;
                        $userId = $this->Common_model->insert_single('ai_user', $signupArr);
                        if ($this->db->trans_status() === TRUE) {
                            $this->db->trans_commit();
                            $mailData = [];
                            $mailData['name'] = $dataArr['full_name'];
                            $mailData['email'] = $dataArr['email'];
                            $this->sendWelcomeMail($mailData);

                            redirect('/web/index/verification?id='. base64_encode($userId));
                        }else{
                            $this->db->trans_rollback();
                            $error = $e->getMessage();
                            $this->session->set_flashdata("flash-message", $error);
                            $this->session->set_flashdata("flash-type", "danger");
                            load_outerwebcropper_views('/index/signup', $data);
                        }                            
                    }
                }
            } else {
                load_outerwebcropper_views('/index/signup', $data);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    /*
     * @function:verification
     * @param: no param
     * @description: this page is used for thank you message after registration
     */

    public function verification() {	     
        $data['id'] = $id = $this->input->get('id');
        if(empty($id)){
            error404();
        }else{
            $data['useremail'] = $this->Common_model->fetch_data('ai_user', 'email', array('where' => array('user_id' => base64_decode($id))), true);
        }
        load_outerweb_views('/index/verification', $data);
    }
    
    
    /*
     * @function:resetsuccess
     * @param: no param
     * @description: this page is used for thank you message after reset password success
     */

    public function resetsuccess() {	        
        load_outerweb_views('/index/resetsuccess', $data);
    }

    
    /*
     * @function:forgotsuccess
     * @param: no param
     * @description: this page is used for thank you message after reset password success
     */

    public function forgotsuccess() {	        
        load_outerweb_views('/index/forgotsuccess', $data);
    }
    
    
     /*
     * @function:resetpassword
     * @param: newpassword 
     * @param: confirmpassword 
     * @description: updating password of the user 
     */

    public function resetpassword() {	        
        try {
            $data = array();           
            $this->load->library("commonfn");
            $this->session->set_flashdata("flash-message", '');
            $this->session->set_flashdata("flash-type", "");
            $post = $this->input->post();
            $data['token'] = $token = $this->input->get('token');                       
            if ( !isset($token) || empty($token) ) {                
                error404(); exit;
            }                        
            $result = $this->Common_model->fetch_data('ai_user', 'user_id,reset_link_time', array('where' => array('reset_token' => $token)), true);
            if ($post) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[30]|alpha_numeric');
                $this->form_validation->set_rules('cnfpassword', 'Confirm Password', 'trim|required|matches[password]|min_length[8]|max_length[30]|alpha_numeric');
                if ($this->form_validation->run() == FALSE) {                          
                    $data["csrfName"] = $this->security->get_csrf_token_name();
                    $data["csrfToken"] = $this->security->get_csrf_hash();
                    load_outerweb_views('/index/resetpassword', $data);
                } else {                    
                    $newPass = trim($post['password']);
                    $newCnfPassword = trim($post['cnfpassword']);
                    if($newPass != $newCnfPassword){                        
                        $this->session->set_flashdata("flash-message", 'New Password and Confirm password mismatched!');
                        $this->session->set_flashdata("flash-type", "danger");
                        $data["csrfName"] = $this->security->get_csrf_token_name();
                        $data["csrfToken"] = $this->security->get_csrf_hash();
                        load_outerweb_views('/index/resetpassword', $data);
                        
                    }else{                           
                        //echo $newPass.'==='.$newCnfPassword; die;
                        //$currenttime = time();
                        $currenttime = strtotime(date("Y-m-d H:i:s"));
                        if (!empty($result)){                        
                            $pass = encrypt($newPass);
                            if ($currenttime < strtotime($result['reset_link_time'])){                            
                                $whereArr['where'] = ['user_id' => $result['user_id']];
                                $updateArr = ['reset_token' => "", 'isreset_link_sent' => "0", 'password' => $pass];
                                //echo '<pre>'; print_r($updateArr);print_r($whereArr); die;
                                $update = $this->Common_model->update_single('ai_user', $updateArr, $whereArr);
                                if ($update) {                                  
                                    redirect('/web/index/resetsuccess');
                                } else {                                    
                                    $this->session->set_flashdata("flash-message", 'Something went wrong!');
                                    $this->session->set_flashdata("flash-type", "danger");
                                    $data["csrfName"] = $this->security->get_csrf_token_name();
                                    $data["csrfToken"] = $this->security->get_csrf_hash();
                                    load_outerweb_views('/index/resetpassword', $data);
                                }
                            } else {                                
                                $this->session->set_flashdata("flash-message", 'Token expired!');
                                $this->session->set_flashdata("flash-type", "danger");
                                $data["csrfName"] = $this->security->get_csrf_token_name();
                                $data["csrfToken"] = $this->security->get_csrf_hash();
                                load_outerweb_views('/index/resetpassword', $data);
                            }
                        } else {
                            error404("Invalid token");
                        }
                    }
                    
                }
            } else {
                 if (!empty($result)) {
                    $data["csrfName"] = $this->security->get_csrf_token_name();
                    $data["csrfToken"] = $this->security->get_csrf_hash();
                    load_outerweb_views('/index/resetpassword', $data);
                } else {                    
                    error404("Invalid token");
                }                  
            }
        } catch (Exception $e) {
            //echo $e->getMessage(); die;
            $this->session->set_flashdata("flash-message", $e->getMessage());
            $this->session->set_flashdata("flash-type", "danger");            
            load_outer_views('/index/resetpassword', $data);
        }
    }
    

    /*
     * @function:check_email_avalibility
     * @param:N/A
     * @description:If email is exist in db or not
     */

    public function check_email_avalibility() {

        if (!$this->input->is_ajax_request()) {
            exit('No Direct Script allowed');
        }
        $postemail = $this->input->post('email');
        $csrftoken = $this->security->get_csrf_hash();
        $respArr = $this->Common_model->fetch_data('ai_user', '*', array('where' => array('email' => $postemail)), true);
        if ($respArr) {
            $respArr = array('code' => 201, 'msg' => 'Email Exist', "csrf_token" => $csrftoken);
        } else {
            $respArr = array('code' => 200, 'msg' => 'Email Doesnot Exist', "csrf_token" => $csrftoken);
        }
        echo json_encode($respArr);
        die;
    }
    
    public function valid_email($email,$userid = false) {
        if($email){ 
            if($userid){
                $respArr = $this->Common_model->fetch_data('ai_user', '*', array('where' => array('email' => $email),'where_not_in'=> array('user_id'=>$userid)), true);
            }else{
                $respArr = $this->Common_model->fetch_data('ai_user', '*', array('where' => array('email' => $email)), true);
            }  
            if ($respArr) {
                return false;
            } 
            return true;
        }else{
            return false;
        }
    }
    
    
    public function sendWelcomeMail($mailData) {

        $this->load->helper('url');
        $data = [];
        $data['url'] = base_url() . 'request/welcomeMail?email=' . $mailData['email'] . '&name=' . urlencode($mailData['name']);
        sendGetRequest($data);
    }
    

}
