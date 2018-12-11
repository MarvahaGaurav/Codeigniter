<?php

require_once 'BaseController.php';
require_once APPPATH . "/libraries/Traits/QuickCalc.php";

/**
 * Comapany Controller
 */
class QuickCalcController extends BaseController
{

    use QuickCalc;
    /**
     * Post Request data
     *
     * @var array
     */
    private $postRequest;
    private $getRequest;

    public function __construct()
    {
        parent::__construct();
        $this->neutralGuard();
        $this->load->library('form_validation');
        $this->load->model("UtilModel");
        if (isset($this->userInfo, $this->userInfo['user_id']) && !empty($this->userInfo['user_id'])) {
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
                in_array((int)$applicationType, [APPLICATION_PROFESSIONAL, APPLICATION_RESIDENTIAL], true)) {
                $params['type'] = (int)$applicationType;
            }

            $applications = $this->Application->get($params);
            $this->data['applicationChunks'] = array_chunk($applications, 4);
            $this->data['type'] = $params['type'];

            website_view('quickcalc/application', $this->data);
        } catch (\Exception $error) {
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
            $application = $this->Application->details($params);
            if (empty($application)) {
                show404('Data Not Found', base_url('home/applications'));
            }

            $this->data['encrypted_application_id'] = encryptDecrypt($application['application_id']);
            $params['where']['rooms.application_id'] = $applicationId;
            $rooms = $this->Room->get($params);
            $rooms['result'] = array_map(function ($data) {
                $data['encrypted_room_id'] = encryptDecrypt($data['room_id']);
                return $data;
            }, $rooms['result']);
            $rooms['result'] = array_chunk($rooms['result'], 4);

            $this->data['application'] = $application;
            $this->data['roomChunks'] = $rooms['result'];

            website_view('quickcalc/rooms', $this->data);
        } catch (\Exception $error) {
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

            $this->data['applicationId'] = $applicationId;
            $this->data['roomId'] = $roomId;
            $this->data['room_id'] = $roomId;
            $this->data['application_id'] = $applicationId;


            $applicationId = encryptDecrypt($applicationId, 'decrypt');
            $roomId = encryptDecrypt($roomId, 'decrypt');

            if (empty($applicationId) || empty($roomId)) {
                show404('Invalid Request', base_url('home/applications'));
            }

            $this->load->model(['Application', 'Room']);

            $params['application_id'] = $applicationId;

            $application = $this->Application->details($params);
            if (empty($application)) {
                show404('Data Not Found', base_url('home/applications'));
            }
            $params['room_id'] = $roomId;
            $room = $this->Room->get($params);
            $this->data['room'] = $room;
            $this->data['validation_errors'] = [];
            $this->data['validation_error_keys'] = [];
            $this->postRequest = $this->input->post();
            if (!empty($this->postRequest)) {
                $this->quickCalcFormHandler($room);
            }

            $get_array = [];
            $this->data['units'] = ["Meter", "Inch", "Yard"];
            $this->load->helper('cookie');
            $cookie_data = get_cookie("quick_cal_form_data");
            parse_str($cookie_data, $get_array);
            if (!count($get_array)) {
                $get_array = $this->setBlankArray();
            }
            $this->data['mounting_type'] = $this->mountingType();
            $this->data['selected_product'] = $this->setSelectedRoomrray();
            $this->data['cookie_data'] = $get_array;
            
            $this->data['showSuspensionHeight'] = false;
            if (isset($this->data['selected_product']['product_id'])) {
                $mountingTypes = $this->UtilModel->selectQuery('type', 'product_mounting_types', [
                    'where' => ['product_id' => $this->data['selected_product']['product_id'], 'type !=' => 0]
                ]);
                $mountingTypes = array_column($mountingTypes, 'type');
                $suspendedFilter = array_filter($mountingTypes, function ($type) {
                    return in_array((int)$type, [MOUNTING_SUSPENDED, MOUNTING_PENDANT], true);
                });
                $this->data['showSuspensionHeight'] = (bool)!empty($suspendedFilter);
            }

            website_view('quickcalc/quickcalc', $this->data);
        } catch (\Exception $error) {
        }
    }


    /**
     * Room dimesnin post handler
     *
     * @param array $room
     * @return void
     */
    private function quickCalcFormHandler($room)
    {
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($this->postRequest);

        $this->form_validation->set_rules($this->validateQuickCalcForm());

        $validData = (bool)$this->form_validation->run();

        $uld = "";
        if (isset($this->postRequest['product_id'], $this->postRequest['article_code'])) {
            $uld = $this->UtilModel->selectQuery('uld', 'product_specifications', [
                'where' => [
                    'product_id' => $this->postRequest['product_id'],
                    'articlecode' => $this->postRequest['article_code']
                ],
                'single_row' => true
            ]);

            $uld = $uld['uld'];
        }
        if (empty($uld)) {
            $this->session->set_flashdata("flash-message", $this->lang->line('we_are_unable_to_get_data'));
            $this->session->set_flashdata("flash-type", "danger");
            redirect(base_url(uri_string()));
        }

        if ($validData) {
            $this->load->helper(['utility', 'input_data', 'quick_calc']);
            $length = convert_to_meter($this->postRequest['length_unit'], $this->postRequest['length']);
            $width = convert_to_meter($this->postRequest['width_unit'], $this->postRequest['width']);
            $height = convert_to_meter($this->postRequest['height_unit'], $this->postRequest['height']);
            $this->postRequest = trim_input_parameters($this->postRequest, false);
            $insert = [
                // "application_id" => $this->postRequest['application_id'],
                "room_id" => $this->postRequest['room_id'],
                "name" => $this->postRequest['name'],
                "length" => $length,
                "width" => $width,
                "height" => $height,
                "maintainance_factor" => isset($this->postRequest['maintainance_factor']) && !empty($this->postRequest['maintainance_factor']) ? $this->postRequest['maintainance_factor'] : $room['maintainance_factor'],
                "shape" => "Rectangular",
                "suspension_height" => isset($this->postRequest['pendant_length']) ? convert_to_meter($this->postRequest['pendant_length_unit'], $this->postRequest['pendant_length']) : 0.00,
                "working_plane_height" => isset($this->postRequest['room_plane_height']) ? $this->postRequest['room_plane_height'] / 100 : 0.00, //need to confirm
                "rho_wall" => isset($this->postRequest['rho_wall']) && !empty($this->postRequest['rho_wall']) ? $this->postRequest['rho_wall'] : $room['reflection_values_wall'],
                "rho_ceiling" => isset($this->postRequest['rho_ceiling']) && !empty($this->postRequest['rho_ceiling']) ? $this->postRequest['rho_ceiling'] : $room['reflection_values_ceiling'],
                "rho_floor" => isset($this->postRequest['rho_floor']) && !empty($this->postRequest['rho_floor']) ? $this->postRequest['rho_floor'] : $room['reflection_values_floor'],
                "lux_value" => isset($this->postRequest['lux_values']) && !empty($this->postRequest['lux_values']) ? $this->postRequest['lux_values'] : $room['lux_values'],
                "luminaries_count_x" => $this->postRequest['room_luminaries_x'],
                "luminaries_count_y" => $this->postRequest['room_luminaries_y'],
                "fast_calc_response" => '',
                "created_at" => $this->datetime,
                'type' => $this->postRequest['type'],
                'product_id' => $this->postRequest['product_id'],
                'article_code' => $this->postRequest['article_code']
            ];

            $response = $this->fetchQuickCalcData($insert, $uld);
            $decodedResponse = json_decode($response, true);
            if (!isset($decodedResponse['projectionTop'], $decodedResponse['projectionSide'], $decodedResponse['projectionFront'])) {
                $this->session->set_flashdata("flash-message", $this->lang->line('we_are_unable_to_get_data'));
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url(uri_string()));
            }

            $insert['fast_calc_response'] = $response;
            $insert['side_view'] = $decodedResponse['projectionSide'];
            $insert['top_view'] = $decodedResponse['projectionTop'];
            $insert['front_view'] = $decodedResponse['projectionFront'];

            $quickRoomId = $this->UtilModel->insertTableData($insert, 'quick_rooms', true);
            $this->load->helper('cookie');
            delete_cookie('quick_cal_selectd_room');
            delete_cookie('quick_cal_form_data');

            $this->session->set_flashdata("flash-message", $this->lang->line("room_calculated"));
            $this->session->set_flashdata("flash-type", "success");
            redirect(base_url('home/applications/view-result/' . encryptDecrypt($quickRoomId)));
        } else {
            $this->data['validation_errors'] = $this->form_validation->error_array();
            $this->data['validation_error_keys'] = array_keys($this->data['validation_errors']);
        }
    }

    /**
     * Validate data to be inserted into project rooms
     *
     * @return array
     */
    private function validateQuickCalcForm()
    {
        return [
            [
                'field' => "name",
                'label' => "Name",
                'rules' => 'trim|required'
            ],
            [
                'field' => "room_id",
                'label' => "Room id",
                'rules' => 'trim|required'
            ],
            [
                'field' => "length",
                'label' => "Length",
                'rules' => 'trim|required|numeric'
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
                'field' => "width",
                'label' => "Width",
                'rules' => 'trim|required|numeric'
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
                'field' => "height",
                'label' => "Height",
                'rules' => 'trim|required|numeric'
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
                'field' => "room_plane_height",
                'label' => "Room plane height",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "room_plane_height_unit",
                'label' => "Room plane height unit",
                'rules' => 'trim|required|regex_match[/^(cms)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "room_luminaries_x",
                'label' => "Room luminaries x",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "room_luminaries_y",
                'label' => "Room luminaries y",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "pendant_length",
                'label' => "Pendant length",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "pendant_length_unit",
                'label' => "Pendant length unit",
                'rules' => 'trim|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "rho_wall",
                'label' => "Rho wall",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "rho_ceiling",
                'label' => "Rho ceiling",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "rho_floor",
                'label' => "Rho floor",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "maintainance_factor",
                'label' => "Maintainance factor",
                'rules' => 'trim|required|numeric'
            ], [
                'field' => "lux_values",
                'label' => "Lux values",
                'rules' => 'trim|numeric'
            ],
            // [
            //     'field' => "application_id",
            //     'label' => "Application id",
            //     'rules' => 'trim|required|is_natural_no_zero'
            // ],
            [
                'field' => "article_code",
                'label' => "Article code",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "type",
                'label' => "Type",
                'rules' => 'trim|required|regex_match[/^(1|2|3|4|5|6|7)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line("bad_request")
                ]
            ],
            [
                'field' => "product_id",
                'label' => "Product id",
                'rules' => 'trim|required'
            ],
        ];
    }


    /**
     *  if cookie is not set, return Blank Array
     * @return Array
     */
    private function setBlankArray()
    {
        return [
            "room_refrence" => "",
            "room_lenght" => "",
            "room_lenght_unit" => "",
            "room_breadth" => "",
            "room_breadth_unit" => "",
            "room_height" => "",
            "room_height_unit" => "",
            "room_plane_height" => "",
            "room_plane_height_unit" => "",
            "room_luminaries_x" => "",
            "room_luminaries_y" => "",
            "room_shape" => "",
            "room_pendant_length" => "",
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
        } catch (Exception $ex) {
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
            if (isset($this->userInfo, $this->userInfo['user_id']) && !empty($this->userInfo['user_id'])) {
                $this->data['userInfo'] = $this->userInfo;
            }
            $this->data['js'] = 'select_product_quick';
            $this->load->model("Room");
            $option = ["where" => ["room_id" => encryptDecrypt($roomId, 'decrypt'), "application_id" => encryptDecrypt($applicationId, 'decrypt')]];
            $this->data['room_id'] = $roomId;
            $this->data['application_id'] = $applicationId;
            $this->data['room'] = $this->Room->get($option, true);
            // pd($this->data['room']);
            $this->data['searchData'] = json_encode([
                'room_id' => $roomId
            ]);
            $this->data["csrfName"] = $this->security->get_csrf_token_name();
            $this->data["csrfToken"] = $this->security->get_csrf_hash();
            website_view('quickcalc/select_product', $this->data);
        } catch (Exception $ex) {

        }
    }



    /**
     *
     */
    function articles($applicationId = '', $roomId = '', $mounting = 1, $product_id = '')
    {
        try {
            $queryString = $applicationId . '/rooms/' . $roomId . '/mounting/' . $mounting . '/articles/' . $product_id;

            $this->neutralGuard();
            if (isset($this->userInfo, $this->userInfo['user_id']) && !empty($this->userInfo['user_id'])) {
                $this->data['userInfo'] = $this->userInfo;
            }
            $this->load->helper('utility');
            $product_id = encryptDecrypt($product_id, 'decrypt');
            //Loading Models
            $this->load->model('Product');
            $this->load->model('ProductTechnicalData');
            $this->load->model('ProductSpecification');
            $this->load->model('ProductRelated');
            $this->load->model('ProductGallery');
            $params = [
                'product_id' => $product_id
            ];
            $productData = $this->Product->details($params);
            $productTechnicalData = $this->ProductTechnicalData->get($params);
            $productSpecifications = $this->ProductSpecification->get($params);
            $relatedProducts = $this->ProductRelated->get($params);
            // $productSpecifications         = array_strip_tags($productSpecifications, ['title']);
            // $productTechnicalData          = array_strip_tags($productTechnicalData, ['title', 'info']);
            $productData['body'] = trim(strip_tags($productData['body']));
            $productData['how_to_specity'] = trim(strip_tags($productData['how_to_specity']));
            $this->data['images'] = $this->ProductGallery->get($product_id);

            $classifiedProductArticles = [];

            foreach ($productSpecifications as $article) {
                if (!isset($classifiedProductArticles[$article['colour_temperature']])) {
                    $classifiedProductArticles[$article['colour_temperature']] = [$article];
                } else {
                    array_push($classifiedProductArticles[$article['colour_temperature']], $article);
                }
            }


            $productSpecifications = array_map(function ($article) {
                $article['image'] = preg_replace("/^\/home\/forge\//", "https://", $article['image']);
                // $article['title'] = trim(strip_tags($article['title']));
                return $article;
            }, $productSpecifications);

            $this->data['mounting'] = $mounting;
            $this->data['js'] = "article_quick";
            $this->data['product'] = $productData;
            $this->data['product_id'] = $product_id;
            $this->data['application_id'] = $applicationId;
            $this->data['room_id'] = $roomId;
            $this->data['technical_data'] = $productTechnicalData;
            $this->data['articles'] = $classifiedProductArticles;
            $this->data['related_products'] = $relatedProducts;
            $this->data['urlString'] = $queryString;

            website_view('quickcalc/select_article', $this->data);
        } catch (Exception $ex) {
            show404("ERROR");
        }
    }



    function quick_cal()
    {
        try {
            $this->load->model("Room");
            $post = $this->input->post();
            $post['project_id'] = $this->session->userdata('project_id');
            $option = ["where" => ["room_id" => encryptDecrypt($post['room_id'], 'decrypt'), "application_id" => encryptDecrypt($post['application_id'], 'decrypt')]];
            $room = $this->Room->get($option, true);
            $lenght = $this->calc($post['room_lenght'], $post['room_lenght_unit']);
            $width = $this->calc($post['room_breadth'], $post['room_breadth_unit']);
            $height = $this->calc($post['room_height'], $post['room_height_unit']);
            $post['room'] = $room;

            $insert = [
                "project_id" => 0,
                "room_id" => $room['room_id'],
                "name" => $room['title'],
                "count" => 1,
                "length" => $lenght,
                "width" => $width,
                "height" => $height,
                "maintainance_factor" => $room['maintainance_factor'],
                "shape" => $post['room_shape'],
                "working_plane_height" => $post['room_plane_height'], //need to confirm
                "rho_wall" => $room['reflection_values_wall'],
                "rho_ceiling" => $room['reflection_values_ceiling'],
                "rho_floor" => $room['reflection_values_floor'],
                "lux_value" => $room['lux_values'],
                "luminaries_count_x" => $post['room_luminaries_x'],
                "luminaries_count_y" => $post['room_luminaries_y'],
                "fast_calc_response" => "",
                "created_at" => date('Y-m-d H:i:s'),
                "article_code" => $post['article_code'],
                "product_id" => $post['product_id'],
                "type" => $post['type']
            ];


            $this->db->trans_begin();
            $this->load->model("UtilModel");
            $room_id = $this->UtilModel->insertTableData($insert, "quick_rooms", true);
            $this->db->trans_commit();
            $this->evaluate($insert, $room_id);
            redirect(base_url("home/applications/view-result/" . encryptDecrypt($room_id)));
        } catch (Exception $ex) {
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
            $uld = $this->UtilModel->selectQuery("uld", "product_specifications", $option);


            if (isset($uld['uld']) and '' != $uld['uld']) {
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
                    "uldUri" => $uld['uld']
                ];
            }

            $res = $this->hitCulrQuickCal($request_data);
            $options['fast_calc_response'] = $res;
            $options['id'] = $temp['id'];
            return $this->updateQuickCalData($options, $room_id);
        } catch (Exception $ex) {
        }
    }



    /**
     *
     */
    function updateQuickCalData($data, $room_id)
    {
        $temp = json_decode($data['fast_calc_response'], true);

        $update = [
            "side_view" => $temp['projectionSide'],
            "top_view" => $temp['projectionTop'],
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
            $id = encryptDecrypt($project_room_id, "decrypt");
            $this->load->model("UtilModel");
            $room_data = $this->UtilModel->selectQuery("*", "quick_rooms", ["single_row" => true, "where" => ["id" => $id]]);
            $this->data['specifications'] = $this->UtilModel->selectQuery(
                "*",
                "product_specifications",
                ["single_row" => true, "where" => ["articlecode" => $room_data['article_code'], "product_id" => $room_data['product_id']]]
            );
//            echo $this->db->last_query();
            $this->data['room_data'] = $room_data;
            $this->data["csrfName"] = $this->security->get_csrf_token_name();
            $this->data["csrfToken"] = $this->security->get_csrf_hash();
            website_view('quickcalc/evaluation_result', $this->data);
        } catch (Exception $ex) {
        }
    }

    /**
     * Article Detail
     * 
     * This method is used to display the details of the article product details.
     * 
     * @param type $applicationId
     * @param type $roomId
     * @param type $mounting
     * @param type $product_id
     * @param type $articleCode
     */
    function articleDetail($applicationId = '', $roomId = '', $mounting = 1, $productId = '', $articleCode = '')
    {

        try {
            $this->load->helper('utility');
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('basic-with-font-awesome');

            $languageCode = "en";
            $productId = encryptDecrypt($productId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");

            $this->validationData = ['room_id' => $roomId, 'product_id' => $productId, 'article_code' => $articleCode];

            $this->validateAccessoryArticleDetail();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }
            // Load models
            $this->load->model(['ProductTechnicalData', 'ProductSpecification', 'Product']);


            $productData = $this->Product->details([
                'product_id' => $productId
            ]);

            if (empty($productData)) {
                show404($this->lang->line('no_data_found'), base_url(''));
            }

            $articleData = $this->ProductSpecification->getch([
                'product_id' => $productId,
                'single_row' => true,
                'where' => ['articlecode' => $articleCode]
            ]);

            if (empty($articleCode)) {
                show404($this->lang->line('no_data_found'), base_url(''));
            }

            $technicalData = $this->ProductTechnicalData->get(['product_id' => $productId]);

            $articleData['accessory_data'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                'article_code' => $articleData['articlecode'],
                'product_id' => encryptDecrypt($productId),
            ]);

            $selectedProduct = $this->UtilModel->selectQuery('id', 'project_room_products', [
                'where' => [
                    'product_id' => $productId, 'article_code' => $articleCode
                ]
            ]);

            $this->data['isSelected'] = (bool)!empty($selectedProduct);

            $this->data['technicalData'] = $technicalData;
            $this->data['productData'] = $productData;
            $this->data['articleData'] = $articleData;
            $this->data['applicationId'] = $applicationId;
            $this->data['productId'] = encryptDecrypt($productId);
            $this->data['roomId'] = encryptDecrypt($roomId);
            $this->data['articleCode'] = $articleCode;
            $this->data['mounting'] = $mounting;

            $this->data['js'] = "article_quick_cal";

            website_view('quickcalc/article_details', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('home/applications'));
        }
    }


    private function validateAccessoryArticleDetail()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([

            [
                'field' => 'product_id',
                'label' => 'Product id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'room_id',
                'label' => 'Room id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'article_code',
                'label' => 'Article code',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
        ]);
    }
}
