<?php

require_once 'BaseController.php';

/**
 * Comapany Controller
 */
class QuickCalcController extends BaseController {

    public function __construct()
    {
        parent::__construct();
        $this->neutralGuard();
        if (isset($this->userInfo, $this->userInfo['user_id']) && ! empty($this->userInfo['user_id'])) {
            $this->data['userInfo'] = $this->userInfo;
        }

    }



    /**
     * Lists Application
     *
     * @return string
     */
    public function applications()
    {
        try {
            $this->load->model('Application');

            $applicationType = $this->input->get("type");

            $params['language_code'] = 'en';
            $params['type'] = APPLICATION_RESIDENTIAL;
            $params['all_data'] = true;
            $params['where']['(EXISTS(SELECT id FROM rooms WHERE application_id=app.application_id))'] = null;

            if (is_numeric($applicationType) &&
                in_array((int) $applicationType, [APPLICATION_PROFESSIONAL, APPLICATION_RESIDENTIAL], true)
            ) {
                $params['type'] = (int) $applicationType;
            }

            $applications                    = $this->Application->get($params);
            $this->data['applicationChunks'] = array_chunk($applications, 4);
            $this->data['type']              = $params['type'];

            website_view('quickcalc/application', $this->data);
        }
        catch (\Exception $error) {

        }

    }



    /**
     * Display Rooms by Application
     *
     * @return void
     */
    public function rooms($applicationId = '')
    {
        try {
            $applicationId = encryptDecrypt($applicationId, 'decrypt');

            $this->load->model(['Application', 'Room']);

            if (empty($applicationId)) {
                show404('Invalid Request', base_url('home/applications'));
            }

            $params['application_id'] = $applicationId;
            $application              = $this->Application->details($params);
            if (empty($application)) {
                show404('Data Not Found', base_url('home/applications'));
            }

            $this->data['encrypted_application_id']  = encryptDecrypt($application['application_id']);
            $params['where']['rooms.application_id'] = $applicationId;
            $rooms                                   = $this->Room->get($params);
            $rooms['result']                         = array_map(function ($data) {
                $data['encrypted_room_id'] = encryptDecrypt($data['room_id']);
                return $data;
            }, $rooms['result']);
            $rooms['result'] = array_chunk($rooms['result'], 4);

            $this->data['application'] = $application;
            $this->data['roomChunks']  = $rooms['result'];

            website_view('quickcalc/rooms', $this->data);
        }
        catch (\Exception $error) {

        }

    }



    /**
     * Undocumented function
     *
     * @return void
     */
    public function quickcalc($applicationId, $roomId)
    {
        try {
            $this->data['js'] = 'quickcalc';

            $this->data['applicationId']  = $applicationId;
            $this->data['roomId']         = $roomId;
            $this->data['room_id']        = $roomId;
            $this->data['application_id'] = $applicationId;


            $applicationId = encryptDecrypt($applicationId, 'decrypt');
            $roomId        = encryptDecrypt($roomId, 'decrypt');

            if (empty($applicationId) || empty($roomId)) {
                show404('Invalid Request', base_url('home/applications'));
            }

            $this->load->model(['Application', 'Room']);

            $params['application_id'] = $applicationId;

            $application = $this->Application->details($params);
            if (empty($application)) {
                show404('Data Not Found', base_url('home/applications'));
            }
            $params['room_id']  = $roomId;
            $room               = $this->Room->get($params);
            $this->data['room'] = $room;

            $get_array           = [];
            $this->data['units'] = ["Meter", "Inch", "Yard"];
            $this->load->helper('cookie');
            $cookie_data         = get_cookie("quick_cal_form_data");
            parse_str($cookie_data, $get_array);
            if ( ! count($get_array)) {
                $get_array = $this->setBlankArray();
            }
            $this->data['mounting_type'] = $this->mountingType();
            $this->data['selectd_room']  = $this->setSelectedRoomrray();
            $this->data['cookie_data']   = $get_array;


            website_view('quickcalc/quickcalc', $this->data);
        }
        catch (\Exception $error) {

        }

    }



