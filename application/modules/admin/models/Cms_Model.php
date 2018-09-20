<?php

class Cms_Model extends CI_Model
{

    public $finalrole = array();

    public function __construct() 
    {
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
    public function pagelist($limit, $offset, $params) 
    {


        $this->db->select("SQL_CALC_FOUND_ROWS u.*", false);
        $this->db->from('page_master as u');
        $this->db->order_by("u.created_date", "DESC");
        $this->db->limit($limit, $offset);
        
        if (!empty($params['searchlike'])) {
            $this->db->group_start();
            $this->db->like('name', $params['searchlike']);
            $this->db->group_end();
        }

        $query = $this->db->get();
        $res['result'] = $query->result_array();
        $res['total'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        return $res;
    }

    /* common function for paggination */

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
        $config['full_tag_open'] = "<div class='row pagination_display'> <div class='col-lg-6 col-sm-6 col-xs-6'><div id='data-count'><span class='count-text'>Showing $current_page_start to $current_page_total of $total_rows entries  </span></div></div><div class='col-lg-6 col-sm-6 col-xs-6'> <div class='paination-wraper pull-right'> <ul id='custom_pagination'>";
        $config['full_tag_close'] = "</ul> </div> </div> </div>";
        $config['page_query_string'] = true;
        $config['num_links'] = 20;
        $config['uri_segment'] = 2;
        $config['use_page_numbers'] = true;
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

}
