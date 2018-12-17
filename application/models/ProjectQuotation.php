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
            company_name, u.first_name as user_name, additional_product_charges, discount,
            totalQuotationChargesPerRoom(pr.project_id, c.company_id) as quotation_price,
            pq.created_at, pq.created_at_timestamp, pq.status,pr.project_id", false)
            ->from("project_quotations as pq")
            ->join('project_requests as pr', 'pr.id=pq.request_id')
            ->join("ai_user as u", "u.user_id=pq.user_id")
            ->join("company_master as c", 'c.company_id=pq.company_id')
            ->where('pr.project_id',$params['project_id']);

            if($params['search']!='') {
                $search = $params['search'];
                $this->db->where("(company_name LIKE '%{$search}%')");
            }
            $this->db->order_by("pq.id", "DESC");
            
        if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
            $this->db->limit((int)$params['limit']);
        }
        
        if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
            $this->db->offset((int)$params['offset']);
        }
        
        

        $query = $this->db->get();

        
        //echo $this->db->last_query();die;

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
        $this->db->select('sum(price_per_luminaries) as price_per_luminaries, 
            sum(installation_charges) as installation_charges, 
            avg(discount_price) as discount_price')
            ->from('project_room_quotations as prq')
            ->join('project_rooms as pr', 'pr.id=prq.project_room_id')
            ->where('pr.project_id', $params['project_id'])
            ->where('prq.company_id', $params['company_id']);
        
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

        $data = $query->row_array();

        return $data;
    }

    /**
     * Get prices by quotation
     *
     * @param array $params
     * @return array
     */
    public function quotationChargesByInstaller($params)
    {
        $this->db->select('sum(price_per_luminaries) as price_per_luminaries,
        sum(installation_charges) as installation_charges, avg(discount_price) as discount_price,
        IFNULL(additional_product_charges, 0.00) as additional_product_charges,
        IFNULL(discount, 0.00) as discount')
            ->from("project_room_quotations as prq")
            ->join("project_rooms as pr", 'pr.id=prq.project_room_id')
            ->join('project_requests as preq', 'preq.project_id=pr.project_id')
            ->join("project_quotations as pq", "pq.request_id=preq.id", "left")
            // ->from('project_quotations as pq')
            ->where('prq.company_id', $params['company_id'])
            ->where('preq.project_id', $params['project_id'])
            ->group_by('pr.project_id');

        if (isset($params['level'])) {
            $this->db->where('pr.level', $params['level']);
        }

        $query = $this->db->get();

        $data = $query->row_array();

        return $data;
    }

    /**
     * Approved project quotation price
     *
     * @param array $params
     * @return array
     */
    public function approvedProjectQuotationPrice($params)
    {
        $this->db->select('sum(price_per_luminaries) as price_per_luminaries,
            sum(installation_charges) as installation_charges,
            SUM(((installation_charges + price_per_luminaries) * ((100 - discount_price)/ 100))) as discounted_price, additional_product_charges, discount')
            ->from('project_quotations as pq')
            ->join('project_room_quotations as prq', 'prq.user_id=pq.user_id AND prq.company_id=pq.company_id')
            ->join('project_rooms as pr', 'pr.id=prq.project_room_id')
            ->join('project_requests as preq', 'preq.id=pq.request_id')
            ->where('preq.project_id', $params['project_id'])
            ->where('pq.status', QUOTATION_STATUS_APPROVED)
            ->group_by('preq.project_id');

        if (isset($params['level'])) {
            $this->db->where('pr.level', $params['level']);
        }

        $query = $this->db->get();

        $data = $query->row_array();

        return $data;
    }

    /**
     * Approved quotation price
     *
     * @param array $params
     * @return array
     */
    public function approvedQuotationPrice($params)
    {
        $this->db->select('additional_product_charges, discount')
            ->from('project_quotations as pq')
            ->join('project_requests as preq', 'preq.id')
            ->where();
        
        $query = $this->db->get();

        $data = $query->row_array();

        return $data;
    }

    public function approvedOwner($quotationId)
    {
        $this->db->select('u.user_id')
            ->from('project_quotations as pq')
            ->join('ai_user as u', 'u.company_id=pq.company_id AND is_owner=' . ROLE_OWNER)
            ->where('pq.id', $quotationId);

        $query = $this->db->get();

        $data = $query->row_array();

        return $data;
    }

    public function activeQuotation()
    {
        $this->db->select();
    }
}
