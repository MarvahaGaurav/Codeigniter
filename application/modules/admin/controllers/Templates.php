<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Templates extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'debuging','custom_cookie', 'form', 'encrypt_openssl', 'input_data']);
        $this->load->model('Common_model');
        $this->load->model('Admin_Model');
        $this->load->library('session');
        $sessionData = validate_admin_cookie('rcc_appinventiv', 'admin');
        if ($sessionData) {
            $this->session->set_userdata('admininfo', $sessionData);
        }
        $this->admininfo = $this->session->userdata('admininfo');
        if (empty($this->admininfo)) {
            redirect(base_url() . 'admin/Admin');
        }
        $this->data = [];
        $this->data['admininfo'] = $this->admininfo;
        $this->data['numeric_field_maxlength'] = 10;
        if ($this->admininfo['role_id'] == 2) {
            $whereArr = ['where' => ['admin_id' => $this->admininfo['admin_id']]];
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['viewp', 'addp', 'editp', 'blockp', 'deletep', 'access_permission', 'admin_id', 'id'], $whereArr, false);
            $this->data['admin_access_detail'] = $access_detail;
        }
        $this->load->model("Template");
        $this->datetime = date("Y-m-d H:i:s");
    }

    public function index()
    {
        $this->load->helper("datetime");
        $get = $this->input->get();
        if (isset($get['startDate']) ) {
            $get['startDate'] = convert_date_time_format("d/m/Y", $get['startDate'], "Y-m-d");
        }
        if (isset($get['endDate']) ) {
            $get['endDate'] = convert_date_time_format("d/m/Y", $get['endDate'], "Y-m-d");
        }
        if (isset($get['building_type']) ) {
            $get['building_type'] = encryptDecrypt($get['building_type'], 'decrypt');
        }
        $get = trim_input_parameters($get);
        $page = isset($get['page'])&&!empty((int)$get['page'])?(int)$get['page']:1;
        $limit = isset($get['limit'])&&!empty((int)$get['limit'])?(int)$get['limit']:10;
        $search = isset($get['searchlike'])?$get['searchlike']:""; 
        // pd($get);
        $filter_data = [
            "building_type" => ["templates.category_id" => isset($get['building_type'])?$get['building_type']:'' ],
            // "total_rooms" => ["templates.created_at" => isset($get['total_rooms'])?$get['total_rooms']:'' ],
            "start_date" => ["DATE(templates.created_at) >=" => isset($get['startDate'])?$get['startDate']:'' ],
            "end_date" => ["DATE(templates.created_at) <=" =>  isset($get['endDate'])?$get['endDate']:''],
            "room_shape" => [ "templates.room_shape" => isset($get['room_shape'])?$get['room_shape']:'' ],
            "lux_value" => ["templates.lux_value" => isset($get['lux_value'])?$get['lux_value']:''],
        ];

        $filter_data = trim_input_parameters($filter_data);
        
        $defaultPermission['viewp'] = 1;
        $defaultPermission['blockp'] = 1;
        $defaultPermission['editp'] = 1;
        $defaultPermission['deletep'] = 1;
        if ($role_id != 1) {
            $whereArr = [];
            $whereArr['where'] = array('admin_id' => $this->admininfo['admin_id'], 'access_permission' => 1, 'status' => 1);
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['viewp', 'blockp', 'deletep', 'editp', 'blockp'], $whereArr, true);
        }

        $this->data['accesspermission'] = ($role_id == 2) ? $access_detail : $defaultPermission;
        
        $options = [
            "limit" => $limit,
            "offset" => ($page - 1) * $limit,
            "room" => true
        ];

        $options['where'] = [];

        foreach ( $filter_data as $key => $value ) {
            $options['where'] = array_merge($options['where'], $value);
        }
        //fetch data
        $this->load->model("Application");
        $data = $this->Template->get($options);
        $application_data = $this->Application->get(['type' => '', 'language_code' => 'en']);
        
        $application_data = array_map(
            function ($data) {
                $data['id'] = $data['application_id'];
                $data['application_id'] = encryptDecrypt($data['application_id']);
                return $data;
            }, $application_data
        );
        //prepare view
        $this->data['applications'] = $application_data;
        $this->data['searchlike'] = $search;
        $this->data['limit'] = $limit;
        $this->data['templates'] = $data['result'];
        $this->data['filter_display'] = [
            "building_type" => isset($get['building_type'])?$get['building_type']:'',
            // "total_rooms" => ["templates.created_at" => isset($get['total_rooms'])?$get['total_rooms']:'' ],
            "startDate" => isset($get['startDate'])?convert_date_time_format("Y-m-d", $get['startDate'], 'd/m/Y'):'',
            "endDate" => isset($get['endDate'])?convert_date_time_format("Y-m-d", $get['endDate'], 'd/m/Y'):'',
            "room_shape" => isset($get['room_shape'])?$get['room_shape']:'',
            "lux_value" => ''
        ];

        $this->data['filter_display'] = trim_input_parameters($this->data['filter_display']);

        $this->load->helper("datetime");
        $counter = 0;
        $this->data['room_map'] = [
            1 => "Rectangular",
            2 => "Circular"   
        ];
        $this->data['templates'] = array_map(
            function ($template) use ($page, $counter) {
                // $template['page'] = $page - 1;
                $template['dimensions'] = "{$template['room_length']}{$template['room_length_unit']}x" .
                                      "{$template['room_breath']}{$template['room_breath_unit']}x".
                                      "{$template['room_height']}{$template['room_height_unit']}";
                $template['room_shape'] = in_array($template['room_shape'], array_keys($this->data['room_map']))?$this->data['room_map'][$template['room_shape']]:"";
                $template['template_id'] = encryptDecrypt($template['id']);
                $template['created_at'] = convert_date_time_format("Y-m-d H:i:s", $template['created_at'], "d M Y g:i A");
                $template['updated_at'] = convert_date_time_format("Y-m-d H:i:s", $template['updated_at'], "d M Y g:i A");
                return $template;
            }, $this->data['templates']
        );

        
        $this->load->library("Commonfn");
        $this->data['sno_start'] = (($page - 1) * $limit) + 1;
        $this->data['link'] = $this->commonfn->pagination("admin/templates", $data['count'], $limit);
        // pd($this->data['templates']) ;
        load_views("project_templates/index", $this->data);
    }

    public function add()
    {
        $this->load->library("form_validation");
        $this->form_validation->set_rules($this->validation_rules());
        $post = $this->input->post();
        if (isset($post['room_type']) && !empty($post['room_type']) ) {
            $post['room_type'] = encryptDecrypt($post['room_type'], 'decrypt');
        }
        if (isset($post['category']) && !empty($post['category']) ) {
            $post['category'] = encryptDecrypt($post['category'], 'decrypt');
        }
        $post = trim_input_parameters($post);
        $this->form_validation->set_data($post);
        if ($this->form_validation->run() ) {
            $this->load->helper(['images']);
            $this->Template->room_id = $post['room_type'];
            $this->Template->category_id = $post['category'];
            $this->Template->type = $post['lighting'];
            if (isset($post['imgurl']) ) {
                $this->Template->image = $imageName = s3_image_uploader(ABS_PATH.$post['imgurl'], $post['imgurl']);
            }
            $this->Template->room_length = $post['room_length'];
            $this->Template->room_length_unit = $post['room_length_unit'];
            $this->Template->room_breath = $post['room_breath'];
            $this->Template->room_breath_unit = $post['room_breath_unit'];
            $this->Template->room_height = $post['room_height'];
            $this->Template->room_height_unit = $post['room_height_unit'];
            $this->Template->workplane_height = $post['workplane_height'];
            $this->Template->workplane_height_unit = $post['workplane_height_unit'];
            $this->Template->room_shape = $post['room_shape'];
            $this->Template->lux_value = $post['lux_value'];
            $this->Template->created_at = $this->datetime;
            $this->Template->updated_at = $this->datetime;
            
            try {
                $this->Template->save();
                redirect(base_url("/admin/templates"));
            } catch ( \Exception $error ) {
                
            }
        }
        
        $this->data['category_data'] = [];
        $this->data['room_data'] = [];
        if (! $this->form_validation->run() ) {
            $this->load->model("UtilModel");
            $post['category'] = encryptDecrypt($post['category']);
            $this->data['category_data'] = $this->UtilModel->selectQuery(
                "id, title as text",
                "applications",
                ["where" => ["type" => $post['lighting'], 'language_code' => "en"]]
            );
            $this->data['room_data'] = $this->UtilModel->selectQuery(
                "id, title as text",
                "rooms",
                ["where" => ["application_id" => encryptDecrypt($post['category'], 'decrypt')]]
            );
            $this->data['category_data'] = array_map(
                function ($data) {
                    $data['id'] = encryptDecrypt($data['id']);
                    return $data;
                }, $this->data['category_data']
            );
            $this->data['room_data'] = array_map(
                function ($data) {
                    $data['id'] = encryptDecrypt($data['id']);
                    return $data;
                }, $this->data['room_data']
            );
            
        }
        load_views_cropper("project_templates/add", $this->data);
    }

    public function edit($template_id = 0)
    {
        $template_id = encryptDecrypt($template_id, 'decrypt');
        if (!isset($template_id) || empty($template_id) ) {
            error404("", base_url("admin"));
        }
        $this->load->library("form_validation");
        $this->form_validation->set_rules($this->validation_rules());
        $post = $this->input->post();
        if (isset($post['room_type']) && !empty($post['room_type']) ) {
            $post['room_type'] = encryptDecrypt($post['room_type'], 'decrypt');
        }
        if (isset($post['category']) && !empty($post['category']) ) {
            $post['category'] = encryptDecrypt($post['category'], 'decrypt');
        }
        $post = trim_input_parameters($post);
        $this->form_validation->set_data($post);
        if ($this->form_validation->run() ) {
            $this->Template->room_id = $post['room_type'];
            $this->Template->category_id = $post['category'];
            $this->Template->type = $post['lighting'];
            if (isset($post['imgurl']) ) {
                $this->load->helper(['images']);
                $this->Template->image = $imageName = s3_image_uploader(ABS_PATH.$post['imgurl'], $post['imgurl']);
            }
            $this->Template->room_length = $post['room_length'];
            $this->Template->room_length_unit = $post['room_length_unit'];
            $this->Template->room_breath = $post['room_breath'];
            $this->Template->room_breath_unit = $post['room_breath_unit'];
            $this->Template->room_height = $post['room_height'];
            $this->Template->room_height_unit = $post['room_height_unit'];
            $this->Template->workplane_height = $post['workplane_height'];
            $this->Template->workplane_height_unit = $post['workplane_height_unit'];
            $this->Template->room_shape = $post['room_shape'];
            $this->Template->lux_value = $post['lux_value'];
            $this->Template->updated_at = $this->datetime;

            try {
                $this->Template->update(['id' => $template_id]);
                redirect(base_url("/admin/templates"));
            } catch ( \Exception $error ) {

            }
        }
        $options = [
            "template_id" => $template_id,
            "limit" => 1,
            "room" => true
        ];

        $data = $this->Template->get($options);
        $this->data['template'] = $data;

        $this->load->model("UtilModel");
        
        if (! $this->form_validation->run() ) {
            $post['category'] = encryptDecrypt($post['category']);
            $application_id =  encryptDecrypt($post['category'], 'decrypt');
            $room_type = $post['lighting'];
        } 
        if (isset($post) || empty($post)) {
            $application_id = $data['category_id'];
            $room_type = $data['type'];
        }
        //fetch data
        $this->data['category_data'] = $this->UtilModel->selectQuery(
            "id, title as text",
            "applications",
            ["where" => ["type" => $room_type, 'language_code' => "en"]]
        );
        $this->data['room_data'] = $this->UtilModel->selectQuery(
            "id, title as text",
            "rooms",
            ["where" => ["application_id" => $application_id]]
        );
        //format data
        $this->data['category_data'] = array_map(
            function ($data) {
                $data['id'] = encryptDecrypt($data['id']);
                return $data;
            }, $this->data['category_data']
        );
        $this->data['room_data'] = array_map(
            function ($data) {
                $data['id'] = encryptDecrypt($data['id']);
                return $data;
            }, $this->data['room_data']
        );
        $this->data['template_id'] = encryptDecrypt($data['id']);
        $this->data['template']['category_id'] = encryptDecrypt($this->data['template']['category_id']);
        $this->data['template']['room_id'] = encryptDecrypt($this->data['template']['room_id']);
        load_views_cropper("project_templates/edit", $this->data);
    }

    public function details($template_id = "")
    {
        $template_id = encryptDecrypt($template_id, 'decrypt');
        if (!isset($template_id) || empty($template_id) ) {
            error404("", base_url("admin"));
        }
        
        $options = [
            "template_id" => $template_id,
            "limit" => 1,
            "room" => true,
            "application" => true
        ];

        $data = $this->Template->get($options);
        if (empty($data) ) {
            error404("", base_url("admin"));
        }
        // pd($data);
        $this->data["room_type_map"] = [
            APPLICATION_RESIDENTIAL => "Residential",
            APPLICATION_PROFESSIONAL => "Professional"  
        ];
        $this->data['room_shape'] = [
            "1" => "Rectangular",
            "2" => "Circular"
        ];
        
        $this->data['template'] = $data;
        load_views("project_templates/details", $this->data);
    }

    private function validation_rules()
    {
        return [
            [
                "field" => "lighting",
                "lable" => "Select Lighting",
                "rules" => "trim|required"
            ],
            [
                "field" => "category",
                "lable" => "Select Category",
                "rules" => "trim|required"
            ],
            [
                "field" => "room_type",
                "lable" => "Select Room Type",
                "rules" => "trim|required"
            ],
            [
                "field" => "room_length",
                "lable" => "Room Lenght",
                "rules" => "trim|required|numeric"
            ],
            [
                "field" => "room_length_unit",
                "lable" => "Room Length",
                "rules" => "trim|required"
            ],
            [
                "field" => "room_breath",
                "lable" => "Room Breath",
                "rules" => "trim|required|numeric"
            ],
            [
                "field" => "room_breath_unit",
                "lable" => "Room Breath",
                "rules" => "trim|required"
            ],
            [
                "field" => "room_height",
                "lable" => "Room Height",
                "rules" => "trim|required|numeric"
            ],
            [
                "field" => "room_height_unit",
                "lable" => "Room Height",
                "rules" => "trim|required"
            ],
            [
                "field" => "workplane_height",
                "lable" => "Workplane Height",
                "rules" => "trim|required|numeric"
            ],
            [
                "field" => "workplane_height_unit",
                "lable" => "Workplane Height",
                "rules" => "trim|required"
            ],
            [
                "field" => "room_shape",
                "lable" => "Room Shape",
                "rules" => "trim|required"
            ],
            [
                "field" => "lux_value",
                "lable" => "Lux Value",
                "rules" => "trim|required|numeric"
            ],
        ];
    }

}