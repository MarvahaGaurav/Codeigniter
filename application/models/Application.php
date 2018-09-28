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

    public function get($params)
    {
        $this->db->select("application_id, type, title, subtitle, image")
        ->from("applications as app")
        ->where("app.language_code", $params['language_code']);
        if (isset($params['type']) && !empty($params['type'])) {
            $this->db->where("app.type", $params["type"]);
        }

        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }
}
