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
/*
 * Custom Rule Validate Phone
 * @param: Phone number
 */

function validate_phone($phone) {
    if (isset($phone) && !preg_match("/^[0-9]{10}$/", $phone) && !empty($phone)) {
        $this->form_validation->set_message('validate_phone', 'This {field} is not valid');
        return FALSE;
    } else {
        return TRUE;
    }
}

/*
 * Custom Rule Validate Dob
 * @param: user dob
 */

function validate_dob($dob) {
    if (isset($dob) && !$this->isValidDateTimeString($dob, 'm/d/Y', 'UTC') && !empty($dob)) {
        $this->form_validation->set_message('validate_dob', 'This {field} should in m/d/Y format');
        return false;
    } else {
        return true;
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

?>
