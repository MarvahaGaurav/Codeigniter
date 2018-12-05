<?php

class Commonfn {

    private $web_push_key_patientApp = '';
    private $Android_push_key_patientApp = '';
    private $Android_push_key_vendorApp = '';

    public function __construct() {
        ini_set('display_errors', 1);

        $this->CI = & get_instance();
        $this->CI->load->library('pagination');
        $this->CI->load->library('email');
        $this->CI->config->load('email');
        //--------------
    }

    public function getKey() {

        $tripmagic = 'tripmagicmagictrip';
        $tripmagic = base64_encode($tripmagic);
        $tripmagic = md5($tripmagic);
        return $tripmagic;
    }

    public function getunread($userid) {
//        $Common_model $cmn = new Common_model();   
        $notification_count = $this->CI->Common_model->getunreadcount($userid);
        return $notification_count;
    }

    /*
     *  Authorization checking
     */

    public function Authorized() {
        $request = $this->CI->head();
        if (isset($request['Api-Key']) && !empty($request['Api-Key'])) {
            return $request['Api-Key'];
        } else {
            return false;
        }
    }

    public function getemployeesdetail($userid) {
//        echo $userid;die;
        $emplist = $this->CI->Common_model->getemployees($userid);
        return $emplist;
//        print_r($emplist);die;
    }

    public function sendPushMessage($deviceType, $deviceToken, $payload, $usertype) {

        if (strtolower($deviceType) == 'android') {

            $jsonreturn = $this->androidPush($deviceToken, $payload, $usertype);
//            print_r($jsonreturn);die;
            $jsonObj = json_decode($jsonreturn);
            $result = $jsonObj->results;
            $key = $result[0];

            if ($jsonObj->failure > 0 and $key->error == 'Unavailable') {
                $this->androidPush($deviceToken, $payload, $usertype);
            }
        } else if (strtolower($deviceType) == 'iphone') {
            $this->iospush($deviceToken, $payload, $usertype);
        } else if (strtolower($deviceType) == 'website') {
            $this->webPush($deviceToken, $payload, $usertype);
        }
    }

    public function getlocaltime($params) {
        $datetime = new DateTime($params['time']);
        $timezone = new DateTimeZone($params['timezone']);
        $datetime->setTimezone($timezone);
        $localtime = $datetime->format($params['req_time_format']);
        return $localtime;
    }

    public function iospush($deviceToken, $payload_data) {
        $data['aps'] = $payload_data;
        try {
            $apnsHost = 'gateway.sandbox.push.apple.com'; // this is for developement mode 
//            $apnsHost = 'gateway.push.apple.com';  // production mode (distribution mode)
            $apnsPort = '2195';
            $apnsCert = getcwd() . '/public/ckpm/development.pem';
            // $apnsCert = getcwd().'/public/ckpm/distribution.pem';
            $passPhrase = '1234';

            $streamContext = stream_context_create();
            $a = stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
            $a = stream_context_set_option($streamContext, 'ssl', 'passphrase', $passPhrase);

            try {
                $apnsConnection = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
            } catch (Exception $e) {
                echo '<pre>';
                print_r($e);
                die;
            }
            if ($apnsConnection == false) {
                echo "Some Error Occured";
                exit;
            }
            $payload = json_encode($data);
            $token = str_replace(' ', '', $deviceToken);

            if ($token == 'sgdhsfgdfgdjasfhuie7' || strlen($token) < 40) {
                return false;
            }

            if (!empty($payload)) {
                try {
                    $apnsMessage = chr(0) . pack("n", 32) . pack('H*', $token) . pack("n", strlen($payload)) . $payload;
                    if (fwrite($apnsConnection, $apnsMessage)) {
                        return "true";
                    } else {
                        return "false";
                    }
                } catch (Exception $e) {
                    return true;
                }
            }
        } catch (Exception $e) {
            echo "<pre>";
            print_r($e->getMessage());
            die;
        }
    }

