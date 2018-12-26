<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class ProjectRooms extends BaseModel
{

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

    public function details($params)
    {
        $this->db->select("id as project_room_id, room_id, level, project_id, name, length, width, height, reference_name,
            maintainance_factor, shape, working_plane_height, rho_wall, rho_ceiling, rho_floor, suspension_height,
            lux_value, luminaries_count_x, luminaries_count_y, fast_calc_response, created_at, reference_number", false)
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
        
        $result = $query->row_array();

        return $result;
    }

    /**
     * get project listing
     *
     * @param array $params
     * @return array
     */
    public function get($params)
    {
        $this->db->select("SQL_CALC_FOUND_ROWS id as project_room_id, room_id, level, project_id, name, length,
            width, height, count, maintainance_factor, shape, working_plane_height, rho_wall, rho_ceiling,
            reference_name, reference_number, rho_floor, suspension_height, lux_value, luminaries_count_x,
            luminaries_count_y, created_at", false)
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
     * get project listing
     *
     * @param array $params
     * @return array
     */
    public function fetchData($params)
    {
        $this->db->select("id as project_room_id, room_id, level, project_id, name, length,
            width, height, count, maintainance_factor, shape, working_plane_height, rho_wall, rho_ceiling,
            reference_name, reference_number, rho_floor, suspension_height, lux_value, luminaries_count_x,
            luminaries_count_y, created_at", false)
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
        
        $result = $query->result_array();

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
            ->join('project_room_quotations as prq', 'prq.project_room_id=pr.id AND prq.company_id=' . $params['company_id'], 'left');
            
        if (isset($params['request_id'])) {
            $this->db->where('preq.id', $params['request_id']);
        }

        if (isset($params['project_id'])) {
            $this->db->where('pr.project_id', $params['project_id']);
        }

        $query = $this->db->get();
        
        $data = $query->result_array();

        return $data;
    }

    /**
     * Project and room data
     *
     * @param array $params
     * @param string $fields
     * @return void
     */
    public function projectAndRoomData($params)
    {
        $this->db->select("pr.id as project_room_id, pr.count, pr.project_id, projects.user_id, projects.company_id")
            ->from("project_rooms as pr")
            ->join("projects", "projects.id=pr.project_id");

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        $query = $this->db->get();
        
        $result = $query->row_array();

        return $result;
    }

    /**
     * Fetches complete room data
     *
     * @param array $params
     * @return array
     */
    public function roomsData($params, $field = "*")
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

    /**
     * Clone Rooms for a Level
     *
     * @throws \Exception
     * @param array $roomsData
     * @param array $destinationLevels
     * @return bool
     */
    public function cloneLevelRooms($roomsData, $destinationLevels, $time)
    {
        $levelsData = [];

        foreach ($destinationLevels as $level) {
            foreach ($roomsData as $room) {
                $levelsData[] = [
                    "language_code" => $room['language_code'],
                    "project_id" => $room['project_id'],
                    "room_id" => $room['room_id'],
                    "level" => $level,
                    "name" => $room['name'],
                    "length" => $room['length'],
                    "width" => $room['width'],
                    "height" => $room['height'],
                    "maintainance_factor" => $room['maintainance_factor'],
                    "shape" => $room['shape'],
                    "working_plane_height" => $room['working_plane_height'],
                    "rho_wall" => $room['rho_wall'],
                    "rho_ceiling" => $room['rho_ceiling'],
                    "rho_floor" => $room['rho_floor'],
                    "lux_value" => $room['lux_value'],
                    "luminaries_count_x" => $room['luminaries_count_x'],
                    "luminaries_count_y" => $room['luminaries_count_y'],
                    "fast_calc_response" => $room['fast_calc_response'],
                    "side_view" => $room['side_view'],
                    "top_view" => $room['top_view'],
                    "front_view" => $room['front_view'],
                    "created_at" => $time['time'],
                    "created_at_timestamp" => $time['timestamp'],
                    "updated_at" => $time['time'],
                    "updated_at_timestamp" => $time['timestamp'],
                ];
            }
        }

        $status = $this->db->insert_batch('project_rooms', $levelsData);

        if (!$status) {
            throw new \Exception('Insert Error');
        } else {
            return true;
        }
    }


    /**
     * Project check for project
     *
     * @param integer $projectId
     * @return array
     */
    public function projectPriceCheck($projectId)
    {
        $this->db->select("pr.id, prq.id as project_room_quotation")
            ->from("project_rooms as pr")
            ->join("project_room_quotations as prq", "pr.id=prq.project_room_id", "left")
            ->where("pr.project_id", $projectId);
        
        $query = $this->db->get();
        
        $result = $query->result_array();

        return $result;
    }

    /**
     * Fetch room count by level
     *
     * @param string $projectId
     *
     * @return void
     */
    public function roomCountByLevel($projectId)
    {
        $this->db->select('id, COUNT(id) as room_count, level', false)
            ->from('project_rooms')
            ->where('project_id', $projectId)
            ->group_by('level');

        $query = $this->db->get();
        
        $result = $query->result_array();

        return $result;
    }

    /**
     * Clone
     *
     * @return void
     */
    public function cloneProjectRooms($roomsData, $projectId, $time)
    {
        $levelsData = [];

        foreach ($roomsData as $room) {
            $levelsData[] = [
                "language_code" => $room['language_code'],
                "project_id" => $projectId,
                "room_id" => $room['room_id'],
                "level" => $room['level'],
                "name" => $room['name'],
                "length" => $room['length'],
                "width" => $room['width'],
                "height" => $room['height'],
                "maintainance_factor" => $room['maintainance_factor'],
                "shape" => $room['shape'],
                "working_plane_height" => $room['working_plane_height'],
                "rho_wall" => $room['rho_wall'],
                "rho_ceiling" => $room['rho_ceiling'],
                "rho_floor" => $room['rho_floor'],
                "lux_value" => $room['lux_value'],
                "luminaries_count_x" => $room['luminaries_count_x'],
                "luminaries_count_y" => $room['luminaries_count_y'],
                "fast_calc_response" => $room['fast_calc_response'],
                "side_view" => $room['side_view'],
                "top_view" => $room['top_view'],
                "front_view" => $room['front_view'],
                "created_at" => $time['datetime'],
                "created_at_timestamp" => $time['timestamp'],
                "updated_at" => $time['datetime'],
                "updated_at_timestamp" => $time['timestamp'],
            ];
        }


        
        $status = $this->db->insert_batch('project_rooms', $levelsData);

        if (!$status) {
            throw new \Exception('Insert Error');
        } else {
            return true;
        }
    }
}
