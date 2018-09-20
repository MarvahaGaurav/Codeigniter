<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller
{

    function __construct() 
    {
        parent::__construct();
        $this->load->helper(['url', 'form', 'custom_cookie']);
        $this->load->model('Common_model');
        $this->load->library('session');
        $this->lang->load('common', "english");
        $this->load->library('form_validation');
        $sessionData = validate_admin_cookie('rcc_appinventiv', 'admin');
        if ($sessionData) {
            $this->session->set_userdata('admininfo', $sessionData);
        }
        $this->admininfo = $this->session->userdata('admininfo');
        if ($this->admininfo) {
            redirect(base_url() . "admin/Dashboard");
        }
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
        if ($this->input->post()) {
            $postDataArr = $this->input->post();
            $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required');
            $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required');
            if ($this->form_validation->run() == false) {
                load_outer_views('/admin/login', $data);
            } else {
                $email = $postDataArr['email'];
                $password = $postDataArr['password'];
                $pass = hash('sha256', $password);

                /*
                 * Matched the Credentials
                 */
                try {
                    $adminInfo = $this->Common_model->fetch_data('admin', array('admin_id', 'admin_name', 'admin_email', 'admin_profile_pic', 'admin_profile_thumb', 'role_id'), array('where' => array('admin_email' => $email, 'admin_password' => $pass, 'status' => 1)), true);
                } catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                /*
                 * If credentials are matched set the session
                 */
                if (!empty($adminInfo)) {

                    $admindata = array();
                    $admindata = array(
                        "admin_id" => $adminInfo['admin_id'],
                        "admin_name" => $adminInfo['admin_name'],
                        "admin_email" => $adminInfo['admin_email'],
                        "admin_profile_pic" => $adminInfo['admin_profile_pic'],
                        "admin_profile_thumb" => $adminInfo['admin_profile_thumb'],
                        "role_id" => $adminInfo['role_id'],
                    );

                    //SETS COOKIE DATA
                    if (isset($postDataArr["remember_me"]) && $postDataArr["remember_me"] == "remember_me") {
                        $this->load->helper(["cookie", "string"]);
                        $cookieData["cookie_validator"] = random_string('alnum', 12);
                        $cookieData["cookie_selector"] = hash("sha256", date("Y-m-d H:i:s") . $postDataArr["email"]);

                        $cookieExpiryTime = time() + COOKIE_EXPIRY_TIME;

                        set_cookie(
                            "rcc_appinventiv", "{$cookieData['cookie_selector']}:{$cookieData['cookie_validator']}", $cookieExpiryTime
                        );

                        $cookieData["cookie_validator"] = hash("sha256", $cookieData["cookie_validator"] . $adminInfo["create_date"]);

                        $this->Common_model->update_single("admin", $cookieData, ["where" => ["admin_id" => $adminInfo["admin_id"]]]);
                    }
                    $this->session->set_flashdata("greetings", "Welcome!");
                    $this->session->set_flashdata("message", "You have successfully logged in");
                    $this->session->set_userdata('admininfo', $admindata);
                    redirect(base_url() . "admin/dashboard");
                } else {
                    $data['email'] = $email;
                    $data['password'] = $password;
                    $data['error'] = $this->lang->line('invalid_email_password');
                    load_outer_views('/admin/login', $data);
                }
            }
        } else {

            load_outer_views('/admin/login', $data);
        }
    }

    /*
     * @function:forget
     * @param:email:Admin email
     * @param:name:Admin name
     * @description:if admin forget the password then he can reset his password
     */

    public function forgot() 
    {
        try {
            $data = array();
            $this->load->library("commonfn");
            if ($this->input->post()) {

                $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required');

                if ($this->form_validation->run() == false) {
                    load_outer_views('/admin/forgotpassword', $data);
                } else {

                    $dataArr = $this->input->post();
                    $email = $dataArr;

                    $admininfo = $this->Common_model->fetch_data('admin', '*', array('where' => array('admin_email' => $email['email'])), true);

                    if (!empty($admininfo) && is_array($admininfo)) {

                        $name = $admininfo['admin_name'];
                        $email = $admininfo['admin_email'];

                        $subject = "RESET PASSWORD";
                        $reset_token = hash('sha256', date("Y-m-d h:i:s"));
                        $timeexpire = time() + (24 * 60 * 60);

                        $dataArr['name'] = $name;
                        $insert['reset_token'] = $reset_token;
                        $insert['timestampexp'] = $timeexpire;
                        $condition = ['email' => $email];
                        $where = array("where" => array('admin_email' => $email));
                        $update = $this->Common_model->update_single('admin', $insert, $where);

                        $mailinfoarr = [];
                        $mailinfoarr['link'] = base_url() . 'admin/reset?token=' . $reset_token;
                        $mailinfoarr['email'] = $email;
                        $mailinfoarr['subject'] = $subject;
                        $mailinfoarr['name'] = $name;
                        $mailinfoarr['mailerName'] = 'forgot';

                        $isSuccess = $this->commonfn->sendEmailToUser($mailinfoarr);

                        if ($isSuccess) {
                            $this->session->set_flashdata('Success', $this->lang->line('success_prefix') . $this->lang->line('reset_email') . $this->lang->line('success_suffix'));
                            redirect(base_url() . 'admin/forgot');
                        } else {
                            load_outer_views('/admin/forgotpassword', $data);
                        }
                    } else {
                        $data['error'] = $this->lang->line('invalid_email');
                        load_outer_views('/admin/forgotpassword', $data);
                    }
                }
            } else {
                load_outer_views('/admin/forgotpassword', $data);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /*
     * @function:reset password
     * @param:N/A
     * @description:admin can set the password again
     */

    public function reset() 
    {
        try {
            $post = $this->input->post();
            $token = $this->input->get('token');
            if (empty($token)) {
                show404("Token missing");
            }
            $result = $this->Common_model->fetch_data('admin', 'admin_id, admin_email,reset_token,timestampexp', array('where' => array('reset_token' => $token)), true);
            if (!empty($post)) {
                $newPass = $post['password'];
                if (!empty($newPass)) {
                    $currenttime = time();
                    if (!empty($result)) {
                        $pass = hash('sha256', $newPass);
                        if ($currenttime < $result['timestampexp']) {
                            $updateArr = [];
                            $whereArr['where'] = ['admin_email' => $result['admin_email']];

                            $updateArr = ['reset_token' => "", 'admin_password' => $pass];
                            $update = $this->Common_model->update_single('admin', $updateArr, $whereArr);
                            if ($update) {
                                $this->session->set_flashdata('message', $this->lang->line('success_prefix') . $this->lang->line('password_changed') . $this->lang->line('success_suffix'));
                                redirect('/admin/');
                            } else {
                                $this->session->set_flashdata('message', $this->lang->line('error_prefix') . $this->lang->line('error_suffix'));
                                $data["csrfName"] = $this->security->get_csrf_token_name();
                                $data["csrfToken"] = $this->security->get_csrf_hash();
                                load_outer_views('/admin/resetpassword', $data);
                            }
                        } else {
                            show404("Access token expired");
                        }
                    } else {
                        show404("Invalid token");
                    }
                }
            } else {
                if (!empty($result)) {
                    $data["csrfName"] = $this->security->get_csrf_token_name();
                    $data["csrfToken"] = $this->security->get_csrf_hash();
                    load_outer_views('/admin/resetpassword', $data);
                } else {
                    show404("Invalid token");
                }
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

    public function check_email_avalibility() 
    {

        if (!$this->input->is_ajax_request()) {
            exit('No Direct Script allowed');
        }
        $postemail = $this->input->post('email');
        $csrftoken = $this->security->get_csrf_hash();
        $respArr = $this->Common_model->fetch_data('admin', '*', array('where' => array('admin_email' => $postemail)), true);
        if ($respArr) {
            $respArr = array('code' => 201, 'msg' => 'Email Exist', "csrf_token" => $csrftoken);
        } else {
            $respArr = array('code' => 200, 'msg' => 'Email Doesnot Exist', "csrf_token" => $csrftoken);
        }
        echo json_encode($respArr);
        die;
    }

}
