<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/Authentication.php';

class Login extends Authentication {

    function __construct() {
        parent::__construct();
        $this->config->load('signup_key');
        $this->load->model('Common_model');
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->database();
    }

    /*
     *
     * function : login 
     * description : function to login page 
     * param : null
     *
     *
     */

    public function login_post() {

        $post_data = $this->post();

        if (isset($post_data) && !empty($post_data)) {

            $config = array(
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
                    'label' => 'Device ID',
                    'rules' => 'required'
                ),
            );




            $this->form_validation->set_rules($config);
            if ($this->form_validation->run()) {

                try {

                    $encrypt_pass = $this->encrypt($post_data["password"]);
                    $email = filter_var($post_data['email'], FILTER_VALIDATE_EMAIL);
                    $result = $this->Common_model->fetch_data('ai_user', 'user_id,first_name,email,image,status', array('where' => array('email' => $email, 'password' => $encrypt_pass)), true);

                    if (count($result) != 0) {

                        // check active or inactive status 

                        if ($result['status'] == ACTIVE_USER) {

                            $pArray = $this->config->item('sign_keys'); // load possible sign-up data from the configuration sign_key file
                            $data = $this->defineDefaultValue($pArray, $post_data); // empty unnecessay fields 	
                            $update_device_data = $this->GET_session_arr($data, $result['user_id']); // get session data to update in device
                            $access_token = $this->create_access_token($result['user_id'], $email);
                            $update_device_data['public_key'] = $access_token['public_key'];
                            $update_device_data['private_key'] = $access_token['private_key'];

                            $login_type = $this->config->item('LOGIN_TYPE');

                            $this->db->trans_begin();
                            if ($login_type == SINGLE_LOGIN) {

                                $this->Common_model->update_single('ai_session', $update_device_data, array('where' => array('user_id' => $result['user_id'])));
                            } else if ($login_type == MULTIPLE) {

                                // check if device data  exists or not 
                                $res = $this->Common_model->fetch_data('ai_session', 'device_id', array('where' => array('device_id' => $data['device_id'])), true);

                                if (count($res)) {
                                    // do nothing 
                                } else {

                                    $this->Common_model->insert_single('ai_session', $update_device_data);
                                }
                            }

                            if ($this->db->trans_status() === TRUE) {

                                $this->db->trans_commit();
                            } else {

                                $this->db->trans_rollback();
                                throw new Exception("account_creation_unsuccessful || " . ERROR_INSERTION);
                            }


                            $result['accesstoken'] = $update_device_data['private_key'] . '||' . $update_device_data['public_key'];
                            $this->response_data($result, SUCCESS_LOGIN, 'login_successful');
                        } else {

                            throw new Exception('login_unsuccessful_not_active || ' . INACTIVE_USER);
                        }
                    } else {

                        throw new Exception('login_unsuccessful || ' . INVALID_LOGIN);
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    list($msg, $code) = explode(" || ", $error);
                    $this->response_data([], $code, $msg);
                }
            } else {

                $err = $this->form_validation->error_array();
                $message = array_values($err);
                $this->response_data([], PARAM_REQ, '', $message[0]);
            }
        } else {
            $this->response_data([], PARAM_REQ, 'REQUIRED_PARAMETER_MISSING');
        }
    }

    /*
     * function : GET_session_arr
     * description : function to get possible device data
     * 
     */

    private function GET_session_arr($data, $user_id) {


        $session = [
            "user_id" => $user_id,
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
