<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*********************************************************************
*	This library is used for used common function for web services
*************************************************************************/
class CommonApi
{

	private $_salt = 'R3w@rD#%!dRaWeR@#&rEwArD' ; // for php >5.6, need to 24 chars
	private $_android_push_key = 'AIzaSyDIR0JhiwoVzgIWWylSsOq0h608YADTrvs';
	
	public function Request($key)
        {
		$keyValue = @$_REQUEST[$key];
		$keyValue = isset($keyValue) ? $keyValue : '' ; 
		$encoded = isset($keyValue) ? $keyValue : '' ; 
		if ($encoded) {
			$keyValue = urldecode($keyValue);
		}
		return $keyValue;
	}
	
	
	public function __construct()
	{
		
	}
	
	
	
	public function Decoded()
        {
		$handle = fopen('php://input','r');
		$jsonInput = fgets($handle);
		$decoded = json_decode($jsonInput,true);
		if(isset($decoded['encoded'])){
			foreach($decoded as $key => $val){
				$new_decoded[$key] = urldecode($val);
			}
			return $new_decoded ;
		}else{
			return $decoded ;
		}
	}
	
	
    

        public function encrypt($text, $salt = 'R3w@rD#%!dRaWeR@#&rEwArD', $isBaseEncode = true)
        {
        	if($isBaseEncode) {
            	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt , $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
            } else {
            	return trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt , $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
            }
        }

        public function decrypt($text,  $salt = 'R3w@rD#%!dRaWeR@#&rEwArD')
        {
            return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt , base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        }
        
        
        public function encrypt_new($text, $salt = 'R3w@rD#%!dRaWeR@#&rEwArD')
        {
        	return trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt , $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        }

        public function decrypt_new($text,  $salt = 'R3w@rD#%!dRaWeR@#&rEwArD')
        {
            return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt , ($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
        }


	
	public function sendMail($email, $message, $subject = 'No Subject', $from =  FROM, $replyTo = NO_REPLY)
        {
		$extraKey = '-f'.$replyTo ;
                
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$from.' <'.$replyTo.'>' . "\r\n" ;
                            
                if(is_array($message))
                {
                    $message = json_encode($message);
                }
               
		return mail($email,$subject, $message, $headers,$extraKey);
	}
	
       /**
	* To send push message on device(Android and iPhone)
	* @param $deviceType string // example android
	* @param $deviceToken string 
	* @param $payload array 
      **/
	public function sendPushMessage($deviceType,$deviceToken,$payload)
        {
           
		if (strtolower($deviceType) == 'android') {
                    
			$jsonreturn = $this->andriodPush($deviceToken,$payload);
                        
			$jsonObj = json_decode($jsonreturn);
			$result = $jsonObj->results;
			$key = $result[0];   
                        
			if ($jsonObj->failure > 0 and $key->error == 'Unavailable') {
				$this->andriodPush($deviceToken,$payload);
			}
                        
		} else if (strtolower($deviceType) == 'iphone') {

			$apnsHost = 'gateway.push.apple.com'; // distribution
		        //$apnsHost = 'gateway.sandbox.push.apple.com';
			$apnsPort = '2195';
			$apnsCert = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ck.pem';
			$passPhrase = '12345678';
			
			$streamContext = stream_context_create();
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
			//stream_context_set_option($streamContext, 'ssl', 'passphrase', $passPhrase);
			//stream_context_set_opemption($streamContext, 'ssl', 'allow_self_signed', true);
			//stream_context_set_option($streamContext, 'ssl', 'verify_peer', false);
			
			$apnsConnection = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT , $streamContext);
			if ($apnsConnection === false) {
				echo "ERROR: $error - $errorString<br />\n";
				exit('FALSE');
			}
			
			$data['aps'] = $payload;
			$payload = json_encode($data);
                      
			$apnsMessage = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;

			if (fwrite($apnsConnection, $apnsMessage)) {
				echo "ERROR: $error - $errorString<br />\n";
				exit('done');
			}
			unset($payload);
			fclose($apnsConnection);  
		}
	}
	
