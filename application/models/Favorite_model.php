<?php
defined("BASEPATH") OR exit("No direct script access allowed");

use DatabaseExceptions\InsertException;

class Favorite_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        /**
         * load database
         */
        $this->load->database();
    }

    /**
     * get review list
     * @param array
     * @return array
     */
    public function getFavorites($params) {
        $this->db->select('SQL_CALC_FOUND_ROWS c.company_id,company_name,company_reg_number,'.
        'company_image,company_image_thumb,f.is_favorite,'.
        'IF(cl.name IS NULL,"",cl.name) as country_name,'.
        'IF(cyl.name IS NULL,"",cyl.name) as city_name',false);
        $this->db->from('ai_favorite as f');
        $this->db->join('company_master as c', '(c.company_id = f.company_id AND f.user_id='.$params['user_id'].')');
        $this->db->where('f.user_id =  '.$params['user_id'].'');
        $this->db->join('country_list as cl', 'cl.country_code1=c.country', 'LEFT');
        $this->db->join('city_list as cyl', 'cyl.id=c.city', 'LEFT');
        $this->db->where(['owner_type !=' => '1', 'status' => '1']);
        $this->db->where('f.is_favorite', 1);
        $this->db->limit($params['limit']);
        if ( isset($params['offset']) && !empty((int)$params['offset']) ) {
            $this->db->offset($params['offset']);
        }
        
        $this->db->order_by("f.id");
        $query = $this->db->get();
//        echo $this->db->last_query();die;
        $resArr = [];
        $resArr['result'] = $query->result_array();
        $resArr['count'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        return $resArr;
    }

    public function save() 
    {
        $status = $this->db->insert("ai_favorite", $this);
        if ( ! $status ) {
            throw new InsertException("Insert Error");
        }

    }

    public function update($where) 
    {
        $status = $this->db->set($this);
        foreach ( $where as $key => $value ) {
            $this->db->where($key, $value);
        }
        $this->db->update("ai_favorite");
        
    }

}
