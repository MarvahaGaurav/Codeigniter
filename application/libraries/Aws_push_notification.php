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

    /**
     * Send push notifications to user
     *
     * @param array $userIds
     * @param array $payloadData
     * @return void
     */
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

    /**
     * Send silent push notification to user
     *
     * @param array $userIds
     * @param array $payloadData
     * @return void
     */
    public function sendSilentPushNotification($userIds, $payloadData)
    {
        $this->ci->load->model(['User']);

        $userTokens = $this->ci->User->getArnTokens($userIds);

        if (!empty($userTokens)) {
            $arns = array_column($userTokens, 'endpoint_arn');
            $this->payloadData = $payloadData;

            $payload = $this->silentNotificationPayload();

            $this->ci->sns->asyncPublish($arns, $payload);   
        }
    }

    /**
     * Payload for notification
     *
     * @return array    
     */
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

    /**
     * Payload for silent push notifications
     *
     * @return array
     */
    private function silentNotificationPayload()
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
                    "content-available" => 1
                ],
                'data' => $this->payloadData
            ]),
        ];
    }

}

