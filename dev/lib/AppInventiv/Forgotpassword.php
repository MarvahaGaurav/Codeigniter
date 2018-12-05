<?php

namespace AppInventiv;

use AppInventiv\database\Db;
use AppInventiv\model\Usermodel;

use AppInventiv\Rest;
use Exception;
//include 'Commonmailfunction.php';

class Forgotpassword extends Rest {

    private $config;
    private $db;
    private $Usermodel;
    private $mailler;

    public function __construct() {
        parent::__construct();

        $this->db = new Db();
        $this->Usermodel = new Usermodel();
        //$this->mailler = new Commonmailfunction();
        
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
     * @DateCreated: 02/08/2017
     */
    private function Forgotpassword() {

        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            } else {
                
            }
            //Get all request Data
            $data = $this->_request;
            $this->checkEmptyParameter($data, ["email"]);
            $this->validateData(["email" => $data["email"]]);

            $this->db->beginTransaction();
            
            // check if user exists 
            $isUserExist = $this->Usermodel->getRecordExists(["email" => $data['email']]);
            
            if (count($isUserExist) > 0) {
                $condition = 'email=' . "'".$data["email"]."'";
                $user= [
                    'reset_token' => (int)mt_rand(100000,999999),
               
               'reset_date'=> date('Y-m-d H:i:s',time())];    
            //    $user = json_encode($user);
                //$user = "reset_otp=".mt_rand(100000,999999)."reset_date=".date('Y-m-d H:i:s',time());
                $query_run = $this->db->updatedata('ai_user', $user, $condition);
                //send mail 
               //$this->mailler->send_mail($data['email'],$user['reset_token']);
                $this->db->executeTransaction();
            } else {
                 throw new Exception("Invalid User || 212");
            }
        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        
        $this->response(["otp"=>$user["reset_token"]], 200, [], "Reset OTP has been sent to your mail and mobile");
    }

}