    public function androidPush($deviceToken, $payload, $usertype) {

        $registrationIDs = array($deviceToken);

        $url = 'https://android.googleapis.com/gcm/send';

        $push_data['payload'] = $payload;
        $fields = array(
            'registration_ids' => $registrationIDs,
            'data' => $push_data,
        );
        if ($usertype == 1) {
            $androidkey = $this->Android_push_key_patientApp;
        } else {
            $androidkey = $this->Android_push_key_vendorApp;
        }
        $headers = array(
            'Authorization: key=' . $androidkey,
            'Content-Type: application/json'
        );
//        print_r($fields);die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        json_encode($fields);

        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result; die;
        return $result;
    }

    public function webPush($deviceToken, $payload, $usertype) {

        $registrationIDs = array($deviceToken);

        $url = 'https://fcm.googleapis.com/fcm/send';

        $push_data['payload'] = $payload;
        $fields = array(
            'registration_ids' => $registrationIDs,
            'data' => $push_data,
        );
        if ($usertype == 1) {
            $androidkey = $this->web_push_key_patientApp;
        } else {
            $androidkey = $this->web_push_key_patientApp;
        }
        $headers = array(
            'Authorization: key=' . $androidkey,
            'Content-Type: application/json'
        );
//        print_r($fields);die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        json_encode($fields);

        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result; die;
        return $result;
    }

    public function checkParamater($fieldsArr, $post) {

        foreach ($fieldsArr as $val) {
            if (!isset($post[$val]) || empty($post[$val])) {
                return false;
            }
        }
        return true;
    }

    public function sendEmailToUser($mailinfoarr) {
        $subject = $mailinfoarr['subject'];
        $issuccess = $this->sendmail($subject, $mailinfoarr, $mailinfoarr['mailerName']);
        return $issuccess;
    }

    protected function sendmail($subject, $data, $mailtemplate) {
        $this->CI->email->from($this->CI->config->item('from'), $this->CI->config->item('from_name'));
        $this->CI->email->reply_to($this->CI->config->item('reply_to'), $this->CI->config->item('reply_to_name'));
        $this->CI->email->to($data['email']);
        $this->CI->email->subject($subject);
        $body = $this->CI->load->view('mail/' . $mailtemplate, $data, TRUE);
        $this->CI->email->message($body);
        return $this->CI->email->send() ? true : false;
    }

    public function manageprofilepicsocial($image, $thumb = false) {

//        echo $image;die;
        $is_send_image = TRUE;
        //Get the file
        try {
            $content = file_get_contents($image);
        } catch (exception $e) {
            echo '<pre>';
            print_r($e);
            die('df');
        }
//        print_r($content);die('df');
        $ImageFolder = "/public/upload/user_image/";
        $thumb_path = "/public/upload/user_profile/profile_thumb/";
        $height = $width = 150;
        $name = explode('.', $image);
        $ext = array_pop($name);
        $target_path = getcwd() . $thumb_path;
        $destpath = getcwd() . $ImageFolder;
        //Store in the filesystem.
        $picture_name = uniqid('user_') . '_' . strtotime("now") . '_utc.jpg';
        $path = $destpath . "{$picture_name}";
        $fp = fopen($path, "w");
        $st = fwrite($fp, $content);
        if ($st !== false && $thumb) {
            //$picture = PROFILE_IMAGE . $picture_name;
            $this->thumb_create($picture_name, $destpath . $picture_name, $height, $width, $target_path);
            //$thumb_path = 'upload/profile_image/profile_thumb/' . $thumb_image ;
        }
        fclose($fp);
        return $picture_name;
    }

