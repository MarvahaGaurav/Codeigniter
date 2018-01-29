<?php

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

        $this->db->select('SQL_CALC_FOUND_ROWS c.company_id,company_name as name',false);
        $this->db->from('ai_favorite as f');
        $this->db->join('company_master as c', '(c.company_id = f.company_id AND f.user_id='.$params['user_id'].')', 'left');
        $this->db->where('f.user_id =  '.$params['user_id'].'');
        $this->db->limit($params['limit'], $params['offset']);
        $query = $this->db->get();
//        echo $this->db->last_query();die;
        $resArr = [];
        $resArr['result'] = $query->result_array();
        $resArr['count'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        return $resArr;
    }

}
