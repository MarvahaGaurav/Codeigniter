<?php

namespace AppInventiv;

use AppInventiv\database\Db;
use AppInventiv\model\Usermodel;

use AppInventiv\Rest;
use Exception;
//include 'Commonmailfunction.php';

class Follow extends Rest {

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
    private function do_follow() {

        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }  
            $data = $this->_request;

            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["sender_id", "receiver_id"]);

            $frequest['sender_id']   = $data["sender_id"];
            $frequest['receiver_id'] = $data["receiver_id"];
            $frequest['status']      = 1;
            
            $frequest['created']  = date("Y-m-d H:i:s");

            //--------insertion of data-----
            $this->db->beginTransaction();
            //-----------
            $checkExist = $this->db->row("select COUNT(*) as request_sent from ai_follows where sender_id=".$frequest['sender_id']." and receiver_id=".$frequest['receiver_id']);
            //------------if request already exist
            if(isset($checkExist['request_sent']) && $checkExist['request_sent']!=''){
                throw new Exception("you already follow this user || 208");
            }

            $frequest_id = $this->db->insert('ai_follows', $frequest);
            $this->db->executeTransaction();

            if($frequest_id!=''){

                //---------------
                $result = $this->db->row("Select id as frequest_id,sender_id,receiver_id from ai_follows where id=".$frequest_id);
                $this->response($result, 200, [], 'your follow request successful complete.');
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
     * @FunctionName : find_users
     * @params - string : search_text
     * @params - integer : page
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    private function find_users() {

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
                    $already_follow = 0;
                    $receiver_id = $user['user_id'];
                    //---------check data from database
                    $get_row = $this->db->row("Select Count(*) as is_follow from ai_follows where (sender_id=".$user_id." and receiver_id=".$receiver_id.") OR (sender_id=".$receiver_id." and receiver_id=".$user_id.") and status=1");
                  
                    if(isset($get_row['is_follow']) && $get_row['is_follow']>0){
                         $already_follow = 1;
                    }
                    //-------------update user friend field -----
                    $updated_result[$j] = $user;
                    $updated_result[$j]['already_follow'] = $already_follow;
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
     * @FunctionName : get_follow_list
     * @params - integer : user_id
     * @params - integer : type
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    function get_follow_list(){
        try {
            if ($this->get_request_method() != "GET") {
                $this->response([], 405);
            }  
            $data = $this->_request;
            
            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["user_id","type"]);

            $user_id = $data["user_id"];
            $type    = $data["type"];

            $this->db->beginTransaction();
            //---------type =1 for which user i follow type=2 users whose follow me---
            if($type==1){
                $my_friends = $this->db->all_row("select f.id as frequest_id,u.user_id,u.first_name,u.last_name from ai_follows f inner join ai_user u on f.receiver_id = u.user_id where f.status = 1 and f.sender_id = ".$user_id);
            }else{
                $my_friends = $this->db->all_row("select f.id as frequest_id,u.user_id,u.first_name,u.last_name from ai_follows f inner join ai_user u on f.sender_id = u.user_id where f.status = 1 and f.receiver_id = ".$user_id);
            }
            $this->db->executeTransaction();   
            if(!empty($my_friends)){
                 $results = $my_friends;
                 $this->response($results, 200,$page, 'follow users');
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
     * @FunctionName : do_unfollow
     * @params - integer : user_id
     * @params - integer : follower_id
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    function do_unfollow(){
        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }  
            $data = $this->_request;
            
            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["user_id","follower_id"]);

            $user_id     = $data["user_id"];
            $follower_id = $data["follower_id"];

            $this->db->beginTransaction();

            $my_friend = $this->db->all_row("select id from ai_follows where sender_id=".$user_id." and receiver_id=".$follower_id);

            $this->db->executeTransaction();   
            //----------

            if(!empty($my_friend)){
                 $ids = implode(",",array_column($my_friend, 'id'));
                 $friend_request_id = $this->db->delete('ai_follows', ' id IN('.$ids.')');
                 $results = array();
                 $this->response($results, 200,$page, 'user unfollowed successfully');
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
