<?php
defined("BASEPATH") or exit("Direct script access not allowed");

class Aws_push_notification
{

    private $sns;
    private $ci;
    private $payloadData;

    public function __construct()
    {
        $ci = &get_instance();
        $this->ci = $ci;
        $ci->load->library(['sns']);
    }

    public function sendNotificationToUsers($userIds, $payloadData)
    {
        $this->ci->load->model(['User']);

        $userTokens = $this->ci->User->getArnTokens($userIds);

        if (!empty($userTokens)) {
            $arns = array_column($userTokens, 'endpoint_arn');
            $this->payloadData = $payloadData;

            $payload = $this->payload();

            $this->ci->sns->asyncPublish($arns, $payload);   
        }

    }

    private function payload()
    {
        return [
            'default' => 'SG Notification',
            'GCM' => json_encode([
                'data' => $this->payloadData,
                'notification' => $this->payloadData,
                'priority' => 'high',
            ]),
            'APNS_SANDBOX' => json_encode([
                'aps' => [
                    'alert' => isset($this->payloadData['message'])?$this->payloadData['message']:'SG Notification',
                    'sound' => 'default',
                    'data' => $this->payloadData,
                ],
            ]),
        ];
    }

}

