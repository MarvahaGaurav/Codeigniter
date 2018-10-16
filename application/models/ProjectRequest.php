<?php

defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class ProjectRequest extends BaseModel {

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "project_requests as pr";
    }



    /**
     * get project listing
     *
     * @param array $params
     * @return array
     */
    public function get($params)
    {
        $query = "";
        $this->db->select($query, false)
            ->join('project_id')
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
     * Awaiting request query
     *
     * @param array $params
     * @return array
     */
    public function awaitingRequest($params)
    {
        $query = "SQL_CALC_FOUND_ROWS pr.id as request_id, project_id, name, number, levels, address, lat, lng,
        pr.created_at, UNIX_TIMESTAMP(pr.created_at) as created_at_timestamp";

        if (isset($params['type']) && (int)$params['type'] === AWAITING_REQUEST_TECHNICIAN) {
            $query .= ", GeoDistDiff('km', lat, lng, {$params['lat']}, {$params['lng']}) as distance";
            // $this->db->having('distance <=', REQUEST_SEARCH_RADIUS);
            $this->db->where("NOT EXISTS(SELECT pq.id FROM project_quotations as pq WHERE pq.request_id=pr.id AND pq.company_id={$params['company_id']} LIMIT 1)", null, false);
        } else {
            $this->db->where("NOT EXISTS(SELECT pq.id FROM project_quotations as pq WHERE request_id=pr.id LIMIT 1)", null, false);
        }

        $this->db->select($query, false)
                ->from($this->tableName)
                ->join("projects", "pr.project_id=projects.id")
                ->where('is_active', 1)
                ->order_by('pr.id', 'DESC');
        
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
        // echo $this->db->last_query();die;
        $result['data'] = $query->result_array();
        $result['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;

        return $result;
    }
}