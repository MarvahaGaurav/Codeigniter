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
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="favorite",
     *     in="query",
     *     description="favorite = 1 to retrive favorite company",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="paginate",
     *     in="query",
     *     description="paginate = 1 to paginate",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="company_id",
     *     in="query",
     *     description="pass company id to fetch company detail",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     description="search string",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="return the offset in response, if offset in response is -1 there are no further results",
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="OK"),
     *   @SWG\Response(response=401, description="Unauthorize"),
     *   @SWG\Response(response=202, description="No data found"), 
     * )
     */
    public function company_get()
    {
        $language_code = $this->langcode_validate();
        $header = $this->head();
        $accessTokenSet = (isset($header['accesstoken']) && !empty(trim($header['accesstoken'])) || isset($header['Accesstoken']) && !empty(trim($header['Accesstoken']))) ? true: false;
        
        $getData = $this->get();
        $getData = trim_input_parameters($getData); 
        $offset = isset($getData['offset'])&&!empty((int)$getData['offset'])?(int)$getData['offset']:0;
        $paginate = isset($getData['paginate'])&&(int)$getData['paginate'] === 1?true: false;
        if ( (isset($getData['favorite']) && (int)$getData['favorite'] === 1) && ! $accessTokenSet ) {
            $this->response([
                "code" => HTTP_UNAUTHORIZED,
                "api_code_result" => "UNAUTHORIZED",
                "msg" => $this->lang->line("invalid_access_token")
            ], HTTP_UNAUTHORIZED);
        }
        if (  $accessTokenSet ) {
            $userData = $this->accessTokenCheck();
            $params['user_id'] = $userData['user_id'];
        }
        $result = [];
        $params["offset"] = $offset;
        $params["limit"] = RECORDS_PER_PAGE;

        if ( isset($getData['search']) ) {
            $params['search'] = $getData['search'];
        }
        
        if ( $accessTokenSet && isset($getData['favorite']) && (int)$getData['favorite'] === 1) {
            $this->load->model("Favorite_model");
            $lang = "favorite_company_found";
            // if ( ! $paginate ) {
            //     $params['offset'] = 0;
            //     $params['limit'] = 0;
            // } else {
            //     $params['paginate'] = $paginate;
            // }
            $result = $this->Favorite_model->getFavorites($params);
            $offset = $offset + RECORDS_PER_PAGE;
            if ( (int)$result['count'] <= $offset ) {
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
            if ( ! $paginate ) {
                $params['offset'] = 0;
                $params['limit'] = 0;
            } else {
                $params['paginate'] = $paginate;
            }

            $this->load->model("Company_model");
            $result = $this->Company_model->getCompanyList($params);
            $lang = "company_records_found";

            if ( $offsetFlag ) {
                $offset = $offset + RECORDS_PER_PAGE;
                if ( (int)$result['count'] <= $offset ) {
                    $offset = -1;
                }
            }
            $result = $result['result'];
        }

        if ( ! $result ) {
            $this->response([
                "code" => NO_DATA_FOUND,
                "api_code_result" => "NO_DATA_FOUND",
                "msg" => $this->lang->line("no_records_found")
            ]);
        }
        $response = [
            "code" => HTTP_OK,
           "api_code_result" => "OK",
           "msg" => $this->lang->line($lang),
           "data" => $result,
           "offset" => $offset
        ];
        if (  ! isset($getData['favorite']) && (int)$getData['favorite'] !== 1 && ! $paginate ) {
            unset($response['offset']);
        }
        $this->response($response, HTTP_OK);
    }
}