<?php
defined("BASEPATH") or exit("No direct script access allowed");
require 'BaseController.php';

class AwsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index_post()
    {
        try {
            $userData = $this->accessTokenCheck('a.session_id, a.device_token, a.platform, first_name as full_name');

            $this->load->library(['sns']);

            $sns = $this->sns;

            $result = $sns->addDeviceEndPoint($userData['device_token'], $userData['user_id'], $userData['platform']);

            if ((bool)!$result['success']) {
                throw new \Exception('something went wrong');
            }
            
            $this->UtilModel->updateTableData([
                'endpoint_arn' => $result['result']['EndpointArn']
            ], 'ai_session', [
                'session_id' => $userData['session_id']
            ]);
            
            $this->response([
                'code' => HTTP_OK,
                'msg' => 'done'
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }
}
