<?php

class Common_model extends CI_Model {

    public function __construct() {
        
    //    $this->load->library(array('pagination', 'session','s3'));
         $this->load->library(array('pagination', 'session'));
         $this->load->database();
    }

    /**
     * Function name: fetch_login_data
     * Description- fetch login data 
     * Date: 25/03/2017
     */
    public function fetch_login_data($table, $fields = "*", $email = NULL, $password) {

        $this->db->select($fields);
        $this->db->from($table);

        if (!empty($email)) {
            $where = "admin_password='" . hash('sha256', $password) . "' AND admin_email='" . $email . "'";

            $this->db->where($where);
        }
        $query = $this->db->get();
        $data = $query->row_array();

        return $data;
    }

    /**
     * Function name: load_views
     * Description- fetch views
     * Params - view page and view data 
     * Date: 25/03/2017
     */
    function load_views($customView, $data = array()) {
        $this->load->view('template/header', $data);
        $this->load->view('template/leftMenu', $data);
        $this->load->view($customView, $data);
        $this->load->view('template/footer', $data);
    }
    
    
    /*
     * function : updateotherdriver request 
     * description : function to update other driver request 
     */
    
    function updateotherdriverrequest($id){
        
        $return_val = FALSE;
        
        if(is_numeric($id) && $id > 0){
            
            // query to update load_request table when a single driver request has been accepted
            
            $sql = "update load_request t SET status=? WHERE t.id in (
                    select g.id from (select * from load_request) as g WHERE g.id!=? AND g.load_id = 
                    (SELECT k.load_id from (select * from load_request) as k WHERE k.id=?) AND g.status=1)";
            
            $return_val  = $this->db->query($sql,array(LOAD_REQUEST_STATUS_REJECTED_BY_COMPANY,$id,$id));
            
        }
        
