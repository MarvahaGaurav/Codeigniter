<?php
defined("BASEPATH") or exit("No direct script access allowed");
require 'BaseController.php';

use DatabaseExceptions\InsertException;

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @SWG\Put(path="/user/settings",
     *   tags={"User"},
     *   summary="Edit settings",
     *   description="Edit Settings",
     *   operationId="edit_settings_put",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="Language code",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="company_discount",
     *     in="formData",
     *     description="Company discount",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="language",
     *     in="formData",
     *     description="valid languages as mentioned at the beginning of the docs",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="currency",
     *     in="formData",
     *     description="DKK - Danish Krone, NOK - Norwegian Krone, SEK - Swedish Krona, EUR - Euro",
     *     type="string"
     *   ),
     * @SWG\Response(response=422, description="Missing parameters/Nothing to update/invalid parameters"),
     * @SWG\Response(response=200, description="Settings update"),
     * @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function edit_put()
    {
        try {
            $user_data = $this->accessTokenCheck('company_id');
            $language_code = $this->langcode_validate();

            $request_data = $this->put();
            $request_data = trim_input_parameters($request_data, false);

            $validUpdateKeys = ["language", "currency", "company_discount"];
            foreach ($request_data as $key => $value) {
                if (!in_array($key, $validUpdateKeys)) {
                    unset($request_data[$key]);
                }
            }


            if (empty($request_data)) {
                $this->response(
                    [
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'api_code_result' => 'UNPROCESSABLE_ENTITY',
                    'msg' => $this->lang->line('nothing_to_update')
                    ]
                );
            }

            if (isset($request_data['company_discount']) &&
             !is_numeric($request_data['company_discount']) &&
             (double)$request_data['company_discount'] < 0) {
                $this->response(
                    [
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'api_code_result' => 'UNPROCESSABLE_ENTITY',
                    'msg' => $this->lang->line('invalid_discount')
                    ]
                );
            }



            //used to map request data keys to database keys
            $updateMap = [
                "language" => "language",
                "currency" => "currency"
            ];

            $conditional_maps = [
                "language" => [
                    "pattern" => '/^(en|da|nb|sv|fi|fr|nl|de)$/',
                    "message" => $this->lang->line("invalid_language_code")
                ],
                "currency" => [
                    "pattern" => '/^(DKK|NOK|SEK|EUR)$/',
                    "message" => $this->lang->line("invalid_currency_code")
                ]
            ];

            $this->load->model("User");
            foreach ($request_data as $key => $value) {
                if ($key === "company_discount") {
                    continue;
                }
                if (isset($conditional_maps[$key])
                    && !empty($conditional_maps[$key])
                    && !preg_match($conditional_maps[$key]["pattern"], $value)
                ) {
                    $this->response(
                        [
                        'code' => HTTP_UNPROCESSABLE_ENTITY,
                        'api_code_result' => 'UNPROCESSABLE_ENTITY',
                        'msg' => $conditional_maps[$key]["message"]
                        ]
                    );
                }
                $this->User->{$updateMap[$key]} = $value;
            }

            if (isset($request_data['company_discount'])) {
                $this->load->model("UtilModel");
                $this->UtilModel->updateTableData([
                    'company_discount' => (double)$request_data['company_discount']
                ], 'company_master', [
                    'company_id' => $user_data['company_id']
                ]);
            }

            $where = [
                "ai_user.user_id" => $user_data['user_id']
            ];

            $message = "";
            foreach ($updateMap as $key => $value) {
                if (isset($request_data[$key])) {
                    $message = "_{$key}";
                }
            }

            if (isset($this->User->language) || isset($this->User->currency)) {
                $this->User->update($where);
            }

            $this->response(
                [
                'code' => HTTP_OK,
                'api_code_result' => 'OK',
                'msg' => $this->lang->line("settings_updated" . $message)
                ]
            );
        } catch (\Exception $error) {
            $this->response(
                [
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line('internal_server_error')
                ]
            );
        }
    }

    /**
     * @SWG\Put(path="/user/location",
     *   tags={"User"},
     *   summary="Edit Location",
     *   description="Edit Location",
     *   operationId="edit_location_put",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="Language code",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="lat",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="lng",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     * @SWG\Response(response=422, description="Validation Error"),
     * @SWG\Response(response=200, description="Location updated"),
     * @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function location_put()
    {
        try {
            $user_data = $this->accessTokenCheck();
            $language_code = $this->langcode_validate();

            $this->requestData = $this->put();

            $this->validateLocation();

            if (! (bool) $this->form_validation->run()) {
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($this->form_validation->error_array()),
                ]);
            }

            $this->UtilModel->updateTableData([
                'user_lat' => $this->requestData['lat'],
                'user_long' => $this->requestData['lng']
            ], 'ai_user', [
                'user_id' => $user_data['user_id']
            ]);

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('location_updated'),
            ]);
        } catch (\Exception $error) {
            $this->response(
                [
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line('internal_server_error')
                ]
            );
        }
    }

    /**
     * Validates Location data
     *
     * @return void
     */
    private function validateLocation()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'lat',
                'label' => 'Location',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'lng',
                'label' => 'Location',
                'rules' => 'trim|required|numeric'
            ],

        ]);
    }
}
