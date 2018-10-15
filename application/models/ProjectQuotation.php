<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class ProjectQuotation extends BaseModel
{

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "project_quotations";
    }



    /**
     * get project listing
     *
     * @param array $params
     * @return array
     */
    public function get($params)
    {
        $this->db->select("*", false)
            ->from($this->tableName);
        
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

    /**
     * Lists all the requests which have been quoted
     *
     * @param array $params
     * @return void
     */
    public function quotedRequestList($params)
    {
        $fields = 'SQL_CALC_FOUND_ROWS pr.id as request_id, projects.name as project_name, projects.address as project_address,
        projects.lat as project_lat, projects.lng as project_lng';
        if ((int)$params['type'] === QUOTED_REQUEST_CUSTOMER) {
            $this->db->where('EXISTS(SELECT pq.id FROM project_quotations as pq WHERE request_id=pr.id AND pq.status=1 LIMIT 1)');
        } elseif ((int)$params['type'] === QUOTED_REQUEST_TECHNICIAN) {
            $fields .= ', pq.price';
            $this->db->join(
                "project_quotations as pq",
                "pq.request_id=pr.id AND pq.company_id={$params['company_id']} AND pq.status=" . QUOTATION_STATUS_QUOTED
            );
        }
        $this->db->select($fields, false)
            ->from('project_requests as pr')
            ->join('projects', 'projects.id=pr.project_id')
            ->order_by("pr.id", "DESC");
        
        if (isset($params['user_id']) && is_numeric($params['user_id'])) {
            $this->db->where('projects.user_id', $params['user_id']);
        }

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

    public function quotations($params)
    {
        $this->db->select("SQL_CALC_FOUND_ROWS pq.id as quotation_id, request_id, c.company_id,
            company_name, u.first_name as user_name, pq.price", false)
            ->from("project_quotations as pq")
            ->join('project_requests as pr', 'pr.id=pq.request_id')
            ->join("ai_user as u", "u.user_id=pq.user_id")
            ->join("company_master as c", 'c.company_id=pq.company_id')
            ->order_by("pq.id", "DESC");
            
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
        $result['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;

        return $result;
    }

    public function activeQuotation()
    {
        $this->db->select();
    }
}
