<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class ProjectRoomQuotation extends BaseModel
{

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "project_room_quotations as prq";
    }

    public function getQuotationRoom()
    {
        $this->db->select('id, project_room_id')
            ->from($this->tableName)
            ->join('project_rooms as pr', 'pr.id=prq.project_room_id')
            ->join('project_request as preq', 'preq.project_id=pr.project_id AND pr.id=' . $params['request_id']);

        $query = $this->db->get();

        $data = $query->result_array();

        return $data;
    }
    
}
