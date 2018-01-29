<?php

class User_model extends CI_Model {

    public function __construct() {

        //    $this->load->library(array('pagination', 'session','s3'));
        $this->load->library(array('pagination', 'session'));
        $this->load->database();
    }

    /*
     * function : Insert_userData	
     * description : insert_user_into the database create access token and 
     * 	
     *
     */

    public function Insert_userData($user_arr, $session_arr) {

        $return_arr = array();
        $this->db->insert('ai_user', $user_arr);
        $inser_id = $this->db->insert_id();
        $access_token = $this->create_access_token($inser_id, $user_arr["email"]);
        $access_token_gen = $access_token['private_key'] . '||' . $access_token['public_key'];
        // store new insertion 
        $session_arr['user_id'] = $inser_id;
        $session_arr['public_key'] = $access_token['public_key'];
        $session_arr['private_key'] = $access_token['private_key'];

        //unset unnecessary param 
        unset($user_arr['password']);
        $return_arr = $user_arr;
        $return_arr['image'] = (isset($user_arr['image']) && !empty($user_arr['image']))?IMAGE_PATH.$user_arr['image']:"";
        $return_arr['image_thumb'] = (isset($user_arr['image_thumb']) && !empty($user_arr['image_thumb']))?IMAGE_PATH.$user_arr['image_thumb']:"";
        $return_arr['accesstoken'] = $access_token_gen;
        $inser_id = $this->db->insert('ai_session', $session_arr);
        return $return_arr;
    }

    public function encrypt($text, $salt = 'A3p@pI#%!nVeNiT@#&vNaZiM', $isBaseEncode = true) {
        if ($isBaseEncode) {
            return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        } else {
            return trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        }
    }

    public
            function decrypt($text, $salt = 'A3p@pI#%!nVeNiT@#&vNaZiM') {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    public
            function create_access_token($user_id = '1', $email = 'engineer.nazim@gmail.com') {
        $session_private_key = chr(mt_rand(ord('a'), ord('z'))) . substr(md5(time()), 1);
        $session_public_key = $this->encrypt($user_id . $email, $session_private_key, true);
        $access_token['private_key'] = $session_private_key;
        $access_token['public_key'] = $session_public_key;
        return $access_token;
    }

}
