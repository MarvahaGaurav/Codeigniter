<?php

defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

/**
 * Quick Cal Controller
 */
class ImageController extends BaseController {

    private $request;

    public function __construct($config = 'rest')
    {
        parent::__construct();

    }



    public function index()
    {
        try {

            $this->load->helper("s3_helper");
            $path = s3_image_uploader($_FILES['avatar'], date("YmdHis") . ".png", $_FILES['avatar']['type'], "");
            $data = ["url" => $path];
            echo json_encode($data);
        }
        catch (Exception $ex) {

        }

    }



}