    public function thumb_create($filename, $filepath, $targetpath, $width = 150, $height = 150) {
        try {

            /*             * * a new imagick object ** */
            $im1 = new \Imagick($filepath);

            /*             * * ping the image ** */
            $im1->pingImage($filepath);

            /*             * * read the image into the object ** */
            $im1->readImage($filepath);

            /*             * * thumbnail the image ** */
            $im1->thumbnailImage($width, $height);

            /*             * * Write the thumbnail to disk ** */

            $im1->writeImage($targetpath . $filename);
            //echo $image;die;
            /*             * * Free resources associated with the Imagick object ** */
            $im1->destroy();

            return true;
        } catch (Exception $e) {
            print ($e);
            die;
            return $file;
        }
    }

    public function getthumb($videopath, $uplodedvideoname, $thumbpath) {

        $name = explode('.', $uplodedvideoname);
//        print_r($name);die;
        $ext = array_pop($name);
        $fullvideopath = $videopath . $uplodedvideoname;
        $name = $name[0] . '.png';
        $thumbpath = $thumbpath . $name;
//        shell_exec("ffmpeg -i $fullvideopath -deinterlace -an -ss 11 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumbpath 2>&1");
        $cmd = "ffmpeg -i " . $fullvideopath . " -ss 00:00:01.435 -vframes 1 " . $thumbpath . "";
        exec($cmd);
        return $name;
    }

    public function checkAccessToken($userId, $request = array()) {
        $request = (!empty($request)) ? $request : $this->CI->head();

        if (isset($request['Access-Token']) && !empty($request['Access-Token'])) {
            $result = array();
            $where = array('where' => array('userId' => $userId, 'accessToken' => $request['Access-Token']));
            $table = "oe_users";
            $result = $this->CI->Common_model->fetch_data($table, 'userId', $where);
            $flag = !empty($result) ? true : false;
            return $flag;
        } else {
            return false;
        }
    }

    public function upload($tmppath, $uploadpath, $filename) {

        $name = explode('.', $filename);
//        echo "<pre>"; print_r($name); die;
        $ext = array_pop($name);
        $name = $this->clean($name[0]);
        $filename = $name . '_' . uniqid() . strtotime("now") . '.' . $ext;
        //$target_path = getcwd().$thumb_path;
        //echo $path;die;
        $st = move_uploaded_file($tmppath, $uploadpath . $filename);
        if ($st) {
            return $filename;
        } else {
            return false;
        }
    }

