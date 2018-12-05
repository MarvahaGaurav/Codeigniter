<?php

namespace AppInventiv;

use AppInventiv\database\Db;
use AppInventiv\model\Usermodel;

use AppInventiv\Rest;
use Exception;
//include 'Commonmailfunction.php';

class Friend extends Rest {

    private $config;
    private $db;
    private $Usermodel;
    private $mailler;

    public function __construct() {
        parent::__construct();

        $this->db = new Db();
        $this->Usermodel = new Usermodel();
        //$this->mailler = new Commonmailfunction();
        
        $this->processApi();
    }

    private function processApi() {

        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));


        if ((int) method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 404);    // If the method not exist with in this class, response would be "Page not found".
    }

    /**
     * @FunctionName : send_request
     * @params - integer : sender_id
     * @params - integer : receiver_id
     * @params - string : invite_text
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    private function send_request() {

        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }  
            $data = $this->_request;

            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["sender_id", "receiver_id"]);

            $frequest['sender_id']   = $data["sender_id"];
            $frequest['receiver_id'] = $data["receiver_id"];
            $frequest['invite_text'] = (isset($data["invite_text"]) && $data["invite_text"]!='') ? $data["invite_text"]:'';
            
            $frequest['created']  = date("Y-m-d H:i:s");

            //--------insertion of data-----
            $this->db->beginTransaction();
            //-----------
            $checkExist = $this->db->row("select COUNT(*) as request_sent from ai_friend_request where sender_id=".$frequest['sender_id']." and receiver_id=".$frequest['receiver_id']);
            //------------if request already exist
            if(isset($checkExist['request_sent']) && $checkExist['request_sent']!=''){
                throw new Exception("Request is already sent by you || 208");
            }

            $frequest_id = $this->db->insert('ai_friend_request', $frequest);
            $this->db->executeTransaction();

            if($frequest_id!=''){

                //---------------
                $result = $this->db->row("Select id as frequest_id,sender_id,receiver_id,invite_text from ai_friend_request where id=".$frequest_id);
                $this->response($result, 200, [], 'Request added successfully.');
            }else{
                throw new Exception("Request can not be added || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        
        
    }
    /**
     * @FunctionName : request_response
     * @params - integer : frequest_id
     * @params - integer : status
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    private function request_response() {

        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }  
            $data = $this->_request;

            //echo "<pre>";print_r( $data );die;
            // Required Keys in array
            $this->checkEmptyParameter($data, ["frequest_id","status"]);

            if(in_array($data["status"],array(1,2))){

                $cond     = "id =".$data["frequest_id"];
                $frequest['status']  = $data["status"];
                $frequest['updated'] = date("Y-m-d H:i:s");
               

                //Validate Data

                $this->db->beginTransaction();
                $frequest_id = $this->db->update('ai_friend_request', $frequest,$cond);

                $this->db->executeTransaction();

                if($frequest_id!=''){
                    $result = $this->db->row("Select id as frequest_id,sender_id,receiver_id,status from ai_friend_request where id=".$data["frequest_id"]);
                    $this->response($result, 200, [], 'status updated successfully.');
                }else{
                    throw new Exception("Request can not be updated || 208");
                }
            }else{
                throw new Exception("invalid status provided || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        
        
    }
    
    /**
     * @FunctionName : find_friends
     * @params - string : search_text
     * @params - integer : user_id
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    private function find_friends() {

        try {
            if ($this->get_request_method() != "GET") {
                $this->response([], 405);
            }  
            $data = $this->_request;
            
            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["search_text","user_id"]);
            
           
            $this->db->beginTransaction();

            $search_text = $data["search_text"];
            $user_id = $data["user_id"];
            //ai_friend_request
            $friends_count = $this->db->row("Select COUNT(*)  as total from ai_user where first_name LIKE '%".$search_text."%' OR middle_name LIKE '%".$search_text."%' OR last_name LIKE '%".$search_text."%' and user_id !=".$user_id);
            
            $numrows = $friends_count['total'];
            $rowsperpage = 10;
            $next =1;
             
            // find out total pages
            $totalpages = ceil($numrows / $rowsperpage);
             
            // get the current page or set a default
            if (isset($data['page']) && is_numeric($data['page'])) {
                 $currentpage = (int) $data['page'];
            } else {
                $currentpage = 1;  // default page number
            }
             
            // if current page is greater than total pages
            if ($currentpage >= $totalpages) {
            // set current page to last page
                $currentpage = $totalpages;
                $next = 0;
            }
            // if current page is less than first page
            if ($currentpage < 1) {
            // set current page to first page
                $currentpage = 1;
            }
             
            // the offset of the list, based on current page
            $offset = ($currentpage - 1) * $rowsperpage;

            $user_list = $this->db->all_row("Select user_id,first_name,middle_name,last_name from ai_user where user_id !=".$user_id." and first_name LIKE '%".$search_text."%' OR middle_name LIKE '%".$search_text."%' OR last_name LIKE '%".$search_text."%'  order by ai_user.first_name asc");
 
            $this->db->executeTransaction();    

          
            if(!empty($user_list)){
                $j = 0;
                $updated_result = array();
                //-------------check if user already in field list----
                foreach($user_list as $user){
                    $already_friend = 0;
                    $receiver_id = $user['user_id'];
                    //---------check data from database
                    $get_row = $this->db->row("Select Count(*) as is_friend from ai_friend_request where (sender_id=".$user_id." and receiver_id=".$receiver_id.") OR (sender_id=".$receiver_id." and receiver_id=".$user_id.") and status=1");
                  
                    if(isset($get_row['is_friend']) && $get_row['is_friend']>0){
                         $already_friend = 1;
                    }
                    //-------------update user friend field -----
                    $updated_result[$j] = $user;
                    $updated_result[$j]['already_friend'] = $already_friend;
                    $j++;
                }
                //-------------set pagination data--------
                $page['page'] = $currentpage;
                $page['next_page'] = $next;
                $results = $updated_result;
                $this->response($results, 200,$page, 'User results.');
            }else{
                throw new Exception("no record found || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        
        
    }
     /**
     * @FunctionName : my_friends
     * @params - integer : user_id
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    function my_friends(){
        try {
            if ($this->get_request_method() != "GET") {
                $this->response([], 405);
            }  
            $data = $this->_request;
            
            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["user_id"]);

            $user_id = $data["user_id"];

            $this->db->beginTransaction();
            $my_friends = $this->db->all_row("select u.user_id,u.first_name,u.last_name from ai_friend_request f inner join ai_user u on f.sender_id = u.user_id where f.status = 1 and f.receiver_id = ".$user_id." union select u.user_id,u.first_name,u.last_name from ai_friend_request f inner join ai_user u on f.receiver_id = u.user_id where f.status = 1 and f.sender_id = ".$user_id);
            $this->db->executeTransaction();   
            if(!empty($my_friends)){
                 $results = $my_friends;
                 $this->response($results, 200,$page, 'friends result.');
            }else{
                throw new Exception("no record found || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
    }
     /**
     * @FunctionName : do_unfriend
     * @params - integer : user_id
     * @params - integer : friend_id
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    function do_unfriend(){
        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }  
            $data = $this->_request;
            
            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["user_id","friend_id"]);

            $user_id   = $data["user_id"];
            $friend_id = $data["friend_id"];

            $this->db->beginTransaction();

            $my_friend = $this->db->all_row("select id from ai_friend_request where (sender_id=".$user_id." and receiver_id=".$friend_id.") OR (sender_id=".$friend_id." and receiver_id=".$user_id.")");

            $this->db->executeTransaction();   
            //----------

            if(!empty($my_friend)){
                 $ids = implode(",",array_column($my_friend, 'id'));
                 $friend_request_id = $this->db->delete('ai_friend_request', ' id IN('.$ids.')');
                 $results = array();
                 $this->response($results, 200,$page, 'user unfriend successfully');
            }else{
                throw new Exception("no record found || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
    }
    /**
     * @FunctionName : send_request_list
     * @params - integer : user_id
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    function send_request_list(){
        try {
            if ($this->get_request_method() != "GET") {
                $this->response([], 405);
            }  
            $data = $this->_request;
            
            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["user_id"]);

            $user_id = $data["user_id"];

            $this->db->beginTransaction();
            $my_friends = $this->db->all_row("select f.id as frequest_id,u.user_id,u.first_name,u.last_name from ai_friend_request f inner join ai_user u on f.receiver_id = u.user_id where f.status = 0 and f.sender_id = ".$user_id);
            $this->db->executeTransaction();   
            if(!empty($my_friends)){
                 $results = $my_friends;
                 $this->response($results, 200,$page, 'send request result.');
            }else{
                throw new Exception("no record found || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
    }
    /**
     * @FunctionName : get_request_list
     * @params - integer : user_id
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    function get_request_list(){
        try {
            if ($this->get_request_method() != "GET") {
                $this->response([], 405);
            }  
            $data = $this->_request;
            
            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["user_id"]);

            $user_id = $data["user_id"];


            $this->db->beginTransaction();

            $request_count = $this->db->row("Select COUNT(*)  as total  from ai_friend_request f inner join ai_user u on f.sender_id = u.user_id where f.status = 0 and f.receiver_id = ".$user_id."");
            
            $numrows = $request_count['total'];

            $rowsperpage = 10;
            $next =1;
             
            // find out total pages
            $totalpages = ceil($numrows / $rowsperpage);
             
            // get the current page or set a default
            if (isset($data['page']) && is_numeric($data['page'])) {
                 $currentpage = (int) $data['page'];
            } else {
                $currentpage = 1;  // default page number
            }
             
            // if current page is greater than total pages
            if ($currentpage >= $totalpages) {
            // set current page to last page
                $currentpage = $totalpages;
                $next = 0;
            }
            // if current page is less than first page
            if ($currentpage < 1) {
            // set current page to first page
                $currentpage = 1;
            }
             
            // the offset of the list, based on current page
            $offset = ($currentpage - 1) * $rowsperpage;

           

            $my_requests = $this->db->all_row("select f.id as frequest_id,f.status,u.user_id,u.first_name,u.last_name from ai_friend_request f inner join ai_user u on f.sender_id = u.user_id where f.status = 0 and f.receiver_id = ".$user_id." LIMIT $offset, $rowsperpage");
            $this->db->executeTransaction();   
            if(!empty($my_requests)){
                 $results = $my_requests;
                 $page['page'] = $currentpage;
                 $page['next_page'] = $next;
                 $this->response($results, 200,$page, 'get request list.');
            }else{
                throw new Exception("no record found || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
    }

}
