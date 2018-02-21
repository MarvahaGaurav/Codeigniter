<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {

    function __construct() {		
        parent::__construct();
        $this->load->helper(['url', 'form', 'custom_cookie']);
        $this->load->model('Common_model');
        $this->load->library('session');
        $this->lang->load('common', "english");
        $this->load->library('form_validation');        
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
                    $sg_userinfo = $this->Common_model->fetch_data('ai_user', array('user_id', 'first_name', 'email'), array('where' => array('email' => $email, 'password' => $pass, 'status' => 1)), true);
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
                        //"first_name" => $sg_userinfo['first_name'],
                        //"email" => $sg_userinfo['email'],
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

                        $cookieData["cookie_validator"] = hash("sha256", $cookieData["cookie_validator"] . $sg_userinfo["create_date"]);

                        $this->Common_model->update_single("ai_user", $cookieData, ["where" => ["user_id" => $sg_userinfo["user_id"]]]);
                    }
                    $this->session->set_flashdata("greetings", "Welcome!");
                    $this->session->set_flashdata("message", "You have successfully logged in");
                    $this->session->set_userdata('sg_userinfo', $sg_userinfo);
                    redirect(base_url() . "web/home/index");
                } else {
                    $data['email'] = $email;
                    $data['password'] = $password;
                    $data['error'] = $this->lang->line('invalid_email_password');
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
            if ($this->input->post()) {

                $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    load_outerweb_views('/index/forgotpassword', $data);
                } else {

                    $dataArr = $this->input->post();
                    $email = $dataArr;

                    $sg_userinfo = $this->Common_model->fetch_data('ai_user', '*', array('where' => array('email' => $email['email'])), true);

                    if (!empty($sg_userinfo) && is_array($sg_userinfo)) {

                        $name = $sg_userinfo['first_name'];
                        $email = $sg_userinfo['email'];

                        $subject = "RESET PASSWORD";
                        $reset_token = hash('sha256', date("Y-m-d h:i:s"));
                        $timeexpire = time() + (24 * 60 * 60);

                        $dataArr['name'] = $name;
                        $insert['reset_token'] = $reset_token;
                        $insert['timestampexp'] = $timeexpire;
                        $condition = ['email' => $email];
                        $where = array("where" => array('email' => $email));
                        $update = $this->Common_model->update_single('admin', $insert, $where);

                        $mailinfoarr = [];
                        $mailinfoarr['link'] = base_url() . 'web/index/reset?token=' . $reset_token;
                        $mailinfoarr['email'] = $email;
                        $mailinfoarr['subject'] = $subject;
                        $mailinfoarr['name'] = $name;
                        $mailinfoarr['mailerName'] = 'forgot';

                        $isSuccess = $this->commonfn->sendEmailToUser($mailinfoarr);

                        if ($isSuccess) {
                            $this->session->set_flashdata('Success', $this->lang->line('success_prefix') . $this->lang->line('reset_email') . $this->lang->line('success_suffix'));
                            redirect(base_url() . 'web/index/forgot');
                        } else {
                            load_outerweb_views('/index/forgotpassword', $data);
                        }
                    } else {
                        $data['error'] = $this->lang->line('invalid_email');
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
            if ($this->input->post()) {

                $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    load_outerweb_views('/index/signup', $data);
                } else {

                    $dataArr = $this->input->post();
                    pr($dataArr);
                    
                    $sg_userinfo = $this->Common_model->fetch_data('ai_user', '*', array('where' => array('email' => $email['email'])), true);

                    if (!empty($sg_userinfo) && is_array($sg_userinfo)) {
                        pr($sg_userinfo);
                    } else {
                        $data['error'] = $this->lang->line('email_exists');
                        load_outerweb_views('/index/signup', $data);
                    }
                }
            } else {
                load_outerweb_views('/index/signup', $data);
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
     * @function:resetpassword
     * @param: newpassword 
     * @param: confirmpassword 
     * @description: updating password of the user 
     */

    public function resetpassword() {	        
        try {
            $data = array();
            $this->load->library("commonfn");
            if ($this->input->post()) {

                $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    load_outerweb_views('/index/resetpassword', $data);
                } else {

                    $dataArr = $this->input->post();
                    pr($dataArr);
                    
                    $sg_userinfo = $this->Common_model->fetch_data('ai_user', '*', array('where' => array('email' => $email['email'])), true);

                    if (!empty($sg_userinfo) && is_array($sg_userinfo)) {
                        pr($sg_userinfo);
                    } else {
                        $data['error'] = $this->lang->line('email_exists');
                        load_outerweb_views('/index/resetpassword', $data);
                    }
                }
            } else {
                load_outerweb_views('/index/resetpassword', $data);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
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

    
    /*
     * @function:logout
     * @param: 
     * @description: this is used to logout the user and redirect him to home page after logout
     */

    public function logout() {	                
        $this->session->sess_destroy();
        redirect(base_url() . "web/home/index");
    }
    
}
