<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Logout extends REST_Controller
{

    function __construct() 
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->library('form_validation');
    }

    /**
     * @SWG\Put(path="/user/logout",
     *   tags={"User"},
     *   summary="User logout",
     *   description="User logout",
     *   operationId="logout_put",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="query",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Response(response=200, description="Success"),
     * @SWG\Response(response=206, description="Unauthorized request"),     
     * @SWG\Response(response=207, description="Header is missing"),       
     * @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function index_put() 
    {
        $language_code = $this->langcode_validate();
        $putDataArr = $this->put();
        $head = $this->head();
        if ((!isset($head['accesstoken']) || empty(trim($head['accesstoken']))) && (!isset($head['Accesstoken']) || empty(trim($head['Accesstoken']))) ) {
            $this->response(
                [
                "code" => HTTP_UNAUTHORIZED,
                "api_code_result" => "UNAUTHORIZED",
                "msg" => $this->lang->line("invalid_access_token")
                ], HTTP_UNAUTHORIZED
            );
        }
        if (isset($head['Accesstoken']) && !empty($head['Accesstoken']) ) {
            $head['accesstoken'] = $head['Accesstoken'];
        }
        $config = [];
        
        /* $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            )
        ); */

        // $set_data = array(
        //     'accesstoken' => $this->put('accesstoken')
        // );

        // $this->form_validation->set_data($set_data);
        // $this->form_validation->set_rules($config);
        // /*
        //  * Setting Error Messages for rules
        //  */
        // $this->form_validation->set_message('required', 'Please enter the %s');

        try {
            $accessToken = $head['accesstoken'];
            $accessTokenArr = explode("||", $accessToken);
            $whereArr = [];
            $whereArr['where'] = ['public_key' => $accessTokenArr[0], 'private_key' => $accessTokenArr[1]];
            //                pr($whereArr);
            $isSuccess = $this->Common_model->update_single('ai_session', ['login_status' => 0], $whereArr);
            if ($isSuccess) {
                $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('logout_successful'), 'result' => (object)[]));
            } else {
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $this->lang->line('try_again'), 'result' => (object)[]));
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response(array('code' => $code, 'msg' => $msg, 'result' => (object)[]));
        }
    }

    private function langcode_validate()
    {
        $language_code = $this->head("X-Language-Code");
        $language_code = trim($language_code);
        $valid_language_codes = ["en","da","nb","sv","fi","fr","nl","de"];

        if (empty($language_code) ) {
            $this->response(
                [
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('header_missing'),
                'extra_info' => [
                    "missing_parameter" => "language_code"
                ]
                ]
            );
        }

        if (! in_array($language_code, $valid_language_codes) ) {
            $this->response(
                [
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('invalid_header'),
                'extra_info' => [
                    "missing_parameter" => $this->lang->line('invalid_language_code')
                ]
                ]
            );
        }

        $language_map = [
            "en" => "english",
            "da" => "danish",
            "nb" => "norwegian",
            "sv" => "swedish",
            "fi" => "finnish",
            "fr" => "french",
            "nl" => "dutch",
            "de" => "german"
        ];

        $this->load->language("common", $language_map[$language_code]);
        $this->load->language("rest_controller", $language_map[$language_code]);

        return $language_code;
    }

}
