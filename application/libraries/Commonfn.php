<?php

class Commonfn {

    private $androidPushKey = 'AAAAIsWFxxY:APA91bHzE2QOlyhTafy6ND49WyE1gtrgIgF5-1YTz932yiHlz1QpMV6T_IUch_6vctwko2xoALOl3YgZbkxCrXI8N2TjaF-VvwLJMPEo4Ss6YnUOyUlWxY-uDpArbH1QmPZreDpz7F-n';

    public function __construct() {
        ini_set('display_errors', 1);

        $this->CI = & get_instance();
        $this->CI->load->library('pagination');
        $this->CI->load->library('email');
        $this->CI->config->load('email');
        //--------------
    }

    public function iosPush($deviceToken, $payload) {
        $data['aps'] = $payload;
        try {
            /*
             * for developement mode
             */
//            $apnsHost = 'gateway.sandbox.push.apple.com';
            /*
             * production or distribution mode)
             */
            // $apnsHost = 'gateway.push.apple.com'; // distribution
            $apnsHost = 'gateway.sandbox.push.apple.com';
            $apnsPort = '2195';
//            $apnsCert = base_url() . 'public/ckpm/development.pem';
            $apnsCert = getcwd() . '/public/ios/SmartGuide_Development.pem';
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
                die("Push Sending Failed");
            }
            $payload = json_encode($data);

            if (!empty($payload)) {
                try {
                    /*
                     * Device token is of array then send push using loop
                     */
                    if (is_array($deviceToken)) {
                        foreach ($deviceToken as $token) {
                            $apnsMessage = chr(0) . pack("n", 32) . pack('H*', $token) . pack("n", strlen($payload)) . $payload;
                            fwrite($apnsConnection, $apnsMessage);
                        }
                    } else {
                        /*
                         * Device token is single then send push
                         */
                        $token = $deviceToken;
                        $apnsMessage = chr(0) . pack("n", 32) . pack('H*', $token) . pack("n", strlen($payload)) . $payload;
                        if (fwrite($apnsConnection, $apnsMessage)) {
                            return "true";
                        } else {
                            return "false";
                        }
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

    public function androidPush($deviceToken, $payload) {

        $deviceToken = is_array($deviceToken) ? $deviceToken : array($deviceToken);

        $fields = array(
            'registration_ids' => $deviceToken,
            'data' => $payload,
        );

        $pushKey = $this->androidPushKey;

        $headers = array(
            'Authorization: key=' . $pushKey,
            'Content-Type: application/json'
        );

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
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

    public function sendEmailToUser($mailinfoarr) {
        $subject = $mailinfoarr['subject'];
        $issuccess = $this->sendmail($subject, $mailinfoarr, $mailinfoarr['mailerName']);
        return $issuccess;
    }

    private function sendmail($subject, $data, $mailtemplate) {
        $this->CI->email->from($this->CI->config->item('from'), $this->CI->config->item('from_name'));
        $this->CI->email->reply_to($this->CI->config->item('reply_to'), $this->CI->config->item('reply_to_name'));
        $this->CI->email->to($data['email']);
        $this->CI->email->subject($subject);
        $body = $this->CI->load->view('mail/' . $mailtemplate, $data, TRUE);
        $this->CI->email->message($body);
        $status = $this->CI->email->send();
        
        return (bool)$status ? true : false;
    }

    public function sendMailWithAttachment($subject, $data, $mailtemplate, $attachments) {
        $this->CI->email->from($this->CI->config->item('from'), $this->CI->config->item('from_name'));
        $this->CI->email->reply_to($this->CI->config->item('reply_to'), $this->CI->config->item('reply_to_name'));
        $this->CI->email->to($data['email']);
        $this->CI->email->subject($subject);
        $body = $this->CI->load->view('mail/' . $mailtemplate, $data, TRUE);
        $this->CI->email->message($body);

        foreach ($attachments as $attachment) {
            $this->CI->email->attach($attachment['attachment'], '', $attachment['name'], $attachment['mime']);
        }

        $status = $this->CI->email->send();
        
        return (bool)$status ? true : false;
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
        $ext = array_pop($name);
        $fullvideopath = $videopath . $uplodedvideoname;
        $name = $name[0] . '.png';
        $thumbpath = $thumbpath . $name;
        $cmd = "ffmpeg -i " . $fullvideopath . " -ss 00:00:01.435 -vframes 1 " . $thumbpath . "";
        exec($cmd);
        return $name;
    }

    public function upload($tmppath, $uploadpath, $filename) {

        $name = explode('.', $filename);
        $ext = array_pop($name);
        $name = $this->clean($name[0]);
        $filename = $name . '_' . uniqid() . strtotime("now") . '.' . $ext;
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

    public function pagination($pageurl, $totalrows, $limit) {
        $config = array();
        $config["per_page"] = $limit;
        $config['base_url'] = base_url() . $pageurl;
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';
        $config['query_string_segment'] = 'page';
        $config['total_rows'] = $totalrows;
        $config['attributes'] = array('class' => "pagination prolist-pagination");
        $this->CI->pagination->initialize($config);
        return $this->CI->pagination->create_links();
    }
    
    /*
     * Send Push Notification
     */
    public function sendPush($pushData) {

        $whereArr = [];
        $whereArr['where'] = ['user_id' => $pushData['receiver_id']];
        $whereArr['group_by'] = 'device_token';
        $whereArr['order_by'] = ['login_time' => 'desc'];

        $userInfo = $this->CI->Common_model->fetch_data('ai_session', ['user_id', 'platform', 'device_token', 'login_status'], $whereArr);

        foreach ($userInfo as $user) {

            if (!empty($user['device_token']) && strlen($user['device_token']) > 60 && $user['login_status'] == 1) {

                if ($user['platform'] == 1) {
                    $isSuccess = $this->androidPush($user['device_token'], $pushData['androidPayload']);
                } else if ($user['platform'] == 2) {
                    $this->iosPush($user['device_token'], $pushData['iosPayload']);
                }
            }
        }
    }

}

?>
