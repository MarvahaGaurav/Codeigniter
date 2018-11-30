<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';
require_once APPPATH . "/libraries/Traits/TechnicianChargesCheck.php";

class TechnicianChargesController extends BaseController
{
    use TechnicianChargesCheck;
    /**
     * Request Data
     *
     * @var array
     */
    private $requestData;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    /**
     * @SWG\Post(path="/projects/technician/charges",
     *   tags={"Projects"},
     *   summary="Save project final price for project",
     *   description="Save project final price for project when added by an installer",
     *   operationId="addCharges_post",
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
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="project_id",
     *     in="formData",
     *     description="",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="additional_product_charges",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="discount",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=422, description="Validation Errors"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function index_post()
    {
        try {
            $user_data = $this->accessTokenCheck('u.company_id, u.user_lat, u.user_long, u.user_type, u.is_owner');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER]);

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_add', 'project_edit']);

            $this->requestData = $this->post();

            $this->validateAddCharges();

            $this->validationRun();

            $projectId = $this->requestData['project_id'];
            $additionalProductCharges = isset($this->requestData['additional_product_charges'])?$this->requestData['additional_product_charges']:0.00;
            $discount = isset($this->requestData['discount'])?$this->requestData['discount']:0.00;

            $projectData = $this->UtilModel->selectQuery('id, user_id, company_id', 'projects', [
                'where' => ['id' => $projectId], 'single_row' => true
            ]);

            if ((int)$projectData['company_id'] !== (int)$this->user['company_id']) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('forbidden_action')
                ]);
            }

            $this->handleTechnicianChargesCheck($projectId, 'api');

            $insertData = [
                'project_id' => (int)$projectData['id'],
                'company_id' => $this->user['company_id'],
                'user_id' => $this->user['user_id'],
                'language_code' => $language_code,
                'additional_product_charges' => isset($this->requestData['additional_product_charges'])?$this->requestData['additional_product_charges']:0.00,
                'discount' => isset($this->requestData['discount'])?$this->requestData['discount']:0.00,
                'created_at' => $this->datetime,
                'created_at_timestamp' => $this->timestamp,
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => $this->timestamp,
            ];

            $this->UtilModel->insertTableData($insertData, 'project_technician_charges');

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('technician_charges_added')
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    private function validateAddCharges()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'additional_product_charges',
                'label' => 'Additional product charges',
                'rules' => 'trim|numeric|greater_than_equal_to[0]'
            ],
            [
                'field' => 'discount',
                'label' => 'Discount',
                'rules' => 'trim|numeric|greater_than_equal_to[0]|less_than_equal_to[100]'
            ]
        ]);
    }
}
