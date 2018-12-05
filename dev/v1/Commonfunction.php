<?php

//include_once (__DIR__ . '\AppInventiv\Rest.php');

function http_digest_parse($txt) {
    // protect against missing data
    $needed_parts = array('nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));
    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);
    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }
 

    return $needed_parts ? false : $data;
}

function authenticate() {
   
    $realm = "8AC74BD0018D507238924D65D0184E93";
    //$nonce = md5(time() . $realm);
    // $realm = get_app_parameters('realm');
    //user => password
    $users = array('admin' => 'mypass', 'guest' => 'guest');
    if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
-
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Digest realm="' . $realm .
                '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');

        //print('Text to send if user hits Cancel button');
        return false;
    }



// analyze the PHP_AUTH_DIGEST variable
    
    if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
            !isset($users[$data['username']])) {

        //print('Wrong Credentials! -- 1');
//        print_r($_SERVER['PHP_AUTH_DIGEST']);
        return false;
    }



// generate the valid response
   
    $A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
   
    $A2 = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
   
    $valid_response = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);
    if ($data['response'] != $valid_response) {
     //   print('Wrong Credentials!---2');
        return false;
    }


// ok, valid username & password
    //echo 'You are logged in as: ' . $data['username'];
    return true;
}

//



