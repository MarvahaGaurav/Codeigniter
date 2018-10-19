<?php

defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class ProjectRooms extends BaseModel {

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "project_rooms";
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
        $this->db->select("id as project_room_id, room_id, count, project_id, name, length, width, height,
            maintainance_factor, shape, working_plane_height, rho_wall, rho_ceiling, rho_floor,
            lux_value, luminaries_count_x, luminaries_count_y, fast_calc_response, created_at", false)
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
     * Return Array of rooms for a request Id, along with quoted room
     *
     * @param array $params
     * @return array
     */
    public function getQuotedRooms($params)
    {
        $this->db->select('project_room_id, IFNULL(prq.id, "empty") as empty_room_quotations')
            ->from($this->tableName . ' as pr')
            ->join('project_requests as preq', 'preq.project_id=pr.project_id')
            ->join('project_room_quotations as prq', 'prq.project_room_id=pr.id AND prq.company_id=' . $params['company_id'], 'left')
            ->where('preq.id', $params['request_id']);

        $query = $this->db->get();
        
        $data = $query->result_array();

        return $data;
    }
}