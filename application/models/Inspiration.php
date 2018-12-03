<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class Inspiration extends BaseModel {

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'inspirations';

    }

    public function inspirations($params)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS id as inspiration_id, title, description, inspirations.status,
        inspirations.address, inspirations.lat, inspirations.lng, company_name, first_name as user_name,
        inspirations.created_at, inspirations.updated_at,
        inspirations.created_at_timestamp, inspirations.updated_at_timestamp', false)
            ->from('inspirations')
            ->join('ai_user as users', 'users.user_id=inspirations.user_id')
            ->join("company_master as company", "company.company_id=inspirations.company_id")
            ->order_by('id', "DESC");
        
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

        if (!empty($data['data'])) {
            $this->load->helper('db');
            $this->load->model(['InspirationProduct', 'InspirationMedia']);
            $inspirationIds = array_column($data['data'], 'inspiration_id');
            $products = $this->InspirationProduct->get($inspirationIds);
            $media = $this->InspirationMedia->get($inspirationIds);

            $data['data'] = getDataWith($data['data'], $media, 'inspiration_id', 'inspiration_id', 'media');
            $data['data'] = getDataWith($data['data'], $products, 'inspiration_id', 'inspiration_id', 'products');
        }

        
        return $data;
    }



    public function get($options)
    {
        $media                = "";
        $products             = "";
        $poster_details       = "";
        $user_additional_join = "";
        if (isset($options['poster_details']) && ! empty((bool) $options['poster_details'])) {
            $poster_details       = ",user.first_name as full_name, user.user_id";
            $user_additional_join = " AND user.user_id=i.user_id";
        }

        if (isset($options['media']) && ! empty($options['media'])) {
            $media = ", IFNULL(getInspirationMedia(i.id), '') as media";
        }

        if (isset($options['products']) && ! empty($options['products'])) {
            // using PHP heredocs syntax to use both ' and " quotes in order to build JSON DATA
            $products = ', IFNULL(getInspirationProducts(i.id), "") as products';
        }

        $this->db->from($this->tableName . " as i")
            ->where('i.status', 1)
            ->where('i.is_deleted', 0)
            ->join("company_master as company", "company.company_id=i.company_id", 'left')
            ->join('ai_user as user', 'user.company_id=company.company_id' . $user_additional_join, "left")
            ->join('city_list as cl', 'cl.id=user.city_id', 'left')
            ->join('country_list as country', 'country.country_code1=user.country_id', 'left')
            ->order_by("i.id", "desc")
            ->group_by("i.id");

        if (isset($options['inspiration_id']) && ! empty($options['inspiration_id'])) {
            $this->db->select("i.id as inspiration_id, IFNULL(company.company_name, '') as company_name, cl.id as city_id, cl.name as city_name," .
                "country.country_code1 as country_code, country.name as country_name, i.address, i.lat, i.lng," .
                "IFNULL(i.company_id, '') as company_id, i.title, i.description" . $media . $poster_details . $products);
        }
        else {
            $this->db->select("SQL_CALC_FOUND_ROWS i.id as inspiration_id, cl.id as city_id, cl.name as city_name," .
                "country.country_code1 as country_code, country.name as country_name, i.address, i.user_id," .
                "IFNULL(company.company_name, '') as company_name, IFNULL(i.company_id, '') as company_id, i.title, i.description, i.created_at, i.updated_at" . $media . $poster_details . $products, false);
        }

        if (isset($options['company_id']) && ! empty($options['company_id'])) {
            $this->db->where('i.company_id', $options['company_id']);
        }

        if (isset($options['limit']) && ! empty((int) $options['limit'])) {
            $this->db->limit((int) $options['limit']);
        }
        else {
            $this->db->limit(RECORDS_PER_PAGE);
        }

        if (isset($options['search']) && ! empty($options['search'])) {
            $this->db->where('i.title LIKE', "%{$options['search']}%");
        }

        if (isset($options['user_id']) && ! empty($options['user_id'])) {
            $this->db->where('i.user_id', $options['user_id']);
        }

        if (isset($options['offset']) && ! empty($options['offset'])) {
            $this->db->offset($options['offset']);
        }

        if (isset($options['inspiration_id']) && ! empty($options['inspiration_id'])) {
            $this->db->where("i.id", $options['inspiration_id']);
        }

        $query = $this->db->get();
        
        if ( ! $query) {
            throw new SelectException($this->db->last_query());
        }

        if (isset($options['inspiration_id']) && ! empty($options['inspiration_id'])) {
            $result = $query->row_array();
        }
        else {
            $result['result'] = $query->result_array();
            $result['count']  = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        }
        // pd($result);
        return $result;

    }



    public function inspirationByCompany($params, $companyId)
    {
        $this->db->reset_query();
        $this->db->select('SQL_CALC_FOUND_ROWS cl.name city_name, country.name country_name, inspirations.id, inspirations.user_id, inspirations.company_id, inspirations.title, inspirations.description,
            inspirations.status, inspirations.created_at, inspirations.updated_at', false)
            ->from($this->tableName)
            ->where('inspirations.company_id', $companyId)
            ->join("ai_user user", "inspirations.user_id=user.user_id")
            ->join('city_list as cl', 'cl.id=user.city_id', 'left')
            ->join('country_list as country', 'country.country_code1=user.country_id', 'left')
            ->order_by('id', 'DESC')
            ->limit($params['limit']);

        if (isset($params['offset']) && is_numeric($params['offset']) && (int) $params['offset'] > 0) {
            $this->db->offset($params['offset']);
        }

        if (isset($params['where']) && is_array($params['where']) && ! empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        $query = $this->db->get();

        $result['data']  = $query->result_array();
        $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        $this->load->helper(['db', 'debuging']);

        //Return result with media and products
        if ( ! empty($result['data'])) {
            $this->load->model('InspirationMedia');
            $inspirationIds = array_column($result['data'], 'id');
            $images         = $this->InspirationMedia->get($inspirationIds);
            $result['data'] = getDataWith($result['data'], $images, 'id', 'inspiration_id', 'media');
            $this->load->model('Product');
            $products       = $this->Product->productsByInspiration($inspirationIds);
            $result['data'] = getDataWith($result['data'], $products, 'id', 'inspiration_id', 'products');
        }

        return $result;

    }



}