    /**
     *  if cookie is not set, return Blank Array
     * @return Array
     */
    private function setBlankArray()
    {
        return [
            "room_refrence"            => "",
            "room_lenght"              => "",
            "room_lenght_unit"         => "",
            "room_breadth"             => "",
            "room_breadth_unit"        => "",
            "room_height"              => "",
            "room_height_unit"         => "",
            "room_plane_height"        => "",
            "room_plane_height_unit"   => "",
            "room_luminaries_x"        => "",
            "room_luminaries_y"        => "",
            "room_shape"               => "",
            "room_pendant_length"      => "",
            "room_pendant_length_unit" => ""
        ];

    }



    /**
     *
     */
    private function setSelectedRoomrray()
    {
        try {
            $selectd_room = get_cookie("quick_cal_selectd_room");
            if ('' != $selectd_room) {
                return json_decode($selectd_room, true);
            }
            return ["articel_id" => "", "product_id" => "", "type" => "", "product_name" => ""];
        }
        catch (Exception $ex) {

        }

    }



    /**
     *
     */
    private function mountingType()
    {
        return get_cookie("quick_mounting");

    }



    /**
     *
     * @param type $applicationId
     * @param type $roomId
     */
    function select_product($applicationId = '', $roomId = '')
    {
        try {
            $this->neutralGuard();
            if (isset($this->userInfo, $this->userInfo['user_id']) && ! empty($this->userInfo['user_id'])) {
                $this->data['userInfo'] = $this->userInfo;
            }
            $this->data['js']             = 'select_product_quick';
            $this->load->model("Room");
            $option                       = ["where" => ["room_id" => encryptDecrypt($roomId, 'decrypt'), "application_id" => encryptDecrypt($applicationId, 'decrypt')]];
            $this->data['room_id']        = $roomId;
            $this->data['application_id'] = $applicationId;
            $this->data['room']           = $this->Room->get($option, true);
            $this->data["csrfName"]       = $this->security->get_csrf_token_name();
            $this->data["csrfToken"]      = $this->security->get_csrf_hash();
            website_view('projects/select_product', $this->data);
        }
        catch (Exception $ex) {

        }

    }



    /**
     *
     */
    function articles($applicationId = '', $roomId = '', $product_id = '')
    {
        try {
            $this->neutralGuard();
            if (isset($this->userInfo, $this->userInfo['user_id']) && ! empty($this->userInfo['user_id'])) {
                $this->data['userInfo'] = $this->userInfo;
            }
            $this->load->helper('utility');
            //Loading Models
            $this->load->model('Product');
            $this->load->model('ProductTechnicalData');
            $this->load->model('ProductSpecification');
            $this->load->model('ProductRelated');
            $this->load->model('ProductGallery');
            $params                        = [
                'product_id' => $product_id
            ];
            $productData                   = $this->Product->details($params);
            $productTechnicalData          = $this->ProductTechnicalData->get($params);
            $productSpecifications         = $this->ProductSpecification->get($params);
            $relatedProducts               = $this->ProductRelated->get($params);
            $productSpecifications         = array_strip_tags($productSpecifications, ['title']);
            $productTechnicalData          = array_strip_tags($productTechnicalData, ['title', 'info']);
            $productData['body']           = trim(strip_tags($productData['body']));
            $productData['how_to_specity'] = trim(strip_tags($productData['how_to_specity']));
            $this->data['images']          = $this->ProductGallery->get($product_id);

            $this->data['js']               = "article_quick";
            $this->data['product']          = $productData;
            $this->data['product_id']       = $product_id;
            $this->data['application_id']   = $applicationId;
            $this->data['room_id']          = $roomId;
            $this->data['technical_data']   = $productTechnicalData;
            $this->data['specifications']   = $productSpecifications;
            $this->data['related_products'] = $relatedProducts;

            website_view('projects/select_article', $this->data);
        }
        catch (Exception $ex) {
            show404("ERROR");
        }

    }



