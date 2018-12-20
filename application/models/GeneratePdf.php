<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class GeneratePdf extends BaseModel {

    public function __construct()
    {

        $this->load->database();

    }



    public function getProjectProduct($project_id)
    {

        $this->db->select("room.name room_name, room.length room_length, room.width room_width, room.height room_height, room.*, room_product.*, s.*", false)
            ->from("project_rooms room")
            ->join("project_room_products room_product", "room.id  = room_product.project_room_id")
            ->join("product_specifications s", "s.product_id  = room_product.product_id and s.articlecode=room_product.article_code")
            ->where("room.project_id", $project_id)
            ->where("room_product.type", 1)
            ->order_by("room.level");
        $query = $this->db->get();
        return $query->result_array();

    }



    public function getProjectAllProduct($project_id)
    {

        $this->db->select("room.level,room.id room_id, product.title product_name, room.id room_id, (room.luminaries_count_x * room.luminaries_count_y)amount, room.count room_number,room.name room_name, room.length room_length, room.width room_width, room.height room_height, room_product.*, s.*",
                          false)
            ->from("project_rooms room")
            ->join("project_room_products room_product", "room.id  = room_product.project_room_id")
            ->join("product_specifications s", "s.product_id  = room_product.product_id and s.articlecode=room_product.article_code")
            ->join("products product", "product.product_id=room_product.product_id")
            ->where("room.project_id", $project_id)
            ->order_by("room.level");
        $query = $this->db->get();
        return $query->result_array();

    }



    /**
     *
     * @param type $compnyId
     */
    function getCompanyDetails($compnyId)
    {
        $query = $this->db->where("company_id", $compnyId)->get('company_master');
        return $query->row_array();

    }



    function getUserDetails($projectid)
    {
        $query = $this->db->select("u.*, p.name project_name, p.number project_number", false)
            ->from("projects  p")
            ->join("ai_user u", "u.user_id =p.user_id")
            ->where("p.id", $projectid)
            ->get();
        return $query->row_array();

    }



}
