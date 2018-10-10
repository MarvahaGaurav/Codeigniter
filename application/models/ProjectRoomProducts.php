<?php

defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class ProjectRoomProducts extends BaseModel {

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "project_room_products";
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
        $this->db->select("project_room_id, article_code, products.product_id, type, title", false)
            ->join('products' , 'products.product_id=' . $this->tableName . '.product_id')
            ->from($this->tableName);
        
        if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
            $this->db->limit((int)$params['limit']);
        }

        if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
            $this->db->offset((int)$params['offset']);
        }

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                if (is_array($searchValue)) {
                    $this->db->where_in($tableColumn, $searchValue);
                } else {
                    $this->db->where($tableColumn, $searchValue);
                }
            }
        }

        $query = $this->db->get();
        
        $result['data'] = $query->result_array();

        if (!empty($result['data'])) {
            $this->load->model("ProductGallery");
            $this->load->helper('db');
            $productIds = array_column($result['data'], 'product_id');
            $images = $this->ProductGallery->get($productIds);
            $result['data'] = getDataWith($result['data'], $images, 'product_id', 'product_id', 'images', 'image');
        }

        return $result;
    }
}