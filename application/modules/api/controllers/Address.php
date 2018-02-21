<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Address extends REST_Controller 
{

    function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
    }

    /**
     * @SWG\Get(path="/address",
     *   tags={"Address"},
     *   summary="Get address information",
     *   description="Get country,state and city list",
     *   operationId="address_get",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="country_code",
     *     in="query",
     *     description="hit with country code to get list of state belongs to it",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="state_code",
     *     in="query",
     *     description="hit it with country code to get list of city belongs to it",
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Success"),
     *   @SWG\Response(response=201, description="Please try again"),       
     *   @SWG\Response(response=206, description="Unauthorized request"),     
     *   @SWG\Response(response=207, description="Header is missing"),             
     * )
     */
    public function index_get() {
        $language_code = $this->langcode_validate();
        $getDataArr = $this->input->get();
        /*
         * Dont send any state or country code for getting the list of country
         */
        try {
            $getDataArr = $this->get();
            $listType = '';
            $listData = '';
            $country_code = isset($getDataArr['country_code']) ? $getDataArr['country_code'] : "";
            $state_code = isset($getDataArr['state_code']) ? $getDataArr['state_code'] : "";
            $searchlike = isset($getDataArr['searchlike']) ? $getDataArr['searchlike'] : "";
            $whereArr = [];
            $page = isset($getDataArr['page']) ? $getDataArr['page'] : 1;
            $limit = 20;
            $offset = ($page - 1) * $limit;
            if (empty($country_code) && empty($state_code)) {
                $listType = 'countryList';
                $table = 'country_list';
            } else if (!empty($country_code) && empty($state_code)) {
                $listType = 'city_list';
                $whereArr['where'] = array('country_code' => $country_code);
                $table = 'city_list';
            } else {
                //$whereArr['limit'] = array($limit, $offset);
                if (!empty($searchlike)) {
                    $whereArr['like'] = array('name' => $searchlike);
                }

                $listType = 'cityList';
                $whereArr['where'] = array('country_code' => $country_code, 'state_code' => $state_code);
                $table = 'city_list';
            }
            $whereArr['order_by'] = ['name' => 'asc'];
            $whereArr['group_by'] = ['id'];
            $listData = $this->Common_model->fetch_data($table, array('SQL_CALC_FOUND_ROWS *'), $whereArr);
            $totalrows = $this->Common_model->totalrows;
            if (($totalrows > ($page * $limit))) {
                $page++;
            } else {
                $page = 0;
            }
            if (!empty($listData)) {
                $this->response(array('code' => SUCCESS_CODE, 'msg' => 'list fetched', 'next_page' => $page, 'totalrows' => $totalrows, 'result' => $listData));
            } else {
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => 'list fetched', 'result' => []));
            }
        } catch (Exception $e) {
            $this->response(array('code' => EMAIl_SEND_FAILED, 'msg' => $e->getMessage(), 'result' => []));
        }
    }
    
    public function versionupdate_post() {

        $postDataArr = $this->post();
        $config = [];

        $config = array(
            array(
                'field' => 'user_id',
                'label' => 'User ID',
                'rules' => 'required'
            ),
            array(
                'field' => 'cur_ver',
                'label' => 'Current Version Number',
                'rules' => 'trim|required'
            ),
        );
        
        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {
            try {
                pr($postrDataArr);            
            } catch (Exception $e) {
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $e->getMessage(), 'result' => []));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => []));
        }
    }
    
    private function langcode_validate()
    {
        $language_code = $this->head("X-Language-Code");
        $language_code = trim($language_code);
        $valid_language_codes = ["en","da","nb","sv","fi","fr","nl","de"];

        if ( empty($language_code) ) {
            $this->response([
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('header_missing'),
                'extra_info' => [
                    "missing_parameter" => "language_code"
                ]
            ]);
        }

        if ( ! in_array($language_code, $valid_language_codes) ) {
            $this->response([
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('invalid_header'),
                'extra_info' => [
                    "missing_parameter" => $this->lang->line('invalid_language_code')
                ]
            ]);
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

} //end
