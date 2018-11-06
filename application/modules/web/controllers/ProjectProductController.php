<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class ProjectProductController extends BaseController
{
    /**
     * Post Request Data
     *
     * @var array
     */
    private $postRequest;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function selectProduct($projectId, $level, $applicationId, $roomId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->data['js']       = 'select_product';
            // if ("room-edit" == $this->uri->segment(4)) {
            //     $this->data['js'] = 'select_product_edit';
            // }

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $applicationId = encryptDecrypt($applicationId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'application' => $applicationId, 'room_id' => $roomId];

            $this->validateRoomProducts();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'Application', 'Room']);

            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            $levelCheck = $this->UtilModel->selectQuery('id', 'project_levels', [
                'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            if (empty($levelCheck)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) ||
                (in_array((int)$this->userInfo['user_type'], [INSTALLER,WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id']
                )
            ) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }
                
            $this->load->model("Room");
            $option = ["room_id" => $roomId, "where" => ["application_id" => $applicationId]];
            $this->data['room_id']         = encryptDecrypt($roomId);
            $this->data['project_id']      = encryptDecrypt($projectId);
            $this->data['level']        = $level;
            // $this->data['project_room_id'] = $project_room_id;
            $this->data['application_id']  = encryptDecrypt($applicationId);
            $this->data['room']            = $this->Room->get($option, true);
            $this->data["csrfName"]        = $this->security->get_csrf_token_name();
            $this->data["csrfToken"]       = $this->security->get_csrf_hash();
            website_view('projects/select_product', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    public function productDetails($projectId, $level, $applicationId, $roomId, $productId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo']        = $this->userInfo;
            $this->load->helper('utility');
            //Loading Models
            $this->load->model('Product');
            $this->load->model('ProductTechnicalData');
            $this->load->model('ProductSpecification');
            $this->load->model('ProductRelated');
            $this->load->model('ProductGallery');
            $params                        = [
                'product_id' => $product_id
            ];
            $productData                   = $this->Product->details($params);
            $productTechnicalData          = $this->ProductTechnicalData->get($params);
            $productSpecifications         = $this->ProductSpecification->get($params);
            $relatedProducts               = $this->ProductRelated->get($params);
            $productSpecifications         = array_strip_tags($productSpecifications, ['title']);
            $productTechnicalData          = array_strip_tags($productTechnicalData, ['title', 'info']);
            $productData['body']           = trim(strip_tags($productData['body']));
            $productData['how_to_specity'] = trim(strip_tags($productData['how_to_specity']));
            $this->data['images']          = $this->ProductGallery->get($product_id);

            $this->data['js'] = "article";
            if ("room-edit" == $this->uri->segment(4)) {
                $this->data['js'] = 'article_edit';
            }
            $this->data['project_room_id']  = 1;
            $this->data['product']          = $productData;
            $this->data['product_id']       = $product_id;
            $this->data['application_id']   = $applicationId;
            $this->data['room_id']          = $roomId;
            $this->data['technical_data']   = $productTechnicalData;
            $this->data['specifications']   = $productSpecifications;
            $this->data['related_products'] = $relatedProducts;

            website_view('projects/select_article', $this->data);
        } catch (Exception $ex) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

     /**
     * Validate room products
     *
     * @return void
     */
    private function validateRoomProducts()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'application',
                'label' => 'Application',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'room_id',
                'label' => 'Application',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }
}
