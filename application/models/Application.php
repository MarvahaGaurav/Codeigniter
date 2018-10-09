<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class Application extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'applications';
    }

    /**
     * Get application listing
     *
     * @param array $params
     * @return array
     */
    public function get($params)
    {
        if (isset($params['all_data']) && (bool)$params['all_data']) {
            $this->db->select("*");
        } else {
            $this->db->select("application_id, type, title, subtitle, image");
        }
        $this->db->from("applications as app")
        ->where("app.language_code", $params['language_code']);
        if (isset($params['type']) && !empty($params['type'])) {
            $this->db->where("app.type", $params["type"]);
        }

        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }

    /**
     * Application Details
     *
     * @param array $params
     * @return array
     */
    public function details($params)
    {
        $this->db->select("*")
            ->from($this->tableName)
            ->where('application_id', $params['application_id']);
        
        $query = $this->db->get();

        $data = $query->row_array();

        return $data;
    }
}
