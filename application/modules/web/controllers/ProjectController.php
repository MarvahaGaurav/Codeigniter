<?php

defined("BASEPATH") or exit("No direct script access allowed");
require_once "BaseController.php";

class ProjectController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->neutralGuard();
        if (isset($this->userInfo, $this->userInfo['user_id']) && ! empty($this->userInfo['user_id'])) {
            $this->data['userInfo'] = $this->userInfo;
        }
        if (! empty($this->userInfo) && isset($this->userInfo['status']) && $this->userInfo['status'] != BLOCKED) {
            $params['limit'] = 5;
            $page            = $this->input->get('page');
            $search          = $this->input->get('search');
            $search          = trim($search);
            if (is_numeric($page) && (int) $page > 0) {
                $params['offset'] = ((int) $page - 1) * $params['limit'];
            }

            if (isset($search) && is_string($search) && strlen($search) > 0) {
                $params['where']['name LIKE'] = "%{$search}%";
            }
            $params['where']['user_id'] = $this->userInfo['user_id'];

            $this->load->model("Project");
            $this->data['search']   = (string) $search;
            $temp                   = $this->Project->get($params);
            $this->data['projects'] = $temp['data'];
            $this->load->library('Commonfn');
            $this->data['links']    = $this->commonfn->pagination(uri_string(), $temp['count'], $params['limit']);
            load_website_views("projects/main", $this->data);
        } else {
            load_website_views("projects/main_inactive_session", $this->data);
        }
    }

    /**
     * create project
     *
     * @return void
     */
    public function create()
    {
        $this->activeSessionGuard();
        $this->data['userInfo'] = $this->userInfo;
        $this->load->config('css_config');
        $this->data['css'] = $this->config->item('create-project');

        $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

        $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_add'], base_url('home/applications'));

        $languageCode = "en";

        $this->data['employees'] = [];

        if ((int)$this->userInfo['user_type'] === INSTALLER && (int)$this->userInfo['is_owner'] === ROLE_OWNER) {
            $this->load->model('Employee');
            $this->data['employees'] = $this->Employee->employees([
                'where' => ['company_id' => $this->userInfo['company_id'], 'is_owner' => ROLE_EMPLOYEE]
            ]);
        }

        try {
            $post = $this->input->post();
            if (isset($post) and ! empty($post)) {
                $this->load->library('form_validation');
                $this->form_validation->CI = & $this;
                $rules                     = $this->setValidationRule();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run()) {
                    $insert = [
                        "user_id"       => $this->userInfo['user_id'],
                        "number"        => trim($post['project_number']),
                        "name"          => trim($post['project_name']),
                        "address"       => trim($post['address']),
                        "lat"       => trim($post['address_lat']),
                        "lng"       => trim($post['address_lng']),
                        "levels"        => trim($post['levels']),
                        "created_at"    => $this->datetime,
                        "updated_at"    => $this->datetime,
                        'created_at_timestamp' => $this->timestamp,
                        'updated_at_timestamp' => $this->timestamp,
                        "language_code" => $languageCode
                    ];

                    $levelsCount = (int)trim($post['levels']);
                    $levelsData = [];

                    if (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true)) {
                        $insert['company_id'] = (int)$this->userInfo['company_id'];
                    }

                    if ((int)$this->userInfo['user_type'] === INSTALLER &&
                     (int)$this->userInfo['is_owner'] === ROLE_OWNER
                     && strlen(trim($post['installers'])) > 0) {
                        $insert['installer_id'] = encryptDecrypt(trim($post['installers']), 'decrypt');
                    }

                    $this->load->model("Project");
                    $this->db->trans_begin();
                    $projectId = $this->Project->save_project($insert);

                    foreach (range(1, $levelsCount) as $key => $level) {
                        $levelsData[$key] = [
                            'project_id' => $projectId,
                            'level' => $level
                        ];
                    }

                    $this->load->model("UtilModel");
            
                    $this->UtilModel->insertBatch('project_levels', $levelsData);
                    if ($this->db->trans_status() === true) {
                        $this->db->trans_commit();
                        $this->session->set_userdata('project_id', $projectId);
                        $this->session->set_flashdata("flash-message", 'Project Created Successfully');
                        $this->session->set_flashdata("flash-type", "success");

                        redirect(base_url("home/create-projects/levels"));
                    } else {
                        throw new Exception("Something Went Wrong", 500);
                    }
                }
            }
            $this->data['js'] = 'project';
            website_map_modal_view("projects/create_project", $this->data);
        } catch (Exception $ex) {
            $this->db->trans_rollback();
        }
    }



    private function setValidationRule()
    {
        $rules =  [
            ['field' => 'project_number', 'label' => 'Project Number', 'rules' => 'trim'],
            ['field' => 'project_name', 'label' => 'Project Name', 'rules' => 'required|trim'],
            ['field' => 'levels', 'label' => 'Levels', 'rules' => 'required|trim'],
            ['field' => 'address', 'label' => 'Address', 'rules' => 'required|trim'],
            ['field' => 'address_lat', 'label' => 'Address', 'rules' => 'required|trim'],
            ['field' => 'address_lng', 'label' => 'Address', 'rules' => 'required|trim'],
        ];

        if ((int)$this->userInfo['user_type'] === INSTALLER && (int)$this->userInfo['is_owner'] === ROLE_OWNER) {
            $rules[] = [
                'field' => 'installer_id', 'label' => 'Installer', 'rules' => 'trim|is_natural_no_zero'
            ];
        }

        return $rules;
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
            $params['type']          = APPLICATION_RESIDENTIAL;
            $params['all_data']      = true;

            if (is_numeric($applicationType) && in_array((int) $applicationType, [APPLICATION_PROFESSIONAL, APPLICATION_RESIDENTIAL], true)) {
                $params['type'] = (int) $applicationType;
            }

            $applications                    = $this->Application->get($params);
            $this->data['applicationChunks'] = array_chunk($applications, 4);
            $this->data['type']              = $params['type'];

            website_view('projects/application', $this->data);
        } catch (\Exception $error) {
        }
    }



    /**
     *
     */
    public function rooms($application_id)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo']       = $this->userInfo;
            $this->data['project_id']     = $this->session->userdata('project_id');
            $this->data['application_id'] = $application_id;
            $this->session->set_userdata('application_id', $application_id);
            $this->load->model("ProjectRooms");
            $params                       = [
                "where" => [
                    "project_id" => $this->data['project_id']
                ]
            ];
            $params['limit']              = 5;
            $page                         = $this->input->get('page');
            $search                       = $this->input->get('search');
            $search                       = trim($search);
            if (is_numeric($page) && (int) $page > 0) {
                $params['offset'] = ((int) $page - 1) * $params['limit'];
            }

            if (isset($search) && is_string($search) && strlen($search) > 0) {
                $params['where']['name LIKE'] = "%{$search}%";
            }

            $this->load->model("ProjectRooms");
            $this->load->library('Commonfn');
            $temp                    = $this->ProjectRooms->get($params);
            $this->data['links']     = $this->commonfn->pagination(uri_string(), $temp['count'], $params['limit']);
            $this->data['rooms']     = $temp['data'];
            $this->data["csrfName"]  = $this->security->get_csrf_token_name();
            $this->data["csrfToken"] = $this->security->get_csrf_hash();
            $this->data['js']        = 'caltest';
            $this->data['is_edit']   = $this->checkData($this->data['project_id']);
            website_view('projects/room_list', $this->data);
        } catch (Exception $ex) {
        }
    }



    private function checkData($project_id)
    {
        $this->load->model("UtilModel");
        $i    = 1;
        $data = $this->UtilModel->selectQuery("fast_calc_response", "project_rooms", ["single_row" => true, "where" => ["project_id" => $project_id]]);

        if (isset($data['fast_calc_response']) and '' != $data['fast_calc_response']) {
            $i = $i * 0;
        }
        return $i;
    }



    /**
     * Display Rooms by Application
     *
     * @return void
     */
    public function room_type($applicationId = '')
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo']       = $this->userInfo;
            $this->data['project_id']     = $this->session->userdata('project_id');
            $this->data['application_id'] = isset($application_id)?$application_id:'';

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

            website_view('projects/rooms_type', $this->data);
        } catch (\Exception $error) {
        }
    }



    /**
     * Add A new room
     *
     * @param type $applicationId
     * @param type $roomId
     */
    function add_rooms($applicationId = '', $roomId = '')
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $get_array              = [];
            $this->load->helper('cookie');
            $cookie_data            = get_cookie("add_room_form_data");
            parse_str($cookie_data, $get_array);
            if (! count($get_array)) {
                $get_array = $this->setBlankArray();
            }

            $this->data['mounting_type'] = $this->mountingType();
            $this->data['selectd_room']  = $this->setSelectedRoomrray();
            $this->data['cookie_data']   = $get_array;
            $this->data['js']            = 'room_js';
            $this->load->model("Room");
            $option = ["where" => ["room_id" => encryptDecrypt($roomId, 'decrypt'), "application_id" => encryptDecrypt($applicationId, 'decrypt')]];

            $this->data['room_id']        = $roomId;
            $this->data['units']          = ["Meter", "Inch", "Yard"];
            $this->data['application_id'] = $applicationId;
            $this->data['room']           = $this->Room->get($option, true);

            website_view('projects/add_room', $this->data);
        } catch (Exception $ex) {
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
            $selectd_room = get_cookie("selectd_room");
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
        return get_cookie("mounting");
    }



    /**
     *
     */
    private function EditMountingType()
    {
        return get_cookie("quick_mounting");
    }



    /**
     *
     * @param type $applicationId
     * @param type $roomId
     */
    function select_product($applicationId = '', $roomId = '', $project_room_id = '')
    {
        try {
            $this->uri->segment(4);
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->data['js']       = 'select_product';
            if ("room-edit" == $this->uri->segment(4)) {
                $this->data['js'] = 'select_product_edit';
            }
            $this->load->model("Room");
            $option                        = ["where" => ["room_id" => encryptDecrypt($roomId, 'decrypt'), "application_id" => encryptDecrypt($applicationId, 'decrypt')]];
            $this->data['room_id']         = $roomId;
            $this->data['project_room_id'] = $project_room_id;
            $this->data['application_id']  = $applicationId;
            $this->data['room']            = $this->Room->get($option, true);
            $this->data["csrfName"]        = $this->security->get_csrf_token_name();
            $this->data["csrfToken"]       = $this->security->get_csrf_hash();
            website_view('projects/select_product', $this->data);
        } catch (Exception $ex) {
        }
    }



    /**
     *
     */
    function articles($applicationId = '', $roomId = '', $product_id = '', $project_room_id = '')
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo']        = $this->userInfo;
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

            $this->data['js'] = "article";
            if ("room-edit" == $this->uri->segment(4)) {
                $this->data['js'] = 'article_edit';
            }
            $this->data['project_room_id']  = encryptDecrypt($project_room_id);
            $this->data['product']          = $productData;
            $this->data['product_id']       = $product_id;
            $this->data['application_id']   = $applicationId;
            $this->data['room_id']          = $roomId;
            $this->data['technical_data']   = $productTechnicalData;
            $this->data['specifications']   = $productSpecifications;
            $this->data['related_products'] = $relatedProducts;

            website_view('projects/select_article', $this->data);
        } catch (Exception $ex) {
            show404("ERROR");
        }
    }



    /**
     *
     */
    function get_product()
    {
        try {
            if (! $this->input->is_ajax_request()) {
                exit('No direct script access allowed');
            }
            $post = $this->input->post();

            $this->load->model('Product');

            $option = [
                "where" => ["type"    => $post['mounting'],
                    "room_id" => encryptDecrypt($post['room_id'], 'decrypt')
                ]
            ];
            $array  = $this->Product->productByMountingType($option);
            if (count($array['data'])) {
                $res = [
                    "code" => 200,
                    "data" => $array['data']
                ];
            } else {
                $res = [
                    "code" => 500,
                    "data" => []
                ];
            }
            echo json_encode($res);
        } catch (Exception $ex) {
        }
    }



    function evaluation()
    {
        $this->data['js']        = 'caltest';
        $this->data["csrfName"]  = $this->security->get_csrf_token_name();
        $this->data["csrfToken"] = $this->security->get_csrf_hash();
        website_view('quickcalc/evaluation_result', $this->data);
    }



    function create_room()
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
            $insert             = [
                "project_id"           => $post['project_id'],
                "room_id"              => $room['room_id'],
                "name"                 => $room['title'],
                "level"                => 1,
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
                "fast_calc_response"   => '',
                "created_at"           => date('Y-m-d H:i:s')
            ];
