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
            }

            $this->load->model("UtilModel");

            $userData = $this->UtilModel->selectQuery(
                "*",
                "ai_user",
                [
                    "where" => [
                        "user_id" => $userId,
                        "status !=" => DELETED
                    ],
                    "single_row" => true
                ]
            );

            load_alternate_views("users/profile", $this->data);
            if ( empty($userData) ) {
                show_404();
            }
        } catch ( DatabaseExceptions\SelectException $error ) {
            

        }
    }

}