<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class User extends BaseModel
{
    public function __construct()
    {
        $this->load->database();
        $this->tableName = "ai_user";
    }

    public function basicUserInfo($userId)
    {
        $this->db->select("user_id, first_name as full_name, email, image")
            ->from($this->tableName);
        
        if (is_array($userId)) {
            $this->db->where_in('user_id', $userId);
        } else {
            $this->db->where('user_id', $userId);
        }

        $result = $this->db->get()->result_array();

        return $result;
    }
    
    public function login($email, $password)
    {
        $this->db->select('company_id, ai_user.user_id,first_name as full_name,email,'.
        'image, image_thumb,'.
        'ai_user.language, ai_user.currency,' .
        'ai_user.status,user_type,is_owner,'.
        'country.name as country_name,' .
        'IFNULL(quote_view, 0) as quote_view, IFNULL(quote_add, 0) as quote_add,'.
        'IFNULL(quote_edit, 0) as quote_edit, IFNULL(quote_delete, 0) as quote_delete,'.
        'IFNULL(insp_view, 0) as insp_view,'.
        'IFNULL(insp_add, 0) as insp_add, IFNULL(insp_edit, 0) as insp_edit,'.
        'IFNULL(insp_delete, 0) as insp_delete, IFNULL(project_view, 0) as project_view,'.
        'IFNULL(project_add, 0) as project_add, IFNULL(project_edit, 0) as project_edit, IFNULL(project_delete, 0) as project_delete')
            ->from("ai_user")
            ->join("user_employee_permission as uep", "uep.employee_id=ai_user.user_id", 'left')
            ->join("country_list as country", "country.country_code1=ai_user.country_id")
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
        if (!isset($params['user_id']) || empty(trim($params['user_id']))) {
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

    public function installers($params)
    {
        $this->db->select("company_name, company.company_id, first_name as user_name, user_lat, user_long as user_lng,
        lat as company_lat, lng as company_lng, users.email,
        GeoDistDiff('km', lat, lng, {$params['lat']}, {$params['lng']}) as distance", false)
                ->from('ai_user as users')
                ->join('company_master as company', 'company.company_id=users.company_id')
                ->having('distance <', $params['search_radius'])
                ->where('user_type', INSTALLER)
                ->where('is_owner', ROLE_OWNER);
                
        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        $query = $this->db->get();

        $data = $query->result_array();

        return $data;
    }

    /**
     * Owner by company
     *
     * @param array|int $companyId
     * @return array
     */
    public function ownerByCompany($companyId)
    {
        $this->db->select('user_id, first_name, email, company_id')
            ->from('ai_user')
            ->where('is_owner', ROLE_OWNER);

        if (is_array($companyId)) {
            $this->db->where_in('company_id', $companyId);
        } else {
            $this->db->where('company_id', $companyId);
        }

        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }
}
