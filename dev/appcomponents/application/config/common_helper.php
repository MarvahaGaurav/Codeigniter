<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('pr')) {

    function pr($d) {
        echo "<pre>";
        print_r($d);
        echo "</pre>";
        exit();
    }

}


function getConfig($uploadPath, $acptFormat, $maxSize = 3000, $maxWidth = 1024, $maxHeight = 768, $encryptName = TRUE) {
    $config = [];
    $config['upload_path'] = $uploadPath;
    $config['allowed_types'] = $acptFormat;
    $config['max_size'] = $maxSize;
    $config['max_width'] = $maxWidth;
    $config['max_height'] = $maxHeight;
    $config['encrypt_name'] = $encryptName;
    return $config;
}

function create_access_token($user_id = '1', $email = 'dummyemail@gmail.com') {
    $session_private_key = chr(mt_rand(ord('a'), ord('z'))) . substr(md5(time()), 1);
    $session_public_key = encrypt($user_id . $email, $session_private_key, true);
    $access_token['private_key'] = $session_private_key;
    $access_token['public_key'] = $session_public_key;
    return $access_token;
}

function encrypt($text, $salt = 'A3p@pI#%!nVeNiT@#&vNaZiM', $isBaseEncode = true) {
    if ($isBaseEncode) {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    } else {
        return trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
}

function decrypt($text, $salt = 'A3p@pI#%!nVeNiT@#&vNaZiM') {
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

?>
