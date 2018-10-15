<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';


class ProductSpecification extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "product_specifications";
    }

    public function get($params)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('product_id', $params['product_id'])
            ->where('CHAR_LENGTH(uld) >', 0)
            ->group_by('articlecode');

        $query = $this->db->get();

        $data = $query->result_array();

        return $data;
    }
}