<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * get rest controller file
 */
require_once APPPATH . "libraries/REST_Controller.php";

class Rcc_Controller extends REST_Controller {
 
    
    function __construct() {
        parent::__construct();
        
         /**
         * load models
         */
        $this->load->model("Common_model","cm");
        /**
         * load language
         */
        $this->load->language("common_lang");
     
    }
    
    
    

    /**
     * authenticate user through access token and get user details
     * 
     * @param  void 
     * @return void 
     * 
     * 
     */
    protected function authenticate_user() {
        $head = $this->head();
       
        try {
            if (!isset($head['Accesstoken']) && empty($head['Accesstoken']) ||
                    !is_string($head['Accesstoken'])) {
                $this->response(['code' => UNAUTHORIZED_ACCESS,
                    'message' => $this->lang->line('unauthorized_access')]);
            }
            $token = array_filter(explode("||", $head['Accesstoken']));
            /**
             * get private and public key
             */
            if (isset($token) && !empty($token) && is_array($token) && count($token) === 2) {
                /**
                 * public and private key
                 */
                $this->private = $token[1];
                $this->public = $token[0];
            } else {
                throw new Exception($this->lang->line('unauthorized_access'), UNAUTHORIZED_ACCESS);
            }
            /**
             * get logged in user data
             */
           $login_user = $this->cm->fetch_data("ai_session", "user_id,"
                    . "platform,device_token", array("where" =>
                array("public_key" => $this->public,
                    "private_key" => $this->private,
                    "login_status" => 1)), TRUE);
           
            /**
             * check for user details
             */
            if (!isset($login_user) && empty($login_user) &&
                    !is_array($login_user)) {
                $this->response(['code' => UNAUTHORIZED_ACCESS,
                    'message' => $this->lang->line('unauthorized_access')]);
            }else{
                return $login_user;
            }
        } catch (Exception $ex) {
            /**
             * log message
             */
            log_message("error", $ex->getMessage());

            $this->response(["code" => $ex->getCode(), "message" => $ex->getMessage()]);
        }
    }
    
    
    
     
    /**
     *  check for mandatory fields
     * @param array $param data which is posted
     * @param array $mandatory_fields array of fields which is mandatory
     * @param array $any_one all the fields from which at least one is mandatory (optional)
     * @return boolean
     */
    protected function check_mandatory($param, $mandatory_fields, $any_one = array()) {
        /**
         * extract fields from post data
         */
        if (isset($param) && !empty($param)) {
            $keys = array_keys($param);
        }
        /**
         * check for common fields in posted fields and mandatory fields
         */
        if (isset($mandatory_fields) && !empty($mandatory_fields) &&
                isset($keys) && !empty($keys) && is_array($keys)) {
            $result = array_intersect($keys, $mandatory_fields);
        }

        /**
         * check difference between mandatory fields array and common 
         * fields array
         * 
         * if difference of result array and mandatory field array is none 
         * return true else return false for param is missing
         */
        if (!empty($result) && empty(array_diff($mandatory_fields, $result))) {
            return TRUE;
        } else {
            return FALSE;
        }

        if (isset($any_one) && !empty($any_one) && is_array($any_one)) {
            /**
             * get common element
             */
            $result2 = array_intersect($keys, $any_one);
            if (isset($result2) && !empty($result2) &&
                    is_array($result2) && count($result2) > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
    
}