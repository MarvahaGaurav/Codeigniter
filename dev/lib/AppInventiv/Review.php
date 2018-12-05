<?php

namespace AppInventiv;

use AppInventiv\database\Db;
use AppInventiv\model\Usermodel;

use AppInventiv\Rest;
use Exception;
//include 'Commonmailfunction.php';

class Review extends Rest {

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
     * @FunctionName : add_review
     * @params - integer : user_id
     * @params - integer : post_id
     * @params - float : rating
     * @params - string : review
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    private function add_review() {

        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }  
            $data = $this->_request;

            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["user_id", "post_id", "review",'rating']);

            $comment['user_id'] = $data["user_id"];
            $comment['post_id'] = $data["post_id"];
            $comment['review']  = $data["review"];
            $comment['rating']  = $data["rating"];
            $comment['status']  = 1;

            //Validate Data
        
            $comment['created']  = date("Y-m-d H:i:s");

            $this->db->beginTransaction();
            $comment_id = $this->db->insert('ai_reviews', $comment);
            $this->db->executeTransaction();
            if($comment_id!=''){
                 $result = $this->db->row("Select r.id as review_id,r.rating,r.user_id,r.post_id,r.review,r.status,u.first_name from ai_reviews as r left join ai_user as u on r.user_id=u.user_id where r.id=".$comment_id);
                 $this->response($result, 200, [], 'Review added successfully.');
            }else{
                throw new Exception("Review can not be added || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        
        
    }
    /**
     * @FunctionName : update_review
     * @params - integer : review_id
     * @params - float : rating
     * @params - string : review
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    private function update_review() {

        try {
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }  
            $data = $this->_request;

            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["review_id","review","rating"]);

            $cond     = "id =".$data["review_id"];
           
            $comment['review']  = $data["review"];
            $comment['rating']  = $data["rating"];
            $comment['updated'] = date("Y-m-d H:i:s");
           

            //Validate Data

            $this->db->beginTransaction();
            $comment_id = $this->db->update('ai_reviews', $comment,$cond);
            $this->db->executeTransaction();
            if($comment_id!=''){
                $result = $this->db->row("Select r.id as review_id,r.user_id,r.rating,r.post_id,r.review,r.status,u.first_name from ai_reviews as r left join ai_user as u on r.user_id=u.user_id where r.id=".$data["review_id"]);
                 $this->response($result, 200, [], 'Review updated successfully.');
            }else{
                throw new Exception("Review can not be added || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        
        
    }
    /**
     * @FunctionName : delete_review
     * @params - integer : review_id
     * @params - integer : user_id
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    private function delete_review() {

        try {
            if ($this->get_request_method() != "GET") {
                $this->response([], 405);
            }  
            $data = $this->_request;

            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["review_id", "user_id"]);
            $this->db->beginTransaction();
            $review_id = $this->db->delete('ai_reviews', 'user_id='.$data["user_id"].' and id='.$data["review_id"]);
            $this->db->executeTransaction();
            if($review_id!=''){
                 $this->response([], 200, [], 'Review deleted successfully.');
            }else{
                throw new Exception("Review can not be added || 208");
            }

        } catch (Exception $ex) {
            $this->db->rollBack();
            $error = $ex->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        
        
    }
    /**
     * @FunctionName : get_review
     * @params - integer : user_id
     * @params - integer : post_id
     * @params - integer : page
     * @response - json result 
     * @DateCreated: 02/08/2017
     */
    private function get_review() {

        try {
            if ($this->get_request_method() != "GET") {
                $this->response([], 405);
            }  
            $data = $this->_request;
            
            
            // Required Keys in array
            $this->checkEmptyParameter($data, ["post_id"]);
            
           
            $this->db->beginTransaction();

            $comments_count = $this->db->row("Select COUNT(*)  as total from ai_reviews as c where c.post_id=".$data['post_id']." order by c.id desc");
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

            $comments = $this->db->all_row("Select r.id as review_id,r.user_id,r.post_id,r.review,r.rating,r.status,u.first_name from ai_reviews as r left join ai_user as u on r.user_id=u.user_id where  r.post_id=".$data['post_id']." order by r.id desc LIMIT $offset, $rowsperpage");
 
            $this->db->executeTransaction();

          
            if(!empty($comments)){
                 $page['page'] = $currentpage;
                 $page['next_page'] = $next;
                 $results = $comments;
                 $this->response($results, 200,$page, 'Review results.');
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
