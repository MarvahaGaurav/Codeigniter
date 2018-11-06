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

    /**
     * fetch product articles
     *
     * @param array $params
     * @return array
     */
    public function fetchArticles($params)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS image, product_id, articlecode, title, uld,
                        type, driver, length, width, height', false)
            ->from($this->tableName);

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
