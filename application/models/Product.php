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
        if (((isset($params['product_id']) && !empty($params['product_id'])) || (isset($params['product_listing']) && !empty($params['product_listing']))) && (!isset($params['application_id']) && empty($params['application_id']))) {
            $this->db->from("products");
            if (isset($params['product_listing']) && !empty($params['product_listing'])) {
                $query = "SQL_CALC_FOUND_ROWS product_id, products.title as product_title, products.image";
                $this->db->where("products.language_code", $params['language_code']);
                $search = true;
                if (!isset($params['search']) || empty($params['search'])) {
                    $search = false;
                    $this->db->limit(RECORDS_PER_PAGE);
                } else {
                    $this->db->like('products.title', $params['search']);
                }
                if (isset($params['offset']) && !empty((int)$params['offset']) && !$search) {
                    $this->db->offset((int)$params['offset']);
                }
            } else {
                $query = "product_id, products.title as product_title, how_to_specity as description, products.image, productTechnicalData(products.id) as technical_data," .
                    "IFNULL(GROUP_CONCAT(gallery.image), '') as images";
                $single_row = true;

                $this->db->where("products.id", $params['product_id'])
                    ->join("product_gallery as gallery", "gallery.product_id=products.id", "left")
                    ->group_by("products.id")
                    ->limit(1);
            }
        } else {
            $query = "SQL_CALC_FOUND_ROWS product_id, products.title as product_title, products.image";
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
            if (json_last_error() > 0) {
                $result['technical_data'] = $technical_data;
            } else {
                $result['technical_data'] = array_map(function ($data) {
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

    /**
     * Fetch product by Application Type
     *
     * @param array $options
     * @return array
     */
    public function productByType($options)
    {
        $this->db->select("SQL_CALC_FOUND_ROWS products.id as product_id, products.title as product_title, products.image", false)
            ->from("(SELECT product_applications.product_id, product_applications.application_id FROM product_applications " .
                "GROUP BY product_applications.product_id) as distinct_products")
            ->join("products", "products.id=distinct_products.product_id")
            ->join("applications", "applications.id=distinct_products.application_id")
            ->where("applications.type", isset($options['type']) ? (int)$options['type'] : APPLICATION_RESIDENTIAL)
            ->where("products.language_code", isset($options['language_code']) ? $options['language_code'] : 'en')
            ->limit(isset($options['limit']) ? (int)$options['limit'] : RECORDS_PER_PAGE)
            ->offset(isset($options['offset']) ? (int)$options['offset'] : 0);

        if (!isset($options['search']) || empty($options['search'])) {
            $this->db->where('products.title LIKE', "%{$options['search']}%");
        }

        $query = $this->db->get();
        $data['result'] = $query->result_array();
        $data['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;

        return $data;
    }

    public function searchProduct($params)
    {
        // pr($params);
        $query = $this->db->select('SQL_CALC_FOUND_ROWS pro.* , pro_sp.uld as uld,pro_sp.image as ps_image , pro_sp.title as ps_title , pro_sp.articlecode as ps_articlecode', false)
            ->from('products as pro')
            ->join('product_specifications as pro_sp', 'pro.product_id = pro_sp.product_id');

        if (isset($params['like']) && !empty($params['like'])) {
            foreach ($params['like'] as $field_name => $field_value) {
                if (empty($field_value)) {
                    $this->db->like($field_name, "''");
                } else {
                    $this->db->like($field_name, $field_value);
                }
            }
        }


        if (isset($params['offset']) && $params['offset'] >= NO) {
            $this->db->offset($params['offset']);
        }

        if (isset($params['limit']) && !empty($params['limit'])) {
            $this->db->limit($params['limit']);
        }



        if (isset($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $field_name => $field_value) {
                $this->db->where($field_name, $field_value);
            }
        }

        $query = $query->get();
        $result['data'] = $query->result_array();
        $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;
        return $result;
    }

    /**
     * Returns Product by mmounting type and room Id (if given)
     *
     * @param array $params
     * @return array
     */
    public function productByMountingType($params = [])
    {
        $this->db->select('rp.product_id, room_id, products.title, type, type_string, body')
            ->from('room_products as rp')
            ->join('products', 'products.product_id=rp.product_id')
            ->group_by('product_id');

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        $this->load->model(['ProductGallery', 'ProductTechnicalData']);

        $query = $this->db->get();

        $result['data'] = $query->result_array();
        $this->load->helper(['db', 'debuging', 'utility']);

        if (!empty($result['data'])) {
            $productIds = array_column($result['data'], 'product_id');
            $images = $this->ProductGallery->get($productIds);
            $result['data'] = getDataWith($result['data'], $images, 'product_id', 'product_id', 'images', 'image');
            $technicalData = $this->ProductTechnicalData->get([
                'product_id' => $productIds
            ]);
            $technicalData = array_strip_tags($technicalData, ['title', 'info']);
            $result['data'] = getDataWith(
                $result['data'],
                $technicalData,
                'product_id',
                'product_id',
                'technical_data'
            );
        }

        return $result;
    }

    /**
     * Room Products
     *
     * @return void
     */
    public function roomProducts($params)
    {
        $this->db->select('title, products.product_id, body')
            ->from('related_products_room')
            ->join('products', 'products.product_id=related_products_room.product_id')
            ->where('room_id', $params['room_id']);

        $query = $this->db->get();

        $result = $query->result_array();

        if (!empty($result)) {
            $this->load->model("ProductGallery");
            $this->load->helper(['db']);
            $productIds = array_column($result, 'product_id');
            $images = $this->ProductGallery->get($productIds);
            $result = getDataWith($result, $images, 'product_id', 'product_id', 'images', 'image');
        }

        return $result;
    }



    public function details($params)
    {
        $this->db->select('product_id, title, body, subtitle, how_to_specity')
            ->from($this->tableName)
            ->where('product_id', $params['product_id']);

        $query = $this->db->get();

        $data = $query->row_array();

        return $data;
    }

    /**
     * Products By inserpotation
     *
     * @param interger|array $inspirationIds
     * @return void
     */
    public function productsByInspiration($inspirationIds)
    {
        $this->db->select('title, ip.product_id, inspiration_id')
            ->join('products', 'products.product_id=ip.product_id')
            ->from('inspiration_products as ip');

        if (is_array($inspirationIds)) {
            $this->db->where_in('inspiration_id', $inspirationIds);
        } elseif (is_numeric($inspirationIds)) {
            $this->db->where('inspiration_id', $inspirationIds);
        }

        $query = $this->db->get();

        $data = $query->result_array();

        return $data;
    }


    /**
     * Return products list
     *
     * @param array $params
     * @return array
     */
    public function products($params)
    {
        if (isset($params['uld']) && (bool)$params['uld']) {
            $this->db->select("*")
                ->from('products_with_uld');
        } else {
            $this->db->select('SQL_CALC_FOUND_ROWS product_id, title, body', false)
                ->from('products');
        }

        if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
            if (isset($params['uld']) && (bool)$params['uld']) {
                $this->db->limit((int)$params['limit'] + 1);
            } else {
                $this->db->limit((int)$params['limit']);
            }
        }

        if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
            $this->db->offset((int)$params['offset']);
        }

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        $query = $this->db->get();

        $data['data'] = $query->result_array();
        if (isset($params['uld']) && (bool)$params['uld']) {
            // $data['count'] = $this->db->query(
            //     'SELECT COUNT(product_id) as count FROM products_with_uld WHERE language_code="'.$params['language_code'] . '"'
            // )->row()->count;
        } else {
            $data['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;
        }
        if (!empty($data['data'])) {
            $this->load->helper(['db', 'utility']);
            $this->load->model(['ProductGallery', 'ProductTechnicalData']);
            $productIds = array_column($data['data'], 'product_id');
            $images = $this->ProductGallery->get($productIds);
            $data['data'] = getDataWith($data['data'], $images, 'product_id', 'product_id', 'images', 'image');
            $technicalData = $this->ProductTechnicalData->get([
                'product_id' => $productIds
            ]);
            $technicalData = array_strip_tags($technicalData, ['title', 'info']);
            $data['data'] = getDataWith(
                $data['data'],
                $technicalData,
                'product_id',
                'product_id',
                'technical_data'
            );
        }


        return $data;
    }
}
