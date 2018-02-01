<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseController.php';

use DatabaseExceptions\UpdateException;
use DatabaseExceptions\SelectException;
use DatabaseExceptions\InsertException;
use DatabaseExceptions\DeleteException;

class InspirationController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Inspiration');
    }

    /**
     * @SWG\Post(path="/company/inspiration",
     *   tags={"Company"},
     *   summary="Post inspiration",
     *   description="Post inspiration",
     *   operationId="inspiration_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="company_id",
     *     in="formData",
     *     description="Company Id",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="title",
     *     in="formData",
     *     description="Title",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="description",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="product_ids",
     *     in="formData",
     *     description="array of ids",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="media",
     *     in="formData",
     *     description="json array [{'media':'http//image/url', 'type':1}, {'media':'http//image/url', 'type':1}] 1-image, 2-video, 3-pdf",
     *     type="string"
     *   ),
     *   @SWG\Response(response=422, description="Missing parameters"),
     *   @SWG\Response(response=200, description="Inspiration added"),     
     *   @SWG\Response(response=500, description="Internal server error")        
     * )
     */
    public function inspiration_post()
    {
        $user_data = $this->accessTokenCheck();
        $request_data = $this->post();
        $request_data = trim_input_parameters($request_data);

        $mandatoryFields = ["company_id", "title", "description"];

        $check = check_empty_parameters($request_data, $mandatoryFields);

        if ( $check['error'] ) {
            $this->response([
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('missing_parameter'),
                'extra_info' => [
                    "missing_parameter" => $check['parameter']
                ]
            ]);
        }

        // print_r($request_data['media']);die;
        if ( isset($request_data['media']) && !empty($request_data['media']) ) {
            $this->load->model("InspirationMedia");
            $media = json_decode($request_data['media'], true);
        }

        $this->Inspiration->company_id = $request_data['company_id'];
        $this->Inspiration->title = $request_data['title'];
        $this->Inspiration->description = $request_data['description'];
        $this->Inspiration->created_at = $this->datetime;
        $this->Inspiration->updated_at = $this->datetime;

        try {
            $this->db->trans_begin();
            $inspirationId = $this->Inspiration->save();
            if ( ! empty($media) && is_array($media) ) {
                foreach ( $media as $key => $value ) {
                    $this->InspirationMedia->batch_data[] = [
                        "inspiration_id" => $inspirationId,
                        "media_type" => $media['type'],
                        "media" => $media['media']
                    ];
                }
                $this->InspirationMedia->batch_save();
            }
            $this->db->trans_commit();
            $this->response([
                'code' => HTTP_OK,
                'api_code_result' => 'OK',
                'msg' => $this->lang->line("inspiration_added")
            ]);
        } catch ( InsertException $error ) {
            $this->db->trans_rollback();
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line('internal_server_error'),
                'debug' => [
                    'info' => 'Insert Error'
                ]
            ]);
        }
        
        
    }

    /**
     * @SWG\Get(path="/company/inspiration/{company_id}/{inspiration_id}",
     *   tags={"Company"},
     *   summary="Inspiration List and detail",
     *   description="Inspiration List and detail",
     *   operationId="employee_get",
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="inspiration_id",
     *     in="query",
     *     description="Inspiration id is optional and can be passed to fetch inspiration details",
     *     type="string"
     *   ),
     *  @SWG\Parameter(
     *     name="company_id",
     *     in="query",
     *     description="if company Id is given it will fetch based on company Id",
     *     type="string"
     *   ),
     *   @SWG\Response(response=202, description="No data found"),
     *   @SWG\Response(response=200, description="Inspiration added"),     
     *   @SWG\Response(response=500, description="Internal server error")   
     * )
     */
    public function inspiration_get()
    {
        $userData = $this->accessTokenCheck("company_id");
        $request_data = $this->get();
        $request_data = trim_input_parameters($request_data);

        $companyId = isset($request_data['company_id'])&&!empty((int)$request_data['company_id'])?$request_data['company_id']:0;

        $offset = isset($request_data['offset'])?$request_data['offset']:0;
        
        $options = [
            "offset" => $offset,
            "company_id" => $companyId,
            "media" => true
        ];

        $is_single_row = false;
        if ( isset($request_data['inspiration_id']) ) {
            $is_single_row = true;
            $options['inspiration_id'] = $request_data['inspiration_id'];
            unset($options['offset']);
        }

        $result = $this->Inspiration->get($options);
        if ( $is_single_row ) {
            $data = $result;
        } else {
            $data = $result['result'];
            $offset = (int)$offset + RECORDS_PER_PAGE;
            if ( (int)$offset >= (int)$result['count'] ) {
                $offset = -1;
            }
        }

        if ( empty($data) ) {
            $this->response([
                "code" => NO_DATA_FOUND,
                "api_code_result" => "NO_DATA_FOUND",
                "msg" => $this->lang->line("no_records_found")
            ]);
        }
        if ( $is_single_row ) {
            $data['media'] = array_filter(explode(",", $inspiration['media']));
        } else {
            $data = array_map(function($inspiration){
                $inspiration['media'] = array_filter(explode(",", $inspiration['media']));
                return $inspiration;
            }, $data);
        }

        $response = [
            "code" => HTTP_OK,
           "api_code_result" => "OK",
           "msg" => $this->lang->line("inspiration_fetched"),
           "data" => $data
        ];
        if ( ! $is_single_row ) {
            $response['offset'] = $offset;
        }

        $this->response($response, HTTP_OK);
    }

    /**
     * @SWG\Put(path="/company/inspiration",
     *   tags={"Company"},
     *   summary="Put inspiration",
     *   description="Put inspiration",
     *   operationId="inspiration_put",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="inspiration_id",
     *     in="formData",
     *     description="Inspiration Id",
     *     type="string"
     *     
     *   ),
     *   @SWG\Parameter(
     *     name="title",
     *     in="formData",
     *     description="Title",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="description",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="product_ids",
     *     in="formData",
     *     description="array of ids",
     *     type="string"
     *   ),
     *   @SWG\Response(response=422, description="Missing parameters/Nothing to update"),
     *   @SWG\Response(response=200, description="Inspiration updated"),     
     *   @SWG\Response(response=500, description="Internal server error")   
     * )
     */
    public function inspiration_put()
    {
        $user_data = $this->accessTokenCheck();
        $request_data = $this->put();
        $request_data = trim_input_parameters($request_data);

        $mandatoryFields = ["inspiration_id"];

        $check = check_empty_parameters($request_data, $mandatoryFields);

        if ( $check['error'] ) {
            $this->response([
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('missing_parameter'),
                'extra_info' => [
                    "missing_parameter" => $check['parameter']
                ]
            ]);
        }
        $check = $request_data;
        unset($check['inspiration_id']);

        if ( empty($check) ) {
            $this->response([
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('nothing_to_update')
            ]);
        }

        $inspiration_update_map = [
            "title" => "title",
            "description" => "description"
        ];

        foreach ( $inspiration_update_map as $request_key => $db_key  ) {
            if ( isset($request_data[$request_key]) && !empty($request_data[$request_key]) ) {
                $this->Inspiration->$db_key = $request_data[$request_key];
            }
        }

        $where = [
            'id' => $request_data['inspiration_id']
        ];

        try {
            if ( isset($request_data['title']) || isset($request_data['description']) ) {
                $this->Inspiration->update($where);
            }
            $this->response([
                'code' => HTTP_OK,
                'api_code_result' => 'OK',
                'msg' => $this->lang->line("inspiration_updated")
            ]);
        } catch (UpdateException $error) {
            if ( $error->code == 101 ) { // zero rows affected
                //success
                $this->response([
                    'code' => HTTP_OK,
                    'api_code_result' => 'OK',
                    'msg' => $this->lang->line("inspiration_updated")
                ]);
            } else {
                $this->response([
                    'code' => HTTP_INTERNAL_SERVER_ERROR,
                    'api_code_result' => 'INTERNAL_SERVER_ERROR',
                    'msg' => $this->lang->line('internal_server_error'),
                    'debug' => [
                        'info' => 'Update Error'
                    ]
                ]);
            }
        }
    }

    /**
     * @SWG\Delete(path="/company/inspiration",
     *   tags={"Company"},
     *   summary="Delete inspiration",
     *   description="Delete inspiration",
     *   operationId="inspiration_delete",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     *  @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="inspiration_id",
     *     in="formData",
     *     description="Inspiration Id",
     *     type="string"
     *   ),
     *   @SWG\Response(response=422, description="Missing parameters/Nothing to update"),
     *   @SWG\Response(response=200, description="Inspiration updated"),     
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function inspiration_delete()
    {
        $request_data = $this->delete();
        $request_data = trim_input_parameters($request_data);

        $mandatory = ['inspiration_id'];

        $check = check_empty_parameters($request_data, $mandatory);

        if ( $check['error'] ) {
            $this->response([
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('missing_parameter'),
                'extra_info' => [
                    "missing_parameter" => $check['parameter']
                ]
            ]);
        }

        $where = [
            "inspiration_id" => $request_data['inspiration_id']
        ];
        
        try {
            $this->Inspiration->delete($where);
            $this->response([
                'code' => HTTP_OK,
                'api_code_result' => 'OK',
                'msg' => $this->lang->line("inspiration_updated")
            ]);
        } catch ( Exception $error ) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line('internal_server_error'),
                'debug' => [
                    'info' => 'Delete Error'
                ]
            ]);
        }
    }
}