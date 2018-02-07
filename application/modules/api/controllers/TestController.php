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

    public function index_post()
    {
        $url = generate_video_thumbnail("https://s3.amazonaws.com/appinventiv-development/smartguide_sample_video.mp4");
        print_r($url);
    }
}