	public function andriodPush($deviceToken,$payload)
        {
		$registrationIDs = array($deviceToken);
                
		$url = 'https://android.googleapis.com/gcm/send';
                
		$push_data['payload'] = $payload;
		$fields = array(
			'registration_ids'  => $registrationIDs,
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
        
        /**
        @Controller : Api.php
        @function Name :  sendMultipleIphonePush
        @By = Manvendra Singh Jadaun
        @functionalty - // send iphone push to multiple devices

        */
        
        public function sendMultipleIphonePush($devicedata,$payload )
        {
                
                $data['aps'] = $payload;
                $apnsHost = 'gateway.push.apple.com'; // distribution
                //$apnsHost = 'gateway.sandbox.push.apple.com';
                $apnsPort = '2195';
                $apnsCert = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ck.pem';
                $passPhrase = '';
                $streamContext = stream_context_create();
                stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);  
                $apnsConnection = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);
                $payload = json_encode($data);

                if (!empty($payload) && !empty($devicedata)) {  
                    
                    foreach($devicedata as $val) {
                        
                        $token = trim($val['device_token']); 
                        $apnsMessage = chr(0) . pack("n",32) . pack('H*', $token ) . pack("n",strlen($payload)) . $payload;
                        if (fwrite($apnsConnection, $apnsMessage)) {
                                //echo "ERROR: $error - $errorString<br />\n";
                                 //exit('done');
                        }
                    }
                }
                unset($payload);
                fclose($apnsConnection);
		}
        
        /**
			@Controller : Api.php
			@function Name :  sendMultipleAndroidPush
			@By = Manvendra Singh Jadaun
			@functionalty - // send android push to multiple devices

        */
        	
	public function sendMultipleAndroidPush($registrationIDs,$payload, $extra)
        {
	
		$url = 'https://android.googleapis.com/gcm/send';
                
		$push_data['payload'] = $payload;
                
		$fields = array(
			'registration_ids'  => $registrationIDs,
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
                
		return $result;
	}
        
        
        /**
        @Controller : Api.php
        @function Name :  sendIphonePushToUserWithDifferentPayLod
        @By = Manvendra Singh Jadaun
        @functionalty - // send iphone push to multiple devices

        */
        
        public function sendIphonePushToUserWithDifferentPayLod($data )
        {
                $apnsHost = 'gateway.push.apple.com'; // distribution
                //$apnsHost = 'gateway.sandbox.push.apple.com';
                $apnsPort = '2195';
                $apnsCert = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ck.pem';
                $passPhrase = 'apple';
                $streamContext = stream_context_create();
                stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);  
                stream_context_set_option($streamContext, 'ssl', 'passphrase', $passPhrase);
                $apnsConnection = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);

                if ($data) {  
                    
                    $new_data = array();
                    
                    foreach($data as $val) {
                         
                        $token = trim($val['device_token']); 
                        unset($val['device_token']);
                        $new_data['aps'] = $val;
                        
                        $payload = json_encode($new_data);
                        $apnsMessage = chr(0) . pack("n",32) . pack('H*', $token ) . pack("n",strlen($payload)) . $payload;
                        
                        if (fwrite($apnsConnection, $apnsMessage)) {
                            //echo "ERROR: $error - $errorString<br />\n";
                            //exit('done');
                        } else {
                            //echo "ERROR: $error - $errorString<br />\n";
                            //exit('error');
                        }
                    }
                }
               // unset($payload);
                fclose($apnsConnection);
	}
        
        /**
        @Controller : Api.php
        @function Name :  sendMultipleAndroidPush
        @By = Manvendra Singh Jadaun
        @functionalty - // send android push to multiple devices

        */
        	
	public function sendAndroidPushToUserWithDifferentPayLod($data)
        {
	
		$url = 'https://android.googleapis.com/gcm/send';
                $headers = array(
		                 'Authorization: key=' . $this->_android_push_key,
		                  'Content-Type: application/json'
		          );
                
                $push_data = array();
                
                foreach($data as $key=>$val) {
                    
                    $registrationID = trim($val['device_token']);
		    $registrationID = array($registrationID);
                    unset($val['device_token']);
                    
                    $payload = $val;
                    $push_data['payload'] = $payload;
                    
                    $fields = array(
		               'registration_ids'  => $registrationID,
		               'data'              => $push_data,
		          );
                    
                    $ch = curl_init();
                    curl_setopt( $ch, CURLOPT_URL, $url );
                    curl_setopt( $ch, CURLOPT_POST, true );
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
                    //curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                    curl_setopt($ch, CURLOPT_VERBOSE, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
                   

                    curl_exec($ch);
                    curl_close($ch);

                }
		
                
		
	}

    public function randomstring( $length = 6) 
    {
        return $a = mt_rand(100000,999999); 
    }
    
 
    
    

}
