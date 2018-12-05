<?php

namespace AppInventiv;

use AppInventiv\Rest;


use \Exception;


class ValidatePhone extends Rest {

    public function __construct() {
        
        parent::__construct();
       
        
        $this->processApi();
    }

    //call to a member function
    private function processApi() {

        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));

        if ((int) method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 404);    // If the method not exist with in this class, response would be "Page not found".
    }

    /**
     * @FunctionName : ValidatePhone
     * @params - string : mobile
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    private function ValidatePhone() {
        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }
            $data = $this->_request;

            //Required Keys in array
            $this->checkEmptyParameter($data, ["mobile"]);

            //validate mobile number
            $phoneIntel = $this->validateMobile($data['mobile']);
            
            /* Condition 
             * if number is valid mobile number return valid
             * else return invalid
             */
            if($phoneIntel['valid'] == 1 && $phoneIntel['line_type'] == "mobile") {
                $arr = ["is_valid"=>1];
                
                $this->response($arr, 200, [], $this->lang('valid_number'));
            } else {
                 $arr = ["is_valid"=>2];
                $this->response($arr, 200, [], $this->lang('invalid_number'));
            }
        } catch (Exception $ex) {

            $error = $e->getMessage();

            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
    }

}
