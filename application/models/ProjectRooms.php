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

        if (isset($params['limit']) && is_numeric($params['limit']) && (int) $params['limit'] > 0) {
            $this->db->limit((int) $params['limit']);
        }

        if (isset($params['offset']) && is_numeric($params['offset']) && (int) $params['offset'] > 0) {
            $this->db->offset((int) $params['offset']);
        }

        if (isset($params['where']) && is_array($params['where']) && ! empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        $query = $this->db->get();

        $result['data']  = $query->result_array();
        $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        return $result;

    }



    /**
     *
     */
    function getRoomsDataProduct($projectId)
    {
        try {
            return $this->db->select("pr.*, ps.uld")
                    ->from("projects")
                    ->join("project_rooms pr", "pr.project_id = projects.id", "left")
                    ->join("project_room_products prp", "prp on pr.id = prp.project_room_id", "left")
                    ->join("product_specifications ps", "(prp.article_code = ps.articlecode and prp.product_id = ps.product_id)", "left", false)
                    ->where("projects.id", $projectId)->get()->result_array();
//            echo $this->db->last_query();
        }
        catch (Exception $ex) {

        }

    }



    /**
     *
     */
    function updateQuickCalData($data)
    {
        $temp = json_decode($data['fast_calc_response'], true);

        $update                       = [
            "side_view"  => $temp['projectionSide'],
            "top_view"   => $temp['projectionTop'],
            "front_view" => $temp['projectionFront'],
        ];
        unset($temp['projectionSide']);
        unset($temp['projectionTop']);
        unset($temp['projectionFront']);
        $update['fast_calc_response'] = json_encode($temp);

        $this->db->where("id", $data['id'])->update($this->tableName, $update);
        return $this->db->last_query();

    }



}
