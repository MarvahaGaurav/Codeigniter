<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class ApplicationController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Application");
    }

    /**
     * @SWG\Get(path="/applications",
     *   tags={"Products"},
     *   summary="Application",
     *   description="",
     *   operationId="application_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="type",
     *     in="query",
     *     description="1-Residential and 2-Professional",
     *     type="string"
     *   ),*
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=202, description="No data found"),
     * )
     */
    public function application_get()
    {
        $language_code = $this->langcode_validate();
        
        $request_data = $this->get();
        $request_data = trim_input_parameters($request_data);

        $params['type'] = isset($request_data['type'])&&!empty($request_data['type'])?$request_data['type']:0;
        $params['language_code'] = $language_code;
        $this->response(

        $data = $this->Application->get($params);

        if (empty($data)) {
            $this->response(
                [
                "code" => NO_DATA_FOUND,
                "api_code_result" => "NO_DATA_FOUND",
                "msg" => $this->lang->line("no_records_found")
                ]
            );
        }

        $response = [
            "code" => HTTP_OK,
            "api_code_result" => "OK",
            "msg" => $this->lang->line("application_data_fetched"),
            "data" => $data
        ];
        
        $this->response($response);
    }
}
