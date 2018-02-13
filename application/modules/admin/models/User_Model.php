<?php

class User_Model extends CI_Model {

    public $finalrole = array();

    public function __construct() {
        $this->load->database();
        $this->load->library('session');
        $this->load->library('pagination');
    }

    //--------------------------------------------------------------------------
    /**
     * @name userlist
     * @description Used to filter the users
     * @param type $where
     * @param type $offset
     * @param type $limit
     * @param type $params
     * @return type
     */
    public function userlist($params) {

        $sortMap = [
            "name" => "name",
            "registered" => "u.user_id"
        ];

        $this->db->select("SQL_CALC_FOUND_ROWS u.*, cl.name, ccl.name as cityname", False);
        $this->db->from('ai_user as u');
        $this->db->join('country_list as cl', 'cl.country_code1=u.country_id', 'left');
        $this->db->join('city_list as ccl', 'ccl.country_code=u.country_id AND ccl.id = u.city_id', 'left');       

        if (!empty($params['searchlike'])) {
            $this->db->group_start();
            $this->db->like('concat_ws(" ",first_name,middle_name,last_name)', $params['searchlike']);
            $this->db->or_like('email', $params['searchlike']);
            $this->db->group_end();
        }
        if ((isset($params["sortfield"]) && !empty($params["sortfield"]) && in_array($params["sortfield"], array_keys($sortMap)) ) &&
                (isset($params["sortby"]) && !empty($params["sortby"]))) {
            if ($params["sortfield"] == "name") {
                $this->db->order_by("u.first_name", $params["sortby"]);
                $this->db->order_by("u.middle_name", $params["sortby"]);
                $this->db->order_by("u.last_name", $params["sortby"]);
            } else {
                $this->db->order_by($sortMap[$params["sortfield"]], $params["sortby"]);
            }
        } else {
            $this->db->order_by("u.user_id", "DESC");
        }

        if (!empty($params['status'])) {
            $this->db->where('status', $params['status']);
        } else {
            $this->db->where('status != 3');
        }

        if (!empty($params['country'])) {
            $this->db->where('country_id', $params['country']);
        }
        if (!empty($params['user_type'])) {
            $this->db->where_in('user_type', explode(',',$params['user_type']));
        }
        if (!empty($params['startDate']) && !empty($params['endDate'])) {
            $startDate = date('Y-m-d', strtotime($params['startDate']));
            $endDate = date('Y-m-d', strtotime($params['endDate']));
            $this->db->where("DATE(registered_date) >= '" . $startDate . "' AND DATE(registered_date) <= '" . $endDate . "' ");
        }
        
        $this->db->limit($params['limit'], $params['offset']);

        $query = $this->db->get();
//        echo $this->db->last_query();die;
        $res['result'] = $query->result_array();
        $res['total'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;

        return $res;
    }

    /* common function for paggination */

    function paginaton_link_custom($total_rows, $pageurl, $limit = 2, $per_page = 1) {
        $ci = & get_instance();
        $current_page_total = $limit * $per_page;
        $current_page_start = ($current_page_total - $limit) + 1;
        if ($current_page_total > $total_rows) {
            $current_page_start = ($current_page_total - $limit) + 1;
            $current_page_total = $total_rows;
        }
        $config['total_rows'] = $total_rows;
        $config['base_url'] = base_url() . $pageurl;
        $config['per_page'] = $limit;
        $config['full_tag_open'] = "<div class='row pagination_display'> <div class='col-lg-6 col-sm-6 col-xs-6'><div id='data-count'><span class='count-text'>Showing $current_page_start to $current_page_total of $total_rows entries  </span></div></div><div class='col-lg-6 col-sm-6 col-xs-6'> <div class='paination-wraper pull-right'> <ul id='custom_pagination'>";
        $config['full_tag_close'] = "</ul> </div> </div> </div>";
        $config['page_query_string'] = TRUE;
        $config['num_links'] = 20;
        $config['uri_segment'] = 2;
        $config['use_page_numbers'] = TRUE;
        $config['cur_tag_open'] = '<li><a href="javascript:void(0);" class="active" >';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page_next_tag">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page_last_tag">';
        $config['last_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="page_first_tag">';
        $config['first_tag_close'] = '</li>';
        $config['num_link'] = '<a href="javascript:void(0);" class=""></a>';
        $config['num_tag_open'] = '<li class="pag_num_tag">';
        $config['num_tag_close'] = '</a></li>';

        $ci->pagination->initialize($config);
        $pagination = $ci->pagination->create_links();
        return $pagination;
    }

    
        //--------------------------------------------------------------------------
    /**
     * @name userdetail
     * @description getting all user information by user id
     * @param type $id
     * @return array
     */
    public function userdetail($param) {        

        $this->db->select("u.*, cl.name, ccl.name as cityname, cm.*", False);
        $this->db->from('ai_user as u');
        $this->db->join('country_list as cl', 'cl.country_code1=u.country_id', 'left');       
        $this->db->join('city_list as ccl', 'ccl.country_code=u.country_id AND ccl.id = u.city_id', 'left');       
        $this->db->join('company_master as cm', 'cm.company_id=u.company_id', 'left');       
        $this->db->where('user_id', $param['user_id']);
        
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $res = $query->result_array();        
        return $res;
    }

    
}
