<?php
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class Inspiration extends BaseModel {

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'inspirations';
    }

    public function get($options) 
    {
        $media = "";
        if ( isset($options['media']) && !empty($options['media']) ) {
            $media = ",IFNULL(GROUP_CONCAT(media.media), '') as media,".
                "IFNULL(GROUP_CONCAT(media.id), '') as media_id,".
                "IFNULL(GROUP_CONCAT(media.media_type), '') as media_type";
            $this->db->join("inspiration_media as media", "media.inspiration_id=i.id", "left");
            $this->db->group_by("i.id");
        }

        if ( isset($options['inspiration_id']) && !empty($options['inspiration_id']) ) {
            $this->db->select("i.id as inspiration_id, company.company_name, user.address, company.company_address, i.company_id, title, description, i.status" . $media);
        } else {
            $this->db->select("SQL_CALC_FOUND_ROWS i.id as inspiration_id, user.address, company.company_name, company.company_address, i.company_id, title, description, i.status" . $media , FALSE);
        }
        
        $this->db->from($this->tableName . " as i")
        ->where('i.status', 1)
        ->where('i.is_deleted', 0)
        ->join("company_master as company", "company.company_id=i.company_id")
        ->join('ai_user as user', 'user.company_id=company.company_id AND user.is_owner = 2')
        ->join('city_list as cl', 'cl.')
        ->join('')
        ->limit(RECORDS_PER_PAGE)
        ->order_by("i.id", "desc");
        if ( isset($options['company_id']) && !empty($options['company_id']) ) {
            $this->db->where('i.company_id', $options['company_id']);
        }
        
        if ( isset($options['offset']) && !empty($options['offset']) ) {
            $this->db->offset($options['offset']);
        }

        if ( isset($options['inspiration_id']) && !empty($options['inspiration_id']) ) {
            $this->db->where("i.id", $options['inspiration_id']);
        }

        $query = $this->db->get();

        if ( ! $query  ) {
            throw new SelectException($this->db->last_query());
        }

        if ( isset($options['inspiration_id']) && !empty($options['inspiration_id']) ) {
            $result = $query->row_array();
        } else {
            $result['result'] = $query->result_array();
            $result['count'] = $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
        }
        return $result;
    }

}