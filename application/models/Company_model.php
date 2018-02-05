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
        $queryString = "";
        $userId = isset($params['user_id']) && !empty($params['user_id'])?$params['user_id']:false;
        if ( $userId ) {
            $query = 'SQL_CALC_FOUND_ROWS c.company_id,company_name,company_reg_number,user.zipcode as company_zipcode,' .
                'company_image,company_image_thumb,IF(f.is_favorite is null,0,f.is_favorite) as is_favorited,' .
                'IF(cl.name IS NULL,"",cl.name) as country_name,user.phone as company_phone,user.alt_userphone as alt_company_phone,' .
                'IF(cyl.name IS NULL,"",cyl.name) as city_name, user.email as company_email, user.first_name as owner_name,'.
                'user.country_id, user.city_id, user.image as user_image ';
        } else {
            $query = 'SQL_CALC_FOUND_ROWS c.company_id,company_name,company_reg_number,user.zipcode as company_zipcode,' .
                'company_image,company_image_thumb, user.phone as company_phone,' .
                'IF(cl.name IS NULL,"",cl.name) as country_name,user.email as company_email,user.alt_userphone as alt_company_phone,' .
                'IF(cyl.name IS NULL,"",cyl.name) as city_name, user.first_name as owner_name,'.
                'user.country_id, user.city_id, user.image as user_image ';
        }
        $this->db->select($query, false);
        $this->db->from('company_master as c');
        if ( $userId ) {
            $this->db->join('ai_favorite as f', 'c.company_id = f.company_id AND f.user_id=' . $params['user_id'] . '', 'left');
        }
        $this->db->join('country_list as cl', 'cl.country_code1=c.country', 'LEFT');
        $this->db->join('city_list as cyl', 'cyl.id=c.city', 'LEFT');
        $this->db->join('ai_user as user', 'user.company_id=c.company_id AND is_owner=2', 'left');
        
        $this->db->where(['owner_type !=' => '1', 'c.status' => '1']);
        if (!empty($params['limit']) && !empty($params['offset'])) {
            $this->db->limit($params['limit'], $params['offset']);
        } else if ( isset($params['paginate']) && (bool)$params['paginate'] ) {
            $this->db->limit($params['limit'], $params['offset']);
        }
        if ( isset($params['company_id']) && !empty((int)$params['company_id']) ) {
            $this->db->where('c.company_id', $params['company_id']);
        }
        $query = $this->db->get();
//        echo $this->db->last_query();die;
        $resArr = [];
        if ( isset($params['company_id']) && !empty((int)$params['company_id']) ) {
            $resArr['result'] = $query->row_array();
        } else {
            $resArr['result'] = $query->result_array();
            $resArr['count'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        }
        return $resArr;
    }

}
