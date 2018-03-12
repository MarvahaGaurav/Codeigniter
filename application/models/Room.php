<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class Room extends BaseModel
{
    public function __construct()
    {
        $this->load->database();
        $this->tableName = "rooms";
    }
    
    public function get($options) 
    {
        $query = "";
        $singleRow = false;
        
        if ( isset($options['room_id']) && !empty($options['room_id'])) {
            $query = "*";
            $this->db->where("rooms.id", $options['room_id']);
            $singleRow = true;
        } else {
            $query = "SQL_CALC_FOUND_ROWS rooms.id as room_id, title, image, icon," .
                     "length, breath, height, working_plane_height, units, lux_value," .
                     "number_of_luminaries";
        }

        $this->db->select($query, false)
        ->from($this->tableName);

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

        if ( $singleRow ) {
            $data = $exec->row_array();
        } else {
            $data['result'] = $exec->result_array();
            $data['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        }

        return $data;
        
    }


}