<?php
defined("BASEPATH") OR exit("No direct script access allowed");

require 'BaseController.php';

class CompanyController extends BaseController 
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @SWG\Get(path="/company",
     *   tags={"Company"},
     *   summary="Company",
     *   description="",
     *   operationId="company_get",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="favorite",
     *     in="query",
     *     description="favorite = 1 to retrive favorite company",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="return the offset in response, if offset in response is -1 there are no further results",
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="OK"),
     *   @SWG\Response(response=404, description="No data found"), 
     * )
     */
    public function company_get()
    {
        $userData = $this->accessTokenCheck();

        $getData = $this->get();
        $getData = trim_input_parameters($getData); 
        $offset = isset($getData['offset'])&&!empty((int)$getData['offset'])?(int)$getData['offset']:0;
        $result = [];
        $params = [
            "user_id" => $userData['user_id'],
            "offset" => $offset,
            "limit" => RECORDS_PER_PAGE
        ];
        if ( isset($getData['favorite']) && (int)$getData['favorite'] === 1) {
            $this->load->model("Favorite_model");
            $result = $this->Favorite_model->getFavorites($params);
            $offset = $offset + RECORDS_PER_PAGE;
            if ( (int)$result['count'] < $offset ) {
                $offset = -1;
            }
            $result = $result['result'];

        } else {
            $offsetFlag = true;
            if ( isset($getData['company_id']) && !empty((int)$getData['company_id']) ) {
                $params['company_id'] = $getData['company_id'];
                $params['offset'] = 0;
                $params['limit'] = 0;
                $offsetFlag = false;
            }
            $this->load->model("Company_model");
            $result = $this->Company_model->getCompanyList($params);

            if ( $offsetFlag ) {
                $result = $result['result'];
                $offset = $offset + RECORDS_PER_PAGE;
                if ( (int)$result['count'] < $offset ) {
                    $offset = -1;
                }
            }
        }

        if ( ! $result ) {
            $this->response([
                "code" => HTTP_NOT_FOUND,
                "api_code_result" => "NOT_FOUND",
                "msg" => $this->lang->line("no_records_found")
            ], HTTP_NOT_FOUND);
        }

        $this->response([
             "code" => HTTP_OK,
            "api_code_result" => "OK",
            "msg" => $this->lang->line(""),
            "data" => $result,
            "offset" => $offset
        ], HTTP_OK);
    }
}