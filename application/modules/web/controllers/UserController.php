<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once "BaseController.php";

class UserController extends BaseController
{

    private $userData;
    public function __construct()
    {
        parent::__construct();
        $this->activeSessionGuard();
        $this->data['activePage'] = 'users';
    }

    /**
     * User Profile
     *
     * @param string $user_id
     * @return void
     */
    public function profile($user_id = '')
    {
        try {
            $this->data['userInfo'] = $this->userInfo;
            
            $userData = $this->validateUser($user_id);
            if ($userData['company_id'] > 0) {
                $whereArr['where'] = ['company_id'=>$userData['company_id']];
                $compnaydetail = $this->Common_model->fetch_data('company_master', '*', $whereArr, true);
            } else {
                $compnaydetail = [];
            }
            $this->data['user'] = $userData;
            $this->data['compnaydetail'] = $compnaydetail;
            $this->data['user_id'] = $user_id;
            $userTypeMap = [];
            load_website_views("users/profile", $this->data);
        } catch (DatabaseExceptions\SelectException $error) {
        }
    }

    public function edit_profile($user_id = "")
    {
        $this->data['userInfo'] = $this->userInfo;

        $userData = $this->validateUser($user_id);
        
        // dd($userData);

        if ($userData['company_id'] > 0) {
            $whereArr['where'] = ['company_id'=>$userData['company_id']];
            $compnaydetail = $this->Common_model->fetch_data('company_master', '*', $whereArr, true);
        } else {
            $compnaydetail = [];
        }
        $this->load->helper(["location", "input_data"]);
        $countries = fetch_countries();
        
        $this->data['countries'] = $countries;
        $cities = fetch_cities($userData['country_id'], ['where' => ['id' => $userData['city_id']]]);
        // echo $this->db->last_query();die;
        $this->data['cities'] = $cities;
        $this->data['user'] = $userData;
        $this->data['compnaydetail'] = $compnaydetail;
        $this->data['js'] = 'edit-profile';

        /* form validation load */
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;
        $validation_rules = $this->edit_profile_validation();
        $rules = $validation_rules['basic_details'];
        $dataArr = $this->input->post();
        $dataArr = trim_input_parameters($dataArr);
        if (isset($dataArr['company_name'])) {
            $rules = array_merge($rules, $validation_rules['company_details']);
        }

        $this->form_validation->set_rules($rules);
        $this->form_validation->set_data($dataArr);
        // if ($this->input->post()) {
        // dd($this->form_validation->run(), false);
        // dd(validation_errors(), false);
        // dd($dataArr);
        // dd(validation_errors());
        if (!empty($dataArr) && $this->form_validation->run()) {
            // dd($dataArr);
            $this->db->trans_begin();
            if (isset($dataArr['company_id']) && !empty($dataArr['company_id'])) {
                /* Company update here */
                $companyArr['company_reg_number'] = $dataArr['company_reg_number'];
                $companyArr['company_name'] = $dataArr['company_name'];
                $companyArr['country'] = $dataArr['country'];
                $companyArr['city'] = $dataArr['city'];
                $companyArr['zipcode'] = $dataArr['zip_code'];
                
                if (isset($dataArr['company_image']) && !empty($dataArr['company_image'])) {
                    $this->load->helper("images");
                    try {
                        $companyArr['company_image'] = $imageName=s3_image_uploader(ABS_PATH.$dataArr['company_image'], $dataArr['company_image']);
                    } catch (Exception $e) {
                        $this->data['error'] = strip_tags($this->form_validation->display_errors());
                        $this->session->set_flashdata("flash-message", $e->getMessage());
                        $this->session->set_flashdata("flash-type", "danger");
                        load_alternatecropper_views("users/edit_profile", $this->data);
                    }
                } else {
                    //$companyArr['company_image'] = isset($dataArr['prevcompimg']) && !empty($dataArr['prevcompimg'])?$dataArr['prevcompimg']:"";
                }

                $this->Common_model->update_single('company_master', $companyArr, array('where'=>array('company_id'=>$dataArr['company_id'])));
                /* Company update here */
            }
            /* userdata update here */
            $userDataArr['first_name'] = $dataArr['name'];
            $userDataArr['prm_user_countrycode'] = $dataArr['prmccode'];
            $userDataArr['phone'] = $dataArr['phone'];
            $userDataArr['alt_user_countrycode'] = $dataArr['altccode'];
            $userDataArr['alt_userphone'] = $dataArr['alt_phone'];
            $userDataArr['country_id'] = $dataArr['country'];
            $userDataArr['city_id'] = $dataArr['city'];
            $userDataArr['zipcode'] = $dataArr['zip_code'];
            if (isset($dataArr['imgurl']) && !empty($dataArr['imgurl'])) {
                $this->load->helper("images");
                try {
                    $userDataArr['image'] = $imageName=s3_image_uploader(ABS_PATH.$dataArr['imgurl'], $dataArr['imgurl']);
                } catch (Exception $e) {
                    $this->data['error'] = strip_tags($this->upload->display_errors());
                    $this->session->set_flashdata("flash-message", $e->getMessage());
                    $this->session->set_flashdata("flash-type", "danger");
                    load_alternatecropper_views("users/edit_profile", $this->data);
                }
            } else {
                //$userData['image'] = isset($dataArr['previmg']) && !empty($dataArr['previmg'])?$dataArr['previmg']:"";
            }
            
            //echo '<pre>'; print_r($userDataArr);  die;
            $updateuserdata = $this->Common_model->update_single('ai_user', $userDataArr, array('where'=>array('user_id'=>$userData['user_id'])));
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                if ($updateuserdata) {
                    $this->session->set_flashdata("flash-message", 'Profile Update Successfully');
                    $this->session->set_flashdata("flash-type", "success");
                    redirect(base_url("home/profile/" . $user_id));
                    //redirect(base_url("users/settings/" . encryptDecrypt($user_id)));
                    //load_alternatecropper_views("users/edit_profile", $this->data);
                } else {
                    $this->session->set_flashdata("flash-message", 'Profile not updated!');
                    $this->session->set_flashdata("flash-type", "danger");
                    load_alternatecropper_views("users/edit_profile", $this->data);
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata("flash-message", 'Profile not updated!');
                $this->session->set_flashdata("flash-type", "danger");
                load_alternatecropper_views("users/edit_profile", $this->data);
            }
        } else {
        }
        
        load_alternatecropper_views("users/edit_profile", $this->data);
    }

