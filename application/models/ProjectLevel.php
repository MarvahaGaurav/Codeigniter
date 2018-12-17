<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

class ProjectLevel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'project_levels';
    }

    public function get($params)
    {
        $this->db->select("SQL_CALC_FOUND_ROWS level, status", false)
            ->from($this->tableName)
            ->where('project_id', $params['project_id']);

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
        // $data['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

        return $data;
    }

    /**
     * Add Project Levels data for a particular
     *
     * @throws \Exception
     * @param array $levelsData
     * @param int $projectId
     * @return bool
     */
    public function addProjectLevels($levelsData, $projectId)
    {
        $levelsInsertData = [];

        foreach ($levelsData as $level) {
            $levelsInsertData[] = [
                'level' => $level['level'],
                'project_id' => $projectId,
                'status' => $level['status']
            ];
        }

        $status = $this->db->insert_batch($this->tableName, $levelsInsertData);

        if (!$status) {
            throw new \Exception('Insert Error');
        }

        return $status;
    }

    /**
     * Fetches complete project level data
     *
     * @param array $params
     * @return array
     */
    public function projectLevelData($params, $field = "*")
    {
        $this->db->select($field)
            ->from($this->tableName);

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        if (isset($params['where_in']) && is_array($params['where_in']) && !empty($params['where_in'])) {
            foreach ($params['where_in'] as $tableColumn => $searchValue) {
                $this->db->where_in($tableColumn, $searchValue);
            }
        }

        if (isset($params['group_by']) && is_array($params['group_by']) && !empty($params['group_by'])) {
            foreach ($params['group_by'] as $field) {
                $this->db->group_by($field);
            }
        }

        $query = $this->db->get();
        
        if (isset($params['single_row']) && (bool)$params['single_row']) {
            $data = $query->row_array();
        } else {
            $data = $query->result_array();
        }

        return $data;
    }

    /**
     * Used to check if rooms for all levels have been added
     * 
     * @param $projectId project ID for which the check need to be done
     *
     * @return array
     */
    public function levelRoomCheck($projectId)
    {
        $this->db->select("pl.level, pl.id, pr.id as project_room_id")
         ->from("project_levels as pl")
         ->join("project_rooms as pr", 'pr.project_id=pl.project_id', 'left')
         ->where('pl.project_id', $projectId)
         ->group_by('pl.level');

        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }

    /**
     * Used to check if all room prices have been added
     * 
     * @param $projectId project ID for which the check need to be done
     *
     * @return array
     */
    public function isAllRoomPriceAdded($projectId,$level)
    {
        $this->db->select("pr.id,prq.installation_charges")
         ->from('project_rooms as pr')
         ->join("project_room_quotations as prq","pr.id=prq.project_room_id",'left')
         ->where('pr.project_id', $projectId)
         ->where('pr.level', $level);

         $query = $this->db->get();

         $result = $query->result_array();

        foreach($result as $key=>$val) {
            if($val['installation_charges']=='') {
                return false;
            }
            
        }
        return true;

        
    }
    
}
