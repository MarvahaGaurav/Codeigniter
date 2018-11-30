<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class Company extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'company_master';
    }

    /**
     * fetch company list
     *
     * @param array $params
     * @return array
     */
    public function company($params)
    {
        $this->db->select(['SQL_CALC_FOUND_ROWS company_name',
         'company_image', 'country.name as country', 'city.name as city', 'company_id'], false)
            ->from($this->tableName)
            ->join('country_list as country', 'country.country_code1=company_master.country')
            ->join('city_list as city', 'city.id=company_master.city')
            ->order_by('company_id', 'DESC')
            ->limit($params['limit']);
        
        if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
            $this->db->offset($params['offset']);
        }

        if (isset($params['where']) && is_array($params['where'])) {
            foreach ($params['where'] as $tableColumn => $value) {
                $this->db->where($tableColumn, $value);
            }
        }

        $query = $this->db->get();

        $result['data'] = $query->result();
        $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        return $result;
    }

    /**
     * fetches company list
     *
     * @param array $params
     * @return array
     */
    public function companyWithFavorite($params)
    {
        $this->db->select(['SQL_CALC_FOUND_ROWS company_name', 'IFNULL(is_favorite, 0) as is_favorite',
        'company_image', 'country.name as country', 'city.name as city', 'company_master.company_id'], false)
           ->from($this->tableName)
           ->join('country_list as country', 'country.country_code1=company_master.country')
           ->join(
               'ai_favorite as fav',
               'fav.company_id=company_master.company_id AND fav.user_id='. $params['user_id'],
               'left'
           )
           ->join('city_list as city', 'city.id=company_master.city')
           ->order_by('company_id', 'DESC')
           ->limit($params['limit']);
       
        if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
            $this->db->offset($params['offset']);
        }

        if (isset($params['where']) && is_array($params['where'])) {
            foreach ($params['where'] as $tableColumn => $value) {
                $this->db->where($tableColumn, $value);
            }
        }

        $query = $this->db->get();

        $result['data'] = $query->result();
        $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        return $result;
    }

    public function favoriteCompany($params)
    {
        $this->db->select(['SQL_CALC_FOUND_ROWS company_name', 'IFNULL(is_favorite, 0) as is_favorite',
        'company_image', 'country.name as country', 'city.name as city', 'company_master.company_id'], false)
           ->from($this->tableName)
           ->join('country_list as country', 'country.country_code1=company_master.country')
           ->join(
               'ai_favorite as fav',
               'fav.is_favorite=1 AND fav.company_id=company_master.company_id AND fav.user_id='. $params['user_id']
           )
           ->join('city_list as city', 'city.id=company_master.city')
           ->order_by('company_id', 'DESC')
           ->limit($params['limit']);
       
        if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
            $this->db->offset($params['offset']);
        }

        if (isset($params['where']) && is_array($params['where'])) {
            foreach ($params['where'] as $tableColumn => $value) {
                $this->db->where($tableColumn, $value);
            }
        }

        $query = $this->db->get();

        $result['data'] = $query->result();
        $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        return $result;
    }

    public function companyDetails($companyId, $params = [])
    {
        $query = "company_master.company_id, company_name, company_image,
            company_master.company_id, country.name as country, city.name as city, user.email,
            user.prm_user_countrycode, user.alt_user_countrycode, user.phone, user.alt_userphone";

        if (isset($params['user_id'])) {
            $query .= ', IFNULL(is_favorite, 0) as is_favorite';
            $this->db->join(
                'ai_favorite as fav',
                'fav.company_id=company_master.company_id AND fav.user_id='. $params['user_id'],
                'left'
            );
        }

        $this->db->select($query)
            ->from($this->tableName)
            ->join('ai_user as user', 'user.is_owner=2 AND user.company_id=company_master.company_id')
            ->join('country_list as country', 'country.country_code1=company_master.country')
            ->join('city_list as city', 'city.id=company_master.city')
            ->where('company_master.company_id', $companyId);

        if (isset($params['where']) && is_array($params['where'])) {
            foreach ($params['where'] as $tableColumn => $value) {
                $this->db->where($tableColumn, $value);
            }
        }
        
        $query = $this->db->get();

        $data = $query->row();

        return $data;
    }
}
