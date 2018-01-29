<?php

namespace AppInventiv;

//include language file
include 'language/langenglish.php';

class Rest {

    public $_allow = array();
    public $_content_type = "application/json";
    public $_request = array();
    public $_request1 = array();
    public $_apirequestarray = array();
    private $_method = "";
    private $_code = 200;
    private $_parameter = "";

    public function __construct() {

        $this->inputs();
        $this->Apiinputs();
    }

    public function get_referer() {

        return $_SERVER['HTTP_REFERER'];
    }

    public function response($data, $status = 209, $extra = [], $msg = '') {

        $this->_code = ($status) ? $status : 200;
        $this->set_headers();
        if ($msg != '') {
            if (empty($data))
                $data = json_decode("{}");
            $data = json_encode(["error_code" => $this->_code, "error_string" => $msg, "result" => $data, "extraInfo" => $extra]);
            //echo json_encode(["error_code" => $this->_code, "error_string" => $msg, "result" => $data]);

            //$data = $this->encryptResponse($data, '8Tqx(<_8Xh`e"NbL');
            echo $data;
        } else {
            if (empty($data))
                $data = json_decode("{}");
            $data = json_encode(["error_code" => $this->_code, "error_string" => $this->get_status_message(), "result" => $data, "extraInfo" => $extra]);
            // echo json_encode(["error_code" => $this->_code, "error_string" => $this->get_status_message(), "result" => $data]);
            //$data = $this->encryptResponse($data, '8Tqx(<_8Xh`e"NbL');
            echo $data;
        }
        exit;
    }