    /**
     * Handles User Settings
     *
     * @param string $user_id
     * @return void
     */
    public function settings($user_id = '')
    {
        $this->data['userInfo'] = $this->userInfo;

        $session_user_id = $this->session->userdata("sg_userinfo");
        if (encryptDecrypt($user_id, 'decrypt') != $session_user_id['user_id']) {
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
        if (!empty($post)) {
            $this->form_validation->CI =& $this;
            $validation_rules = $this->setValidation();
            $rules = $validation_rules['settings'];
            if (isset($post['old_password']) && strlen($post['old_password']) >= 8) {
                $rules = array_merge($rules, $validation_rules['change-password']);
            }
            $this->form_validation->set_rules($rules);
            $valid = $this->form_validation->run();
            if ((bool)$valid) {
                $this->load->model("User");
                if (isset($post['old_password'])) {
                    $this->User->password = encrypt($post['new_password']);
                }

                if (isset($post['discount_price']) && (int)$this->userInfo['is_owner'] === ROLE_OWNER && (int)$this->userInfo['user_type'] === INSTALLER) {
                    $discountPrice = sprintf("%.2f", $post['discount_price']);
                    $this->UtilModel->updateTableData([
                        'company_discount' => $discountPrice
                    ], 'company_master', [
                        'company_id' => $this->userInfo['company_id']
                    ]);
                }

                $this->User->currency = $post['currency'];
                $this->User->language = $post['language'];

                $this->User->update(["user_id" => $userData['user_id']]);
                $this->session->set_flashdata("flash-message", $this->lang->line("settings_updated"));
                $this->session->set_flashdata("flash-type", "success");
                redirect(base_url("home/settings/" . encryptDecrypt($userData['user_id'])));
            } else {
                
            }
        }
        $this->data['user'] = $userData;
        $this->data['js'] = "settings";
        $this->data['discount_price'] = 0;

         if ((int)$this->userInfo['is_owner'] === ROLE_OWNER && (int)$this->userInfo['user_type'] === INSTALLER) {
             $discountPrice = $this->UtilModel->selectQuery('company_discount', 'company_master', [
                 'where' => ['company_id' => $this->userInfo['company_id']], "single_row" => true
             ]);
             $this->data['discount_price'] = $discountPrice['company_discount'];
         }
        
        load_website_views("users/settings", $this->data);
    }
    
    private function validateUser($user_id)
    {
        $userId = encryptDecrypt($user_id, 'decrypt');
        if (!isset($userId) || empty($userId)) {
            show_404();
            exit;
        }

        $this->load->model("UtilModel");

        $userData = $this->UtilModel->selectQuery(
            "*, cl.name as city_name, country.name as country_name",
            "ai_user",
            [
                "where" => ["user_id" => $this->userInfo['user_id'], "status !=" => DELETED],
                "join" => [
                    "city_list as cl" => "cl.id=ai_user.city_id",
                    "country_list as country" => "country.country_code1=ai_user.country_id"
                ],
                "single_row" => true
            ]
        );
        
        if (empty($userData)) {
            show_404();
            exit;
        }

        return $userData;
    }

    private function setValidation()
    {
        
        $rules = [
            'settings' => [
                [
                    'field' => 'discount_price',
                    'label' => 'Discount Price',
                    'rules' => 'trim|greater_than_equal_to[0]|less_than_equal_to[100]'
                ]
                // [
                //     'field' => 'currency',
                //     'label' => 'Currency',
                //     'rules' => 'required'
                // ],
                // [
                //     'field' => 'language',
                //     'label' => 'Language',
                //     'rules' => 'required'
                // ]
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

    public function old_password_check($password)
    {
        $password = encrypt($password);
        $db_password = $this->userData['password'];
        
        if ($db_password != $password) {
            return false;
        }
        return true;
    }

    private function edit_profile_validation()
    {
        return [
            'basic_details' => [
                [
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required|max_length[255]'
                ],
                [
                    'field' => 'prmccode',
                    'label' => 'Phone Code',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'phone',
                    'label' => 'Phone',
                    'rules' => 'trim|required|max_length[20]'
                ],
                [
                    'field' => 'altccode',
                    'label' => 'Alternate Phone Code',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'alt_phone',
                    'label' => 'Alternate Code',
                    'rules' => 'trim|required|max_length[20]'
                ],
                [
                    'field' => 'country',
                    'label' => 'Country',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'city',
                    'label' => 'City',
                    'rules' => 'trim|required'
                ],
                [
                    'field' => 'zip_code',
                    'label' => 'Zip Code',
                    'rules' => 'trim|required|max_length[10]'
                ]
            ],
            'company_details' => [
                [
                    'field' => 'company_name',
                    'label' => 'Company Name',
                    'rules' => 'trim|required|max_length[255]'
                ],
                [
                    'field' => 'company_reg_number',
                    'label' => 'Company Registration Number',
                    'rules' => 'trim|required|alpha_numeric|max_length[20]'
                ]
            ]
        ];
    }
}
