<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

/**
 * Quick Cal Controller
 */
class QuickCalController extends BaseController {

    private $request;

    public function __construct($config = 'rest')
    {
        parent::__construct();

    }



    /**
     *
     */
    function quickCal()
    {
        try {
            $this->request = $this->input->post();
            $this->quickCalCurl();
        }
        catch (Exception $ex) {

        }

    }



    /**
     *
     */
    private function quickCalCurl()
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl,
                              [
                CURLOPT_URL            => "https://www.dialux-plugins.com/FastCalc/api/arrangement",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_POSTFIELDS     => "{authToken: '28c129e0aca88efb6f29d926ac4bab4d', roomLength: 20,roomWidth: 15,roomHeight: 12, roomType: 'Living room',workingPlaneHeight: 0.1,suspension: 0.5,illuminance: 500,luminaireCountInX: 50,luminaireCountInY: 10,  rhoCeiling: 70, rhoWall: 50, rhoFloor: 20, maintenanceFactor: 0.9, uldUri: 'https://www.sg-as.com/sites/default/files/data/701020_Jupiter%20Firerated/30/7021989124207_Jupiter%20FireRated%20Matt%20White%205.5W%20LED.LDT'}",
                CURLOPT_HTTPHEADER     => ["Content-Type: application/json", "cache-control: no-cache"],
                ]
            );

            $response = curl_exec($curl);
            $err      = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            }
            else {
                echo $response;
            }
        }
        catch (Exception $ex) {

        }

    }



    function evaluate()
    {
        try {
            $post       = $this->input->post();
            $project_id = encryptDecrypt($post['project_id'], "decrypt");
            $this->load->model("ProjectRooms");
            $data       = $this->ProjectRooms->getRoomsDataProduct($project_id);

            foreach ($data as $temp) {
                if ('' == $temp['uld']) {
                    continue;
                }
                $request_data                  = [
                    "authToken"          => "28c129e0aca88efb6f29d926ac4bab4d",
                    "roomLength"         => floatval($temp['length']),
                    "roomWidth"          => floatval($temp['width']),
                    "roomHeight"         => floatval($temp['height']),
                    "roomType"           => $temp['name'],
                    "workingPlaneHeight" => floatval($temp['working_plane_height']),
                    "suspension"         => 0.5,
                    "illuminance"        => 500,
                    "luminaireCountInX"  => floatval($temp['luminaries_count_x']),
                    "luminaireCountInY"  => floatval($temp['luminaries_count_y']),
                    "rhoCeiling"         => floatval($temp['rho_ceiling']),
                    "rhoWall"            => floatval($temp['rho_wall']),
                    "rhoFloor"           => floatval($temp['rho_floor']),
                    "maintenanceFactor"  => floatval($temp['maintainance_factor']),
                    "uldUri"             => $temp['uld'],
                ];
                $res                           = $this->hitCulrQuickCal($request_data);
                $options['fast_calc_response'] = $res;
                $options['id']                 = $temp['id'];
                $data                          = $this->ProjectRooms->updateQuickCalData($options);
            }
        }
        catch (Exception $ex) {

        }

    }



    function hitCulrQuickCal($data)
    {
        $request_data = json_encode($data);
        $curl         = curl_init();
        curl_setopt_array($curl,
                          [
            CURLOPT_URL            => "https://www.dialux-plugins.com/FastCalc/api/arrangement",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => "$request_data",
            CURLOPT_HTTPHEADER     => ["Content-Type: application/json", "cache-control: no-cache"],
            ]
        );
        $response     = curl_exec($curl);
        $err          = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
        else {
            return $response;
        }

    }



    function request_quote()
    {
        try {
            $post = $this->input->post();
            $this->load->model("UtilModel");

            $data = [
                "project_id" => encryptDecrypt($post['project_id'], "decrypt"),
                "is_active"  => 1,
                "created_at" => date('Y-m-d H:i:s')
            ];
            print_r($data);
            $this->UtilModel->insertTableData($data, "project_requests");
        }
        catch (Exception $ex) {

        }

    }



}
