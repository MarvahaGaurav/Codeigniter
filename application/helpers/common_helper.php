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

function load_views($customView, $data = array()) {
    $CI = &get_instance();
    $CI->load->view('templates/header', $data);
    $CI->load->view($customView, $data);
    $CI->load->view('templates/footer', $data);
}

function load_views_cropper($customView, $data = array()) {
    $CI = &get_instance();
    $CI->load->view('templates/header', $data);
    $CI->load->view($customView, $data);
    $CI->load->view('templates/cropper');
    $CI->load->view('templates/footer', $data);
}
function load_outer_views($customView, $data = array()) {
    $CI = &get_instance();
    $CI->load->view('/admin/header', $data);
    $CI->load->view($customView, $data);
    $CI->load->view('/admin/footer', $data);
}

function load_outerweb_views($customView, $data = array()) {
    $CI = &get_instance();
    $CI->load->view('/index/header', $data);
    $CI->load->view($customView, $data);
    $CI->load->view('/index/footer', $data);
}

/**
 * Loads alternate views which depends on the require js modules
 * @param string $view view file
 * @param array $data data array
 */
function load_alternate_views($view, $data = array()) {
    $CI = &get_instance();
    $CI->load->view('templates/alternate_header', $data);
    $CI->load->view($view, $data);
    $CI->load->view('templates/alternate_footer', $data);
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
    $access_token['private_key'] = base64_encode($session_private_key);
    $access_token['public_key'] = base64_encode($session_public_key);
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

function datetime() {
    return date('Y-m-d H:i:s');
}

function encryptDecrypt($string, $type = 'encrypt') {

    if ($type == 'decrypt') {
        $enc_string = decrypt_with_openssl($string);
    }
    if ($type == 'encrypt') {
        $enc_string = encrypt_with_openssl($string);
    }
    return $enc_string;
}

function decrypt_with_openssl($string, $urldecode = true) {
    $obj = new OpenSSLEncrypt($string);
    $obj->key = OPEN_SSL_KEY;
    $string = str_replace(array('Beee', 'Kiii', 'Per'), array('/', '=', '%'), $string);
    $string = rawurldecode($string);
    $dcrypt = explode(":", $string);
    if (count($dcrypt) != 2) {
        return false;
    }

    $decryptedData = $obj->decrypt($dcrypt[0], $dcrypt[1]);


    return $decryptedData;
}

function encrypt_with_openssl($string, $urlencode = true) {
    $obj = new OpenSSLEncrypt($string);
    $obj->key = OPEN_SSL_KEY;
    $iv = $obj->initializationVector;
    $encryptedData = $obj->encrypt() . ":" . $iv;
    $encryptedData = rawurlencode($encryptedData);
    $encryptedData = str_replace(array('/', '=', '%'), array('Beee', 'Kiii', 'Per'), $encryptedData);
    return $encryptedData;
}

function show404($err_msg = "", $redurl = 'admin/') {
    $err_msg = (empty($err_msg)) ? 'Invalid Request' : $err_msg;
    $jsscript = '';
    $cssstyle = '<link href="/public/css/style.css" rel="stylesheet"><link href="/public/css/media.css" rel="stylesheet">';
    $jsscript = '<script>setTimeout(function(){ window.location.href="' . $redurl . '"; }, 5000);</script>';
    $errorpage_html = '<html><head><title>Smart Guide admin</title>'.$jsscript.' '.$cssstyle.'</head><body>';    
    $errorpage_html .= '<div class="form-section"><div class="form-inner-section"><div class="logo"><img src="/public/images/logo.png" alt="logo"></div><div class="form-wrapper"><div class="login-error"><span class="error"></span></div>';
    $errorpage_html .= '<h1 class="form-heading" style="text-align:center;">'.$err_msg.'</h1>';
    $errorpage_html .= '<div class="form-group" style="text-align:center;"><a class="commn-btn save" style="text-align:center;text-decoration:none;" href='.$redurl.'>Click here to redirect</a></div>';
    $errorpage_html .= '</div></div></div>';
    $errorpage_html .= '</body></html>';
    //echo $err_msg;
    echo $errorpage_html;
    die();
}

function sendPostRequest($data) {
    $header = array();
    $ch = curl_init();
    $timeout = 1;
    curl_setopt($ch, CURLOPT_URL, $data['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $respData = curl_exec($ch);
    curl_close($ch);
    return;
}

function sendGetRequest($data) {

    $header = array();
    $ch = curl_init();
    $timeout = 1;
    curl_setopt($ch, CURLOPT_URL, $data['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return;
}

function isValidDate($date, $format, $timezone = TIMEZONE) {
    $d = DateTime::createFromFormat($format, $date);
    return ($d && $d->format($format) == $date);
}

/*
 * Get Session Info
 */

function setSessionVariables($data, $accessToken) {

    $sessionDataArr = [
        "user_id" => $data['user_id'],
        "device_id" => isset($data["device_id"]) ? trim($data["device_id"]) : "",
        "device_token" => isset($data["device_token"]) ? trim($data["device_token"]) : "",
        "platform" => isset($data["platform"]) ? $data["platform"] : "",
        "login_time" => datetime(),
        "public_key" => isset($accessToken['public_key']) ? $accessToken['public_key'] : "",
        "private_key" => isset($accessToken['private_key']) ? $accessToken['private_key'] : "",
    ];
    return $sessionDataArr;
}

function retrieveEmployeePermission($userId) 
{
    $ci = &get_instance();
    $ci->load->model("Common_model");
    $data = $ci->Common_model->fetch_data(
        "user_employee_permission",
        "quote_view, quote_add, quote_edit, quote_delete," .
            "insp_view, insp_add, insp_edit, insp_delete," .
            "project_view, project_add, project_edit, project_delete",
        ["where" => ["employee_id" => $userId]]
    );
    
    return $data;
}

?>
