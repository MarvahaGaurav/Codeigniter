<?php
defined("BASEPATH") or exit("No direct script access allowed");

use DatabaseExceptions\UpdateException;
use DatabaseExceptions\SelectException;
use DatabaseExceptions\InsertException;
use DatabaseExceptions\DeleteException;

class UtilModel extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Runs Select query with neccessary options
     *
     *@param mixed $field accept array or string
     *@param string $tableName table name
     *@param array $options other options
     *
     *@return array Data in multidimensional array
     */
    public function selectQuery($fields, $tableName = "", $options = [])
    {
        if (is_array($fields)) {
            $this->db->select(implode(",", $fields));
        } else {
            $this->db->select($fields);
        }
        if (!empty($tableName)) {
            $this->db->from($tableName);
        }
        $this->optionHandler($options);

        $query = $this->db->get();
        if (! $query) {
            throw new \Exception("fetch error");
        }
        $resultSet = [];
        
        if (isset($options['single_row']) && (bool)$options['single_row'] === true) {
            $resultSet = $query->row_array();
        } else {
            $resultSet = $query->result_array();
        }
        if (count($resultSet) > 0) {
            return $resultSet;
        } else {
            return [];
        }
    }

    private function optionHandler($options)
    {
        $arrayFlag = true;
        if (! isset($options) || empty($options) || ! is_array($options)) {
            $arrayFlag = false;
        }

        if (!$arrayFlag) {
            return false;
        }

        if (isset($options["where"]) && !empty($options["where"])) {
            if (is_array($options["where"])) {
                foreach ($options["where"] as $key => $value) {
                    $this->db->where($key, $value);
                }
            } else {
                $this->db->where($options["where"]);
            }
        }

        if (isset($options["join"]) && !empty($options["join"])) {
            foreach ($options["join"] as $key => $value) {
                $this->db->join($key, $value);
            }
        }

        if (isset($options["left_join"]) && !empty($options["left_join"])) {
            foreach ($options["left_join"] as $key => $value) {
                $this->db->join($key, $value, 'LEFT');
            }
        }

        if (isset($options["sort"]) && !empty($options["sort"])) {
            if (is_array($options["sort"])) {
                foreach ($options["sort"] as $key => $value) {
                    $this->db->order_by($key, $value);
                }
            } else {
                $this->db->order_by($options["sort"], "DESC");
            }
        }

        if (isset($options["limit"]) && !empty($options["limit"])) {
            if (!is_array($options["limit"])) {
                $this->db->limit($options["limit"]);
            } elseif (count($options["limit"]) === 1) {
                $this->db->limit($options["limit"][0]);
            } elseif (count($options["limit"]) === 2) {
                $this->db->limit($options["limit"][0], $options["limit"][1]);
            } else {
                return false;
            }
        }

        if (isset($options["group_by"]) && !empty($options["group_by"])) {
            if (is_array($options["group_by"])) {
                foreach ($options["group_by"] as $value) {
                    $this->db->group_by($value);
                }
            } else {
                $this->db->group_by($options["group_by"]);
            }
        }

        if (isset($options["order_by"]) && !empty($options["order_by"])) {
            if (is_array($options["order_by"])) {
                foreach ($options["order_by"] as $value) {
                    $this->db->order_by($value);
                }
            } else {
                $this->db->order_by($options["order_by"]);
            }
        }

        if (isset($options['where_in']) && is_array($options['where_in'])) {
            foreach ($options["where_in"] as $key => $value) {
                $this->db->where_in($key, $value);
            }
        }
        
        if (isset($options['where_not_in']) && is_array($options['where_not_in'])) {
            foreach ($options["where_not_in"] as $key => $value) {
                $this->db->where_not_in($key, $value);
            }
        }
    }

    /**
     *Updates Data in Database but throws exception when there's an error
     *
     *@param array $data Data to be inserted
     *@param string $tableName Table Name to be inserted
     *@param array $where Key Value pair of field and data eg. ["email" =>"john@aol.com"]
     *@return bool TRUE on successfull Update, FALSE otherwise.
     */
    public function updateTableData($data, $tableName, $where)
    {
        $this->db->set($data);
        foreach ($where as $key => $value) {
            $this->db->where($key, $value);
        }
        if ($this->db->update($tableName)) {
                //return true;
            if ($this->db->affected_rows() == 0) {
                throw new \Exception("zero rows affected", 101);
            } else {
                return true;
            }
        } else {
            throw new \Exception("update error", 100);
        }
    }

    /**
     * Inserts Data into database but throws exception
     *
     * @param array $data Data to be inserted into database
     * @param string $tableName Table Name
     * @param bool $returnLastInsertId Return Last Insert Id when set to true
     *
     * @return bool|int|string Return TRUE|Last Insert Id on successful insertion, FALSE otherwise.
     */
    public function insertTableData($data, $tableName, $returnLastInsertId = false)
    {
         
        if ($this->db->set($data)->insert($tableName)) {
            if ($this->db->affected_rows()) {
                if ($returnLastInsertId == true) {
                    return $this->db->insert_id();
                } else {
                    return true;
                }
            } else {
//                echo $this->db->last_query();exit;
                throw new \Exception("insert error");
            }
        } else {
//            echo $this->db->last_query();exit;
            throw new \Exception("insert error");
        }
    }

    /**
     *
     * @param string $tableName
     * @param array $data
     * @throws InsertException
     */
    public function insertBatch($tableName = '', $data = array())
    {
        $status=$this->db->insert_batch($tableName, $data);
        if (!$status) {
            throw new \Exception("insert error");
        }
    }

    public function updateBatch($tableName, $data, $where)
    {
        $this->db->update_batch($tableName, $data, $where);
    }
}
