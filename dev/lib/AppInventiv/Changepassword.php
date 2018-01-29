<?php

namespace AppInventiv;

use AppInventiv\database\Db;
use AppInventiv\model\Usermodel;
use AppInventiv\Rest;
use \Exception;

class Changepassword extends Rest {

    private $config;
    private $db;
    private $Usermodel;
    private $mail;

    public function __construct() {
        parent::__construct();

        $this->db = new Db();
        $this->Usermodel = new Usermodel();
        $this->processApi();
    }

    private function processApi() {

        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));

        if ((int) method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 404);    // If the method not exist with in this class, response would be "Page not found".
    }

    /**
     * @FunctionName : changepassword
     * @params - string : mobile
     * @response - json result
     */
    private function changepassword() {

        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            } else {
                
            }
            //Get all request Data
            $data = $this->_request;
            $this->checkEmptyParameter($data, ["password", "oldpassword"]);
            

            $header_arr = getallheaders();

            $header_arr = array_change_key_case($header_arr, CASE_LOWER);
            if (isset($header_arr['accesstoken']) && !empty($header_arr['accesstoken'])) {
                $accesstoken = explode('/*/', $header_arr['accesstoken']);

                $public_key = $accesstoken[0];
                $private_key = $accesstoken[1];
                $isUserLoggedIn = $this->Usermodel->getRecords(["public_key" => $public_key, 'private_key' => $private_key], 'ai_session');
                if (!count($isUserLoggedIn) > 0) {
                    $this->response([], 210);
                } else {
                    // to do
                }
            } else {
                $this->response([], 210);
            }
            $this->checkEmptyParameter($data, ["oldpassword"]);


            $this->db->beginTransaction();
            $isUserExist = $this->Usermodel->getRecordExists(["user_id" => $isUserLoggedIn['user_id']]);
           

            if (false === $isUserExist) {
                throw new Exception("User does not exists || 210");
            }
            if ($isUserExist['user_status'] == 2) {
                throw new Exception("Invalid User || 212");
            }

            $email = $isUserExist['email'];
            $oldpassword = $isUserExist['password'];
            $user_id = $isUserExist['user_id'];

            $encrypt_pass = $this->encrypt($data["password"]);
            //check old password 
            $encrypt_pass_old = $this->encrypt($data["oldpassword"]);
            if ($oldpassword != $encrypt_pass_old) {
                throw new Exception("Old password is wrong. || 210");
            }
            if ($oldpassword == $encrypt_pass) {
                throw new Exception("Old password and New password cannot be same||210");
            }

            $user = [
                "password" => $encrypt_pass
            ];


            $condition = 'user_id =' . $user_id;
            $query_run = $this->db->update('ai_user', $user, $condition);

            $this->db->executeTransaction();
        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        $response_data['user_id'] = $user_id;
        $this->response([], 200, [], "Change Password is successful");
    }

}
