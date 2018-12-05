<?php

include 'autoload.php';
include 'PHPMailer/PHPMailerAutoload.php';
include 'Commonfunction.php';

$mail = new PHPMailer;


if (isset($_GET)) {
    try {
        $request = $_GET['rquest'];


        $is_authenticated = authenticate();
        //  $is_authenticated = 1;
        if ($is_authenticated) {


            switch ($request) {
                case 'validatephone': new \AppInventiv\ValidatePhone();
                    break;
                case 'signup': new \AppInventiv\Signup();
                    break;
                case 'login' : new \AppInventiv\Login();
                    break;
                case 'changepassword' : new \AppInventiv\Changepassword();
                    break;
                 case 'forgotpassword' : new \AppInventiv\Forgotpassword();
                    break;
                case 'profiledata' : new \AppInventiv\Profiledata();
                    break;
                case 'resetpassword' : new \AppInventiv\Resetpassword();
                    break;
                case 'add_comment' : new \AppInventiv\Comment();
                    break;
                case 'update_comment' : new \AppInventiv\Comment();
                    break;
                case 'delete_comment' : new \AppInventiv\Comment();
                    break;
                case 'get_comment' : new \AppInventiv\Comment();
                    break;
                case 'add_review' : new \AppInventiv\Review();
                    break;
                case 'update_review' : new \AppInventiv\Review();
                    break;
                case 'delete_review' : new \AppInventiv\Review();
                    break;
                case 'get_review' : new \AppInventiv\Review();
                    break;
                case 'send_request' : new \AppInventiv\Friend();
                    break;
                case 'request_response' : new \AppInventiv\Friend();
                    break;
                case 'find_friends' : new \AppInventiv\Friend();
                    break;
                case 'my_friends' : new \AppInventiv\Friend();
                    break;
                case 'do_unfriend' : new \AppInventiv\Friend();
                    break;
                case 'send_request_list' : new \AppInventiv\Friend();
                    break;
                case 'get_request_list' : new \AppInventiv\Friend();
                    break;

                case 'do_follow' : new \AppInventiv\Follow();
                    break;
                case 'find_users' : new \AppInventiv\Follow();
                    break;
                case 'get_follow_list' : new \AppInventiv\Follow();
                    break;
                case 'do_unfollow' : new \AppInventiv\Follow();
                    break;


                default : throw new Exception("Not Found");
                    break;
            }
        } else {
            echo json_encode(["error_code" => 400, "error_string" => 'Unauthorized', "result" => [], "extraInfo" => []]);
        }
    } catch (Exception $e) {
        echo json_encode(["error_code" => 404, "error_string" => $e->getMessage(), "result" => [], "extraInfo" => []]);
    }
}



