<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once "BaseController.php";

class Index extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        //$this->load->helper("images");
        //$this->load->helper("s3");
        $this->inactiveSessionGuard();
    }

    /*
     * @function:index
     * @param:username:email
     * @param:password:password
     * @description:if email and password are correct then he can login
     */

    public function index()
    {
        $data = [];
        
        // if form is posted ..
        if ($this->input->post()) {
            $postDataArr = $this->input->post();
            // echo '<pre>'; print_r($postDataArr);die('safs');
            $this->session->set_flashdata("flash-message", '');
            $this->session->set_flashdata("flash-type", "");
                    
            $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required');
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required');
            
            if ($this->form_validation->run() == false) {
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
                            "sg_user",
                            "{$cookieData['cookie_selector']}:{$cookieData['cookie_validator']}",
                            $cookieExpiryTime
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

    public function forgot()
    {
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

                if ($this->form_validation->run() == false) {
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
                        $timeexpire = date("Y-m-d H:i:s", strtotime('+24 hour'));

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
    public function signup()
    {
        try {
            $data = [];
            $this->load->config('css_config');
            $data['css'] = $this->config->item('signup');
            $data['js'] = 'signup';
            load_outerwebcropper_views('/index/signup', $data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    /*
     * @function:verification
     * @param: no param
     * @description: this page is used for thank you message after registration
     */
    public function verification()
    {
        $data['id'] = $id = $this->input->get('id');
        if (empty($id)) {
            error404();
        } else {
            $data['useremail'] = $this->Common_model->fetch_data('ai_user', 'email', array('where' => array('user_id' => base64_decode($id))), true);
        }
        load_outerweb_views('/index/verification', $data);
    }
    
    
    /*
     * @function:resetsuccess
     * @param: no param
     * @description: this page is used for thank you message after reset password success
     */

    public function resetsuccess()
    {
        load_outerweb_views('/index/resetsuccess', $data);
    }

    
    /*
     * @function:forgotsuccess
     * @param: no param
     * @description: this page is used for thank you message after reset password success
     */

    public function forgotsuccess()
    {
        load_outerweb_views('/index/forgotsuccess', $data);
    }
    
    
     /*
     * @function:resetpassword
     * @param: newpassword
     * @param: confirmpassword
     * @description: updating password of the user
     */

    public function resetpassword()
    {
        try {
            $data = array();
            $this->load->library("commonfn");
            $this->session->set_flashdata("flash-message", '');
            $this->session->set_flashdata("flash-type", "");
            $post = $this->input->post();
            $data['token'] = $token = $this->input->get('token');
            if (!isset($token) || empty($token)) {
                error404();
                exit;
            }
            $result = $this->Common_model->fetch_data('ai_user', 'user_id,reset_link_time', array('where' => array('reset_token' => $token)), true);
            if ($post) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[30]|alpha_numeric');
                $this->form_validation->set_rules('cnfpassword', 'Confirm Password', 'trim|required|matches[password]|min_length[8]|max_length[30]|alpha_numeric');
                if ($this->form_validation->run() == false) {
                    $data["csrfName"] = $this->security->get_csrf_token_name();
                    $data["csrfToken"] = $this->security->get_csrf_hash();
                    load_outerweb_views('/index/resetpassword', $data);
                } else {
                    $newPass = trim($post['password']);
                    $newCnfPassword = trim($post['cnfpassword']);
                    if ($newPass != $newCnfPassword) {
                        $this->session->set_flashdata("flash-message", 'New Password and Confirm password mismatched!');
                        $this->session->set_flashdata("flash-type", "danger");
                        $data["csrfName"] = $this->security->get_csrf_token_name();
                        $data["csrfToken"] = $this->security->get_csrf_hash();
                        load_outerweb_views('/index/resetpassword', $data);
                    } else {
                        //echo $newPass.'==='.$newCnfPassword; die;
                        //$currenttime = time();
                        $currenttime = strtotime(date("Y-m-d H:i:s"));
                        if (!empty($result)) {
                            $pass = encrypt($newPass);
                            if ($currenttime < strtotime($result['reset_link_time'])) {
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

    public function check_email_avalibility()
    {

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
    
    public function valid_email($email, $userid = false)
    {
        if ($email) {
            if ($userid) {
                $respArr = $this->Common_model->fetch_data('ai_user', '*', array('where' => array('email' => $email),'where_not_in'=> array('user_id'=>$userid)), true);
            } else {
                $respArr = $this->Common_model->fetch_data('ai_user', '*', array('where' => array('email' => $email)), true);
            }
            if ($respArr) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
    
    
    public function sendWelcomeMail($mailData)
    {

        $this->load->helper('url');
        $data = [];
        $data['url'] = base_url() . 'request/welcomeMail?email=' . $mailData['email'] . '&name=' . urlencode($mailData['name']);
        sendGetRequest($data);
    }
}
