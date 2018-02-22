<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class User extends BaseModel
{
    public function __construct()
    {
        $this->load->database();
        $this->tableName = "ai_user";
    }
    
    public function login($email, $password)
    {
        $this->db->select('company_id, ai_user.user_id,first_name as full_name,email,'.
        'image, image_thumb,'.
        'ai_user.language, ai_user.currency,' .
        'ai_user.status,user_type,is_owner,'.
        'IFNULL(quote_view, 0) as quote_view, IFNULL(quote_add, 0) as quote_add,'.
        'IFNULL(quote_edit, 0) as quote_edit, IFNULL(quote_delete, 0) as quote_delete,'.
        'IFNULL(insp_view, 0) as insp_view,'.
        'IFNULL(insp_add, 0) as insp_add, IFNULL(insp_edit, 0) as insp_edit,'.
        'IFNULL(insp_delete, 0) as insp_delete, IFNULL(project_view, 0) as project_view,'.
        'IFNULL(project_add, 0) as project_add, IFNULL(project_edit, 0) as project_edit, IFNULL(project_delete, 0) as project_delete')
            ->from("ai_user")   
            ->join("user_employee_permission as uep", "uep.employee_id=ai_user.user_id", 'left')
            ->where('email', $email)
            ->where('password', $password)
            ->where('ai_user.status !=', 3);

        $query = $this->db->get();

        $result = $query->row_array();

        return $result;
    }

    public function users($params = []) 
    {
        $single_row = false;
        if ( !isset($params['user_id']) || empty(trim($params['user_id'])) ) {
            $single_row = true;
        } 
        $this->db->select('*')
            ->from("ai_user")   
            ->where('password', $password)
            ->where('ai_user.status !=', 3);

        $query = $this->db->get();

        $result = $query->row_array();

        return $result;
    }

}
