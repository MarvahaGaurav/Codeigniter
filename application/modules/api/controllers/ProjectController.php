<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class ProjectController extends BaseController
{

    /**
     * Request Data
     *
     * @var array
     */
    private $requestData;

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Product");
    }

    /**
     * Save project created by user
     *
     * @return string
     */
    /**
     * @SWG\Post(path="/projects",
     *   tags={"Projects"},
     *   summary="Add project",
     *   description="Add projects",
     *   operationId="projects_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="number",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="name",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="levels",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="address",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="lat",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="lng",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=404, description="No data found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function index_post()
    {
        try {
            $user_data = $this->accessTokenCheck();
            $language_code = $this->langcode_validate();

            $this->requestData = $this->post();

            $this->validateProject();

            if (! (bool) $this->form_validation->run()) {
                $this->response([
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'msg' => array_shift($this->form_validation->error_array()),
                ]);
            }

            $this->requestData = trim_input_parameters($this->requestData);

            $project = [
                'language_code' => $language_code,
                'user_id' => $user_data['user_id'],
                'number' => $this->requestData['number'],
                'name' => $this->requestData['name'],
                'levels' => $this->requestData['levels'],
                'address' => $this->requestData['address'],
                'lat' => $this->requestData['lat'],
                'lng' => $this->requestData['lng'],
                'created_at' => $this->datetime
            ];

            $this->UtilModel->insertTableData($project, 'projects');

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_added')
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Validate project
     *
     * @return void
     */
    private function validateProject()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules([
            [
                'label' => 'Project Number',
                'field' => 'number',
                'rules' => 'trim|required'
            ],
            [
                'label' => 'Project Name',
                'field' => 'name',
                'rules' => 'trim|required'
            ],
            [
                'label' => 'Project Levels',
                'field' => 'levels',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'label' => 'Address',
                'field' => 'address',
                'rules' => 'trim|required'
            ],
            [
                'label' => 'Location',
                'field' => 'lat',
                'rules' => 'trim|required|numeric'
            ],
            [
                'label' => 'Location',
                'field' => 'lng',
                'rules' => 'trim|required|numeric'
            ],
        ]);

        $this->form_validation->set_data($this->requestData);
    }
}
