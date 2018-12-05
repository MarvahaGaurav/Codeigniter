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
                'rules' => 'trim|required|valid_email|is_unique[ai_user.email]'
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
//            array(
//                'field' => 'phone',
//                'label' => 'Phone Number',
//                'rules' => 'callback_validate_phone'
//            ),
//            array(
//                'field' => 'dob',
//                'label' => 'Dob',
//                'rules' => 'callback_validate_dob'
//            )
        );

        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_rules($required_fields_arr);
        $this->form_validation->set_message('is_unique', 'The %s is already registered with us');
        $this->form_validation->set_message('required', 'Please enter the %s');


        if ($this->form_validation->run()) {
            try {

                $signupArr = [];
                $signupArr["first_name"] = $postDataArr["first_name"];
                $signupArr["middle_name"] = isset($postDataArr["middle_name"]) ? $postDataArr["middle_name"] : "";
                $signupArr["last_name"] = isset($postDataArr["last_name"]) ? $postDataArr["last_name"] : "";
                $signupArr["email"] = $postDataArr["email"];
                $signupArr["gender"] = isset($postDataArr["gender"]) ? $postDataArr["gender"] : "";
                $signupArr["biography"] = isset($postDataArr["biography"]) ? $postDataArr["biography"] : "";
                $signupArr["dob"] = isset($postDataArr["dob"]) ? strtotime($postDataArr["dob"]) : "";
                $signupArr["age"] = isset($postDataArr["age"]) ? $postDataArr["age"] : "";
                $signupArr["phone"] = isset($postDataArr["phone"]) ? $postDataArr["phone"] : "";
                $signupArr["country_code"] = isset($postDataArr['country_code']) ? $postDataArr['country_code'] : "";
                $signupArr["password"] = encrypt($postDataArr["password"]);
                $signupArr["registered_date"] = date('Y-m-d H:i:s');

                /*
                 *  upload profile pic option 
                 */
                if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image'])) {
                    /*
                     * get configuration file for upload (common helper)
                     * @params: Target Upload Path,Accepted Format,Max Size,Max Width,Max Hieght,Encrpt Name
                     */
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
                }

                $userId = $this->Common_model->insert_single('ai_user', $signupArr);
                $postDataArr['user_id'] = $userId;
                /*
                 * Generate Public and Private Access Token
                 */
                $accessToken = create_access_token($userId, $signupArr['email']);

                $signupArr['private_key'] = $accessToken['private_key'];
                $signupArr['public_key'] = $accessToken['public_key'];
                $signupArr["image"] = isset($signupArr['image']) ? IMAGE_PATH . $signupArr['image'] : "";
                $signupArr["image_thumb"] = isset($signupArr['image_thumb']) ? THUMB_IMAGE_PATH . $signupArr['image_thumb'] : "";
                /*
                 * 
                 */
                $sessionArr = $this->setSessionVariables($postDataArr, $accessToken);
                /*
                 * Insert Session Data
                 */
                $sessionId = $this->Common_model->insert_single('ai_session', $sessionArr);
                if (!empty($sessionId) && !empty($userId)) {
                    unset($signupArr['password']);
                    $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('registration_success'), 'result' => $signupArr));
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                list($msg, $code) = explode(" || ", $error);
                $this->response(array('code' => $code, 'msg' => $msg, 'result' => []));
            }
            // pass data,code,extrainfo and language key
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => []));
        }
    }

    /*
     * 
     * function : GET_user_arr
     * description : function to get user array to store in database 
     * param : insert array , data array 
     */

    private function GET_user_arr($insert = array(), $postDataArr = array()) {

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

    private function setSessionVariables($data, $accessToken) {

        $sessionDataArr = [
            "user_id" => $data['user_id'],
            "device_id" => isset($data["device_id"]) ? $data["device_id"] : "",
            "device_token" => isset($data["device_token"]) ? $data["device_token"] : "",
            "ipaddress" => isset($data["ipaddress"]) ? $data["ipaddress"] : "",
            "platform" => isset($data["platform"]) ? $data["platform"] : "",
            "network" => isset($data["network"]) ? $data["network"] : "",
            "app_version" => isset($data['app_version']) ? $data['app_version'] : "",
            "public_key" => isset($data['public_key']) ? $data['public_key'] : "",
            "private_key" => isset($data['private_key']) ? $data['private_key'] : "",
        ];
        return $sessionDataArr;
    }

    /*
     * Custom Rule Validate Phone
     * @param: Phone number
     */

    public function validate_phone($phone) {
        
        if (isset($phone) && !preg_match("/^[0-9]{10}$/", $phone) && !empty($phone)) {
            $this->form_validation->set_message('validate_phone', 'This {field} is not valid');
            return false;
        } else {
            return true;
        }
    }

    /*
     * Custom Rule Validate Dob
     * @param: user dob
     */

    public function validate_dob($dob) {
        if (isset($dob) && !$this->isValidDateTimeString($dob, 'm/d/Y', 'UTC') && !empty($dob)) {
            $this->form_validation->set_message('validate_dob', 'This {field} should in m/d/Y format');
            return false;
        } else {
            return true;
        }
    }

}
