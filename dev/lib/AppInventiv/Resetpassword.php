<?php

namespace AppInventiv;

use AppInventiv\database\Db;
use AppInventiv\model\Usermodel;
use AppInventiv\Rest;
use \Exception;

class Resetpassword extends Rest {

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
    private function resetpassword() {

        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            } else {
                
            }
            //Get all request Data
            $data = $this->_request;
            $this->checkEmptyParameter($data, ["otp", "password", "email"]);
            
            $email = $data['email'];
            $oldpassword = $data['password'];
            $otp = $data['otp'];
            
            $this->db->beginTransaction();
             
            $isUserExist = $this->Usermodel->getRecordExists(["email" => $email,"reset_token"=>$otp]);
          

            if (false === $isUserExist) {
                $this->response([], 210);
            }
            if ($isUserExist['user_status'] == 2) {
                $this->response([], 212);
            }

            //encrypt new password
            $encrypt_pass = $this->encrypt($data["password"]);

            $user = [
                "password" => $encrypt_pass,
                "reset_token" => ""
            ];
            

            $condition = 'email ='."'".$email."'";
            
            $query_run = $this->db->update('ai_user', $user, $condition);
            
            $this->db->executeTransaction();
        } catch (Exception $ex) {
           
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            
            $this->response([], $code, [], $msg);
        }
        //$response_data['user_id'] = $user_id;
       
        $this->response([], 200, [], "Change Password is successful");
    }

}
