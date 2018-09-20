<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once "BaseController.php";

class TechnicianController extends BaseController
{

    private $userData;
    public function __construct()
    {
        parent::__construct();
        $this->activeSessionGuard();
        $this->load->model("Employee");
        $this->data['userInfo'] = $this->userInfo;
        if (!isset($this->userInfo['user_type']) 
            || !in_array($this->userInfo['user_type'], [INSTALLER, WHOLESALER, ARCHITECT, ELECTRICAL_PLANNER]) 
            || ROLE_OWNER !== (int)$this->userInfo['is_owner']
        ) {
            error404("", base_url());
            exit;
        }
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
        $options['where'] = ['erm.status' => EMPLOYEE_REQUEST_ACCEPTED];
        
        $data = $this->Employee->get($options);
        
        $this->load->library("Commonfn");
        $technicianTypes = [INSTALLER => "Installer", ARCHITECT => "Architect", ELECTRICAL_PLANNER => "Electrical Planner", WHOLESALER => "Wholesaler"];
        $this->data['links'] = $this->commonfn->pagination("home/technicians", $data['count'], $limit);
        $result = array_map(
            function ($row) use ($technicianTypes) {
                $row['user_type'] = in_array($row['user_type'], array_keys($technicianTypes))? $technicianTypes[$row['user_type']]: "Technician";
                $row['image'] = empty($row['image']) ? base_url("public/images/missing_avatar.svg") : $row['image'];
                $row['id'] = encryptDecrypt($row['id']);
                //following delete_data would be used to delete using ajax
                $row['delete_data'] = json_encode(
                    [
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                    "id" => $row['id']
                    ]
                );
                return $row;
            }, $data['result']
        );

        $this->data['technicians'] = $result;
        $this->data['search'] = $search;
        $this->data['js'] = 'technician';
        // $this->session->set_flashdata("flash-message", "");
        // $this->session->set_flashdata("flash-type", "");
        load_website_views("technicians/main", $this->data);
    }

    public function details($employee_id = '')
    {
        $employee_id = encryptDecrypt($employee_id, 'decrypt');
        if (!isset($employee_id) || empty($employee_id) ) {
            error404("", base_url());
            exit;
        }

        $options['employee_id'] = $employee_id;
        $options['limit'] = 1;
        $options['permissions'] = true;
        $data = $this->Employee->get($options);
        $data['image'] = empty($data['image']) ? base_url("public/images/missing_avatar.svg") : $data['image'];
        $this->data['technician'] = $data;

        $post = $this->input->post();
        $this->load->helper("input_data");
        $post = trim_input_parameters($post);
        if (! empty($post) ) {
            $fieldMaps = [
                "quote_view" => "quote_view",
                "quote_add" => "quote_add",
                "quote_edit" => "quote_edit",
                "quote_delete" => "quote_delete",
                "insp_view" => "inspiration_view",
                "insp_add" => "inspiration_add",
                "insp_edit" => "inspiration_edit",
                "insp_delete" => "inspiration_delete",
                "project_view" => "project_view",
                "project_add" => "project_add",
                "project_edit" => "project_edit",
                "project_delete" => "project_delete"
            ];
            $this->load->model("EmployeePermission");
            foreach ( $fieldMaps as $dbColumn => $postArrayKey ) {
                $this->EmployeePermission->$dbColumn = isset($post[$postArrayKey])?1:0;
            }
            if ($data['exist_check'] == 'not_exists' ) {
                $this->EmployeePermission->user_id = $this->userInfo['user_id'];
                $this->EmployeePermission->employee_id = $employee_id;
                $this->EmployeePermission->save();
            } else {
                $this->EmployeePermission->update(["employee_id" => $employee_id]);
            }
            $this->session->set_flashdata("flash-message", $this->lang->line("employee_permissions_updated"));
            $this->session->set_flashdata("flash-type", "success");
            redirect(base_url("home/technicians/". encryptDecrypt($employee_id)));
        }
        $this->data['js'] = 'technician';
        
        // pd($data);
        load_website_views("technicians/details", $this->data);
    }

    public function request_list()
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
        $options['where'] = ['erm.status' => EMPLOYEE_REQUEST_PENDING];

        $data = $this->Employee->get($options);
        
        $this->load->library("Commonfn");
        $technicianTypes = [INSTALLER => "Installer", ARCHITECT => "Architect", ELECTRICAL_PLANNER => "Electrical Planner", WHOLESALER => "Wholesaler"];
        $this->data['links'] = $this->commonfn->pagination("home/technicians", $data['count'], $limit);
        $result = array_map(
            function ($row) use ($technicianTypes) {
                $row['user_type'] = in_array($row['user_type'], array_keys($technicianTypes))? $technicianTypes[$row['user_type']]: "Technician";
                $row['image'] = empty($row['image']) ? base_url("public/images/missing_avatar.svg") : $row['image'];
                $row['id'] = encryptDecrypt($row['id']);
                //following accept_data would be used to accept using ajax
                $row['accept_data'] = json_encode(
                    [
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                    "id" => $row['id'],
                    "action" => "accept"
                    ]
                );
                //following reject_data  would be used to reject using ajax
                $row['reject_data'] = json_encode(
                    [
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                    "id" => $row['id'],
                    "action" => "reject"
                    ]
                );
                return $row;
            }, $data['result']
        );

        $this->data['technicians'] = $result;
        $this->data['search'] = $search;
        $this->data['js'] = 'technician';
        // $this->session->set_flashdata("flash-message", "");
        // $this->session->set_flashdata("flash-type", "");
        load_website_views("technicians/requests", $this->data);
    }

}