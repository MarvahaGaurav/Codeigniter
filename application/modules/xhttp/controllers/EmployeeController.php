<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class EmployeeController extends BaseController
{   
    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("location");
    }

    public function remove()
    {
        $employee_id = $this->input->post("id");

        $employee_id = encryptDecrypt($employee_id, 'decrypt');
        
        if ( !isset($employee_id) || empty($employee_id) ) {
            json_dump([
                "success" => false,
                "message" => $this->lang->line("request_error")
            ]);
        }
        
        $this->load->model("User");

        $this->User->status = DELETED;

        try {   
            $this->User->update(['user_id' => $employee_id]);
            $this->session->set_flashdata("flash-message", $this->lang->line('employee_removed'));
            $this->session->set_flashdata("flash-type", "success");
            json_dump([
                "success" => true,
                "message" => $this->lang->line('employee_removed')
            ]);
        } catch ( \Exception $error ) {
            json_dump([
                "success" => false,
                "message" => $this->lang->line("request_error")
            ]);
        }
        
    }

    public function request_action()
    {
        $employee_id = $this->input->post("id");

        $employee_id = encryptDecrypt($employee_id, 'decrypt');
        $action = $this->input->post("action");
        $validActions = ['accept', 'reject'];
        
        if ( (!isset($employee_id) || empty($employee_id)) ||
            (!isset($action) || !in_array($action, $validActions)) ) {
            json_dump([
                "success" => false,
                "message" => $this->lang->line("request_error")
            ]);
        }

        $actionMap = [
            'accept' => EMPLOYEE_REQUEST_ACCEPTED,
            'reject' => EMPLOYEE_REQUEST_REJECTED
        ];

        $this->load->model("Employee");
        $this->load->model("User");

        $this->Employee->status = $actionMap[$action];
        
        try {   
            $this->Employee->update(['requested_by' => $employee_id]);
            $message = "";
            if ($action == "accept") {
                $message = $this->lang->line('employee_accepted');
            } else {
                $message = $this->lang->line('employee_rejected');
            }
            // $this->User->update(['user_id' => $employee_id]);
            $this->session->set_flashdata("flash-message", $message);
            $this->session->set_flashdata("flash-type", "success");
            json_dump([
                "success" => true,
                "message" => $message
            ]);
        } catch ( \Exception $error ) {
            json_dump([
                "success" => false,
                "message" => $this->lang->line("request_error")
            ]);
        }
    }
    

}