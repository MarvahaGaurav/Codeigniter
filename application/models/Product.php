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
        if (
           ( (isset($params['product_id']) && ! empty($params['product_id'])) ||
            (isset($params['product_listing']) && !empty($params['product_listing'])) ) &&
            (!isset($params['application_id']) && empty($params['application_id'])) 
        ) {
           
            $this->db->from("products");
            if ( isset($params['product_listing']) && !empty($params['product_listing']) ) {
                $query = "SQL_CALC_FOUND_ROWS products.id as product_id, products.title as product_title, products.image";
                $this->db->where("products.language_code", $params['language_code']);
                $search = true;
                if ( ! isset($params['search']) || empty($params['search']) ) {
                    $search = false;
                    $this->db->limit(RECORDS_PER_PAGE);
                } else {
                    $this->db->like('products.title', $params['search']);
                }
                if ( isset($params['offset']) && !empty((int)$params['offset']) && ! $search ) {
                    $this->db->offset((int)$params['offset']);
                }
            } else {
                $query = "products.id as product_id, products.title as product_title, how_to_specity as description, products.image, productTechnicalData(products.id) as technical_data," .
                "IFNULL(GROUP_CONCAT(gallery.image), '') as images";
                $single_row = true;
                
                $this->db->where("products.id", $params['product_id'])
                ->join("product_gallery as gallery", "gallery.product_id=products.id", "left")
                ->group_by("products.id")
                ->limit(1);
            }
        } else {
            $query = "SQL_CALC_FOUND_ROWS products.id as product_id, products.title as product_title, products.image";
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
        if ($single_row) {
            $result = $query->row_array();
            $technical_data = utf8_encode($result['technical_data']);
            $result['technical_data'] = json_decode($result['technical_data'], true);
            if ( json_last_error() > 0 ) {
                $result['technical_data'] = $technical_data;
            } else {
                $result['technical_data'] = array_map(function($data){
                    $data['info'] = strip_tags($data['info']);
                    return $data;
                }, $result['technical_data']);
            }
            $result['images'] = explode(",", $result['images']);
        } else {
            $result["result"] = $query->result_array();
            $result["count"] = $this->db->query("SELECT FOUND_ROWS() as total_rows")->row()->total_rows;
        }
        return $result;
    }

    public function productByType($options) 
    {
        $this->db->select("SQL_CALC_FOUND_ROWS products.id as product_id, products.title as product_title, products.image", false)
        ->from("(SELECT product_applications.product_id, product_applications.application_id FROM product_applications ".
        "GROUP BY product_applications.product_id) as distinct_products")
        ->join("products", "products.id=distinct_products.product_id")
        ->join("applications", "applications.id=distinct_products.application_id")
        ->where("applications.type", isset($options['type'])?(int)$options['type']:APPLICATION_RESIDENTIAL)
        ->where("products.language_code",isset($options['language_code'])?$options['language_code']:'en')
        ->limit(isset($options['limit'])?(int)$options['limit']:RECORDS_PER_PAGE)
        ->offset(isset($options['offset'])?(int)$options['offset']:0);

        $query = $this->db->get();
        $data['result'] = $query->result_array();
        $data['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;
        
        return $data;

        
    }
}