<?php
defined("BASEPATH") or exit("No direct script access allowed");

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

    public function getch($params)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('product_id', $params['product_id'])
            ->group_by('articlecode');

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
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
     * Undocumented function
     *
     * @return void
     */
    public function articlesByRooms($roomId, $params)
    {
        $this->db->select('ps.image, ps.product_id, ps.articlecode, ps.title, ps.uld,
        ps.type, ps.driver, ps.length, ps.width, ps.height, p.title as product_name')
            ->from('product_specifications as ps')
            ->join('products as p', 'p.product_id=ps.product_id')
            ->join('room_products as rp', 'rp.product_id=p.product_id');

        $this->db->where('rp.room_id', $roomId);
        $this->db->where('CHAR_LENGTH(uld) >', "0");

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        $query = $this->db->get();

        $data = $query->result_array();

        return $data;
    }

    /**
     * fetch product articles
     *
     * @param array $params
     * @return array
     */
    public function fetchArticles($params, $additionalFields = '')
    {
        if (isset($params['left_join']) && is_array($params['left_join']) && !empty($params['left_join'])) {
            foreach ($params['left_join'] as $joinTable => $joinCondition) {
                $this->db->join($joinTable, $joinCondition, 'left');
            }
        }

        $this->db->select('SQL_CALC_FOUND_ROWS image, product_specifications.product_id, articlecode, title, uld,
        product_specifications.type, driver, length, width, height' . $additionalFields, false)
            ->from($this->tableName)
            ->order_by('product_specifications.title', 'ASC');

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

        if (isset($params['join']) && is_array($params['join']) && !empty($params['join'])) {
            foreach ($params['join'] as $joinTable => $joinCondition) {
                $this->db->join($joinTable, $joinCondition);
            }
        }
        

        $query = $this->db->get();

        //echo $this->db->last_query();die;

        $data['data'] = $query->result_array();
        $data['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        return $data;
    }

    public function fetchArticlesData($params, $additionalFields = '')
    {
        if (isset($params['left_join']) && is_array($params['left_join']) && !empty($params['left_join'])) {
            foreach ($params['left_join'] as $joinTable => $joinCondition) {
                $this->db->join($joinTable, $joinCondition, 'left');
            }
        }

        $this->db->select('SQL_CALC_FOUND_ROWS product_specifications.image, product_specifications.product_id,project_room_id,
        articlecode, product_specifications.title, uld,
        product_specifications.type, driver, `length`, width, height' . $additionalFields, false)
            ->from($this->tableName)
            ->order_by('product_specifications.title', 'ASC');

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

        if (isset($params['join']) && is_array($params['join']) && !empty($params['join'])) {
            foreach ($params['join'] as $joinTable => $joinCondition) {
                $this->db->join($joinTable, $joinCondition);
            }
        }

        $query = $this->db->get();

        // echo $this->db->last_query();die;

        $data['data'] = $query->result_array();
        $data['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        return $data;
    }

    
}
