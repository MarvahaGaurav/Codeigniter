<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class User extends BaseModel
{
    public function __construct()
    {
        $this->load->database();
    }
    
    public function login()
    {
        $this->db->select('company_id, user_id,first_name,email,'.
        'IF(image !="",CONCAT("' . IMAGE_PATH . '","",image),"") as image,'.
        'IF(image_thumb !="",CONCAT("' . THUMB_IMAGE_PATH . '","",image_thumb),"") as image_thumb,status,user_type,is_owner')
            ->from("ai_user");
    }

}


$userInfo = $this->Common_model->fetch_data('ai_user', '', array('where' => array('email' => $email, 'password' => $encrypt_pass)), true);