//            pd($post);
            $this->load->model("ProjectRooms");
            $this->db->trans_begin();
            $room_id            = $this->ProjectRooms->save_project($insert);
            $this->saveRoomProduct($room_id, $post);
            $this->db->trans_commit();
            redirect(base_url("home/projects/XwD3wMO9DZgBkyTR19PDWgPer3DPer3DPer3A6265617574796c69766b696e67646f6d/rooms"));
        } catch (Exception $ex) {
            show404("Something Went Worng!! Please Try After Some Time");
        }
    }



    /**
     *
     */
    private function saveRoomProduct($room_id, $post)
    {

        $insert = [
            "project_room_id" => $room_id,
            "article_code"    => $post['article_code'],
            "product_id"      => $post['product_id'],
            "type"            => $post['type']
        ];
        $this->load->model("UtilModel");
        return $this->UtilModel->insertTableData($insert, 'project_room_products', true);
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



    public function view_result($project_room_id)
    {
        try {
            $this->neutralGuard();
            if (isset($this->userInfo, $this->userInfo['user_id']) && ! empty($this->userInfo['user_id'])) {
                $this->data['userInfo'] = $this->userInfo;
            }
            $id                      = encryptDecrypt($project_room_id, "decrypt");
            $this->load->model("UtilModel");
            $this->data['room_data'] = $this->UtilModel->selectQuery("*", "project_rooms", ["single_row" => true, "where" => ["id" => $id]]);
            website_view('projects/view_result', $this->data);
        } catch (Exception $ex) {
        }
    }



    /**
     *
     */
    public function project_details($project_id)
    {
        try {
            $this->activeSessionGuard();
            $id                                = encryptDecrypt($project_id, "decrypt");
            $this->load->model("Project");
            $this->load->model("ProjectRooms");
            $this->data['userInfo'] = $this->userInfo;
            $roomParams['where']['project_id'] = $id;
            $roomParams['limit']               = 5;
            $roomData                          = $this->ProjectRooms->get($roomParams);
            $this->data['project']             = $this->Project->details(["project_id" => $id]);
            $rooms                             = $roomData['data'];
            $roomCount                         = (int) $roomData['count'];
            if (! empty($rooms)) {
                $roomIds                                       = array_column($rooms, 'project_room_id');
                $this->load->model('ProjectRoomProducts');
                $roomProductParams['where']['project_room_id'] = $roomIds;
                $roomProducts                                  = $this->ProjectRoomProducts->get($roomProductParams);
                $roomProducts                                  = $roomProducts['data'];
                $roomProducts                                  = array_map(function ($product) {
                    $product['article_image'] = preg_replace("/^\/home\/forge\//", "https://", $product['article_image']);
                    return $product;
                }, $roomProducts);
                $this->load->helper('db');
                $rooms = getDataWith($rooms, $roomProducts, 'project_room_id', 'project_room_id', 'products');
            }

            $this->data['rooms']           = $rooms;
            $this->data['room_count']      = $roomCount;
            $this->data['has_more_rooms']  = $roomCount > 4;
            $this->data['page_room_count'] = $roomParams['limit'];


            website_view('projects/project_details', $this->data);
        } catch (Exception $ex) {
        }
    }


    function edit_room($applicationId, $project_room_id)
    {
        try {
            $room_id                   = encryptDecrypt($project_room_id, "decrypt");
            $this->load->model(["UtilModel", "Room"]);
            $data                      = $this->UtilModel->selectQuery("*", "project_rooms", ["single_row" => true, "where" => ["id" => $room_id]]);
            $this->data['cookie_data'] = $this->setRoomArray($data);

            $this->data['units']           = ["Meter", "Inch", "Yard"];
            $option                        = ["where" => ["room_id" => $data['room_id'], "application_id" => encryptDecrypt($applicationId, 'decrypt')]];
            $this->data['project_room_id'] = $room_id;
            $this->data['room_id']         = encryptDecrypt($data['room_id']);
            $this->data['units']           = ["Meter", "Inch", "Yard"];
            $this->data['application_id']  = $applicationId;
            $this->data['room']            = $this->Room->get($option, true);
            $this->data['js']              = 'room_edit';

            $product                   = $this->UtilModel->selectQuery("*", "project_room_products", ["single_row" => true, "where" => ["project_room_id" => $room_id]]);
            $product_spe               = $this->UtilModel->selectQuery(
                "*",
                "product_specifications",
                ["single_row" => true, "where" => ["articlecode" => $product['article_code'], "product_id" => $product['product_id']]]
            );
            $this->data['product_spe'] = $product_spe;
            $this->data['product']     = $product;

            $this->load->helper("cookie");
            $this->data['mounting_type'] = $this->EditMountingType();
            $this->data['selectd_room']  = $this->editSelectedRoomrray();
            website_view('projects/edit_room', $this->data);
        } catch (Exception $ex) {
        }
    }



    function update_room()
    {
        try {
            $this->load->model(["UtilModel", "Room"]);
            $post         = $this->input->post();
            $option       = ["where" => ["room_id" => encryptDecrypt($post['room_id'], 'decrypt'), "application_id" => encryptDecrypt($post['application_id'], 'decrypt')]];
            $room         = $this->Room->get($option, true);
            $lenght       = $this->calc($post['room_lenght'], $post['room_lenght_unit']);
            $width        = $this->calc($post['room_breadth'], $post['room_breadth_unit']);
            $height       = $this->calc($post['room_height'], $post['room_height_unit']);
            $post['room'] = $room;
            $insert       = [
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
                "fast_calc_response"   => ($post['room_luminaries_x'] * $post['room_luminaries_y'])
            ];
//            pd($post);
            $this->load->model("ProjectRooms");
            $this->db->trans_begin();
            $room_id      = $this->UtilModel->updateTableData($insert, "project_rooms", ['id' => $post['project_room_id']]);

            $update = ['article_code' => $post['article_code'], 'product_id' => $post['product_id']];
            $this->UtilModel->updateTableData($update, "project_room_products", ['project_room_id' => $post['project_room_id']]);
            $this->db->trans_commit();
            redirect(base_url("home/projects/" . $post['application_id'] . "/rooms"));
        } catch (Exception $ex) {
            show404("Something Went Worng!! Please Try After Some Time");
        }
    }



    /**
     *  if cookie is not set, return Blank Array
     * @return Array
     */
    private function setRoomArray($data)
    {
        return [
            "room_refrence"            => $data['name'],
            "room_lenght"              => $data['length'],
            "room_lenght_unit"         => "Meter",
            "room_breadth"             => $data['width'],
            "room_breadth_unit"        => "Meter",
            "room_height"              => $data['height'],
            "room_height_unit"         => "Meter",
            "room_plane_height"        => $data['height'],
            "room_plane_height_unit"   => "Meter",
            "room_luminaries_x"        => $data['luminaries_count_x'],
            "room_luminaries_y"        => $data['luminaries_count_y'],
            "room_shape"               => "",
            "room_pendant_length"      => "",
            "room_pendant_length_unit" => "Meter"
        ];
    }



    /**
     *
     */
    private function editSelectedRoomrray()
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
}
