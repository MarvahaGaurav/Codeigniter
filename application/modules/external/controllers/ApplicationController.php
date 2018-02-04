<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once APPPATH . "libraries/REST_Controller.php";

use GuzzleHttp\Client as GuzzleClient;

class ApplicationController extends REST_Controller
{
    private $http_client;
    public function __construct()
    {
        parent::__construct();
        $this->http_client = new GuzzleClient([
            'base_uri' => "https://sg-as.com/api/v1/en/",
            'timeout' => 60
        ]);
    }

    public function application_post()
    {
        $response = $this->http_client->request(
            'GET',
            'applications',
            [
                'headers' => [
                    'authorization' => 'n6dypPhIi7Gv4l2o2qFf4yPwLIQo2cqo'
                ]
            ]
        );

        $body = $this->http_client->getBody();

        $content = $body->getContent();

        echo $content;
    }
}