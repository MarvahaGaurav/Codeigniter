<?php

class Company_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * get review list
     * @param array
     * @return array
     */
    public function getCompanyList($params) {

        $this->db->select('SQL_CALC_FOUND_ROWS c.company_id,company_name,company_reg_number,company_image,company_image_thumb,IF(f.id is null,0,1) as is_favorited,IF(cl.name IS NULL,"",cl.name) as country_name,if(sl.name IS NULL,"",sl.name) as name,IF(cyl.name IS NULL,"",cyl.name) as city_name', false);
        $this->db->from('company_master as c');
        $this->db->join('ai_favorite as f', 'c.company_id = f.company_id AND f.user_id=' . $params['user_id'] . '', 'left');
        $this->db->join('country_list as cl', 'cl.country_code1=c.country', 'LEFT');
        $this->db->join('state_list as sl', 'sl.id = c.state', 'LEFT');
        $this->db->join('city_list as cyl', 'cyl.id=c.city', 'LEFT');
        $this->db->where(['owner_type !=' => '1', 'status' => '1']);
        if (!empty($params['limit']) && !empty($params['offset'])) {
            $this->db->limit($params['limit'], $params['offset']);
        }
        $query = $this->db->get();
//        echo $this->db->last_query();die;
        $resArr = [];
        $resArr['result'] = $query->result_array();
        $resArr['count'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        return $resArr;
    }

}
