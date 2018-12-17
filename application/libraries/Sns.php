<?php

use Aws\Sns\SnsClient;
use Aws\Sns\SnsClient\AwsClient;

class Sns
{
    const APNS_ARN = APNS_ARN;
    const GCM_ARN = GCM_ARN;
    const REGION = SNS_REGION;

    private $topicARN;
    private $sns;
    private $protocol;

    //public function __construct ( $topicARN = "", $protocol = "" )
    public function __construct($config_options = [])
    {
        $this->topicARN = isset($config_options['topic_arn']) ? $config_options['topic_arn'] : '';
        $this->protocol = isset($config_options['protocol']) ? $config_options['protocol'] : '';

        $this->sns = SnsClient::factory(array(
            'credentials' => array(
                'key' => AWS_KEY,
                'secret' => AWS_SECRET,
            ),
            'region' => self::REGION,
            'version' => 'latest'
        ));
    }

    /**
     * Add device end point to paltform
     *
     * @param string $deviceToken
     * @param string $userData
     * @param string $deviceType
     * @param integer $count
     * @return array
     */
    public function addDeviceEndPoint($deviceToken, $userData = "", $deviceType = "2", $count = 0)
    {
        if ($count > 2) {
            return [
                "success" => false,
                "message" => "Error"
            ];
        }
        if ((int)$deviceType === 1) {
            $platformApplicationArn = self::GCM_ARN;
        } else {
            $platformApplicationArn = self::APNS_ARN;
        }

        try {
            $result = $this->sns->createPlatformEndpoint(array(
                'PlatformApplicationArn' => $platformApplicationArn,
                'CustomUserData' => $userData,
                'Token' => $deviceToken,
            ));

            return [
                "success" => true,
                "message" => "OK",
                "result" => $result
            ];
        } catch (\Exception $error) {
            $substr = $this->getStringBetween($error, "Endpoint ", " already exists with the same Token");
            if (!empty($substr) && strpos($substr, 'endpoint') !== false) {
                $this->deleteDeviceEndPoint($substr);
                $this->deleteSubscription($substr);
                $count++;
                return $this->addDeviceEndPoint($deviceToken, $userData, $deviceType, $count);
            } else {
                return [
                    "success" => false,
                    "message" => "Error",
                    "error" => $error
                ];
            }
        }
    }

    /**
     * Delete device end point
     *
     * @param string $deviceArn
     * @return void
     */
    public function deleteDeviceEndPoint($deviceArn)
    {
        try {
            $this->sns->deleteEndpoint([
                "EndpointArn" => $deviceArn
            ]);

            return [
                "success" => true,
                "message" => "OK"
            ];
        } catch (\Exception $error) {
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /**
     * subscribe
     *
     * @param string $endPointArn
     * @param string $topic
     * @return array
     */
    public function subscribeDevicetoTopic($endPointArn, $topic)
    {
        try {
            $result = $this->sns->subscribe([
                'Endpoint' => $endPointArn,
                'Protocol' => "application",
                'TopicArn' => $topic,
            ]);
            return [
                "success" => true,
                "message" => "OK",
                "result" => $result
            ];
        } catch (\Exception $error) {
            return [
                "success" => false,
                "message" => "error",
            ];
        }
    }


    public function deleteSubscription($subscriptionArn)
    {
        try {
            $this->sns->unsubscribe([
                'SubscriptionArn' => $subscriptionArn
            ]);
            return [
                "success" => true,
                "message" => "OK"
            ];
        } catch (\Exception $error) {
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    public function sendMessageByTopic($message, $topicArn)
    {
        try {
            $result = $this->sns->publish([
                'Message' => json_encode($message),
                'MessageStructure' => 'json',
                'TopicArn' => $topicArn
            ]);
            return [
                "success" => true,
                "message" => "OK",
                "result" => $result
            ];
        } catch (\Exception $error) {
            return [
                "success" => false,
                "message" => "error",
                "error" => $error
            ];
        }
    }


    /*randon sent push by admin*/
    public function asyncPublish($arns, $payLoad)
    {
        try {
            $promises = array();
            $i = 0;
            $insert_data = array();

            foreach ($arns as $row => $arn) {
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($payLoad),
                    'MessageStructure' => 'json',
                    'TargetArn' => $arn
                ]);
            }

            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    public function single_push($endpoint_arn, $payload)
    {
        try {
            $promises[] = $this->sns->publishAsync([
                'Message' => json_encode($payload),
                'MessageStructure' => 'json',
                'TargetArn' => $endpoint_arn
            ]);
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    private function getStringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public static function factory()
    {
        return new static();
    }

}
