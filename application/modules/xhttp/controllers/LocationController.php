<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

/**
 * @property array $data  array of values for view
 * @property array $userInfo session data
 * @property array $user_query_fields - table fields for user table
 * @property array $session_data - session data
 */

class LocationController extends BaseController
{   
    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("location");
    }

    public function cities()
    {
        $request_data = $this->input->get();
        $request_data = trim_input_parameters($request_data);

        if ( !isset($request_data['param']) ) {
            json_dump([
                "success" => false,
                "message" => "missing parameter"
            ]);
        }

        $options['limit'] = 50;
        if ( isset($request_data['query']) ) {
            $options['where'] = ['name LIKE' => "%{$request_data['query']}%"];    
        }
        $cities = fetch_cities($request_data['param'], $options);
        
        if ( empty($cities) ) {
            json_dump([
                "success" => false,
                "message" => "no data found"
            ]);
        }
        // pd($cities);
        json_dump([
            "success" => true,
            "message" => "cities data found",
            "data" => $cities
        ]);
    }
    

}