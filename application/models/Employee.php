<?php
defined("BASEPATH") or exit("No direct script access allowed");
require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class Employee extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'employee_request_master as erm';
    }

    public function employeeList($params)
    {
        $this->db->select('u.user_id,u.first_name as full_name, u.email,u.user_type,u.is_owner,IF(u.image !="",u.image,"") as image,IF(u.image_thumb !="",u.image_thumb,"") as image_thumb')
            ->from("ai_user as u")
            ->join("employee_request_master as erm", "erm.requested_by=u.user_id")
            ->where("erm.status", EMPLOYEE_REQUEST_ACCEPTED)
            ->where("u.company_id", $params['company_id'])
            ->where("u.is_owner", ROLE_EMPLOYEE)
            ->order_by('erm.er_id', 'DESC');

        if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
            $this->db->limit((int)$params['limit']);
        }

        if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
            $this->db->offset((int)$params['offset']);
        }

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        $query = $this->db->get();
        
        $result['data'] = $query->result_array();
        $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        return $result;
    }

    public function employees($params)
    {
        $this->db->select('first_name, email, user_id')
            ->join('employee_request_master as erm', 'erm.requested_to=users.user_id')
            ->from('ai_user as users');

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        if (isset($params['where_in']) && is_array($params['where_in']) && !empty($params['where_in'])) {
            foreach ($params['where_in'] as $tableColumn => $searchValue) {
                $this->db->where_in($tableColumn, $searchValue);
            }
        }

        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }

    /**
     * Undocumented function
     *
     * @param [type] $options
     * @return void
     */
    public function get($options)
    {
        $this->load->library("session");
        $default_values = [
            "limit" => RECORDS_PER_PAGE,
            "offset" => 0,
            "user_id" => (int)$this->session->userdata("sg_userinfo")['user_id'],
            "permissions" => false,
            "search" => "",
            "orderby" => "desc",
            "where" => []
        ];
        foreach ($default_values as $key => $value) {
            if (!isset($options[$key]) || empty($options[$key])) {
                $options[$key] = $value;
            }
        }

        $query = "users.user_id as id, users.first_name, users.image, country_list.name as country," .
            "city_list.name as city, user_type, is_owner, erm.status as request_status";
        $single_row = false;

        if (isset($options['employee_id']) && !empty((int)$options['employee_id'])) {
            $single_row = true;
            $query = $query . ",users.email, users.prm_user_countrycode, users.phone," .
                "users.alt_userphone, users.alt_user_countrycode, users.zipcode";
            $this->db->where("erm.requested_by", $options['employee_id']);
        } else {
            $query = "SQL_CALC_FOUND_ROWS " . $query;
        }

        if ((bool)$options['permissions']) {
            $query = $query . ",IFNULL(quote_view, 0) as quote_view, IFNULL(quote_add, 0) as quote_add," .
                "IFNULL(quote_edit, 0) as quote_edit, IFNULL(quote_delete, 0) as quote_delete," .
                "IFNULL(insp_view, 0) as insp_view," .
                "IFNULL(insp_add, 0) as insp_add, IFNULL(insp_edit, 0) as insp_edit," .
                "IFNULL(insp_delete, 0) as insp_delete, IFNULL(project_view, 0) as project_view," .
                "IFNULL(project_add, 0) as project_add, IFNULL(project_edit, 0) as project_edit, IFNULL(project_delete, 0) as project_delete," .
                "IFNULL(quote_edit, 'not_exists') as exist_check";
            $this->db->join('user_employee_permission as permission', 'erm.requested_by=permission.employee_id AND permission.status = 1', "left");
        }

        if (!empty($options['search'])) {
            $this->db->where("users.first_name LIKE", "%{$options['search']}%");
        }

        if (!empty($options['where']) && is_array($options['where'])) {
            foreach ($options['where'] as $column => $value) {
                $this->db->where($column, $value);
            }
        }
        $this->db->select($query, false)
            ->from($this->tableName)
            ->join("ai_user as users", "users.user_id=erm.requested_by")
            ->join("city_list", "city_list.id=users.city_id")
            ->join("country_list", "country_list.country_code1=users.country_id")
            ->where("requested_to", $options['user_id'])
            ->where('users.status', ACTIVE)
            ->limit($options['limit'])
            ->offset($options['offset'])
            ->order_by("erm.er_id", $options['orderby']);

        $query = $this->db->get();

        if ($single_row) {
            $result = $query->row_array();
        } else {
            $result['result'] = $query->result_array();
            $result['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        }

        return $result;
    }
}
