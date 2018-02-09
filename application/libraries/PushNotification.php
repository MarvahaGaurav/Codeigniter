<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PushNotification
{
    private $_android_push_key;
    public function __construct()
    {
        $this->_android_push_key = "AAAAIsWFxxY:APA91bHzE2QOlyhTafy6ND49WyE1gtrgIgF5-1YTz932yiHlz1QpMV6T_IUch_6vctwko2xoALOl3YgZbkxCrXI8N2TjaF-VvwLJMPEo4Ss6YnUOyUlWxY-uDpArbH1QmPZreDpz7F-n";
    }

    public function androidPush($deviceToken,$payload)
    {
		// $registrationIDs = array($deviceToken);
                
		$url = 'https://fcm.googleapis.com/fcm/send';
                
		$push_data['data'] = $payload;
		$fields = array(
			'to'  => $deviceToken,
			'data' => $push_data,
		);
		$headers = array(
			'Authorization: key=' . $this->_android_push_key,
			'Content-Type: application/json'
		);
                
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
		json_encode($fields);
                
		$result = curl_exec($ch); 
		curl_close($ch);
                //echo $result; die;
		return $result;
	}

    public function sendMultipleIphonePush($devicedata, $payload)
    {   
        $data['aps'] = $payload;
        // $apnsHost = 'gateway.push.apple.com'; // distribution
        $apnsHost = 'gateway.sandbox.push.apple.com';
        $apnsPort = '2195';
        $apnsCert = getcwd() . "/public/ios/SmartGuide_Development.pem";
        $passPhrase = '1234';
        $streamContext = stream_context_create();
        stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
        stream_context_set_option($streamContext, 'ssl', 'passphrase', $passPhrase);
        $apnsConnection = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
        $payload = json_encode($data);
        
        if (!empty($payload) && !empty($devicedata)) {

            foreach ($devicedata as $val) {

                $token = trim($val['device_token']);

                $apnsMessage = chr(0) . pack("n", 32) . pack('H*', $token) . pack("n", strlen($payload)) . $payload;
                if (fwrite($apnsConnection, $apnsMessage)) {
                    // echo "ERROR: $error - $errorString<br />\n";
                    // exit('done');
                }
            }
        }
        unset($payload);
        fclose($apnsConnection);
    }

    public function androidMultiplePush($deviceToken,$payload)
    {
        if ( ! is_array($deviceToken) && is_string($deviceToken) ) {
            $deviceToken = [$deviceToken];
        }

		$url = 'https://android.googleapis.com/gcm/notification';
                
		$push_data['data'] = $payload;
		$fields = array(
			'registration_ids'  => $deviceToken,
			'data' => $push_data,
		);
		$headers = array(
			'Authorization: key=' . $this->_android_push_key,
			'Content-Type: application/json'
		);
                
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
		json_encode($fields);
                
		$result = curl_exec($ch); 
		curl_close($ch);
                //echo $result; die;
		return $result;
	}

}