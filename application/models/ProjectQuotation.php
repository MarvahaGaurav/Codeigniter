<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class ProjectQuotation extends BaseModel
{

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "project_quotations";
    }


    /**
     * get project listing
     *
     * @param array $params
     * @return array
     */
    public function get($params)
    {
        $this->db->select("*", false)
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
        
        $result['data'] = $query->result_array();
        $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        return $result;
    }

    /**
     * Lists all the requests which have been quoted
     *
     * @param array $params
     * @return void
     */
    public function quotations($params)
    {
        $this->db->select("SQL_CALC_FOUND_ROWS pq.id as quotation_id, request_id, c.company_id,
            company_name, u.first_name as user_name,
            pq.created_at, pq.created_at_timestamp", false)
            ->from("project_quotations as pq")
            ->join('project_requests as pr', 'pr.id=pq.request_id')
            ->join("ai_user as u", "u.user_id=pq.user_id")
            ->join("company_master as c", 'c.company_id=pq.company_id')
            ->order_by("pq.id", "DESC");
            
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

        $result['data'] = $query->result_array();
        $result['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;

        return $result;
    }

    /**
     * Get quotation price by installer
     *
     * @param array $params
     * @return array
     */
    public function getProjectQuotationPriceByInstaller($params)
    {
        $this->db->select('totalQuotationChargesPerRoom(pr.project_id, prq.company_id) as price,
            IFNULL(discount, 0.00) as discount, IFNULL(additional_product_charges, 0.00) as additional_product_charges')
            ->from('project_room_quotations as prq')
            ->join('project_quotations as pq', 'pq.company_id=prq.company_id', 'left')
            ->join('project_requests as prr', 'prr.id=pq.request_id', 'left')
            ->join('project_rooms as pr', 'pr.id=prq.project_room_id')
            ->where('pr.project_id', $params['project_id'])
            ->where('prq.company_id', $params['company_id']);

        $query = $this->db->get();

        $data = $query->row_array();

        return $data;
    }

    public function activeQuotation()
    {
        $this->db->select();
    }
}
