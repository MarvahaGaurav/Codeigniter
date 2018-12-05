<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/Authentication.php';

class Signup extends Authentication {

    function __construct() {
        parent::__construct();
        $this->config->load('signup_key');
        $this->load->model('Common_model');
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->database();
    }

    /*
     *  function : signup_post 
     *  description : function to allow user sign-up 
     *                use configuration file sigup_key to 
      see the list of keys required to send
     *  param : email,password,first_name,dob
      FILE_UPLOAD param :  profile_image
     *  respose : json
     */

    public function signup_post() {


        $post_data = $this->post();
        $final_user_array = array();
        $insert = array();
        $return_arr = array();

        /*
         *  Mandatory fields for sign up as look into the config signup_key file to add mandatory fields 
         */
        $required_fields_arr = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email|is_unique[ai_user.email]'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'first_name',
                'label' => 'First Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'device_id',
                'label' => 'Device ID',
                'rules' => 'required'
            ),
            array(
                'field' => 'phone',
                'label' => 'Phone Number',
                'rules' => 'callback_validate_phone'
            ),
            array(
                'field' => 'dob',
                'label' => 'Dob',
                'rules' => 'callback_validate_dob'
            ),
        );

        $this->form_validation->set_rules($required_fields_arr);
        $this->form_validation->set_message('regex_match[/^[0-9]{10}$/]', 'The %s is not valid');
        $this->form_validation->set_message('is_unique', 'The %s is already registered with us');
        $this->form_validation->set_message('required', 'Please enter the %s');


        if ($this->form_validation->run()) {

            $pArray = $this->config->item('sign_keys');
            /*
             *  load possible sign-up data from the configuration sign_key file
             */
            $data = $this->defineDefaultValue($pArray, $post_data);
            /*
             *  empty unnecessay fields 
             */
            try {
                /*
                 *  upload profile pic option 
                 */
                if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image'])) {

                    $this->load->library('commonfn');
                    $config = [];
                    $config['upload_path'] = UPLOAD_IMAGE_PATH;
                    $config['allowed_types'] = 'jpeg|jpg|png';
                    $config['max_size'] = 3000;
                    $config['max_width'] = 1024;
                    $config['max_height'] = 768;
                    $config['encrypt_name'] = TRUE;

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('profile_image')) {

                        $upload_data = $this->upload->data();

                        $insert['image'] = $upload_data['file_name'];
                        $filename = $upload_data['file_name'];
                        $filesource = UPLOAD_IMAGE_PATH . $filename;
                        $targetpath = UPLOAD_THUMB_IMAGE_PATH;
                        $isSuccess = $this->commonfn->thumb_create($filename, $filesource, $targetpath);
                        if ($isSuccess) {
                            $insert['image_thumb'] = $upload_data['file_name'];
                        }
                    } else {
                        $this->response_data([], ERROR_UPLOAD_FILE, '', strip_tags($this->upload->display_errors()));
                    }
                }

                // fetch db user key and map data with it 
                $this->db->trans_begin();
                $final_user_array = $this->GET_user_arr($insert, $data);

                $session_arr = $this->GET_session_arr($data);
                $return_arr = $this->User_model->Insert_userData($final_user_array, $session_arr);

                if ($this->db->trans_status() === TRUE) {
                    $this->db->trans_commit();
                } else {
                    $this->db->trans_rollback();
                    throw new Exception("account_creation_unsuccessful || " . ERROR_INSERTION);
                }
            } catch (Exception $e) {

                $error = $e->getMessage();
                list($msg, $code) = explode(" || ", $error);
                $this->response_data([], $code, $msg);
            }

            $this->response_data($return_arr, SUCCESS_REGISTRATION, 'account_creation_successful');
            // pass data,code,extrainfo and language key
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response_data([], PARAM_REQ, '', $arr[0]);
        }
    }

    /*
     * Custom Rule Validate Phone
     * @param: Phone number
     */

    function validate_phone($phone) {
        if (isset($phone) && !preg_match("/^[0-9]{10}$/", $phone) && !empty($phone)) {
            $this->form_validation->set_message('validate_phone', 'This {field} is not valid');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /*
     * Custom Rule Validate Dob
     * @param: user dob
     */

    function validate_dob($dob) {
        if (isset($dob) && !$this->isValidDateTimeString($dob, 'm/d/Y', 'UTC') && !empty($dob)) {
            $this->form_validation->set_message('validate_dob', 'This {field} should in m/d/Y format');
            return false;
        } else {
            return true;
        }
    }

    /*
     * 
     * function : GET_user_arr
     * description : function to get user array to store in database 
     * param : insert array , data array 
     */

    private function GET_user_arr($insert = array(), $data = array()) {

        $insert_new = [
            "first_name" => $data["first_name"],
            "middle_name" => $data["middle_name"],
            "last_name" => $data["last_name"],
            "email" => $data["email"],
            "gender" => $data["gender"],
            "biography" => $data["biography"],
            "dob" => strtotime($data["dob"]),
            "age" => $data["age"],
            "phone" => $data["phone"],
            "country_code" => $data['country_code'],
            "password" => $this->encrypt($data["password"]),
            "username" => $data['username'],
            "image" => isset($insert['image']) ? $insert['image'] : "",
            "image_thumb" => isset($insert['image_thumb']) ? $insert['image_thumb'] : "",
            'registered_date' => date('Y-m-d H:i:s'),
            "status" => ACTIVE_USER
        ];

        return array_merge($insert, $insert_new);
    }

    private function GET_session_arr($data) {


        $session = [
            "user_id" => 0,
            "device_id" => $data["device_id"],
            "device_token" => $data["device_token"],
            "ipaddress" => $data["ipaddress"],
            "device_model" => $data["device_model"],
            "imei" => $data["imei"],
            "os_version" => $data["os_version"],
            "platform" => $data["platform"],
            "network" => $data["network"],
            "app_version" => $data['app_version'],
            "country_code" => $data['country_code'],
            "region" => $data['region'],
            "city" => $data['city'],
            "postal_code" => $data['postal_code'],
            "longitude" => $data['longitude'],
            "latitude" => $data['latitude'],
        ];

        return $session;
    }

}
