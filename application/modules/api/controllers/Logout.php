<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Logout extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->library('form_validation');
    }

    /**
     * @SWG\Put(path="/Logout",
     *   tags={"Friends"},
     *   summary="User logout",
     *   description="User logout",
     *   operationId="logout_put",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="query",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Success"),
     *   @SWG\Response(response=206, description="Unauthorized request"),     
     *   @SWG\Response(response=207, description="Header is missing"),       
     *   @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function index_put() {

        $putDataArr = $this->put();

        $config = [];

        $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            )
        );

        $set_data = array(
            'accesstoken' => $this->put('accesstoken')
        );

        $this->form_validation->set_data($set_data);
        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {
            try {
                $accessToken = $putDataArr['accesstoken'];
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
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }

}
