<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

use GuzzleHttp\Client as GuzzleClient;

class AppController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Category");
    }

    public function insertApplication_post()
    {
        try {
            $insertData = [];
            foreach ($this->language_code as $language) {
                $response = get_request_handler("{$language}/applications");
                $data = json_decode($response, true);

                $applicationData = array_map(function ($application) use ($language) {
                    $data['application_id'] = $application['id'];
                    $data['type'] =
                        $application['type'] == 'residential'? APPLICATION_RESIDENTIAL: APPLICATION_PROFESSIONAL;
                    $data['title'] = $application['title'];
                    $data['slug'] = preg_replace("/\s+/", "-", trim(strtolower(convert_accented_characters($application['title'])))). "-" . $language;
                    $data['subtitle'] = $application['subTitle'];
                    $data['body'] = $application['body'];
                    $data['language_code'] = $language;
                    $data['image'] = !empty($application['images'])?$application['images'][0]['url']:'';
                    $data['created_at'] = $this->datetime;
                    $data['updated_at'] = $this->datetime;

                    return $data;
                }, $data);
                $insertData = array_merge($insertData, $applicationData);
            }
            $this->UtilModel->insertBatch('applications', $insertData);

            $this->response($insertData);
        } catch (\Exception $error) {
            $this->response([

            ]);
        }
    }

    // private function
}
