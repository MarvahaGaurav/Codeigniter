<?php

/**
 *  DB - A simple database class 
 */

namespace AppInventiv\database;

use AppInventiv\database\Log;
use \PDO;

//require("Log.class.php");


class Db {
    # @object, The PDO object

    private $pdo;

    # @object, PDO statement object
    private $sQuery;

    # @array,  The database settings
    private $settings = ["dbname" => "applau8_resuable_db", "password" => "(r2Jgm.!;shX", "host" => "localhost", "user" => "applau8_resu_usr"];
    //private $settings=["dbname"=>"applau8_reusable_db","password"=>"root","host"=>"localhost","user"=>"root"];
    # @bool ,  Connected to the database
    private $bConnected = false;

    # @object, Object for logging exceptions	
    private $log;

    # @array	, The parameters of the SQL query
    private $parameters;

    /**
     *   Default Constructor 
     *
     * 	1. Instantiate Log class.
     * 	2. Connect to database.
     * 	3. Creates the parameter array.
     */
    public function __construct() {
        $this->log = new Log();

        $this->Connect();
        $this->parameters = array();
    }

    /**
     * 	This method makes connection to the database.
     * 	
     * 	1. Reads the database settings from a ini file. 
     * 	2. Puts  the ini content into the settings array.
     * 	3. Tries to connect to the database.
     * 	4. If connection failed, exception is displayed and a log file gets created.
     */
    private function Connect() {
        //$this->settings = parse_ini_file("settings.ini.php");
        //echo "<pre>"; print_r($this->settings); die;
        $dsn = 'mysql:dbname=' . $this->settings["dbname"] . ';host=' . $this->settings["host"] . '';
        try {
            # Read settings from INI file, set UTF8
            $this->pdo = new \PDO($dsn, $this->settings["user"], $this->settings["password"], array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ));

            # We can now log any exceptions on Fatal error. 
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            # Disable emulation of prepared statements, use REAL prepared statements instead.
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            # Connection succeeded, set the boolean to true.
            $this->bConnected = true;
        } catch (PDOException $e) {
            # Write into log
            echo $this->ExceptionLog($e->getMessage());
            die();
        }
    }

    /*
     *   You can use this little method if you want to close the PDO connection
     *
     */

    public function CloseConnection() {
        # Set the PDO object to null to close the connection
        # http://www.php.net/manual/en/pdo.connections.php
        $this->pdo = null;
    }

    /**
     * 	Every method which needs to execute a SQL query uses this method.
     * 	
     * 	1. If not connected, connect to the database.
     * 	2. Prepare Query.
     * 	3. Parameterize Query.
     * 	4. Execute Query.	
     * 	5. On exception : Write Exception into the log + SQL query.
     * 	6. Reset the Parameters.
     */
    private function Init($query, $parameters = "") {
        # Connect to database
        if (!$this->bConnected) {
            $this->Connect();
        }
        try {
            # Prepare query
            $this->sQuery = $this->pdo->prepare($query);

            # Add parameters to the parameter array	
            $this->bindMore($parameters);

            # Bind parameters
            if (!empty($this->parameters)) {
                foreach ($this->parameters as $param => $value) {

                    $type = PDO::PARAM_STR;
                    switch ($value[1]) {
                        case is_int($value[1]):
                            $type = PDO::PARAM_INT;
                            break;
                        case is_bool($value[1]):
                            $type = PDO::PARAM_BOOL;
                            break;
                        case is_null($value[1]):
                            $type = PDO::PARAM_NULL;
                            break;
                    }
                    // Add type when binding the values to the column
                    $this->sQuery->bindValue($value[0], $value[1], $type);
                }
            }

            # Execute SQL 
            $this->sQuery->execute();
        } catch (PDOException $e) {
            # Write into log and display Exception
            echo $this->ExceptionLog($e->getMessage(), $query);
            die();
        }

        # Reset the parameters
        $this->parameters = array();
    }

    /**
     * 	@void 
     *
     * 	Add the parameter to the parameter array
     * 	@param string $para  
     * 	@param string $value 
     */
    public function bind($para, $value) {
        $this->parameters[sizeof($this->parameters)] = [":" . $para, $value];
    }

    /**
     * 	@void
     * 	
     * 	Add more parameters to the parameter array
     * 	@param array $parray
     */
    public function bindMore($parray) {
        if (empty($this->parameters) && is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $i => &$column) {
                $this->bind($column, $parray[$column]);
            }
        }
    }

    /**
     *  If the SQL query  contains a SELECT or SHOW statement it returns an array containing all of the result set row
     * 	If the SQL statement is a DELETE, INSERT, or UPDATE statement it returns the number of affected rows
     *
     *   	@param  string $query
     * 	@param  array  $params
     * 	@param  int    $fetchmode
     * 	@return mixed
     */
    public function query($query, $params = null, $fetchmode = PDO::FETCH_ASSOC) {
        $query = trim(str_replace("\r", " ", $query));

        $this->Init($query, $params);

        $rawStatement = explode(" ", preg_replace("/\s+|\t+|\n+/", " ", $query));

        # Which SQL statement is used 
        $statement = strtolower($rawStatement[0]);

        if ($statement === 'select' || $statement === 'show') {
            return $this->sQuery->fetchAll($fetchmode);
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            return $this->sQuery->rowCount();
        } else {
            return NULL;
        }
    }

    /**
     * For Insert Record into Table
     * @param : tablename, insert array
     * @return : Last InsertId
     * */
    public function insert($tablename, $arr = []) {
        $param = '';
        foreach ($arr as $key => $value) {
            if ($value) {
                if (!is_bool($value))
                    $value = "'{$value}'";
                $param .= "{$key}" . "={$value},";
            }
        }
        $param = trim($param, ',');
        $query = "insert into {$tablename} set {$param}";


        $this->query($query);
        return $this->lastInsertId();
    }

    /**
     * For Insert And Update On Duplicate Key
     * @param : tablename, insert array , Update array
     * @return : Last InsertId
     * */
    public function insertupdate($tablename, $insertarr = [], $updatearr = []) {
        $param = '';
        $values = '';
        foreach ($insertarr as $key => $value) {
            $param .= "{$key},";
            if (!is_bool($value))
                $value = "'{$value}'";
            $values .= "{$value},";
        }
        $param = trim($param, ',');
        $values = trim($values, ",");
        $query = "insert into {$tablename} ($param) VALUES($values) ON DUPLICATE KEY UPDATE";
        $update = "";
        foreach ($updatearr as $key => $value) {
            if (!is_bool($value))
                $value = "'{$value}'";
            $update = $update . $key . "=" . "(" . $value . "),";
        }
        $update = trim($update, ',');
        $query = $query . " " . $update;
        $this->query($query);
        return $this->lastInsertId();
    }

    /**
     * For Insert Bulk Record into Table
     * @param : tablename, insert key array , insert value array
     * @return : Last InsertId
     * */
    public function bulkinsert($tablename, $arr = [], $valuearr = []) {
        $param = '';
        foreach ($arr as $value) {
            if ($value) {
                if (!is_bool($value))
                    $value = "{$value}";
                $param .= "{$value},";
            }
        }
        $param = trim($param, ',');
        $query = "insert into {$tablename} ($param) VALUES ";
        $data = "";
        $editdata = "";
        foreach ($valuearr as $key => $value) {
            $val = "";
            foreach ($arr as $index) {

                $val = $val . "," . $value[$index];
            };
            $val = ltrim($val, ",");
            $data .= '(' . $val . '),';
        };
        $query = substr($query . $data, 0, -1);

        $this->query($query);
        return $this->lastInsertId();
    }

    /**
     * For Update Bulk Record into Table
     * @param : tablename, insert key array , insert value array
     * @return : Last Updated Id
     * */
    public function bulkupdate($tablename, $arr = [], $valuearr = [], $condition = []) {
        $query_run = array();
        $id = 1;
        foreach ($valuearr as $key => $value) {
            $param = '';
            foreach ($arr as $arr_value) {
                $param .= "{$arr_value}" . "={$value[$arr_value]},";
            }
            $param = trim($param, ',');
            $cond = '1 = 1';
            foreach ($condition as $condition_key => $condition_value) {
                $cond .= " AND " . $condition_value . " = " . $value [$condition_value] . "";
            }
            $query = "UPDATE {$tablename} SET {$param} WHERE $cond";
            $query_run['schedule_' . $id]['schedule_id'] = $value [$condition_value];
            $query_run['schedule_' . $id]['status'] = $this->query($query);
            $id = $id + 1;
        }

        return $query_run;
    }

    /**
     * For Delete Bulk Record into Table
     * @param : tablename, Delete ids
     * @return : Last Delete Status
     * */
    public function bulkdelete($tablename, $arr = []) {
        $delete_schedule_id = implode(",", $arr);
        $query = "DELETE FROM {$tablename} WHERE schedule_id IN ($delete_schedule_id )";
        $query_run = $this->query($query);

        return $query_run;
    }

    /**
     * For Update Record into Table
     * @param : tablename, update array
     * */
    public function update($tablename, $arr = [], $condition = []) {

        $param = '';
        $con = isset($condition) && $condition !== "" ? $condition : "";
        foreach ($arr as $key => $value) {

            //if ($value) {
            if (!is_bool($value)) {
                $value = "'{$value}'";
            } else {
                //todo
            }

            
            $param .= "{$key}" . "={$value},";
            //}
        }

        $param = trim($param, ',');
        $query = "UPDATE {$tablename} SET {$param} WHERE {$con}";
        //print_r($query);die;
        $query_run = $this->query($query);

        return $query_run;
    }

    public function updatedata($tablename, $arr = [], $condition = []) {

        $param = '';
        $con = isset($condition) && $condition !== "" ? $condition : "";
        foreach ($arr as $key => $value) {

            //if ($value) {
            if (is_int($value)) {
                //todo
            } else {

                $value = "'{$value}'";
            }

            // print_r($value);die;
            $param .= "{$key}" . "={$value},";
            //}
        }

        $param = trim($param, ',');
        $query = "UPDATE {$tablename} SET {$param} WHERE {$con}";
        //print_r($query);die;
        $query_run = $this->query($query);

        return $query_run;
    }

    /**
     * Delete the row
     * @param tablename , condition,
     * 
     */
    public function delete($tablename, $condition = []) {
        $con = isset($condition) && $condition !== "" ? $condition : "";
        $query = "DELETE FROM {$tablename}  WHERE {$con}";
        $query_run = $this->query($query);
        return $query_run;
    }

    /**
     *  Returns the last inserted id.
     *  @return string
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * Starts the transaction
     * @return boolean, true on success or false on failure
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    /**
     *  Execute Transaction
     *  @return boolean, true on success or false on failure
     */
    public function executeTransaction() {
        return $this->pdo->commit();
    }

    /**
     *  Rollback of Transaction
     *  @return boolean, true on success or false on failure
     */
    public function rollBack() {
        return $this->pdo->rollBack();
    }

    /**
     * 	Returns an array which represents a column from the result set 
     *
     * 	@param  string $query
     * 	@param  array  $params
     * 	@return array
     */
    public function column($query, $params = null) {
        $this->Init($query, $params);
        $Columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);

        $column = null;

        foreach ($Columns as $cells) {
            $column[] = $cells[0];
        }

        return $column;
    }

    /**
     * 	Returns an array which represents a row from the result set 
     *
     * 	@param  string $query
     * 	@param  array  $params
     *   	@param  int    $fetchmode
     * 	@return array
     */
    public function row($query, $params = null, $fetchmode = PDO::FETCH_ASSOC) {
        $this->Init($query, $params);
        $result = $this->sQuery->fetch($fetchmode);
        $this->sQuery->closeCursor(); // Frees up the connection to the server so that other SQL statements may be issued,
        return $result;
    }

    /**
     *  Returns an array which represents a row from the result set 
     *
     *  @param  string $query
     *  @param  array  $params
     *      @param  int    $fetchmode
     *  @return all rows
     */
    public function all_row($query, $params = null, $fetchmode = PDO::FETCH_ASSOC) {
        $this->Init($query, $params);
        $result = $this->sQuery->fetchAll($fetchmode);
        $this->sQuery->closeCursor(); // Frees up the connection to the server so that other SQL statements may be issued,
        return $result;
    }

    /**
     * 	Returns the value of one single field/column
     *
     * 	@param  string $query
     * 	@param  array  $params
     * 	@return string
     */
    public function single($query, $params = null) {
        $this->Init($query, $params);
        $result = $this->sQuery->fetchColumn();
        $this->sQuery->closeCursor(); // Frees up the connection to the server so that other SQL statements may be issued
        return $result;
    }

    /**
     * Check API PARAMETER
     *
     * @param  string $FUNCTION NAME
     * @param  string $API DATA
     * @return 
     *
     */
    public function checkApiparameter($api_array, $tablename) {
        $param = '';

        foreach ($api_array as $key => $value) {

            if ($value) {

                $value = "'{$value}'";
                $param .= "{$key}" . "={$value},";
            }
        }
        $param = trim($param, ',');

        $query = "insert into {$tablename} set {$param}";
        $this->query($query);
    }

    /** 	
     * Writes the log and returns the exception
     *
     * @param  string $message
     * @param  string $sql
     * @return string
     */
    private function ExceptionLog($message, $sql = "") {
        $exception = 'Unhandled Exception. <br />';
        $exception .= $message;
        $exception .= "<br /> You can find the error back in the log.";

        if (!empty($sql)) {
            # Add the Raw SQL to the Log
            $message .= "\r\nRaw SQL : " . $sql;
        }
        # Write into log
        $this->log->write($message);

        return $exception;
    }

}

?>
