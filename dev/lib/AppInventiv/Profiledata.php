<?php

namespace AppInventiv;

use AppInventiv\database\Db;
use AppInventiv\model\Usermodel;
use AppInventiv\Rest;
use \Exception;

class Profiledata extends Rest {

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
    private function profiledata() {

        try {
            if ($this->get_request_method() != "GET") {
                $this->response([], 405);
            } else {
                
            }
            //Get all request Data
            $data = $this->_request;
            //$this->checkEmptyParameter($data, ["password", "oldpassword"]);


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



            $isUserExist = $this->Usermodel->getRecordExists(["user_id" => $isUserLoggedIn['user_id']]);
            

            if (false === $isUserExist) {
                throw new Exception("User does not exists || 210");
            }
            if ($isUserExist['user_status'] == 2) {
                throw new Exception("Invalid User || 212");
            } else {
                $user_data= [
                    'first_name' => isset($isUserExist['first_name']) && !empty($isUserExist['first_name'])?($isUserExist['first_name']):'',
                    'middle_name' => isset($isUserExist['middle_name']) && !empty($isUserExist['middle_name'])?($isUserExist['middle_name']):'',
                    'last_name' => isset($isUserExist['last_name']) && !empty($isUserExist['last_name'])?($isUserExist['last_name']):'',
                    'gender' => isset($isUserExist['gender']) && !empty($isUserExist['gender'])?($isUserExist['gender']):'',
                    'email' => isset($isUserExist['email']) && !empty($isUserExist['email'])?($isUserExist['email']):'',
                    'image' => isset($isUserExist['image']) && !empty($isUserExist['image'])?($isUserExist['image']):'',
                    'username' => isset($isUserExist['username']) && !empty($isUserExist['username'])?($isUserExist['username']):''
                    ]; 
            }
            
            
           
        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        $response_data['user_id'] = $user_id;
        $this->response([], 200, $user_data, "Success");
    }

}
