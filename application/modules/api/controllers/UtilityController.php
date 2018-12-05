<?php 
defined("BASEPATH") or exit("No direct script access allowed");
/**
 * This controller houses utility functionality 
 * 1)Delete Images from s3
  */
require_once 'BaseController.php';

use DatabaseExceptions\UpdateException;
use DatabaseExceptions\SelectException;
use DatabaseExceptions\InsertException;
use DatabaseExceptions\DeleteException;
use Aws\S3\Exception\S3Exception;

class UtilityController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function delete_media_post() 
    {
        $this->load->helper('s3');

        $postData = $this->post();
       
        $deleteErrormedia = [];
        $media = $postData['media'];
        //    print_r($media);die;
        //    print_r($postData);die;
        foreach ($media as $data) {
            try {   
                s3_delete_image($data);
            } catch ( S3Exception $error) {
                $deleteErrormedia[] = [
                   "media" => $data
                ];
            }
        } 
        // print_r($deleteErrormedia);die;
        if ($deleteErrormedia ) {
            try {
                $this->UtilModel->insertBatch($deleteErrormedia, "media_to_delete");
            } catch ( InsertException $error ) {
                $this->response(
                    [
                    'code' => HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "Error",
                    'info' => $error->getMessage()
                    ], HTTP_INTERNAL_SERVER_ERROR
                );
            }
        }

        $this->response(
            [
            'code' => HTTP_OK,
            'message' => "OK"
            ]
        );
    }
}