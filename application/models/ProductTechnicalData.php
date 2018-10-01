<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';


class ProductTechnicalData extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "product_technical_data";
    }

    public function get($params)
    {
        $this->db->select('title, info')
        ->from($this->tableName)
        ->where('product_id', $params['product_id']);

        $query = $this->db->get();

        $data = $query->result_array();

        return $data;
    }
}
