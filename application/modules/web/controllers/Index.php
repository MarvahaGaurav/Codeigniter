<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once "BaseController.php";

class Index extends BaseController {

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
            }
            else {
                $email    = $postDataArr['email'];
                $password = $postDataArr['password'];
                //$pass = hash('sha256', $password);
                $pass     = encrypt($postDataArr["password"]);

                /*
                 * Matched the Credentials
                 */
                try {
                    $sg_userinfo = $this->Common_model->fetch_data('ai_user', array ('user_id', 'first_name', 'email', 'status'),
                                                                   array ('where' => array ('email' => $email, 'password' => $pass, 'status' => 1)), true);
                    //echo $this->db->last_query(); die;
                }
                catch (Exception $ex) {
                    echo $ex->getMessage();
                }
                /*
                 * If credentials are matched set the session
                 */
                if ( ! empty($sg_userinfo)) {
                    //$sg_userinfo = array();
                    $sg_userinfo = array (
                        "user_id"    => $sg_userinfo['user_id'],
                        "first_name" => $sg_userinfo['first_name'],
                        "email"      => $sg_userinfo['email'],
                        'status'     => $sg_userinfo['status']
                    );
                    //pr($sg_userinfo);
                    //SETS COOKIE DATA
                    if (isset($postDataArr["remember_me"]) && $postDataArr["remember_me"] == "on") {
                        $this->load->helper(["cookie", "string"]);
                        $cookieData["cookie_validator"] = random_string('alnum', 12);
                        $cookieData["cookie_selector"]  = hash("sha256", date("Y-m-d H:i:s") . $postDataArr["email"]);

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
                }
                else {
                    $data['email']    = $email;
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
            $data                  = array ();
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
                }
                else {
                    $dataArr     = $this->input->post();
                    $sg_userinfo = $this->Common_model->fetch_data('ai_user', '*', array ('where' => array ('email' => trim($dataArr['email']))), true);
                    //echo '<pre>'; echo $this->db->last_query(); //print_r($sg_userinfo); die;
                    if ( ! empty($sg_userinfo) && is_array($sg_userinfo)) {
                        $name  = $sg_userinfo['first_name'];
                        $email = $sg_userinfo['email'];

                        $subject     = "RESET PASSWORD";
                        $reset_token = hash('sha256', date("Y-m-d h:i:s"));
                        //$timeexpire = time() + (24 * 60 * 60);
                        $timeexpire  = date("Y-m-d H:i:s", strtotime('+24 hour'));

                        $dataArr['name']             = $name;
                        $insert['reset_token']       = $reset_token;
                        $insert['isreset_link_sent'] = 1;
                        $insert['reset_link_time']   = $timeexpire;
                        $condition                   = ['email' => $email];
                        $where                       = array ("where" => array ('email' => $email));
                        //echo '<pre>'; print_r($insert); die;
                        $update                      = $this->Common_model->update_single('ai_user', $insert, $where);

                        $mailinfoarr               = [];
                        $mailinfoarr['link']       = base_url() . 'web/index/resetpassword?token=' . $reset_token;
                        $mailinfoarr['email']      = $email;
                        $mailinfoarr['subject']    = $subject;
                        $mailinfoarr['name']       = $name;
                        $mailinfoarr['mailerName'] = 'reset';

                        $isSuccess = $this->commonfn->sendEmailToUser($mailinfoarr);

                        if ($isSuccess) {
                            //$this->session->set_flashdata('Success', $this->lang->line('success_prefix') . $this->lang->line('reset_email') . $this->lang->line('success_suffix'));
                            $this->session->set_flashdata("flash-message", $this->lang->line('reset_email'));
                            $this->session->set_flashdata("flash-type", "success");

                            //$data['success'] = $this->lang->line('success_prefix') . $this->lang->line('reset_email') . $this->lang->line('success_suffix');
                            //redirect(base_url() . 'web/index/forgot');
                            redirect('/web/index/forgotsuccess');
                        }
                        else {
                            load_outerweb_views('/index/forgotpassword', $data);
                        }
                    }
                    else {
                        $this->session->set_flashdata("flash-message", $this->lang->line('invalid_email'));
                        $this->session->set_flashdata("flash-type", "danger");
                        //$data['error'] = $this->lang->line('invalid_email');
                        load_outerweb_views('/index/forgotpassword', $data);
                    }
                }
            }
            else {
                load_outerweb_views('/index/forgotpassword', $data);
            }
        }
        catch (Exception $e) {
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
            $data                 = [];
            $this->load->helper(['location', 'form', 'data']);
            $this->lang->load(['sg', 'forms']);
            $this->load->config('css_config');
            $data['countries']    = fetch_countries();
            $data['css']          = $this->config->item('signup');
            $data['js']           = 'signup';
            $data['nonBundledJs'] = true;
            if ($this->input->post()) {
                $this->form_validation->CI = & $this;
                $userType                  = $this->input->post('user_type');
                $this->form_validation->set_rules($this->signupValidationRules());

                //If the user is of technician types add more relavent validation
                if (in_array((int) $userType, [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER, WHOLESALER], true)) {
                    $this->form_validation->set_rules($this->technicianEmployeeRules());
                    $isCompanyOwner = $this->input->post('is_company_owner');

                    //If the user is an owner add relavent validations
                    if (is_numeric($isCompanyOwner) && (int) $isCompanyOwner === 1) {
                        $this->form_validation->set_rules($this->compannyOwnerRules());
                    }
                }
                if ($this->form_validation->run()) {
                    $this->load->helper('input_data');
                    $postData      = $this->input->post();
                    $postData      = trim_input_parameters($postData);
                    $userData      = [
                        'user_type'            => $postData['user_type'],
                        'first_name'           => $postData['fullname'],
                        'email'                => $postData['email'],
                        'password'             => encrypt($postData['password']),
                        'prm_user_countrycode' => $postData['contact_number_code'],
                        'phone'                => $postData['contact_number'],
                        'alt_user_countrycode' => $postData['alternate_contact_number_code'],
                        'alt_userphone'        => $postData['alternate_contact_number'],
                        'country_id'           => $postData['country'],
                        'city_id'              => $postData['city'],
                        'zipcode'              => $postData['zipcode'],
                        'registered_date'      => $this->datetime,
                        'image'                => $postData['user_image']
                    ];
                    $companyInsert = false;
                    $isEmployee    = false;
                    if (in_array((int) $userType, [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER, WHOLESALER], true)) {
                        $userData['is_owner'] = (int) $postData['is_company_owner'] === 1 ? 2 : 1;
                        if ((int) $postData['is_company_owner'] === 1) {
                            if (isset($_FILES['company_logo']) and '' != $_FILES['company_logo']['tmp_name']) {
                                $this->load->helper("s3_helper");
                                $path = s3_image_uploader($_FILES['company_logo'], date("YmdHis") . "." . substr(strrchr($_FILES['company_logo']['name'], '.'), 1), $_FILES['type'], "");

                                $companyData['company_image'] = $path;
                            }
                            $companyInsert                     = true;
                            $companyData['company_name']       = $postData['company_name'];
                            $companyData['company_reg_number'] = $postData['company_registration_number'];
                            $companyData['insert_date']        = $this->datetime;
                        }
                        else {
                            $isEmployee             = true;
                            $userData['company_id'] = $postData['company_name'];
                        }
                    }

                    $userId = $this->Common_model->insert_single('ai_user', $userData);
                    if ($companyInsert) {
                        $companyId = $this->Common_model->insert_single('company_master', $companyData);
                        $this->Common_model->update_single('ai_user', [
                            'company_id' => $companyId
                            ], [
                            'where' => [
                                'user_id' => $userId
                            ]
                        ]);
                    }

                    if ($isEmployee) {
                        $ownerData = $this->Common_model->fetch_data('ai_user', 'user_id', ['where' =>
                            ['is_owner' => 2, 'company_id' => $postData['company_name']]], true);

                        $this->Common_model->insert_single('employee_request_master',
                                                           [
                            'requested_to' => $ownerData['user_id'],
                            'requested_by' => $userId,
                            'request_time' => $this->datetime,
                            'company_id'   => $postData['company_name'],
                        ]);
                    }

                    $this->session->set_flashdata("flash-message", 'You have registered successfully!, Please login to continue');
                    $this->session->set_flashdata("flash-type", "success");
                    redirect(base_url('login'));
                    // pd($this->input->post());
                }
                else {
                    // pd($this->form_validation->error_array());
                }
            }
            website_noauth_view('/index/signup', $data);
        }
        catch (Exception $e) {
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
        $data['id'] = $id         = $this->input->get('id');
        if (empty($id)) {
            error404();
        }
        else {
            $data['useremail'] = $this->Common_model->fetch_data('ai_user', 'email', array ('where' => array ('user_id' => base64_decode($id))), true);
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
            $data          = array ();
            $this->load->library("commonfn");
            $this->session->set_flashdata("flash-message", '');
            $this->session->set_flashdata("flash-type", "");
            $post          = $this->input->post();
            $data['token'] = $token         = $this->input->get('token');
            if ( ! isset($token) || empty($token)) {
                error404();
                exit;
            }
            $result = $this->Common_model->fetch_data('ai_user', 'user_id,reset_link_time', array ('where' => array ('reset_token' => $token)), true);
            if ($post) {
                $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[30]|alpha_numeric');
                $this->form_validation->set_rules('cnfpassword', 'Confirm Password', 'trim|required|matches[password]|min_length[8]|max_length[30]|alpha_numeric');
                if ($this->form_validation->run() == false) {
                    $data["csrfName"]  = $this->security->get_csrf_token_name();
                    $data["csrfToken"] = $this->security->get_csrf_hash();
                    load_outerweb_views('/index/resetpassword', $data);
                }
                else {
                    $newPass        = trim($post['password']);
                    $newCnfPassword = trim($post['cnfpassword']);
                    if ($newPass != $newCnfPassword) {
                        $this->session->set_flashdata("flash-message", 'New Password and Confirm password mismatched!');
                        $this->session->set_flashdata("flash-type", "danger");
                        $data["csrfName"]  = $this->security->get_csrf_token_name();
                        $data["csrfToken"] = $this->security->get_csrf_hash();
                        load_outerweb_views('/index/resetpassword', $data);
                    }
                    else {
                        //echo $newPass.'==='.$newCnfPassword; die;
                        //$currenttime = time();
                        $currenttime = strtotime(date("Y-m-d H:i:s"));
                        if ( ! empty($result)) {
                            $pass = encrypt($newPass);
                            if ($currenttime < strtotime($result['reset_link_time'])) {
                                $whereArr['where'] = ['user_id' => $result['user_id']];
                                $updateArr         = ['reset_token' => "", 'isreset_link_sent' => "0", 'password' => $pass];
                                //echo '<pre>'; print_r($updateArr);print_r($whereArr); die;
                                $update            = $this->Common_model->update_single('ai_user', $updateArr, $whereArr);
                                if ($update) {
                                    redirect('/web/index/resetsuccess');
                                }
                                else {
                                    $this->session->set_flashdata("flash-message", 'Something went wrong!');
                                    $this->session->set_flashdata("flash-type", "danger");
                                    $data["csrfName"]  = $this->security->get_csrf_token_name();
                                    $data["csrfToken"] = $this->security->get_csrf_hash();
                                    load_outerweb_views('/index/resetpassword', $data);
                                }
                            }
                            else {
                                $this->session->set_flashdata("flash-message", 'Token expired!');
                                $this->session->set_flashdata("flash-type", "danger");
                                $data["csrfName"]  = $this->security->get_csrf_token_name();
                                $data["csrfToken"] = $this->security->get_csrf_hash();
                                load_outerweb_views('/index/resetpassword', $data);
                            }
                        }
                        else {
                            error404("Invalid token");
                        }
                    }
                }
            }
            else {
                if ( ! empty($result)) {
                    $data["csrfName"]  = $this->security->get_csrf_token_name();
                    $data["csrfToken"] = $this->security->get_csrf_hash();
                    load_outerweb_views('/index/resetpassword', $data);
                }
                else {
                    error404("Invalid token");
                }
            }
        }
        catch (Exception $e) {
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

        if ( ! $this->input->is_ajax_request()) {
            exit('No Direct Script allowed');
        }
        $postemail = $this->input->post('email');
        $csrftoken = $this->security->get_csrf_hash();
        $respArr   = $this->Common_model->fetch_data('ai_user', '*', array ('where' => array ('email' => $postemail)), true);
        if ($respArr) {
            $respArr = array ('code' => 201, 'msg' => 'Email Exist', "csrf_token" => $csrftoken);
        }
        else {
            $respArr = array ('code' => 200, 'msg' => 'Email Doesnot Exist', "csrf_token" => $csrftoken);
        }
        echo json_encode($respArr);
        die;

    }



    public function valid_email($email, $userid = false)
    {
        if ($email) {
            if ($userid) {
                $respArr = $this->Common_model->fetch_data('ai_user', '*', array ('where' => array ('email' => $email), 'where_not_in' => array ('user_id' => $userid)), true);
            }
            else {
                $respArr = $this->Common_model->fetch_data('ai_user', '*', array ('where' => array ('email' => $email)), true);
            }
            if ($respArr) {
                return false;
            }
            return true;
        }
        else {
            return false;
        }

    }



    public function sendWelcomeMail($mailData)
    {

        $this->load->helper('url');
        $data        = [];
        $data['url'] = base_url() . 'request/welcomeMail?email=' . $mailData['email'] . '&name=' . urlencode($mailData['name']);
        sendGetRequest($data);

    }



    private function signupValidationRules()
    {
        return [
            [
                'label'  => $this->lang->line('user_type'),
                'field'  => 'user_type',
                'rules'  => 'trim|required|regex_match[/^(1|2|3|4|5|6)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('something_went_wrong')
                ]
            ],
            [
                'label' => $this->lang->line('fullname'),
                'field' => 'fullname',
                'rules' => 'trim|required'
            ],
            [
                'label'  => $this->lang->line('email'),
                'field'  => 'email',
                'rules'  => 'trim|required|valid_email|callback_validate_unique_email',
                'errors' => [
                    'validate_unique_email' => $this->lang->line('email_taken')
                ]
            ],
            [
                'label' => $this->lang->line('password'),
                'field' => 'password',
                'rules' => 'trim|required|min_length[6]'
            ],
            [
                'label' => $this->lang->line('confirm_password'),
                'field' => 'confirm_password',
                'rules' => 'trim|matches[password]'
            ],
            [
                'label' => $this->lang->line('contact_number_code'),
                'field' => 'contact_number_code',
                'rules' => 'trim|required'
            ],
            [
                'label'  => $this->lang->line('contact_number'),
                'field'  => 'contact_number',
                'rules'  => 'trim|required|numeric|callback_validate_phone[' . $this->input->post('contact_number_code') . ']',
                'errors' => [
                    'validate_phone' => $this->lang->line('phone_should_be_unique')
                ]
            ],
            [
                'label' => $this->lang->line('alternate_contact_number_code'),
                'field' => 'alternate_contact_number_code',
                'rules' => 'trim|required'
            ],
            [
                'label'  => $this->lang->line('alternate_contact_number'),
                'field'  => 'alternate_contact_number',
                'rules'  => 'trim|required|numeric|callback_validate_alternate_phone[' . $this->input->post('alternate_contact_number_code') . ']',
                'errors' => [
                    'validate_alternate_phone' => $this->lang->line('phone_should_be_unique')
                ]
            ],
            [
                'label' => $this->lang->line('country'),
                'field' => 'country',
                'rules' => 'trim|required'
            ],
            [
                'label' => $this->lang->line('city'),
                'field' => 'city',
                'rules' => 'trim|required'
            ],
            [
                'label' => $this->lang->line('zipcode'),
                'field' => 'zipcode',
                'rules' => 'trim|required'
            ],
        ];

    }



    /**
     * Technician Rules
     *
     * @return void
     */
    private function technicianEmployeeRules()
    {
        return [
            [
                'label' => '',
                'field' => 'is_company_owner',
                'rules' => 'trim|required'
            ],
            [
                'label' => $this->lang->line('company_name'),
                'field' => 'company_name',
                'rules' => 'trim|required'
            ]
        ];

    }



    /**
     * Technicain Company Owner rules
     *
     * @return void
     */
    private function compannyOwnerRules()
    {
        return [
            [
                'label' => $this->lang->line('company_registration_number'),
                'field' => 'company_registration_number',
                'rules' => 'trim|required'
            ]
        ];

    }



    /**
     * Callback to validate unique phone number
     *
     * @param string $phone
     * @param string $country_code
     * @return boolean
     */
    public function validate_phone($phone, $country_code)
    {
        if (empty($country_code)) {
            return false;
        }

        $data = $this->Common_model->fetch_data('ai_user', 'user_id', ['where' => [
                'prm_user_countrycode' => $country_code,
                'phone'                => $phone
            ]], true);

        if (empty($data)) {
            return true;
        }
        else {
            return false;
        }

    }



    /**
     * Callback to validate unique alternate number
     *
     * @param string $phone
     * @param string $country_code
     * @return boolean
     */
    public function validate_alternate_phone($phone, $country_code)
    {
        if (empty($country_code)) {
            return false;
        }

        $data = $this->Common_model->fetch_data('ai_user', 'user_id', ['where' => [
                'alt_user_countrycode' => $country_code,
                'alt_userphone'        => $phone
            ]], true);

        if (empty($data)) {
            return true;
        }
        else {
            return false;
        }

    }



    public function validate_unique_email($email)
    {
        $data = $this->Common_model->fetch_data('ai_user', 'user_id', ['where' => [
                'email' => $email,
            ]], true);

        if (empty($data)) {
            return true;
        }

        return false;

    }



}
