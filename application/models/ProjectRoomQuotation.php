<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class ProjectRoomQuotation extends BaseModel
{
    private $datetime;
    private $timestamp;

    public function __construct()
    {
        $this->load->database();
        $this->tableName = "project_room_quotations as prq";
        $this->datetime = date("Y-m-d H:i:s");
        $this->timestamp = time();
    }

    public function getQuotationRoom()
    {
        $this->db->select('id, project_room_id')
            ->from($this->tableName)
            ->join('project_rooms as pr', 'pr.id=prq.project_room_id')
            ->join('project_request as preq', 'preq.project_id=pr.project_id AND pr.id=' . $params['request_id']);

        $query = $this->db->get();

        $data = $query->result_array();

        return $data;
    }

    /**
     * Get quoted rooms
     *
     * @param array $params
     * @return array
     */
    public function quotedRooms($params)
    {
        $this->db->select('id, project_room_id')
            ->from($this->tableName)
            ->join('project_rooms as pr', 'pr.id=prq.project_room_id')
            ->where('project_id', $params['project_id']);
        
        $query = $this->db->get();

        $data = $query->result_array();

        return $data;
    }

    /**
     * Get installer quotation info
     *
     * @param array $params
     * @return array
     */
    public function quotationInfo($params)
    {
        $this->db->select('id as room_quotation_id, project_room_id, user_id, company_id,price_per_luminaries,
            installation_charges,discount_price,created_at, created_at_timestamp')
                ->from($this->tableName);

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

        $data = $query->result_array();

        return $data;
    }

    /**
     * Return quotaitons price based on company and projects ids
     *
     * @param int $companyId
     * @param string $projectIds
     * @return array
     */
    public function quotationPrice($companyId, $projectIds)
    {
        $this->db->select("project_id, price_per_luminaries,installation_charges, discount_price")
            ->from("project_room_quotations as prq")
            ->join("project_rooms as pr", 'pr.id=prq.project_room_id')
            ->where('prq.company_id', $companyId)
            ->where_in('pr.project_id', $projectIds);

        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }

    public function cloneQuotation($projectRooms, $filterQuotation, $roomQuotations, $userData)
    {
        $quotationsData = [];
        foreach ($projectRooms as $key => $room) {
            if ((bool)$filterQuotation[$key]) {
                $quotationPriceData = array_shift($roomQuotations);
                $quotationsData[] = [
                    'project_room_id' => $room['project_room_id'],
                    'user_id' => $userData['user_id'],
                    'company_id' => $userData['company_id'],
                    'price_per_luminaries' => $quotationPriceData['price_per_luminaries'],
                    'installation_charges' => $quotationPriceData['installation_charges'],
                    'discount_price' => $quotationPriceData['discount_price'],
                    'created_at' => $this->datetime,
                    'created_at_timestamp' => $this->datetime
                ];
            }
        }
        
        if (!empty($quotationsData)) {
            $this->db->insert_batch('project_room_quotations', $quotationsData);
        }
    }
}
