<?php
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class InspirationProduct extends BaseModel {

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'inspiration_products';
    }

    public function get($inspiration_id) 
    {
        $this->db->select("inspiration_products.product_id, title, inspiration_id")
            ->from($this->tableName)
            ->join('products', 'products.product_id=inspiration_products.product_id');

        if ( is_array($inspiration_id) ) {
            $this->db->where_in("inspiration_id", $inspiration_id);
        } else {
            $this->db->where("inspiration_id", $inspiration_id);
        }

        $query = $this->db->get();

        $result = $query->result_array();

        if (!empty($result)) {
            $this->load->helper(['db']);
            $this->load->model(['ProductGallery']);
            $productIds = array_column($result, 'product_id');
            $images = $this->ProductGallery->get($productIds);
            $result = getDataWith($result, $images, 'product_id', 'product_id', 'images', 'image');
        }

        return $result;
    }

}