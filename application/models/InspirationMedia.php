<?php
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class InspirationMedia extends BaseModel {

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'inspiration_media';
    }

    public function get($inspiration_id) 
    {
        $this->db->select("inspiration_id, media_type, media, video_thumbnail")
        ->from($this->tableName);
        if ( is_array($inspiration_id) ) {
            $this->db->where_in("inspiration_id", $inspiration_id);
        } else {
            $this->db->where("inspiration_id", $inspiration_id);
        }
        
        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }

}