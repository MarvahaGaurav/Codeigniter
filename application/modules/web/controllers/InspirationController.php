<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once "BaseController.php";

class InspirationController extends BaseController
{

    private $userData;
    public function __construct()
    {
        parent::__construct();
        $this->active_session_required();
        $this->load->model("Inspiration");
        $this->data['userInfo'] = $this->userInfo;
/*         if (!isset($this->userInfo['user_type']) ||
            !in_array($this->userInfo['user_type'], [INSTALLER, WHOLESALER, ARCHITECT, ELECTRICAL_PLANNER]) ||
            ROLE_OWNER !== (int)$this->userInfo['is_owner']) {
            error404();
            exit;
        } */
    }

    public function index()
    {
        $this->load->helper("input_data");
        $limit = 5;
        $get = $this->input->get();
        $get = trim_input_parameters($get);
        $page = isset($get['page'])&!empty((int)$get['page'])?(int)$get['page']:1;
        $search = isset($get['search'])?$get['search']:"";

        $options['offset'] = ($page - 1) * $limit;
        $options['limit'] = $limit;
        $options['search'] = $search;
        $options['media'] = true;
        $options['products'] = true;

        $data = $this->Inspiration->get($options);
        
        $this->load->library("Commonfn");
        $technicianTypes = [INSTALLER => "Installer", ARCHITECT => "Architect", ELECTRICAL_PLANNER => "Electrical Planner", WHOLESALER => "Wholesaler"];
        $this->data['links'] = $this->commonfn->pagination("home/inspirations", $data['count'], $limit);
        $result = array_map(function($row) use ($technicianTypes){
            // $row['image'] = empty($row['image']) ? base_url("public/images/missing_avatar.svg") : $row['image'];
            $row['inspiration_id'] = encryptDecrypt($row['inspiration_id']);
            $row['description'] = strlen($row['description']) > 140 ? substr($row['description'], 0, 140) . "...": $row['description'];
            $row['products'] = json_decode("[{$row['products']}]", true);
            $row['media'] = json_decode("[{$row['media']}]", true);
            $row['media'] = !empty($row['media'])?$row['media']:base_url("public/images/missing_avatar.svg");
            return $row;
        }, $data['result']);
        $this->data['js'] = "inspirations";
        $this->data['owl'] = true;
        $this->data['inspirations'] = $result;
        $this->data['search'] = $search;
        load_alternate_views("inspirations/main", $this->data);
    }

    public function details($inspiration_id = "")
    {
        $id = $inspiration_id;
        $inspiration_id = encryptDecrypt($inspiration_id, 'decrypt');

        if ( ! isset($inspiration_id) || empty($inspiration_id) ) {
            error404();
            exit;
        }

        $options['poster_details'] = true;
        $options['media'] = true;
        $options['products'] = true;
        $options['inspiration_id'] = (int)$inspiration_id;

        $data = $this->Inspiration->get($options);

        if ( empty($data) ) {
            error404();
            exit;
        }

        $data['products'] = json_decode("[{$data['products']}]", true);
        $data['media'] = json_decode("[{$data['media']}]", true);
        $this->data['inspiration'] = $data;
        // pd($data);
        load_alternate_views("inspirations/details", $this->data);
    }

    public function add() 
    {
        $this->active_session_required();
        
        if (!isset($this->userInfo['user_type']) ||
            !in_array($this->userInfo['user_type'], [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER]) ||
            (ROLE_OWNER !== (int)$this->userInfo['is_owner'] && (!isset($this->employeePermission['insp_add']) || (int)$this->employeePermission['insp_add'] == 0))) {
            error404();
            exit;
        }
        $this->load->library("form_validation");
        $this->form_validation->CI =& $this;
        $rules = $this->addInspirationValidation();
        $this->form_validation->set_rules($rules);
        if ( $this->form_validation->run() ) {
            $this->load->helper("input_data");
            $post = $this->input->post();
            $post = trim_input_parameters($post);
            $this->Inspiration->title = $post['title'];
            $this->Inspiration->description = $post['description'];
            $this->Inspiration->user_id = $this->userInfo['user_id'];
            $this->Inspiration->company_id = $this->userInfo['company_id'];

            try {
                $this->Inspiration->save();
                $this->session->set_flashdata("flash-message", $this->lang->line("inspiration_added"));
                $this->session->set_flashdata("flash-type", "success");
                redirect(base_url("home/inspirations"));
            } catch (\Exception $error) {
                // $this->session->set_flashdata("flash-message", $this->lang->line("something_went_Worng"));
                // $this->session->set_flashdata("flash-type", "danger");
            }
        }
        $this->data['js'] = 'inspiration-add';
        $this->data['custom_select'] = true;
        $this->data['image_video_uploader'] = true;
        load_alternate_views("inspirations/add", $this->data);
    }
    
    public function edit($inspiration_id = '') 
    {
        $this->active_session_required();
        
        if (!isset($this->userInfo['user_type']) ||
            !in_array($this->userInfo['user_type'], [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER]) ||
            (ROLE_OWNER !== (int)$this->userInfo['is_owner'] && (!isset($this->employeePermission['insp_edit']) || (int)$this->employeePermission['insp_edit'] == 0))) {
            error404();
            exit;
        }

        $inspiration_id = encryptDecrypt($inspiration_id, 'decrypt');

        if ( ! isset($inspiration_id) || empty($inspiration_id) ) {
            error404();
            exit;
        }

        $options['poster_details'] = true;
        $options['media'] = true;
        $options['products'] = true;
        $options['inspiration_id'] = (int)$inspiration_id;

        $data = $this->Inspiration->get($options);

        if ( empty($data) ) {
            error404();
            exit;
        }

        $data['products'] = json_decode("[{$data['products']}]", true);
        $data['media'] = json_decode("[{$data['media']}]", true);

        if ( (int)$data['company_id'] !== (int)$this->userInfo['company_id'] ) {
            error404();
            exit;
        }
        $this->data['inspiration'] = $data;
        $this->data['inspiration_id'] =  encryptDecrypt($data['inspiration_id']);
        $this->load->library("form_validation");
        $this->form_validation->CI =& $this;
        $rules = $this->addInspirationValidation();
        $this->form_validation->set_rules($rules);

        if ( $this->form_validation->run() ) {
            $this->load->helper("input_data");
            $post = $this->input->post();
            $post = trim_input_parameters($post);
            $this->Inspiration->title = $post['title'];
            $this->Inspiration->description = $post['description'];
            $this->Inspiration->updated_at = $this->datetime;

            try {
                $this->Inspiration->update(['id' => $inspiration_id]);
                $this->session->set_flashdata("flash-message", $this->lang->line("inspiration_updated"));
                $this->session->set_flashdata("flash-type", "success");
                redirect(base_url("home/inspirations"));
            } catch (\Exception $error) {
                // $this->session->set_flashdata("flash-message", $this->lang->line("something_went_Worng"));
                // $this->session->set_flashdata("flash-type", "danger");
            }
        }

        $this->data['js'] = 'inspiration-add';
        $this->data['custom_select'] = true;
        $this->data['image_video_uploader'] = true;

        load_alternate_views("inspirations/edit", $this->data);
    }

    private function addInspirationValidation() {
        $rules = [
                [
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required|max_length[255]|alpha_numeric_spaces'
                ],
                [
                    'field' => 'description',
                    'label' => 'Description',
                    'rules' => 'trim|required|max_length[255]|alpha_numeric_spaces'
                ]
        ];
        return $rules;
    }
}