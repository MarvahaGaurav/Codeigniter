<?php
defined("BASEPATH") OR exit("No direct script access allowed");

use DatabaseExceptions\UpdateException;
use DatabaseExceptions\SelectException;
use DatabaseExceptions\InsertException;
use DatabaseExceptions\DeleteException;

class BaseModel extends CI_Model {

    protected $tableName;
    public $batch_data;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function save() 
    {
        unset($this->batch_data);
        $status = $this->db->insert($this->tableName, $this);
        if ( ! $status ) {
            throw new InsertException($this->db->last_query(), 101);
        }
        return $this->db->insert_id();
    }

    public function update($where) 
    {
        unset($this->batch_data);
        $this->db->set($this);
        foreach( $where as $key => $value ) {
            $this->db->where($key, $value);
        }
        $status = $this->db->update($this->tableName);

        if ( ! $status ) {
            throw new UpdateException($this->db->last_query(), 102);
        }
        if ( $this->db->affected_rows() == 0 ) {
            throw new UpdateException("Zero rows affected", 101);
        }
    }

    public function delete($where) {
        foreach( $where as $key => $value ) {
            $this->db->where($key, $value);
        }
        $status = $this->db->delete($this->tableName);

        if ( ! $status ) {
            throw new DeleteException($this->db->last_query(), 101);
        }
    }

    public function batch_save() 
    {
        $status = $this->db->insert_batch($this->tableName, $this->batch_data);
        if ( ! $status ) {
            throw new InsertException($this->db->last_query(), 101);
        }
    }
}