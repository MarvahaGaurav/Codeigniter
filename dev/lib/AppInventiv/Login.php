<?php


namespace AppInventiv;


use AppInventiv\database\Db;
use AppInventiv\model\Usermodel;
use AppInventiv\Rest;
use \Exception;

class Login extends Rest
{
    private $department;
    private $config;
    private $db;
    private $Usermodel;

    /**
     * @param $department
     * @param array $config
     */
    public function __construct()
    {
		parent::__construct();
		
		$this->db=new Db();
        $this->Usermodel=new Usermodel();
        $this->processApi();
    }
	
	private function processApi(){
		
		$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
		if((int)method_exists($this,$func) > 0)
			$this->$func();
		else
			$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
	}

    
		private function login(){
			
			try {
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response([],405);
			}
			//Get all request Data
			$data=$this->_request;
			//echo "<pre>"; print_r($data); die;
			//Check requirement parameter missing
			//Total Input Array
			// Required Keys in array
			$this->checkEmptyParameter($data, ["email", "password"]);
			
			//Validate Data
			$this->validateData(["email"=>$data["email"]]);
			
			$pArray=["email","password","device_id","device_token","ipaddress","device_model","imei","os_version","platform","network","app_version","longitude","latitude","country_code","region","city","postal_code"
			];
			$data=$this->defineDefaultValue($pArray, $data );
			
			$this->db->beginTransaction();
			$isUserExist=$this->Usermodel->getRecordExists(["email"=>$data['email']]);
			
			if( false === $isUserExist )
			{
				throw new Exception("User does not exists || 210");
			}
			$encrypt_pass=$this->encrypt($data["password"]);
			if($isUserExist["password"]!=$encrypt_pass)
			{
				throw new Exception("Email or password is wrong. || 210");
			}
			if($data["country_code"]=="")
			{
				$data=array_merge((array)$this->getgeoip($_SERVER['REMOTE_ADDR']), $data);
			}
			$user_id=$isUserExist['user_id'];
			$email=$isUserExist['email'];
			$access_token=$this->create_access_token($user_id,$email);
			list($private_key, $public_key)=explode("||", $access_token);
			$session=[
				
				"user_id"=>$user_id,
				"device_id"=>$data["device_id"],
				"device_token"=>$data["device_token"],
				"ipaddress"=>$data["ipaddress"],
				"device_model"=>$data["device_model"],
				"imei"=>$data["imei"],
				"os_version"=>$data["os_version"],
				"platform"=>$data["platform"],
				"network"=>$data["network"],
				"app_version"=>$data['app_version'],
				"login_time"=>time(),
				"country_code"=>$data['country_code'],
				"region"=>$data['region'],
				"city"=>$data['city'],
				"postal_code"=>$data['postal_code'],
				"longitude"=>$data['longitude'],
				"latitude"=>$data['latitude'],
				"public_key"=>$public_key,
				"private_key"=>$private_key
			];
			
			$session=$this->db->insert('ai_session',$session);
			
			$arr=[
				
				"first_name"=>$isUserExist["first_name"],
				"middle_name"=>$isUserExist["middle_name"],
				"last_name"=>$isUserExist["last_name"],
				"email"=>$isUserExist["email"],
				"gender"=>$isUserExist["gender"],
				"biography"=>$isUserExist["biography"],
				"dob"=>$isUserExist["dob"],
				"age"=>$isUserExist["age"],
				"image"=>$isUserExist["image"],
				"access_token"=>$public_key."/*/".$private_key,
				"user_id"=>$user_id
			
			];
			
			//echo "<pre>"; print_r($isUserExist); die;
			
			$this->db->executeTransaction();
			}
			catch(Exception $e)
			{
				$this->db->rollBack();
				$error= $e->getMessage();
				list($msg, $code)=explode(" || ", $error);
				$this->response([], $code, [], $msg);
			}

			// Success
			$this->response($arr, 200, [], 'You have logged in successfully.');
			
		}
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
}


	


