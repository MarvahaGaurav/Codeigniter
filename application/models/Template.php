<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;
//fields in this table
// "id, room_id,".
// "category_id, type, title, image, room_length, room_length_unit,".
// "room_width, room_width_unit, room_height, room_height_unit, workplane_height,".
// "workplane_height_unit, room_shape, lux_value, number_of_luminaries,".
// "created_at, updated_at";
class Template extends BaseModel
{
    public function __construct()
    {
        $this->load->database();
        $this->tableName = "templates";
    }
    
    public function get($options) 
    {
        $query = "";
        $singleRow = false;

        if ( isset($options['template_id']) && !empty($options['template_id'])) {
            $query = "templates.*, templates.id as template_id";
            $this->db->where("templates.id", $options['template_id']);
            $singleRow = true;
        } else {
            $query = "SQL_CALC_FOUND_ROWS templates.*, templates.id as template_id";
            
        }

        if ( isset($options['room']) && ! empty($options['room']) ) {
            $query .= ", rooms.title as room_type";
            $this->db->join("rooms", "rooms.id=templates.room_id");
        }

        if ( isset($options['application']) && !empty($options['application']) ) {
            $query .= ", applications.title as application_title";
            $this->db->join("applications", "applications.id=templates.category_id");
        }

        $this->db->select($query, false)
        ->from($this->tableName)
        ->order_by("templates.id", "desc");

        if ( isset($options['limit']) && !empty($options['limit']) ) {
            $this->db->limit($options['limit']);
        }

        if ( isset($options['offset']) && !empty($options['offset']) ) {
            $this->db->offset($options['offset']);
        } 

        if ( isset($options['where']) && !empty($options['where']) ) {
            foreach ( $options['where'] as $field_name => $field_value  ) {
                $this->db->where($field_name, $field_value);
            }
        } 

        $data = [];
        $exec = $this->db->get();
        // echo $this->db->last_query();die;
        if ( $singleRow ) {
            $data = $exec->row_array();
        } else {
            $data['result'] = $exec->result_array();
            $data['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        }

        return $data;
    }
}