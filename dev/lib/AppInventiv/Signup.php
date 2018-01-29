<?php

namespace AppInventiv;

use AppInventiv\Rest;
use AppInventiv\database\Db;
use AppInventiv\model\Usermodel;
use AppInventiv\Uploadexception;

include("Rest.php");

use \Exception;

class Signup extends Rest {

    private $department;
    private $config;
    private $db;
    private $Usermodel;

    /**
     * @param $department
     * @param array $config
     */
    public function __construct() {
        parent::__construct();
        $this->db = new Db();
        $this->Usermodel = new Usermodel();
        $this->processApi();
    }

    private function processApi() {

        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
        if ((int) method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 404);    // If the method not exist with in this class, response would be "Page not found".
    }

    private function signup() {

        try {
            // Cross validation if the request method is POST else it will return "Not Acceptable" status
            if ($this->get_request_method() != "POST") {
                $this->response([], 405);
            }
            //Get all request Data
            $data = $this->_request;

            //Check requirement parameter missing
            //Total Input Array
            // Required Keys in array
            $this->checkEmptyParameter($data, ["email", "password", "first_name"]);

            //Validate Data
            $this->validateData(["email" => $data["email"]]);

            //Possible Array
            $pArray = ["first_name", "middle_name", "last_name", "email", "gender", "biography", "dob", "age", "phone", "password", "username", "image", "device_id", "device_token", "ipaddress", "device_model", "imei", "os_version", "platform", "network", "app_version", "longitude", "latitude", "country_code", "region", "city", "postal_code"
            ];
            $data = $this->defineDefaultValue($pArray, $data);

            if ($data["country_code"] == "") {
                $data = array_merge($data, (array) $this->getgeoip($_SERVER['REMOTE_ADDR']));
            }

            $this->db->beginTransaction();
            $isUserExist = $this->Usermodel->getRecordExists(["email" => $data['email']]);

            if (false !== $isUserExist) {
                throw new Exception("User already exists || 208");
            }

            /**
             * File Upload 
             * ** */
            //echo getcwd()."/uploads/"; die;
            define("UPLOAD_DIR", getcwd() . "/uploads/");

            if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image'])) {
                if ($_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {

                    $format = array("IMAGETYPE_JPG", "3", "IMAGETYPE_JPEG", "2", "1", "IMAGETYPE_PNG");
                    if (!in_array(exif_imagetype($_FILES['profile_image']['tmp_name']), $format)) {
                        throw new Exception("Only Jpg, png file required || 209");
                    }
                    // echo "<pre>"; print_r($isUserExist); die;
                    //uploading successfully done 
                    // ensure a safe filename
                    //$name = preg_replace("/[^A-Z0-9._-]/i", "_", $_FILES["profile_image"]['name']);
                    $filename = explode(".", $_FILES["profile_image"]['name']);
                    $dot_count = count($filename);
                    $ext = $filename[$dot_count - 1];
                    $name = time() . "." . $ext;
                    // don't overwrite an existing file
                    // preserve file from temporary directory
                    $success = move_uploaded_file($_FILES["profile_image"]["tmp_name"], UPLOAD_DIR . $name);
                    if (!$success) {
                        throw new \Exception("User profile image could not be save || 209");
                    }

                    // set proper permissions on the new file
                    chmod(UPLOAD_DIR . $name, 0644);
                } else {
                    throw new Uploadexception($_FILES['profile_image']['error']);
                }
            }

            $user = [
                "first_name" => $data["first_name"],
                "middle_name" => $data["last_name"],
                "last_name" => $data["last_name"],
                "email" => $data["email"],
                "gender" => $data["gender"],
                "biography" => $data["biography"],
                "dob" => $data["dob"],
                "age" => $data["age"],
                "phone" => $data["phone"],
                "country_code" => $data['country_code'],
                "password" => $this->encrypt($data["password"]),
                "username" => $data['username'],
                //"image"=>$name,
                "status" => 1
            ];
            if (isset($name) && !empty($name)) {
                $user["image"] = $name;
            }

            $user_id = $this->db->insert('ai_user', $user);
            $access_token = $this->create_access_token($user_id, $data["email"]);
            list($private_key, $public_key) = explode("||", $access_token);
            $session = [
                "user_id" => $user_id,
                "device_id" => $data["device_id"],
                "device_token" => $data["device_token"],
                "ipaddress" => $data["ipaddress"],
                "device_model" => $data["device_model"],
                "imei" => $data["imei"],
                "os_version" => $data["os_version"],
                "platform" => $data["platform"],
                "network" => $data["network"],
                "app_version" => $data['app_version'],
                //"login_time"=>time(),
                "country_code" => $data['country_code'],
                "region" => $data['region'],
                "city" => $data['city'],
                "postal_code" => $data['postal_code'],
                //"longitude" => $data['longitude'],
                //"latitude" => $data['latitude'],
                //"logout_time"=>
                "public_key" => $public_key,
                "private_key" => $private_key
            ];
            $session = $this->db->insert('ai_session', $session);
            $this->db->executeTransaction();
        } catch (Exception $e) {
            $this->db->rollBack();
            $error = $e->getMessage();
            list($msg, $code) = explode(" || ", $error);
            $this->response([], $code, [], $msg);
        }
        // Success
        $this->response([], 200, [], 'You registration process has been completed.');
    }

    /*
     * 	Encode array into JSON
     */

    private function json($data) {
        if (is_array($data)) {
            return json_encode($data);
        }
    }

}
