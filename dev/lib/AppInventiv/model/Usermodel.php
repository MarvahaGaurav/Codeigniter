<?php

namespace AppInventiv\model;

use AppInventiv\database\Db;

class Usermodel {

    public $db;

    public function __construct() {
        ini_set('display_errors', '1');
        $this->db = new Db();
    }

    /**
     * For geting user record from user table
     * @param : condtion array
     * @return : user data
     * */
    public function getRecordExists($whereArr = []) {
        $where = '1';
        foreach ($whereArr as $key => $value) {
            $where.=" && " . $key . "='" . $value . "'";
        }
        return $this->db->row("Select * from ai_user where $where");
    }

    /**
     * For geting session record from session table
     * @param : condtion array
     * @return : session data
     * */
    public function getSessionRecord($whereArr = []) {
        $where = '1';
        foreach ($whereArr as $key => $value) {
            $where.=" && " . $key . "='" . $value . "'";
        }
        return $this->db->row("Select * from ai_session where $where");
    }

    /**
     * For geting schedule record from schedule table
     * @param : condtion array
     * @return : schedule data
     * */
    public function getScheduleRecord($whereArr = []) {
        $where = '1';
        foreach ($whereArr as $key => $value) {
            $where.=" && " . $key . "='" . $value . "'";
        }
        return $this->db->all_row("Select * from ai_schedule_list where $where");
    }
       /**
     * For geting demo schedule record from demmo schedules table
     * @return : schedule data
     * */
    public function getDemoScheduleRecord() {
        return $this->db->all_row("Select * from ai_demoschedules");
    }

    /**
     * For geting content record from content table
     * @param : condtion array
     * @return : content data
     * */
    public function getContent($whereArr = []) {
        $where = '1 = 1';
        foreach ($whereArr as $key => $value) {
            $where.=" AND " . $key . " = '" . $value . "'";
        }
        return $this->db->row("Select * from ai_content where $where");
    }

    /**
     * For geting  record from  table
     * @param : condtion array , Table name
     * @return : record data
     * */
    public function getRecords($whereArr = [],$tablename) {
        $where = '1 = 1';
        foreach ($whereArr as $key => $value) {
            $where.=" AND " . $key . " = '" . $value . "'";
        }
      
        return $this->db->row("Select * from {$tablename} where $where");
    }

    /**
     * For geting schedule id from schedule batch insertion
     * @param : condtion array
     * @return : schedule id
     * */
    public function getScheduleId($whereArr = []) {
        $where = '1 = 1';

        foreach ($whereArr as $key => $value) {
            if ($key == 'schedule_id') {
                $where = $where . " AND " . $key . " > '" . $value . "'";
            } else {
                $where.=" AND " . $key . " = '" . $value . "'";
            }
        }


        return $this->db->all_row(" Select schedule_id,local_id from ai_schedule_list where $where");
    }
    /**
     * For geting  record from  table
     * @param : condtion array , Table name
     * @return : record data
     * */
    public function getAllRecords($fields = "*",$whereArr = [],$tablename) {
        $where = '1 = 1';
        foreach ($whereArr as $key => $value) {
            $where.=" AND " . $key . " = '" . $value . "'";
        }
      
        return $this->db->all_row("Select * from {$tablename} where $where");
    }

    //get maximum id
    public function get_maximum_id($query) {

        return $this->db->all_row($query);
    }

}

?>
