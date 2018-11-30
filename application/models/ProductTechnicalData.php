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
        $this->db->select('product_id, title, info')
            ->from($this->tableName);

        if (is_numeric($params['product_id'])) {
            $this->db->where('product_id', $params['product_id']);
        } elseif (is_array($params['product_id'])) {
            $this->db->where_in('product_id', $params['product_id']);
        }

        $query = $this->db->get();

        $data = $query->result_array();

        return $data;
    }
}
