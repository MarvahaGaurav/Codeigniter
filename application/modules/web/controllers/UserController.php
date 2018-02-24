<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once "BaseController.php";
class UserController extends BaseController
{

    private $userData;
    public function __construct()
    {
        parent::__construct();
        $this->active_session_required();
    }

    public function profile($user_id='')
    {
        try{
            $this->data['userInfo'] = $this->userInfo;
            
            $userData = $this->validateUser($user_id);

            $this->data['user'] = $userData;
            $this->data['user_id'] = $user_id;
            $userTypeMap = [];
            load_alternate_views("users/profile", $this->data);
            
        } catch ( DatabaseExceptions\SelectException $error ) {

        }
    }

    public function edit_profile($user_id = "") 
    {
        $this->data['userInfo'] = $this->userInfo;

        $userData = $this->validateUser($user_id);
        $this->load->helper("location");
        $countries = fetch_countries();
        $cities = fetch_cities($userData['country_id']);
        $this->data['countries'] = $countries;
        $this->data['cities'] = $cities;
        $this->data['user'] = $userData;
        $this->data['js'] = 'edit-profile';
        load_alternate_views("users/edit_profile", $this->data);
    }

    public function settings($user_id='')
    {
        $this->data['userInfo'] = $this->userInfo;

        $session_user_id = $this->session->userdata("sg_userinfo");
        if ( encryptDecrypt($user_id, 'decrypt') != $session_user_id['user_id']) {
            show_404();
            exit;
        }
       
        $userData = $this->validateUser($user_id);

        $this->load->helper("input_data");
        $post = $this->input->post();
        $post = trim_input_parameters($post);
        $this->userData = $userData;
        $this->load->library("form_validation");
        //this is required for callbacks to work with HMVC module
        $this->form_validation->CI =& $this;
        $validation_rules = $this->setValidation();
        $rules = $validation_rules['settings'];
        if ( isset($post['old_password']) && strlen($post['old_password']) >= 8 ) {
            $rules = array_merge($rules, $validation_rules['change-password']);
        }
        $this->form_validation->set_rules($rules);
        if ( $this->form_validation->run() ) {
            $this->load->model("User");
            if ( isset($post['old_password']) ) {
                $this->User->password = encrypt($post['new_password']);
            }

            $this->User->currency = $post['currency'];
            $this->User->language = $post['language'];

            $this->User->update(["user_id" => $userData['user_id']]);
            $this->session->set_flashdata("flash-message", $this->lang->line("settings_updated"));
            $this->session->set_flashdata("flash-type", "success");
            redirect(base_url("home/settings/" . encryptDecrypt($userData['user_id'])));
        }
        $this->data['user'] = $userData;
        $this->data['js'] = "settings";
        load_alternate_views("users/settings", $this->data);
    }

    private function validateUser($user_id) 
    {
        $userId = encryptDecrypt($user_id, 'decrypt');
        if ( !isset($userId) || empty($userId) ) {
            show_404();
            exit;
        }

        $this->load->model("UtilModel");

        $userData = $this->UtilModel->selectQuery(
            "*, cl.name as city_name, country.name as country_name",
            "ai_user",
            [
                "where" => ["user_id" => $userId, "status !=" => DELETED],
                "join" => [
                    "city_list as cl" => "cl.id=ai_user.city_id",
                    "country_list as country" => "country.country_code1=ai_user.country_id"
                ],
                "single_row" => true
            ]
        );
        
        if ( empty($userData) ) {
            show_404();
            exit;
        } 

        return $userData;
    }

    private function setValidation() {
        
        $rules = [
            'settings' => [
                [
                    'field' => 'currency',
                    'label' => 'Currency',
                    'rules' => 'required'
                ],
                [
                    'field' => 'language',
                    'label' => 'Language',
                    'rules' => 'required'
                ]
            ],
            'change-password' => [
                [
                    'field' => 'old_password',
                    'label' => 'Old Password',
                    'rules' => 'trim|required|callback_old_password_check',
                    'errors' => [
                        'old_password_check' => "Old password does not match"
                    ]
                    
                ],
                [
                    'field' => 'new_password',
                    'label' => 'New Password',
                    'rules' => 'trim|required|min_length[8]|max_length[30]|differs[old_password]',
                    'errors' => [
                        'validate_new_password' => "New password cannot be the same as old password"
                    ]
                ],
                [
                    'field' => 'confirm_password',
                    'label' => 'Confirm Password',
                    'rules' => 'trim|required|matches[new_password]'
                ]
            ]
        ];

        return $rules;
    }

    public function old_password_check($password) {
        $password = encrypt($password);
        $db_password = $this->userData['password'];
        
        if ( $db_password != $password ) {
            return FALSE;
        }
        return TRUE;
    }

}