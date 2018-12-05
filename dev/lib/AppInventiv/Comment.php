<?php

namespace AppInventiv;

use AppInventiv\database\Db;
use AppInventiv\model\Usermodel;

use AppInventiv\Rest;
use Exception;
//include 'Commonmailfunction.php';

class Comment extends Rest {

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
     * @FunctionName : add_comment
     * @params - integer : user_id
     * @params - integer : post_id
     * @params - string : comment
     * @response - json result 
     * @DateCreated: 27/09/2017
     */
    private function add_comment() {

        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }  
            $data = $this->_request;

            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["user_id", "post_id", "comment"]);

            $comment['user_id'] = $data["user_id"];
            $comment['post_id'] = $data["post_id"];
            $comment['comment'] = $data["comment"];
            $comment['status']  = 1;

            //Validate Data
        
            $comment['created']  = date("Y-m-d H:i:s");

            $this->db->beginTransaction();
            $comment_id = $this->db->insert('ai_comments', $comment);

            $this->db->executeTransaction();
            if($comment_id!=''){
                $result = $this->db->row("Select c.id as comment_id,c.user_id,c.post_id,c.comment,c.status,u.first_name from ai_comments as c left join ai_user as u on c.user_id=u.user_id where c.id=".$comment_id);
                 $this->response($result, 200, [], 'Comment added successfully.');
            }else{
                throw new Exception("Comment can not be added || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        
        
    }
    /**
     * @FunctionName : update_comment
     * @params - integer : comment_id
     * @params - string : comment
     * @response - json result 
     * @DateCreated: 27/09/2017
     */
    private function update_comment() {

        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }  
            $data = $this->_request;

            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["comment_id","comment"]);

            $cond     = "id =".$data["comment_id"];
           
            $comment['comment'] = $data["comment"];
            $comment['updated'] = date("Y-m-d H:i:s");
           

            //Validate Data

            $this->db->beginTransaction();
            $comment_id = $this->db->update('ai_comments', $comment,$cond);
            $this->db->executeTransaction();
            if($comment_id!=''){
                $result = $this->db->row("Select c.id as comment_id,c.user_id,c.post_id,c.comment,c.status,u.first_name from ai_comments as c left join ai_user as u on c.user_id=u.user_id where c.id=".$data["comment_id"]);
                $this->response($result, 200, [], 'Comment updated successfully.');
            }else{
                throw new Exception("Comment can not be added || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        
        
    }
    /**
     * @FunctionName : delete_comment
     * @params - integer : comment_id
     * @params - integer : user_id
     * @response - json result 
     * @DateCreated: 27/09/2017
     */
    private function delete_comment() {

        try {
            if ($this->get_request_method() != "GET") {
                $this->response([], 405);
            }  
            $data = $this->_request;

            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["comment_id", "user_id"]);
            $this->db->beginTransaction();
            $comment_id = $this->db->delete('ai_comments', 'user_id='.$data["user_id"].' and id='.$data["comment_id"]);
            $this->db->executeTransaction();
            if($comment_id!=''){
                 $this->response([], 200, [], 'Comment deleted successfully.');
            }else{
                throw new Exception("Comment can not be added || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        
        
    }
    /**
     * @FunctionName : get_comment
     * @params - integer : user_id
     * @params - integer : post_id
     * @response - json result 
     * @DateCreated: 27/09/2017
     */
    private function get_comment() {

        try {
            if ($this->get_request_method() != "GET") {
                $this->response([], 405);
            }  
            $data = $this->_request;

            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["post_id"]);
            
           
            $this->db->beginTransaction();

            $comments_count = $this->db->row("Select COUNT(*)  as total from ai_comments as c where  c.post_id=".$data['post_id']." order by c.id desc");

            $numrows = $comments_count['total'];
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

            $results = $this->db->all_row("Select c.id as comment_id,c.user_id,c.post_id,c.comment,c.status,u.first_name from ai_comments as c left join ai_user as u on c.user_id=u.user_id where  c.post_id=".$data['post_id']." order by c.id desc LIMIT $offset, $rowsperpage");
 
            $this->db->executeTransaction();

          
            if(!empty($results)){
                 
                 $page['page'] = $currentpage;
                 $page['next_page'] = $next;
                 $this->response($results, 200,$page, 'Comment results.');
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