    function encryptResponse($text) {
        $salt = RESPONSE_SALT;
        $enc_key = RESPONSE_ENC_KEY;
        
        $padding = 16 - (strlen($text) % 16);
        $data = $text . str_repeat(chr($padding), $padding);
       // return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));   
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $salt, $data, MCRYPT_MODE_CBC, $enc_key)));
    }

    private function get_status_message() {
        ini_set('display_errors', '1');
        $status = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            211 => 'Temporary Password is expired',
            212 => 'Temporary Password is wrong',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            418 => 'Required Parameter Missing',
            207 => 'Invalid email address',
            208 => 'User already exists',
            210 => 'Invalid Request',
            212 => 'Invalid User',
            213 => 'Fb User is not registered',
            214 => 'Invalid Schedule',
            215 => 'Empty Data'
        );
        return ($status[$this->_code]) ? $status[$this->_code] : $status[500];
    }

    /**
     * @functionName : lang 
     * @params  : string code 
     * @params  : array parameter 

     */
    public function lang($code) {

        $this->_lang = $code;
        return $this->get_lang();
    }

    public function get_lang() {
        $lang = json_decode(LANGENGLISH);
        $lang = (array) $lang;

        return ($lang[$this->_lang]) ? $lang[$this->_lang] : $lang['FAILURE'];
    }

    private function get_app_parameters($param = "") {
        ini_set('display_errors', '1');

        $this->_parameter = ($param) ? $param : "";

        $parameters = array(
            'realm' => 'Restricted Area',
            'nonce' => '12345',
            'username' => 'admin',
            'password' => 'mypass'
        );
        return ($parameters[$this->_parameter]) ? $parameters[$this->_parameter] : "Wrong Parameters";
    }

    public function get_request_method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    private function inputs() {
        switch ($this->get_request_method()) {
            case "POST":
                $this->_request = $this->cleanInputs($_POST);
                break;
            case "GET":
            case "DELETE":
                $this->_request = $this->cleanInputs($_GET);
                break;
            case "PUT":
                $marray = (array) json_decode(file_get_contents("php://input"));
                // $this->_request = $this->cleanInputs($marray);
                $this->_request1 = $this->cleanInputs($marray);
                break;
            default:
                $this->response('', 406);
                break;
        }
    }

    private function Apiinputs() {
        switch ($this->get_request_method()) {
            case "POST":
                $this->_apirequestarray = serialize($_POST);
                break;
            case "GET":
            case "DELETE":
                $this->_apirequestarray = $this->cleanInputs($_GET);
                break;
            case "PUT":
                parse_str(file_get_contents("php://input"), $this->_request);
                $this->_apirequestarray = $this->cleanInputs($this->_request);
                break;
            default:
                $this->response('', 406);
                break;
        }
    }

    public function isJSON($string) {
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }

    private function cleanInputs($data) {

        $clean_input = array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->cleanInputs($v);
            }
        } elseif (is_object($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->cleanInputs($v);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $data = trim(stripslashes($data));
            }
            $data = strip_tags($data);
            $clean_input = trim($data);
        }

        return $clean_input;
    }

    private function set_headers() {
        header("HTTP/1.1 " . $this->_code . " " . $this->get_status_message());
        header("Content-Type:" . $this->_content_type);
    }

    // For the case of parameter missing
    private function parameterMissing($panel = false) {
        $this->response([], 418, $panel);
    }

    public function checkEmptyParameter($array = [], $required = [], $panel = false) {
        foreach ($required as $req) {
            //echo "<pre>".$req; print_r($array[$req]); die;

            if (!isset($array[$req]) || empty($array[$req])) {
                $this->parameterMissing($req);
            }
        }
    }

    /**
     * @functionName : validateMobile
     * @params  : string mobile
     * @dateCreated: 01/05/2017
     * @dateUpdated: 02/08/2017
     */
    public function validateMobile($mobile) {
        $api_key = "9b210be9be11458c0e7c51defa5f022e";
        $url = "http://apilayer.net/api/validate?access_key=" . $api_key . "&number=" . $mobile . "&format=1";
        $parameter = [];
        $header = [];
        $method = 'GET';
        $intel = $this->apiCall($url, $parameter, $header, $method);
        return $intel;
    }

    /**
     * @functionName : apicall 
     * @params  : string url
     * @params  : array parameter 
     * @params  : array header
     * @params  : string method 
     * @dateCreated: 01/08/2017
     * @dateUpdated: 02/08/2017
     */
    public function apiCall($url = '', $parameter2 = [], $header = [], $method = '') {

        $parameter2 = json_encode($parameter2);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $parameter2,
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $this->response(array('code' => FAILURE, 'message' => $this->lang->line('FAILURE')));
            die;
        } else {
            return json_decode($response, true);
        }
    }

    public function validateData($data = []) {
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'email': $this->validateEmail($value);
                    break;
                default :$this->response('', 406);
                    break;
            }
        }
    }

    private function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $this->response('', 207);
        }
        return false;
    }

    public function defineDefaultValue($keyArr, $data) {
        foreach ($keyArr as $key) {
            if (!isset($data[$key])) {
                if ($key == "ipaddress") {
                    $data[$key] = $_SERVER["REMOTE_ADDR"];
                } else
                    $data[$key] = "";
            }
        }
        return $data;
    }

    public function getgeoip($ipaddress) {

        //echo $ipaddress; die;
        //$gi = new \geoip_open("/usr/local/share/GeoIP/GeoIP.dat",GEOIP_STANDARD);
        $geoIpArr = geoip_record_by_name($ipaddress);
        return $geoIpArr;
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

    public function create_access_token($user_id = '1', $email = 'engineer.nazim@gmail.com') {
        $session_private_key = chr(mt_rand(ord('a'), ord('z'))) . substr(md5(time()), 1);
        $session_public_key = $this->encrypt($user_id . $email, $session_private_key, true);
        $access_token = $session_private_key . "||" . $session_public_key;
        return $access_token;
    }

    private function uploadImage($file, $uploadFolder, $tablename, $table_field, $matchfield, $matchvalue, $key = 'usrImage') {
        $name = $file[$key]['name'];
        $ext = end(explode(".", $name));
        $newfilename = time() . $matchvalue . "." . $ext;
        $newname = $uploadFolder . '/' . $newfilename;
        $file_path = $uploadFolder . '/' . $newfilename;
        $this->basemedia = Zend_Registry::getInstance()->constants->basemedia;
        $this->uploadpath = Zend_Registry::getInstance()->constants->rootmedia;
        $file_path = str_replace($this->uploadpath, $this->basemedia, $file_path);
        if ($file[$key]['tmp_name'] != "") {
            if ((copy($file[$key]['tmp_name'], $newname))) {
                $where = array("field" => $matchfield, "value" => $matchvalue);
                $data_array = array($table_field => $file_path);
                $this->user->update($data_array, $where);
                return $file_path;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function randomAlphaNum($length) {
        // To generate a random alphaNumeric number
        $rangeMin = pow(36, $length - 1); //smallest number to give length digits in base 36
        $rangeMax = pow(36, $length) - 1; //largest number to give length digits in base 36
        $base10Rand = mt_rand($rangeMin, $rangeMax); //get the random number
        $newRand = base_convert($base10Rand, 10, 36); //convert it
        return $newRand; //spit it out
    }

    private function sendMail($email, $message, $subject = 'No Subject', $from = "iKlef", $replyTo = _MAIL_EMAIL) {
        $extraKey = '-f' . _MAIL_EMAIL;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . $from . ' <' . _MAIL_EMAIL . '>' . "\r\n" .
                'Reply-To: ' . $replyTo . '  "\r\n" ' .
                'X-Mailer: ' . 'PHP/' . phpversion();
        return mail($email, $subject, $message, $headers, $extraKey);
    }

    /*
     * 	To send puch message on device(Android and iPhone)
     * 	@param $deviceType string // example android
     * 	@param $deviceToken string 
     * 	@param $payload array 
     */

    private function sendPushMessage($deviceType, $deviceToken, $payload) {
        if (strtolower($deviceType) == 'android') {

            $jsonreturn = $this->andriodPush($deviceToken, $payload);

            $jsonObj = json_decode($jsonreturn);
            $result = $jsonObj->results;
            $key = $result[0];
            if ($jsonObj->failure > 0 and $key->error == 'Unavailable') {
                $this->andriodPush($deviceToken, $payload);
            }
        } else if (strtolower($deviceType) == 'iphone') {

            $payload = json_encode($payload);
            $apnsHost = 'gateway.push.apple.com';
            //$apnsHost = 'gateway.sandbox.push.apple.com';
            $apnsPort = '2195';
            $apnsCert = getcwd() . '/ckpem/ck.pem';
            $passPhrase = '';

            $streamContext = stream_context_create();
            //	echo '<pre>'; print_r(phpinfo());die;
            stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
            $apnsConnection = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext);

            if ($apnsConnection == false) {
                //echo "False";
                // exit;
            }

            $apnsMessage = chr(0) . pack("n", 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n", strlen($payload)) . $payload; //print_r($apnsMessage);

            if (fwrite($apnsConnection, $apnsMessage)) {
                //echo "Done";
            }
            unset($payload);
            fclose($apnsConnection);
        }
    }

    private function andriodPush($deviceToken, $payload) {

        $registrationIDs = array($deviceToken);

        $apiKey = 'AIzaSyB9wu-YiyFknbaaAGkAzu0I0O6Tm6Wxf-E'; //Please change API Key
        $url = 'https://android.googleapis.com/gcm/send';
        $push_data['payload'] = $payload;
        $fields = array(
            'registration_ids' => $registrationIDs,
            'data' => $push_data,
        );
        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        $u = curl_setopt($ch, CURLOPT_URL, $url);
        $p = curl_setopt($ch, CURLOPT_POST, true);
        $f = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $h = curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $t = curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $c = curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $j = curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $jsonn = json_encode($fields);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}

?>
