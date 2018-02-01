<?php 
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists("validated_image")) {
    function validate_image($image)
    {
        $CI = &get_instance();
        $CI->load->language("common", "english");
        $success = true;
        
        $validMimeTypes = ['image/png','image/jpg', 'image/jpeg', 'image/gif'];
        $validExtensions = ['jpg', 'png', 'jpeg', 'gif'];

        if ( !isset($image) || empty($image) ) {
            return [
                "success" => false
            ];
        }

        if ( is_array($image['name'])  ) {
            $imageFile = reArrayFiles($image);
            $mime = [];
            $error_messages = [];
            foreach ( $imageFile as $single_image ) {
                
                $validation_array = validate_single_image($single_image);
                
                if ( ! $validation_array["success"] ) {
                    $success = false;
                    $error_messages[] = $validation_array["message"];
                } else {
                    $mime[] = $validation_array["mime_type"];
                }

            }
            if ( $success ) {
                return [
                    "success" => true,
                    "image_file" => $imageFile,
                    "mime_array" => $mime
                ];
            } else {
                return [
                    "success" => false,
                    "error_messages" => $error_messages
                ];
            }
            
        } else {
            return validate_single_image($image);
        }
    }
       
}


if (!function_exists("reArrayFiles")) {
    function reArrayFiles($file_post) {
    
        $file_ary = [];
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);
    
        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }
        return $file_ary;
    }
}

if ( ! function_exists( "validate_single_image" ) ) {
    function validate_single_image($image) {
        $CI = &get_instance();
        $CI->load->language("common", "english");
        
        if ( !isset($image) || empty($image) ) {
            return [
                "success" => false,
                "message" => $image['name'] . " " . $CI->lang->line("error_uploading_image")
            ];
        }

        $validMimeTypes = ['image/png','image/jpg', 'image/jpeg', 'image/gif'];
        $validExtensions = ['jpg', 'png', 'jpeg', 'gif'];

        $fileMIME = mime_content_type($image['tmp_name']);

        if ( $image["error"] !== UPLOAD_ERR_OK) {
            return [
                "success" => false,
                "message" => $image['name'] . " " . $CI->lang->line("error_uploading_image")
            ];
        } 
        
        if ( ! in_array($fileMIME, $validMimeTypes) ) {
            return [
                "success" => false,
                "code" => NOT_AN_IMAGE,
                "message" => $image['name'] . " " . $CI->lang->line("not_an_image")
            ];
        }
        
        $imageSize = getimagesize($image['tmp_name']);
        
        
        if ( ! $imageSize || null === $imageSize) {
            return [
                "success" => false,
                "code" => NOT_AN_IMAGE,
                "message" => $image['name'] . " " . $CI->lang->line("not_an_image")
            ];
            
        } 
        
        if ( $image['size'] > MAX_IMAGE_SIZE ) {
            return [
                "success" => false,
                "code" => IMAGE_TOO_BIG,
                "message" => $image['name'] . " " . $CI->lang->line("image_too_big")
            ];
            
        } 
        
        $extension = pathinfo($image['name']);
        $extension = $extension["extension"];
        
        if ( ! in_array($extension, $validExtensions) ) {
            return [
                "success" => false,
                "code" => NOT_AN_IMAGE,
                "message" => $image['name'] . " " . $CI->lang->line("not_an_image")
            ];
        }

        return [
            "success" => true,
            "code" => SUCCESS,
            "mime_type" => $fileMIME
        ];
    }   
}