    function quick_cal()
    {
        try {
            $this->load->model("Room");
            $post               = $this->input->post();
            $post['project_id'] = $this->session->userdata('project_id');
            $option             = ["where" => ["room_id" => encryptDecrypt($post['room_id'], 'decrypt'), "application_id" => encryptDecrypt($post['application_id'], 'decrypt')]];
            $room               = $this->Room->get($option, true);
            $lenght             = $this->calc($post['room_lenght'], $post['room_lenght_unit']);
            $width              = $this->calc($post['room_breadth'], $post['room_breadth_unit']);
            $height             = $this->calc($post['room_height'], $post['room_height_unit']);
            $post['room']       = $room;

            $insert = [
                "project_id"           => 0,
                "room_id"              => $room['room_id'],
                "name"                 => $room['title'],
                "count"                => 1,
                "length"               => $lenght,
                "width"                => $width,
                "height"               => $height,
                "maintainance_factor"  => $room['maintainance_factor'],
                "shape"                => $post['room_shape'],
                "working_plane_height" => $post['room_plane_height'], //need to confirm
                "rho_wall"             => $room['reflection_values_wall'],
                "rho_ceiling"          => $room['reflection_values_ceiling'],
                "rho_floor"            => $room['reflection_values_floor'],
                "lux_value"            => $room['lux_values'],
                "luminaries_count_x"   => $post['room_luminaries_x'],
                "luminaries_count_y"   => $post['room_luminaries_y'],
                "fast_calc_response"   => "",
                "created_at"           => date('Y-m-d H:i:s'),
                "article_code"         => $post['article_code'],
                "product_id"           => $post['product_id'],
                "type"                 => $post['type']
            ];


            $this->db->trans_begin();
            $this->load->model("UtilModel");
            $room_id = $this->UtilModel->insertTableData($insert, "quick_rooms", true);
            $this->db->trans_commit();
            $this->evaluate($insert, $room_id);
            redirect(base_url("home/applications/view-result/" . encryptDecrypt($room_id)));
        }
        catch (Exception $ex) {
            show404("Something Went Worng!! Please Try After Some Time");
        }

    }



    /**
     *
     * @param type $insert
     * @param type $room_id
     */
    function evaluate($temp, $room_id)
    {
        try {
            $this->load->model("UtilModel");
            $option = ["single_row" => true, "where" => ["articlecode" => $temp['article_code'], "product_id" => $temp['product_id']]];
            $uld    = $this->UtilModel->selectQuery("uld", "product_specifications", $option);


            if (isset($uld['uld']) and '' != $uld['uld']) {
                $request_data = [
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
                    "uldUri"             => $uld['uld']
                ];
            }

            $res                           = $this->hitCulrQuickCal($request_data);
            $options['fast_calc_response'] = $res;
            $options['id']                 = $temp['id'];
            return $this->updateQuickCalData($options, $room_id);
        }
        catch (Exception $ex) {

        }

    }



    /**
     *
     */
    function updateQuickCalData($data, $room_id)
    {
        $temp = json_decode($data['fast_calc_response'], true);

        $update                       = [
            "side_view"  => $temp['projectionSide'],
            "top_view"   => $temp['projectionTop'],
            "front_view" => $temp['projectionFront'],
        ];
        unset($temp['projectionSide']);
        unset($temp['projectionTop']);
        unset($temp['projectionFront']);
        $update['fast_calc_response'] = json_encode($temp);


        $this->load->model("UtilModel");
        $this->UtilModel->updateTableData($update, "quick_rooms", ["id" => $room_id]);
        return $this->db->last_query();

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



    /**
     *
     */
    function calc($length, $unit)
    {
        if ('meter' == strtolower($unit)) {
            return $length;
        }

        $specification_string = '';
        switch (strtolower($unit)) {
            case "yard":
                $specification_string = "yard_to_meter";
            case "inch":
                $specification_string = "yard_to_meter";
        }
        $this->load->helper("utility_helper");

        return convert_units($length, $specification_string);

    }



    /**
     *
     * @param type $project_room_id
     */
    public function view_result($project_room_id)
    {
        try {
            $id                           = encryptDecrypt($project_room_id, "decrypt");
            $this->load->model("UtilModel");
            $room_data                    = $this->UtilModel->selectQuery("*", "quick_rooms", ["single_row" => true, "where" => ["id" => $id]]);
            $this->data['specifications'] = $this->UtilModel->selectQuery("*", "product_specifications",
                                                                          ["single_row" => true, "where" => ["articlecode" => $room_data['article_code'], "product_id" => $room_data['product_id']]]);
//            echo $this->db->last_query();
            $this->data['room_data']      = $room_data;
            $this->data["csrfName"]       = $this->security->get_csrf_token_name();
            $this->data["csrfToken"]      = $this->security->get_csrf_hash();
            website_view('quickcalc/evaluation_result', $this->data);
        }
        catch (Exception $ex) {

        }

    }



}