        return $return_val ;
        
    }
    
    
    
    /**
     * Function name: checkSession
     * Description- check session
     * Date: 25/03/2017
     */
    public function checkSession() {

        $user_id = $this->session->userdata('user_id');
        if (empty($user_id)) {

            redirect(SITE_URL . '/admin/auth');
        }
    }

    /*
     * Function name: fetch_data
     * Description- fetch data from database
     * Params - table name, fields to be selected,condition for query,return row or not
     * response - result array
     * Date: 25/03/2017
     */

    public function fetch_data($table, $fields = '*', $conditions = array(), $returnRow = false) {
        //Preparing query
        $this->db->select($fields);
        $this->db->from($table);
        //pr($conditions);
        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        //Return
        return $returnRow ? $query->row_array() : $query->result_array();
    }

    /*
     * Function name: fetch_data_join_table
     * Description- fetch data from database have table joint 
     * Params - table name, fields to be selected,table joint ,condition for query,return row or not
     * response - result array
     * Date: 25/03/2017
     */

    public function fetch_data_join_table($table, $select = '*', $joinTable = array(), $conditions = array(), $returnRow = false) {

        if ($select) {
            $this->db->select($select);
            $this->db->from($table);
        }


        if (count($joinTable) > 0) {
            for ($i = 0; $i < count($joinTable); $i++) {
                $this->db->join($joinTable[$i]['table'], $joinTable[$i]['condition'], $joinTable[$i]['type']);
            }
        }
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        $query = $this->db->get();

        //echo $this->db->last_query(); die;
        return $returnRow ? $query->row_array() : $query->result_array();
    }

    /*
     * Function name: fetch_data_paged
     * Description- fetch data from database have pagination 
     * Params - table name, fields to be selected,condition for query,return row or not,limit,offset
     * response - result array
     * Date: 25/03/2017
     */

    public function fetch_data_paged($table, $fields = '*', $conditions = array(), $returnRow = false, $limit = false, $offset = false) {
        //Preparing query
        $this->db->select($fields);
        $this->db->from($table);

        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        //Return
        return $returnRow ? $query->row_array() : $query->result_array();
    }

    /*
     * Function name: fetch_data_join_paged_table
     * Description- fetch data from database have table joint 
     * Params - table name, fields to be selected,table joint ,condition for query,return row or not
     * response - result array
     * Date: 25/03/2017
     */

    public function fetch_data_join_paged_table($table, $select = '*', $joinTable = array(), $conditions = array(), $returnRow = false, $limit = false, $offset = false) {

        if ($select) {
            $this->db->select($select);
            $this->db->from($table);
        }


        if (count($joinTable) > 0) {
            for ($i = 0; $i < count($joinTable); $i++) {
                $this->db->join($joinTable[$i]['table'], $joinTable[$i]['condition'], $joinTable[$i]['type']);
            }
        }
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        $this->db->limit($limit, $offset);
        $query = $this->db->get();

        //echo $this->db->last_query(); die;
        return $returnRow ? $query->row_array() : $query->result_array();
    }

    /**
     * Handle conditions
     *
     * @access	public
     */
    private function condition_handler($conditions) {
        //Where
        if (array_key_exists('where', $conditions)) {

            //Iterate all where's
            foreach ($conditions['where'] as $key => $val) {
                $this->db->where($key, $val);
            }
        }

        //Where Or
        if (array_key_exists('where_or', $conditions)) {
            //Iterate all where in's
            foreach ($conditions['where_or'] as $key => $val) {
                $this->db->or_where($key, $val);
            }
        }

        //Where In
        if (array_key_exists('where_in', $conditions)) {

            //Iterate all where in's
            foreach ($conditions['where_in'] as $key => $val) {
                $this->db->where_in($key, $val);
            }
        }

        //Where Not In
        if (array_key_exists('where_not_in', $conditions)) {

            //Iterate all where in's
            foreach ($conditions['where_not_in'] as $key => $val) {
                $this->db->where_not_in($key, $val);
            }
        }

        //Having
        if (array_key_exists('having', $conditions)) {
            $this->db->having($conditions['having']);
        }

        //Group By
        if (array_key_exists('group_by', $conditions)) {
            $this->db->group_by($conditions['group_by']);
        }

        //Order By
        if (array_key_exists('order_by', $conditions)) {

            //Iterate all order by's
            foreach ($conditions['order_by'] as $key => $val) {
                $this->db->order_by($key, $val);
            }
        }

        //Like
        if (array_key_exists('like', $conditions)) {

            //Iterate all likes
            $i = 1;
            foreach ($conditions['like'] as $key => $val) {
                if ($i == 1) {
                    $this->db->like('LOWER(' . $key . ')', strtolower($val), 'after');
                } else {
                    $this->db->or_like('LOWER(' . $key . ')', strtolower($val), 'after');
                }
                $i++;
            }
        }

        //Limit
        if (array_key_exists('limit', $conditions)) {
            //If offset is there too?
            if (count($conditions['limit']) == 1) {
                $this->db->limit($conditions['limit'][0]);
            } else {
                $this->db->limit($conditions['limit'][0], $conditions['limit'][1]);
            }
        }

        if (array_key_exists('findinset', $conditions)) {

            //Iterate all find in set 
            foreach ($conditions['findinset'] as $key => $val) {
                $this->db->where("FIND_IN_SET($val, $key)");
            }
        }
    }

    /**
     * Handle Pagination
     *
     * @access	public
     */
    public function handlePagination($totalRows) {

        //Load Pagination Library
        $this->load->config('pagination');
        $this->load->library('pagination');

        //First validate if there are any rows
        if ($totalRows > 0) {

            //Basic Pagination Config
            $finalSegment = $this->uri->segment(2);
            $config['per_page'] = $this->config->item('per_page_' . $finalSegment);
            $showMore = $this->input->get('show_more');
            $pageNumber = (!empty($showMore) and is_numeric($showMore)) ? $showMore - 1 : 0;
            $start = $config['per_page'] * $pageNumber;
            $config['total_rows'] = $totalRows;

            //Handle get params
            $additionalParams = '';
            $get = count($_GET) > 0 ? $_GET : array();
            $pageNumberKey = $this->config->item('query_string_segment');
            if (array_key_exists($pageNumberKey, $get)) {
                unset($get[$pageNumberKey]);
            }
            if (count($get) > 0) {
                $additionalParams = http_build_query($get);
            }
            $config['base_url'] = base_url() . 'view/' . $finalSegment . '?' . $additionalParams;
            $config['full_tag_open'] = '<div class="row"><div class="col-sm-5"><div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing ' . ($start + 1) . ' to ' . ($start + $config['per_page']) . ' of ' . $totalRows . ' entries</div></div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="example2_paginate"><ul class="pagination">';
            $this->pagination->initialize($config);

            return array(
                'totalRecords' => $config['total_rows'],
                'startCount' => $start
            );
        } else {
            return array(
                'totalRecords' => 0,
                'startCount' => 0
            );
        }
    }

    /**
     * Count all records
     *
     * @access	public
     * @param	string
     * @return	array
     */
    public function fetch_count($table, $conditions = array()) {
        $this->db->from($table);
        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        return $this->db->count_all_results();
    }

    public function update_single($table, $updates, $conditions = array()) {
        //If there are conditions

        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }

        return $this->db->update($table, $updates);
        //echo $this->db->last_query(); die;
    }

    /**
     * Insert data in DB
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	string
     * @return	string
     */
    public function insert_single($table, $data = array()) {
        //Check if any data to insert
        $inser_id = 0;
        if (count($data) < 1) {
            return false;
        }
        $this->db->trans_start();
        $this->db->insert($table, $data);
        $inser_id = $this->db->insert_id();
        $error =   $this->db->error();
        if(isset($error['code']) &&  $error['code'] != 0){
            $this->db->trans_rollback();
            throw new Exception("account_creation_unsuccessful || ".ERROR_INSERTION);     
        }
         $this->db->trans_complete();
      
      
        return $inser_id;
         

            

    }

    /**
     * Insert batch data
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	array
     * @param	bool
     * @return	bool
     */
    public function insert_batch($table, $defaultArray, $dynamicArray = array(), $updatedTime = false) {
        //Check if default array has values
        if (count($dynamicArray) < 1) {
            return false;
        }

        //If updatedTime is true
        if ($updatedTime) {
            $defaultArray['UpdatedTime'] = time();
        }

        //Iterate it
        foreach ($dynamicArray as $val) {
            $updates[] = array_merge($defaultArray, $val);
        }
        $this->db->insert_batch($table, $updates);
    }

    /**
     * Delete data from DB
     *
     * @access	public
     * @param	string
     * @param	array
     * @param	string
     * @return	string
     */
    public function delete_data($table, $conditions = array()) {
        //If there are conditions
        if (count($conditions) > 0) {
            $this->condition_handler($conditions);
        }
        return $this->db->delete($table);
    }

    /*
     *  @name uploadfile   
     *  @param type $filename   
     *  @param type $filearr        
     *  @param type $restype       
     *  @param type $foldername           
     *  @return boolean
     */

    public function uploadfile($filename, $filearr, $restype = 'name', $foldername) {
        if ($filearr[$filename]['name'] != '') {

            $new_name = time().$filearr[$filename]['name'];
            $config['file_name'] = $new_name;
            $this->load->library('upload',$config);
            $this->upload->set_upload_path(UPLOAD_IMAGE_PATH);
            $this->upload->set_allowed_types(array('jpg', 'png', 'jpeg'));
            if ($this->upload->do_upload($filename)) {
                unset($_FILES);
                $res = $this->upload->data();
                if ($restype == 'name') {
                    return $res['file_name'];
                } elseif ($restype == 'url') {
                    return BASE_URL_FILE . $foldername . '/' . $res['file_name'];
                }
            } else {
                return false;
            }
        }
    }

    function mcrypt_data($input) {
        /* Return mcrypted data */
        $key1 = "TeachersApp";
        $key2 = "StudentsApp";
        $key = $key1 . $key2;
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $input, MCRYPT_MODE_CBC, md5(md5($key))));
        //var_dump($encrypted);
        return $encrypted;
    }

    function demcrypt_data($input) {
        /* Return De-mcrypted data */
        $key1 = "ShareSpark";
        $key2 = "Org";
        $key = $key1 . $key2;
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        return $decrypted;
    }

    public function manageprofilepicsocial($image) {

        //~ echo $image;die;
        $is_send_image = TRUE;
        //Get the file
        $content = file_get_contents($image);
        $ImageFolder = "/public/teachers/images/profile_image/";
        $name = explode('.', $image);
        $ext = array_pop($name);
        $destpath = getcwd() . $ImageFolder;
        //Store in the filesystem.
        $picture_name = uniqid('user_') . '_' . strtotime("now") . '_UTC.jpg';
        $path = $destpath . "{$picture_name}";
        //echo $path;die;
        $fp = fopen($path, "w");
        $st = fwrite($fp, $content);

        fclose($fp);

        return $picture_name;
    }

   
    
    /**
     * For sending mail
     *
     * @access	public
     * @param	string
     * @param	string
     * @param	string
     * @param	boolean
     * @return	array
     */
    public function sendmail($email, $subject, $message = false, $single = true, $param = false, $templet = false) {
        
        // check for single user email 
        if ($single)            
        $this->load->library('email');
        $this->config->load('email');
        $this->email->set_newline("\r\n");
        $this->email->from($this->config->item('from'), $this->config->item('from_name'));
        $this->email->reply_to($this->config->item('repy_to'), $this->config->item('reply_to_name'));
        $this->email->to($email);
        $this->email->subject($subject);
        if ($templet) {
            $this->email->message($templet);
        } else {
            $this->email->message($message);
        }
        return $this->email->send() ? true : false;
    }
    
     public function BasicAuth() {
        if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
            return false;
        } else {
            return $_SERVER['PHP_AUTH_USER'] . '-' . $_SERVER['PHP_AUTH_PW'];
        }
    }
    
    
    public function parameter($arr){
        foreach($arr as $val){
            if(empty($val)){
                return false;
            }
        }
        return TRUE;
    }
    
    
     public function encrypt($text, $salt, $isBaseEncode = true) {
        if ($isBaseEncode) {
            return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        } else {
            return trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        }
    }


	// upload file img in s3 server
	public function s3_uplode($filename, $temp_name) {

		$name = explode('.', $filename);
		$ext = array_pop($name);
		$name = 'web' . uniqid() . strtotime("now") . '.' . $ext;

		$imgdata = $temp_name;
		$s3 = new S3();
		$uri = 'web/' . $name;
		$bucket = 'appinventiv-development';
		$result = $s3->putObjectFile($imgdata, $bucket, $uri, S3::ACL_PUBLIC_READ);
		//echo $result;die;
		$url = 'https://appinventiv-development.s3.amazonaws.com/web/' . $name;
		return $url;
	}

}
