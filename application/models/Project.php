<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class Project extends BaseModel
{

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "projects";
    }



    public function save_project(array $data)
    {
        $this->db->insert($this->tableName, $data);
        return $this->db->insert_id();
    }

    /**
     * get project listing
     *
     * @param array $params
     * @return array
     */
    public function get($params)
    {
        $this->db->select("SQL_CALC_FOUND_ROWS id as project_id, name, number, levels, address, lat, lng, created_at, 
                    created_at_timestamp, version,
                    IF((SELECT count(id) FROM project_requests WHERE project_id = projects.id) > 0, 1, 0) as is_quotation_requested", false)
            ->from($this->tableName)
            ->order_by('id', 'DESC');
        
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

    public function details($params)
    {
        $this->db->select("id as project_id, name, number, levels, address, lat, lng, created_at, 
                    created_at_timestamp, version,
                    (SELECT count(ps.id) FROM project_quotations as ps JOIN project_requests as pr ON pr.id=ps.request_id  WHERE pr.project_id = projects.id) as quotation_count,
                    IF((SELECT count(id) FROM project_requests WHERE project_id = projects.id) > 0, 1, 0) as is_quotation_requested")
                ->from($this->tableName)
                ->where('id', $params['project_id']);
        
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        $data = $query->row_array();

        return $data;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function saveProject($projectData, $time)
    {
        $project = [
            "language_code" => $projectData['language_code'],
            "user_id" => $projectData['user_id'],
            "company_id" => $projectData['company_id'],
            "version" => $projectData['version'] + 1,
            "installer_id" => $projectData['installer_id'],
            "number" => $projectData['number'],
            "name" => $projectData['name'],
            "levels" => $projectData['levels'],
            "address" => $projectData['address'],
            "lat" => $projectData['lat'],
            "lng" => $projectData['lng'],
            "created_at" => $time['datetime'],
            "created_at_timestamp" => $time['timestamp'],
            "updated_at" => $time['datetime'],
            "updated_at_timestamp" => $time['timestamp']
        ];

        $status = $this->db->insert($this->tableName, $project);

        if (!$status) {
            throw new \Exception('Insert Error');
        }

        return $this->db->insert_id();
    }

    /**
     * Fetches complete room data
     *
     * @param array $params
     * @return array
     */
    public function projectData($params, $field = "*")
    {
        $this->db->select($field)
            ->from($this->tableName);

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

        if (isset($params['group_by']) && is_array($params['group_by']) && !empty($params['group_by'])) {
            foreach ($params['group_by'] as $field) {
                $this->db->group_by($field);
            }
        }

        $query = $this->db->get();
        
        if (isset($params['single_row']) && (bool)$params['single_row']) {
            $data = $query->row_array();
        } else {
            $data = $query->result_array();
        }

        return $data;
    }
}
