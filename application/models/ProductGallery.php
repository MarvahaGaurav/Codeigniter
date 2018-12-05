<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';


class ProductGallery extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "product_gallery";
    }

    public function get($productIds)
    {
        $this->db->select("*")
            ->from($this->tableName)
            ->where_in('product_id', $productIds);

        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }
}
