<?php

class Admin_Model extends CI_Model
{

    public $finalrole = array();

    public function __construct() 
    {
        $this->load->database();
        $this->load->library('session');
        $this->load->library('pagination');
    }
    
    /*Fetch user list with paggination*/
    public function userlist($where,$offset, $limit,$params) 
    {
        $this->db->select("SQL_CALC_FOUND_ROWS u.*", false);
        $this->db->from('ai_user as u');
           $this->db->where($where);
        $this->db->order_by("u.registered_date", "DESC");
        if ((int) $limit >= 0 && (int) $offset >= 0) {
            $this->db->limit($limit, $offset);
        }
        if (!empty($params['searchlike'])) {
            $this->db->group_start();
            $this->db->like('first_name', $params['searchlike'], 'after');
            $this->db->or_like('email', $params['searchlike']);
            $this->db->group_end();
        }

  
        $query = $this->db->get();

        $res['result'] = $query->result_array();
        $res['total'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        return $res;
    }

    /*common function for paggination*/
    function paginaton_link_custom($total_rows, $pageurl, $limit = 2, $per_page = 1) 
    {
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
        $config['full_tag_open'] = "<div class='pull-left'><span class='count-text'>Showing $current_page_start to $current_page_total of $total_rows entries  </span></div><div class='pull-right'> <div class='pagination_inner'> <ul class='pager'>";
        $config['full_tag_close'] = "</ul> </div> </div>";
        $config['page_query_string'] = true;
        $config['num_links'] = 20;
        $config['uri_segment'] = 2;
        $config['use_page_numbers'] = true;
        $config['cur_tag_open'] = '<li class="pages active"><a href="javascript:void(0);"  style="background-color:#007775;" class="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li class="pages">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page_last_tag">';
        $config['last_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li class="disabled"><a href="javascript:void(0)"><i class="fa fa-step-backward" aria-hidden="true">';
        $config['first_tag_close'] = '</i></a></li>';
        $config['num_link'] = '<a href="javascript:void(0);" class=""></a>';
        $config['num_tag_open'] = '<li class="pag_num_tag">';
        $config['num_tag_close'] = '</a></li>';

        $ci->pagination->initialize($config);
        $pagination = $ci->pagination->create_links();
        return $pagination;
    }
    
}
 