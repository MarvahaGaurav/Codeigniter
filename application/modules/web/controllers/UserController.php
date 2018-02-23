<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once "BaseController.php";
class UserController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->active_session_required();
    }

    public function profile($user_id='')
    {
        try{
            $this->data['userInfo'] = $this->userInfo;
            $userId = encryptDecrypt($user_id, 'decrypt');
            if ( !isset($userId) || empty($userId) ) {
                show_404();
                exit;
            }

            $this->load->model("UtilModel");

            $userData = $this->UtilModel->selectQuery(
                "*, cl.name as city_name, country.name as country_name",
                "ai_user",
                [
                    "where" => ["user_id" => $userId, "status !=" => DELETED],
                    "join" => [
                        "city_list as cl" => "cl.id=ai_user.city_id",
                        "country_list as country" => "country.country_code1=ai_user.country_id"
                    ],
                    "single_row" => true
                ]
            );
            // pd($userData);
            if ( empty($userData) ) {
                show_404();
                exit;
            }            
            $this->data['user'] = $userData;
            $userTypeMap = [];
            load_alternate_views("users/profile", $this->data);
            
        } catch ( DatabaseExceptions\SelectException $error ) {
            

        }
    }

}