<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

class Permission extends CI_Model 
{
    public function __construct()
    {
        $this->load->database();
    }
    
    public function get($options)
    {
        if ( isset($options['employee_id']) && !empty(trim($options['employee_id'])) ) {
            $this->db->select("user.first_name as full_name, user.user_id ,user.image as user_image,".
            "user.address, user.user_type,user.email, user.company_id,".
            "IFNULL(quote_view, 0) as quote_view, IFNULL(quote_add, 0) as quote_add,".
            "IFNULL(quote_edit, 0) as quote_edit, IFNULL(quote_delete, 0) as quote_delete,".
            "IFNULL(insp_view, 0) as insp_view,".
            "IFNULL(insp_add, 0) as insp_add, IFNULL(insp_edit, 0) as insp_edit,".
            "IFNULL(insp_delete, 0) as insp_delete, IFNULL(project_view, 0) as project_view,".
            "IFNULL(project_add, 0) as project_add, IFNULL(project_edit, 0) as project_edit, IFNULL(project_delete, 0) as project_delete");
        } else {
            $this->db->select("SQL_CALC_FOUND_ROWS pr_id, user.user_id, user.first_name as full_name,".
            "user.image as user_image, user.address, user.user_type,user.email, user.company_id,".
            "IFNULL(quote_view, 0) as quote_view, IFNULL(quote_add, 0) as quote_add,".
            "IFNULL(quote_edit, 0) as quote_edit, IFNULL(quote_delete, 0) as quote_delete,".
            "IFNULL(insp_view, 0) as insp_view,".
            "IFNULL(insp_add, 0) as insp_add, IFNULL(insp_edit, 0) as insp_edit,".
            "IFNULL(insp_delete, 0) as insp_delete, IFNULL(project_view, 0) as project_view,".
            "IFNULL(project_add, 0) as project_add, IFNULL(project_edit, 0) as project_edit,".
            "IFNULL(project_delete, 0) as project_delete", FALSE);
        }
        if ( isset($options['employee_id']) && !empty(trim($options['employee_id'])) ) {
            $this->db->from('ai_user as user');
        }
        $this->db->join('user_employee_permission as permission', 'user.user_id=permission.employee_id AND permission.status = 1', "left");
        $this->db->where('user.user_id', (int)$options['employee_id'])
        ->where('user.company_id', (int)$options['company_id'])
        ->where('user.status', 1);
        
        $query  = $this->db->get();

        if ( isset($options['employee_id']) && !empty(trim($options['employee_id'])) ) {
            $result = $query->row_array();
        } else {    
            $result['result'] = $query->result_array();
            $result['count'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        }

        return $result;
    }
}