<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once APPPATH . "libraries/REST_Controller.php";

use DatabaseExceptions\SelectException;

class BaseController extends REST_Controller
{
    protected $datetime;
    protected $header;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(["input_data", "debuging"]);
        $this->load->language("common", 'english');
        $this->load->model("UtilModel");
        $this->datetime = date("Y-m-d H:i:s");
        $this->header = $this->head();
    }

    /**
     * Checks for and validates Access Token
     *
     * @param  string $additionalParams (optional) - additional Field name "name,email"
     * @return array
     */
    protected function accessTokenCheck($additionalParams = "", $options = [])
    {
        $accessToken = "";
        if (isset($this->header["accesstoken"])) {
            $accessToken = $this->header["accesstoken"];
        } elseif (isset($this->header["Accesstoken"])) {
            $accessToken = $this->header["Accesstoken"];
        } else {
            $accessToken = "";
        }
        $accessToken = trim($accessToken);
        $additionalParams = !empty(trim($additionalParams))?"," . trim($additionalParams):"";

        if (empty($accessToken)) {
            $this->response(
                [
                "code" => HTTP_UNAUTHORIZED,
                "api_code_result" => "UNAUTHORIZED",
                "msg" => $this->lang->line("invalid_access_token")
                ],
                HTTP_UNAUTHORIZED
            );
        }

        $accessToken = explode("||", $accessToken);
        if (count($accessToken) !== 2) {
            $this->response(
                [
                "code" => HTTP_UNAUTHORIZED,
                "api_code_result" => "UNAUTHORIZED",
                "msg" => $this->lang->line("invalid_access_token")
                ],
                HTTP_UNAUTHORIZED
            );
        }

        try {
            $userData = $this->UtilModel->selectQuery(
                "u.user_id, u.status as user_status" . $additionalParams,
                "ai_session as a",
                [
                    "where" => ["public_key" => $accessToken[0], "private_key" => $accessToken[1], "login_status" => 1, "u.status !=" => DELETED],
                    "join" => [
                        "ai_user as u" => "u.user_id=a.user_id"
                    ],
                    'single_row' => true
                ]
            );
        } catch (SelectException $error) {
            $this->response(
                [
                "code" => HTTP_INTERNAL_SERVER_ERROR,
                "api_code_result" => "INTERNAL_SERVER_ERROR",
                "msg" => $this->lang->line("internal_server_error")
                ],
                HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if (! $userData) {
            $this->response(
                [
                "code" => HTTP_UNAUTHORIZED,
                "api_code_result" => "UNAUTHORIZED",
                "msg" => $this->lang->line("invalid_access_token")
                ],
                HTTP_UNAUTHORIZED
            );
        }



        if ($userData) {
            if ($userData['user_status'] == BLOCKED) {
                $this->response(
                    [
                    "code" => HTTP_FORBIDDEN,
                    "api_code_result" => "FORBIDDEN",
                    "msg" => $this->lang->line("account_blocked")
                    ],
                    HTTP_FORBIDDEN
                );
            }
    
            return $userData;
        } else {
            $this->response(
                [
                "code" => HTTP_INTERNAL_SERVER_ERROR,
                "api_code_result" => "INTERNAL_SERVER_ERROR",
                "msg" => $this->lang->line("internal_server_error")
                ],
                HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    protected function apiKeyCheck()
    {
        $header = $this->head();
        if (!isset($header["api-key"]) || empty($header["api-key"]) || $header["api-key"] !== API_KEY) {
            $this->response(
                [
                "code" => HTTP_UNAUTHORIZED,
                "api_code_result" => "UNAUTHORIZED",
                "msg" => $this->lang->line("invalid_api_key")
                ],
                HTTP_UNAUTHORIZED
            );
        }
    }

    protected function langcode_validate()
    {
        $language_code = $this->head("X-Language-Code");
        $language_code = trim($language_code);
        $valid_language_codes = ["en","da","nb","sv","fi","fr","nl","de"];

        if (empty($language_code)) {
            $this->response(
                [
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('header_missing'),
                'extra_info' => [
                    "missing_parameter" => "language_code"
                ]
                ]
            );
        }

        if (! in_array($language_code, $valid_language_codes)) {
            $this->response(
                [
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('invalid_header'),
                'extra_info' => [
                    "missing_parameter" => $this->lang->line('invalid_language_code')
                ]
                ]
            );
        }

        $language_map = [
            "en" => "english",
            "da" => "danish",
            "nb" => "norwegian",
            "sv" => "swedish",
            "fi" => "finnish",
            "fr" => "french",
            "nl" => "dutch",
            "de" => "german"
        ];

        $this->load->language("common", $language_map[$language_code]);
        $this->load->language("rest_controller", $language_map[$language_code]);

        return $language_code;
    }

    /**
     * Runs form validation
     *
     * @return string
     */
    protected function validationRun()
    {
        if (! (bool) $this->form_validation->run()) {
            $this->response([
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'msg' => array_shift($this->form_validation->error_array()),
            ]);
        }
    }
}
