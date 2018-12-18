<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseController.php';

use DatabaseExceptions\UpdateException;
use DatabaseExceptions\SelectException;
use DatabaseExceptions\InsertException;
use DatabaseExceptions\DeleteException;
use GuzzleHttp\Client as GuzzleClient;

class TestController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Inspiration');
        $this->load->helper('images');
    }

    public function test_get()
    {
        $this->load->model("ProjectQuotation");

        $data = $this->ProjectQuotation->quotationPriceByProjects([234]);

        print_r($data);
    }

    public function index_post()
    {
        $url = generate_video_thumbnail("https://s3.amazonaws.com/appinventiv-development/smartguide_sample_video.mp4");
        print_r($url);
    }

    public function pushtest_post()
    {
        $post = $this->post("device_token");
        $this->load->library("PushNotification");
        $token = "fR2wzrGPEGo:APA91bHZzVAOjePbfCwP6eSceSmWemsZBFfR_IQlWwYgyU3NKfGU2ewnsBxtg6H8fKlBeBfm5PLJTpY49xBIQ3pCzdBMA-iqA_ODv3OC0F6b8sfmmTKIlCJMz1ZPrq1tYKjfDgufq4Lv";

        $android_data = [
            'badge' => 1,
            'sound' => 'default',
            'status' => 1,
            'type' => "new_employee_request",
            'message' => "{$signupArr['full_name']} has requested your approval",
            'time' => strtotime('now')
        ];

        $this->pushnotification->androidPush($token, $android_data);
    }
}