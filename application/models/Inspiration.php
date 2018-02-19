<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class Inspiration extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'inspirations';
    }

    public function get($options)
    {
        $media = "";
        $products = "";
        $poster_details = "";
        $user_additional_join = "";
        if (isset($options['poster_details']) && !empty((bool)$options['poster_details'])) {
            $poster_details = ",user.first_name as full_name, user.user_id";
            $user_additional_join = " AND user.user_id=i.user_id";
        }

        if (isset($options['media']) && !empty($options['media'])) {
            $media = ", IFNULL(getInspirationMedia(i.id), '') as media";
        }

        if (isset($options['products']) && !empty($options['products'])) {
            // using PHP heredocs syntax to use both ' and " quotes in order to build JSON DATA
            $products =', IFNULL(getInspirationProducts(i.id), "") as products';
        }

        $this->db->from($this->tableName . " as i")
            ->where('i.status', 1)
            ->where('i.is_deleted', 0)
            ->join("company_master as company", "company.company_id=i.company_id")
            ->join('ai_user as user', 'user.company_id=company.company_id AND user.is_owner = 2' . $user_additional_join)
            ->join('city_list as cl', 'cl.id=user.city_id', 'left')
            ->join('country_list as country', 'country.country_code1=user.country_id', 'left')
            ->order_by("i.id", "desc")
            ->group_by("i.id");

        if (isset($options['inspiration_id']) && !empty($options['inspiration_id'])) {
            $this->db->select("i.id as inspiration_id, company.company_name, cl.id as city_id, cl.name as city_name," .
                "country.country_code1 as country_code, country.name as country_name," .
                "i.company_id, i.title, i.description" . $media . $poster_details . $products);
        } else {
            $this->db->select("SQL_CALC_FOUND_ROWS i.id as inspiration_id, cl.id as city_id, cl.name as city_name," .
                "country.country_code1 as country_code, country.name as country_name," .
                "company.company_name, i.company_id, i.title, i.description, i.created_at, i.updated_at" . $media . $poster_details . $products, false);
        }

        if (isset($options['company_id']) && !empty($options['company_id'])) {
            $this->db->where('i.company_id', $options['company_id']);
        }

        if (isset($options['limit']) && !empty((int)$options['limit'])) {
            $this->db->limit((int)$options['limit']);
        } else {
            $this->db->limit(RECORDS_PER_PAGE);
        }

        if (isset($options['user_id']) && !empty($options['user_id'])) {
            $this->db->where('i.user_id', $options['user_id']);
        }

        if (isset($options['offset']) && !empty($options['offset'])) {
            $this->db->offset($options['offset']);
        }

        if (isset($options['inspiration_id']) && !empty($options['inspiration_id'])) {
            $this->db->where("i.id", $options['inspiration_id']);
        }

        $query = $this->db->get();
        // pd($this->db->last_query());
        if (!$query) {
            throw new SelectException($this->db->last_query());
        }

        if (isset($options['inspiration_id']) && !empty($options['inspiration_id'])) {
            $result = $query->row_array();
        } else {
            $result['result'] = $query->result_array();
            $result['count'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        }
        // pd($result);
        return $result;
    }
    
}