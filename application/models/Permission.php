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
            $this->db->select("user.first_name as full_name, user.image as user_image, user.address,".
            "quote_view, quote_add, quote_edit, quote_delete, insp_view,".
            "insp_add, insp_edit, insp_delete, project_view, project_add, project_edit, project_delete");
        } else {
            $this->db->select("SQL_CALC_FOUND_ROWS pr_id, user.first_name as full_name, user.image as user_image, user.address,".
            "quote_view, quote_add, quote_edit, quote_delete, insp_view,".
            "insp_add, insp_edit, insp_delete, project_view, project_add, project_edit, project_delete", FALSE);
        }

        if ( isset($options['employee_id']) && !empty(trim($options['employee_id'])) ) {
            $this->db->from('user_employee_permission as permission');
        }
        $this->db->where('employee_id', $options['employee_id']);
        $this->db->join('ai_user as user', 'user.user_id=permission.employee_id AND user.status = 1');
        $this->db->where('permission.user_id', $options['user_id'])
        ->where('permission.status', 1);
        
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