    function clean($string) {
        $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
        $string = str_replace('-', '', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    public function Sendpush($type, $reciever_detail, $sender_detail, $device_detail, $msg = false) {
        //~ echo $type;die;

        $logdataArr = array();
        $logdataArr['sender_detail'] = json_encode($sender_detail);
        $logdataArr['reciever_detail'] = json_encode($reciever_detail);
        $logdataArr['created_date'] = date('Y-m-d h:i:s');
        $this->CI->Common_model->insert_single('notification_log', $logdataArr);
        $payload = $this->getpayload($type, $reciever_detail, $sender_detail, $msg);

        $role_id = isset($reciever_detail['role_id']) ? $reciever_detail['role_id'] : "";

        if ($role_id != 1 && !empty($role_id)) {

            $employees_list = $this->getemployeesdetail($reciever_detail['user_id']);

            if (!empty($employees_list)) {
                foreach ($employees_list as $emplist) {
                    $emp_device_detail = $this->CI->Common_model->gettokenforpush($emplist['user_id']);
                    if (!empty($emp_device_detail)) {
                        $this->sendPushMessage($emp_device_detail['device_type'], $emp_device_detail['device_token'], $payload, 4);
                    }
                }
            }
        }
//        print_r($device_detail);die;
        $this->sendPushMessage($device_detail['device_type'], $device_detail['device_token'], $payload, $role_id);
    }

    public function getpayload($type, $reciever_detail, $sender_detail, $msg = false) {

        $payload = array();
        switch ($type) {
            case 1:
                $substring = ($reciever_detail['like_count'] == 1) ? ' other ' : ' others ';
                if (!$reciever_detail['like_count']) {
                    $payload['message'] = $sender_detail['name'] . POST_LIKE;
                } else if ($reciever_detail['like_count'] >= 1) {
                    $payload['message'] = $sender_detail['name'] . ' and ' . $reciever_detail['like_count'] . $substring . POST_LIKE;
                }
                $payload['post_id'] = $reciever_detail['post_id'];
                break;
            case 2:
                $substring = ($reciever_detail['comment_count'] == 1) ? ' other ' : ' others ';
                if (!$reciever_detail['comment_count']) {
                    $payload['message'] = $sender_detail['name'] . POST_COMMENT;
                } else if ($reciever_detail['comment_count'] >= 1) {
                    $payload['message'] = $sender_detail['name'] . ' and ' . $reciever_detail['comment_count'] . $substring . POST_COMMENT;
                }
                $payload['post_id'] = $reciever_detail['post_id'];
                break;
            case 3:
                $payload['message'] = $sender_detail['name'] . FOLLOW_SUCCESS_MESSAGE;
                $payload['user_token'] = $sender_detail['user_token'];
                $payload['role_id'] = $sender_detail['role_id'];
                $payload['is_distributor'] = $sender_detail['is_distributor'];
                $payload['delivery_services'] = $sender_detail['delivery_services'];
                break;
            case 4:
                $payload['message'] = $sender_detail['name'] . FOLLOW_REQUEST_MESSAGE;
                $payload['user_token'] = $sender_detail['user_token'];
                $payload['role_id'] = $sender_detail['role_id'];
                $payload['is_distributor'] = $sender_detail['is_distributor'];
                $payload['delivery_services'] = $sender_detail['delivery_services'];
                break;
            case 5:
                $payload['message'] = $sender_detail['name'] . FOLLOW_REQUEST_ACCEPT_MESSAGE;
                $payload['user_token'] = $sender_detail['user_token'];
                $payload['role_id'] = $sender_detail['role_id'];
                $payload['is_distributor'] = $sender_detail['is_distributor'];
                $payload['delivery_services'] = $sender_detail['delivery_services'];
                break;
            case 6:
                $payload['message'] = $sender_detail['name'] . CONNECTION_SUCCESS_MESSAGE;
                $payload['user_token'] = $sender_detail['user_token'];
                $payload['role_id'] = $sender_detail['role_id'];
                $payload['is_distributor'] = $sender_detail['is_distributor'];
                $payload['delivery_services'] = $sender_detail['delivery_services'];
                break;
            case 7:
                $payload['message'] = $sender_detail['name'] . CONNECTION_REQUEST_MESSAGE;
                $payload['user_token'] = $sender_detail['user_token'];
                $payload['role_id'] = $sender_detail['role_id'];
                $payload['is_distributor'] = $sender_detail['is_distributor'];
                $payload['delivery_services'] = $sender_detail['delivery_services'];
                break;
            case 8:
                $payload['message'] = $sender_detail['name'] . CONNECTION_REQUEST_ACCEPT_MESSAGE;
                $payload['user_token'] = $sender_detail['user_token'];
                $payload['role_id'] = $sender_detail['role_id'];
                $payload['is_distributor'] = $sender_detail['is_distributor'];
                $payload['delivery_services'] = $sender_detail['delivery_services'];
                break;
            case 9:
                $payload['message'] = $sender_detail['name'] . USER_REVIEW_MESSAGE;
                $payload['user_token'] = $sender_detail['user_token'];
                $payload['role_id'] = $sender_detail['role_id'];
                $payload['is_distributor'] = $sender_detail['is_distributor'];
                $payload['delivery_services'] = $sender_detail['delivery_services'];
                break;
            case 10:
                $payload['message'] = $sender_detail['name'] . PRODUCT_REVIEW_MESSAGE;
                $payload['user_token'] = $reciever_detail['user_token'];
                $payload['product_token'] = $reciever_detail['product_token'];
                $payload['role_id'] = $reciever_detail['role_id'];
                $payload['is_distributor'] = $reciever_detail['is_distributor'];
                $payload['delivery_services'] = $reciever_detail['delivery_services'];
                break;
            case 11:
                $payload['message'] = DROP_REQUEST_MESSAGE;
                $payload['user_token'] = $reciever_detail['user_token'];
                $payload['product_token'] = $reciever_detail['product_token'];
                $payload['role_id'] = $reciever_detail['role_id'];
                $payload['is_distributor'] = $reciever_detail['is_distributor'];
                $payload['delivery_services'] = $reciever_detail['delivery_services'];
                break;
            case 12:
                $payload['message'] = $msg;
                $payload['user_token'] = $reciever_detail['user_token'];
                $payload['product_token'] = $reciever_detail['product_token'];
                $payload['role_id'] = $reciever_detail['role_id'];
                $payload['is_distributor'] = $reciever_detail['is_distributor'];
                $payload['delivery_services'] = $reciever_detail['delivery_services'];
                break;
            case 13:
                $payload['message'] = ADD_PRODUCT_MESSAGE;
                $payload['user_token'] = $reciever_detail['user_token'];
                $payload['product_token'] = $reciever_detail['product_token'];
                $payload['role_id'] = $reciever_detail['role_id'];
                $payload['is_distributor'] = $reciever_detail['is_distributor'];
                $payload['delivery_services'] = $reciever_detail['delivery_services'];
                break;
            case 14:
                $payload['message'] = PERMISSION_UPDATE_MESSAGE;
                $payload['emp_permission'] = $reciever_detail;
                $payload['content-available'] = 1;
                break;
            case 15:
                $payload['message'] = DROP_APPROVAL_MESSAGE;
                $payload['user_token'] = $reciever_detail['user_token'];
                $payload['product_token'] = $reciever_detail['product_token'];
                $payload['role_id'] = $reciever_detail['role_id'];
//                $payload['is_distributor'] = $reciever_detail['is_distributor'];
//                $payload['delivery_services'] = $reciever_detail['delivery_services'];
                break;
            case 16:
                $payload['message'] = OUT_OF_STOCK_MESSAGE;
                $payload['user_token'] = $reciever_detail['user_token'];
                $payload['product_token'] = $reciever_detail['product_token'];
                $payload['role_id'] = $reciever_detail['role_id'];
                break;
            case 17:
                $payload['message'] = INCOMING_CHAT_MESSAGE;
                $payload['name'] = $sender_detail['name'];
                $payload['user_token'] = $sender_detail['user_token'];
                $payload['user_image'] = $sender_detail['user_image'];
                $payload['sender_id'] = $sender_detail['sender_id'];
                $payload['reciever_id'] = $reciever_detail['reciever_id'];
                $payload['role_id'] = $reciever_detail['role_id'];
                $payload['msg_id'] = $sender_detail['msg_id'];
                break;
        }
        $payload['type'] = $type;
        $payload['unread_count'] = $this->getunread($reciever_detail['user_id']);
        $payload['time'] = time();
//        print_r($payload);die;
        return $payload;
    }

    public function pagination($pageurl, $totalrows, $limit) {
        $config = array();
        $config["per_page"] = $limit;
        $config['base_url'] = base_url() . $pageurl;
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['total_rows'] = $totalrows;
        $config['attributes'] = array('class' => "pagination prolist-pagination");
        $this->CI->pagination->initialize($config);
        return $this->CI->pagination->create_links();
    }

    public function encrypt($text, $salt = 'A3p@pI#%!nVeNiT@#&vNaZiM', $isBaseEncode = true) {
        if ($isBaseEncode) {
            return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        } else {
            return trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        }
    }

    public function decrypt($text, $salt = 'A3p@pI#%!nVeNiT@#&vNaZiM') {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

}

?>
