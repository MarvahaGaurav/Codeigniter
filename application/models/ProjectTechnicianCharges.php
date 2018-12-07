<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class ProjectTechnicianCharges extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'project_technician_charges';
    }

    public function totalCharges($params)
    {
        $this->db->select("IFNULL(additional_product_charges, 0.00) as additional_product_charges,
            IFNULL(discount, 0.00) as discount, SUM(price_per_luminaries) as price_per_luminaries,
            SUM(installation_charges) as installation_charges,
            SUM(((installation_charges + price_per_luminaries) * ((100 - discount_price)/ 100))) as discounted_price")
            ->from("project_room_quotations as prq")
            ->join("project_rooms as pr", "pr.id=prq.project_room_id")
            ->join("project_technician_charges as ptc", "ptc.project_id=pr.project_id", "left")
            ->where('pr.project_id', $params['project_id'])
            ->group_by('pr.project_id');

        if (isset($params['level'])) {
            $this->db->where('pr.level', $params['level']);
        }   

        $query = $this->db->get();
        // echo $this->db->last_query();die;

        $result = $query->row_array();

        return $result;
    }
}
