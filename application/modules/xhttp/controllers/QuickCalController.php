<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

/**
 * Quick Cal Controller
 */
class QuickCalController extends BaseController
{

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
        } catch (Exception $ex) {

        }
    }

    /**
     * Quick calc suggestions API
     *
     * @return void
     */
    public function quickCalcSuggestion()
    {
        try {
            $this->requestData = $this->input->post();

            if (isset($this->requestData['room_id'])) {
                $this->requestData['room_id'] = encryptDecrypt($this->requestData['room_id'], 'decrypt');
            }
            if (isset($this->requestData['projectId'])) {
                $this->requestData['projectId'] = encryptDecrypt($this->requestData['projectId'], 'decrypt');
            }
            
            $this->validateQuickCalcSuggestion();

            $roomData = $this->UtilModel->selectQuery('*', 'rooms' , [
                'where' => ['room_id' => $this->requestData['room_id']], 'single_row' => true
            ]);

            if (empty($roomData)) {
                json_dump([
                    'success' => false,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $productData = $this->UtilModel->selectQuery('uld', 'product_specifications', [
                'where' => ['product_id' => $this->requestData['product_id'], 'articlecode' => $this->requestData['article_code']],
                'single_row' => true
            ]);

            if (empty($productData)) {
                json_dump([
                    'success' => false,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $this->load->helper(['quick_calc', 'utility']);

            $length = convert_to_meter($this->requestData['length_unit'], $this->requestData['length']);
            $width = convert_to_meter($this->requestData['width_unit'], $this->requestData['width']);
            $height = convert_to_meter($this->requestData['height_unit'], $this->requestData['height']);

            $data = [
                "authToken" => DIALUX_AUTH_TOKEN,
                "roomLength" => floatval($length),
                "roomWidth" => floatval($width),
                "roomHeight" => floatval($height),
                "illuminance" => floatval($this->requestData['lux_values']),
                "maintenanceFactor" => floatval($this->requestData['maintainance_factor']),
                "roomType" => $roomData['title'],
                "workingPlaneHeight" => isset($this->requestData['room_plane_height'])?floatval($this->requestData['room_plane_height'])/100:floatval($roomData['reference_height']),
                "suspension" => isset($this->requestData['pendant_length'])?floatval($this->requestData['pendant_length']):0.00,
                "rhoCeiling" => isset($this->requestData['rho_ceiling'])?floatval($this->requestData['rho_ceiling']):100 * floatval($roomData['reflection_values_ceiling']),
                "rhoWall" => isset($this->requestData['rho_wall'])?floatval($this->requestData['rho_wall']):100 * floatval($roomData['reflection_values_wall']),
                "rhoFloor" => isset($this->requestData['rho_floor'])?floatval($this->requestData['rho_floor']):100 * floatval($roomData['reflection_values_floor']),
                "uldUri" => $productData['uld']
            ];

            $quickCalcResponse = quickCalcSuggestions($data);

            $quickCalcResponse = json_decode($quickCalcResponse, true);

            if (!isset($quickCalcResponse['luminaireCountInX'], $quickCalcResponse['luminaireCountInY'], $quickCalcResponse['luminaireCount'])) {
                json_dump([
                    'success' => false,
                    'msg' => $this->lang->line('something_went_wrong')
                ]);
            }

            if (isset($quickCalcResponse['projectionTop'], $quickCalcResponse['projectionSide'], $quickCalcResponse['projectionFront'])) {
                unset($quickCalcResponse['projectionTop'], $quickCalcResponse['projectionSide'], $quickCalcResponse['projectionFront']);
            }

            json_dump([
                'success' => true,
                'msg' => $this->lang->line('success'),
                'data' => $quickCalcResponse
            ]);
        } catch (\Exception $error) {
            json_dump([
                'success' => false,
                'msg' => $this->lang->line('internal_server_error')
            ]);
        }
    }

    /**
     * Validate quick suggestion
     *
     * @return void
     */
    private function validateQuickCalcSuggestion()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'room_id',
                'label' => 'Room Id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'article_code',
                'label' => 'Article code',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'product_id',
                'label' => 'Product Id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "height_unit",
                'label' => "Height unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "width_unit",
                'label' => "Width unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "length_unit",
                'label' => "Length unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => 'length',
                'label' => 'Room length',
                'rules' => 'trim|required|numeric|greater_than[0]'
            ],
            [
                'field' => 'width',
                'label' => 'Room width',
                'rules' => 'trim|required|numeric|greater_than[0]'
            ],
            [
                'field' => 'height',
                'label' => 'Room height',
                'rules' => 'trim|required|numeric|greater_than[0]'
            ],
            [
                'field' => 'lux_values',
                'label' => 'Illuminance',
                'rules' => 'trim|required|numeric|greater_than[0]'
            ],
            [
                'field' => 'maintainance_factor',
                'label' => 'Maintenance factor',
                'rules' => 'trim|required|numeric|greater_than[0]'
            ],
            [
                'field' => 'room_type',
                'label' => 'Room type',
                'rules' => 'trim'
            ],
            [
                'field' => 'room_plane_height',
                'label' => 'Room plane height',
                'rules' => 'trim|numeric|greater_than[0]'
            ],
            [
                'field' => 'pendant_length',
                'label' => 'Pendant length',
                'rules' => 'trim|numeric|greater_than_equal_to[0]'
            ],
            [
                'field' => 'luminaire_count_in_x',
                'label' => 'Luminaire count in x',
                'rules' => 'trim|numeric|greater_than[0]'
            ],
            [
                'field' => 'luminaire_count_in_y',
                'label' => 'Luminaire count in y',
                'rules' => 'trim|numeric|greater_than[0]'
            ],
            [
                'field' => 'rho_ceiling',
                'label' => 'Rho ceiling',
                'rules' => 'trim|numeric|greater_than[0]'
            ],
            [
                'field' => 'rho_wall',
                'label' => 'Rho wall',
                'rules' => 'trim|numeric|greater_than[0]'
            ],
            [
                'field' => 'rho_floor',
                'label' => 'Rho floor',
                'rules' => 'trim|numeric|greater_than[0]'
            ],
        ]);


        $status = $this->form_validation->run();

        if (!$status) {
            json_dump([
                'success' => false,
                'msg' => $this->lang->line('bad_request')
            ]);
        }
    }


    /**
     *
     */
    private function quickCalCurl()
    {
        try {
            $curl = curl_init();
            curl_setopt_array(
                $curl,
                [
                    CURLOPT_URL => "https://www.dialux-plugins.com/FastCalc/api/arrangement",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{authToken: '28c129e0aca88efb6f29d926ac4bab4d', roomLength: 20,roomWidth: 15,roomHeight: 12, roomType: 'Living room',workingPlaneHeight: 0.1,suspension: 0.5,illuminance: 500,luminaireCountInX: 50,luminaireCountInY: 10,  rhoCeiling: 70, rhoWall: 50, rhoFloor: 20, maintenanceFactor: 0.9, uldUri: 'https://www.sg-as.com/sites/default/files/data/701020_Jupiter%20Firerated/30/7021989124207_Jupiter%20FireRated%20Matt%20White%205.5W%20LED.LDT'}",
                    CURLOPT_HTTPHEADER => ["Content-Type: application/json", "cache-control: no-cache"],
                ]
            );

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
        } catch (Exception $ex) {

        }

    }



    function evaluate()
    {
        try {
            $post = $this->input->post();
            $project_id = encryptDecrypt($post['project_id'], "decrypt");
            $this->load->model("ProjectRooms");
            $data = $this->ProjectRooms->getRoomsDataProduct($project_id);

            foreach ($data as $temp) {
                if ('' == $temp['uld']) {
                    continue;
                }
                $request_data = [
                    "authToken" => "28c129e0aca88efb6f29d926ac4bab4d",
                    "roomLength" => floatval($temp['length']),
                    "roomWidth" => floatval($temp['width']),
                    "roomHeight" => floatval($temp['height']),
                    "roomType" => $temp['name'],
                    "workingPlaneHeight" => floatval($temp['working_plane_height']),
                    "suspension" => 0.5,
                    "illuminance" => 500,
                    "luminaireCountInX" => floatval($temp['luminaries_count_x']),
                    "luminaireCountInY" => floatval($temp['luminaries_count_y']),
                    "rhoCeiling" => floatval($temp['rho_ceiling']),
                    "rhoWall" => floatval($temp['rho_wall']),
                    "rhoFloor" => floatval($temp['rho_floor']),
                    "maintenanceFactor" => floatval($temp['maintainance_factor']),
                    "uldUri" => $temp['uld'],
                ];
                $res = $this->hitCulrQuickCal($request_data);
                $options['fast_calc_response'] = $res;
                $options['id'] = $temp['id'];
                $data = $this->ProjectRooms->updateQuickCalData($options);
            }
        } catch (Exception $ex) {

        }

    }



    function hitCulrQuickCal($data)
    {
        $request_data = json_encode($data);
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => "https://www.dialux-plugins.com/FastCalc/api/arrangement",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "$request_data",
                CURLOPT_HTTPHEADER => ["Content-Type: application/json", "cache-control: no-cache"],
            ]
        );
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
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
                "is_active" => 1,
                "created_at" => date('Y-m-d H:i:s')
            ];
            print_r($data);
            $this->UtilModel->insertTableData($data, "project_requests");
        } catch (Exception $ex) {

        }

    }



}
