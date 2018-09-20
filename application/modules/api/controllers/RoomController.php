<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseController.php';

use DatabaseExceptions\UpdateException;
use DatabaseExceptions\SelectException;
use DatabaseExceptions\InsertException;
use DatabaseExceptions\DeleteException;
use GuzzleHttp\Client as GuzzleClient;

class RoomController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Room');
        
    }

    /**
     * @SWG\Get(path="/applications/{application_id}/rooms",
     *   tags={"Products"},
     *   summary="Room types based on application",
     *   description="Room types based on application",
     *   operationId="rooms_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="application_id",
     *     in="query",
     *     description="Application id to fetch room type",
     *     type="string",
     *     required=true
     *   ),
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="offset value to be passed back to paginate, if offset is -1, there is no further data",
     *     type="string"
     *   ),
     * @SWG\Response(response=202, description="No data found"),
     * @SWG\Response(response=200, description="Room details fetched"),     
     * @SWG\Response(response=500, description="Internal server error")   
     * )
     */
    public function rooms_get() 
    {
        $language_code = $this->langcode_validate();

        $mandatoryFields = ["application_id"];  

        $request_data = $this->get();
        $request_data = trim_input_parameters($request_data);

        $check = check_empty_parameters($request_data, $mandatoryFields);

        if ($check['error'] ) {
            $this->response(
                [
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('missing_parameter'),
                'extra_info' => [
                    "missing_parameter" => $check['parameter']
                ]
                ]
            );
        }

        $is_single_row = false;
        if (isset($request_data['room_id']) ) {
            $is_single_row = true;
        }

        $offset = isset($request_data['offset'])?$request_data['offset']:0;

        $options = [
            'limit' => RECORDS_PER_PAGE,
            'offset' => $offset,
            'where' => [
                "rooms.application_id" => $request_data['application_id']
            ]
        ];

        if ($is_single_row ) {
            $options['room_id'] = $request_data['room_id'];
        }

        $room_data = $this->Room->get($options);
        $link = "";
        $alt_link = "";

        if ($is_single_row) {
            $data = $room_data;
        } else {
            $data = $room_data['result'];
            $offset = (int)$offset + RECORDS_PER_PAGE;
            if ((int)$offset >= (int)$result['count'] ) {
                $offset = -1;
            } else {
                $link = base_url("api/v1/applications/{$request_data['application_id']}/rooms?offset={$offset}");
            }
            $this->load->helper("url");
           
        }

        if (empty($data) ) {
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
            "msg" => $this->lang->line("room_fetched"),
            "data" => $data
        ];

        if (! $is_single_row ) {
            $response['offset'] = $offset;
            $response['links'] = [
                "url" => $link
            ];
        } 

        $this->response($response, HTTP_OK);
    }

}