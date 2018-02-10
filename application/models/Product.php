<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';


class Product extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "products";
    }

    public function get($params)
    {
        $query = "*";
        $single_row = false;
        if (isset($params['product_id']) && ! empty($params['product_id'])) {
            $query = "products.id as product_id, products.title, products.image, productTechnicalData(products.id) as technical_data," .
                "IFNULL(GROUP_CONCAT(gallery.image), '') as images";
            $this->db->from("products")
                ->join("product_gallery as gallery", "gallery.product_id=products.id", "left")
                ->where("products.id", $params['product_id'])
                ->limit(1);
            $single_row = true;
        } else {
            $query = "SQL_CALC_FOUND_ROWS products.id as product_id, products.title, products.image";
            $this->db->limit(RECORDS_PER_PAGE)
                ->from("product_applications as pa")
                ->join("products", "products.id=pa.product_id")
                ->where("pa.application_id", $params["application_id"]);
            if (isset($params['offset']) && !empty($params['offset'])) {
                $this->db->offset((int)$params['offset']);
            }
        }

        $this->db->select($query, false);

        $query = $this->db->get();
        // echo $this->db->last_query();die;
        if ($single_row) {
            $result = $query->row_array();
            $technical_data = utf8_encode($result['technical_data']);
            $result['technical_data'] = json_decode($result['technical_data'], true);
            if ( json_last_error() > 0 ) {
                $result['technical_data'] = $technical_data;
            }
            $result['images'] = explode(",", $result['images']);
        } else {
            $result["result"] = $query->result_array();
            $result["count"] = $this->db->query("SELECT FOUND_ROWS() as total_rows")->row()->total_rows;
        }

        return $result;
    }
}