<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class ProjectRoomProducts extends BaseModel
{

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "project_room_products as prs";
    }

    /**
     * get project listing
     *
     * @param array $params
     * @return array
     */
    public function get($params)
    {
        $this->db->select("project_room_id, prs.product_id, prs.type, products.title, prs.mounting_type,
        IFNULL(articlecode, '') as articlecode, IFNULL(ps.image, '') as article_image,
        wattage as wattage, lifetime_hours,
         IFNULL(price, 0.00) as  price, IFNULL(currency, 0.00) as  currency, IFNULL(uld, '') as  uld", false)
            ->join('products', 'products.product_id=prs.product_id')
            ->join('product_specifications as ps', 'ps.product_id=products.product_id AND prs.article_code=ps.articlecode')
            ->from($this->tableName)
            ->group_by('articlecode, project_room_id');

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

    /**
     * Selected products
     *
     * @return array
     */
    public function selectedProducts($params)
    {
        $this->db->select('ps.image, ps.product_id, ps.articlecode, ps.title, uld,
        ps.type, driver, length, width, height, products.title as product_name, prp.type as product_type')
            ->from('project_room_products as prp')
            ->join("products", "products.product_id=prp.product_id")
            ->join('product_specifications as ps', 'ps.product_id=prp.product_id AND ps.articlecode=prp.article_code')
            ->where('prp.project_room_id', $params['project_room_id']);

        if (isset($params['search'])) {
            $this->db->where("(products.title LIKE '%{$params['search']}%' OR ps.title LIKE '%{$params['search']}%')");
        }

        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }

    /**
     * fetch all project rooms products
     *
     * @param array $params
     * @return array
     */
    public function projectRoomProducts($params)
    {
        $this->db->select("prs.*")
            ->from($this->tableName)
            ->join('project_rooms as pr', 'pr.id=prs.project_room_id');

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        if (isset($params['where_in']) && is_array($params['where_in']) && !empty($params['where_in'])) {
            foreach ($params['where_in'] as $tableColumn => $searchValue) {
                $this->db->where_in($tableColumn, $searchValue);
            }
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && (bool)$params['single_row']) {
            $data = $query->row_array();
        } else {
            $data = $query->result_array();
        }

        return $data;
    }

    /**
     * Clone Project Rooms Products Data
     *
     * @throws \Exception
     * @param array $productsData
     * @param array $sourceDestinationRoomIdMap
     * @return bool
     */
    public function cloneProjectRoomProducts($productsData, $sourceDestinationRoomIdMap)
    {
        $productInsertData = [];
        foreach ($productsData as $product) {
            $productInsertData[] = [
                'project_room_id' => $sourceDestinationRoomIdMap[$product['project_room_id']],
                'article_code' => $product['article_code'],
                'product_id' => $product['product_id'],
                'type' => $product['type']
            ];
        }

        $status = $this->db->insert_batch('project_room_products', $productInsertData);

        if (!$status) {
            throw new \Exception('Insert Error');
        }

        return $status;
    }

    /**
     * Total project charges
     *
     * @param array $params
     * @return array
     */

    public function totalProductCharges($params)
    {
        $this->db->select('sum(price) as total_product_price, prp.type')
            ->from('project_room_products as prp')
            ->join('project_rooms as pr', 'pr.id=prp.project_room_id')
            ->join('product_specifications as ps', 'ps.articlecode=prp.article_code', 'left')
            ->where('pr.project_id', $params['project_id'])
            ->group_by('prp.type')
            ->group_by('ps.articlecode');

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        if (isset($params['where_in']) && is_array($params['where_in']) && !empty($params['where_in'])) {
            foreach ($params['where_in'] as $tableColumn => $searchValue) {
                $this->db->where_in($tableColumn, $searchValue);
            }
        }

        if (isset($params['group_by']) && is_array($params['group_by']) && !empty($params['group_by'])) {
            foreach ($params['group_by'] as $field) {
                $this->db->group_by($field);
            }
        }

        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function mountingTypes()
    {

    }

    /**
     * fetch product articles
     *
     * @param array $params
     * @return array
     */
    public function fetchArticles($params)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS image, ps.product_id, ps.articlecode, title, uld,
                        ps.type, driver, length, width, height', false)
            ->from("product_specifications as ps")
            ->where('ps.product_id', $params['product_id']);

        if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
            $this->db->limit((int)$params['limit']);
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
        $data['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        return $data;
    }
}
