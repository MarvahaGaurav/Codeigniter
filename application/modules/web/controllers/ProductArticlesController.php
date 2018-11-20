<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class ProductArticlesController extends BaseController
{

    /**
     * Post Request Data
     *
     * @var array
     */
    private $requestData;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function articleDetails($projectId, $level, $applicationId, $roomId, $productId, $mounting, $articleCode)
    {
        try {
            $this->activeSessionGuard();
            $this->load->helper('utility');
            $this->data['js'] = 'article';

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $applicationId = encryptDecrypt($applicationId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");
            $productId = encryptDecrypt($productId, "decrypt");
            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'application' => $applicationId, 'room_id' => $roomId, 'product_id' => $productId, 'mounting' => $mounting, 'article_code' => $articleCode];

            $this->validateArticleDetails();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'Application', 'Room', 'Product', 'ProductSpecification', 'ProductTechnicalData']);

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
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $productData = $this->Product->details([
                'product_id' => $productId
            ]);

            if (empty($productData)) {
                show404($this->lang->line('no_data_found'), base_url(''));
            }

            $articleData = $this->ProductSpecification->getch([
                'product_id' => $productId,
                'single_row' => true,
                'where' => ['articlecode' => $articleCode]
            ]);
            
            if (empty($articleCode)) {
                show404($this->lang->line('no_data_found'), base_url(''));
            }

            $technicalData = $this->ProductTechnicalData->get(['product_id' => $productId]);

            $this->data['technicalData'] = $technicalData;
            $this->data['productData'] = $productData;
            $this->data['articleData'] = $articleData;
            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            $this->data['applicationId'] = encryptDecrypt($applicationId);
            $this->data['roomId'] = encryptDecrypt($roomId);
            $this->data['productId'] = encryptDecrypt($productId);
            $this->data['mounting'] = $mounting;
            $this->data['articleCode'] = $articleCode;

            website_view('projects/article_details', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('home/applications'));
        }
    }


    public function editRoomArticleDetail($projectId, $level, $projectRoomId, $productId, $mounting, $articleCode)
    {
        try {
            $this->activeSessionGuard();
            $this->load->helper('utility');

            $this->data['js'] = "article_edit";

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");
            $productId = encryptDecrypt($productId, "decrypt");
            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'project_room_id' => $projectRoomId, 'product_id' => $productId, 'mounting' => $mounting, 'article_code' => $articleCode];

            $this->validateEditRoomArticleDetail();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'Application', 'Room', 'Product', 'ProductSpecification', 'ProductTechnicalData']);

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
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $productData = $this->Product->details([
                'product_id' => $productId
            ]);

            if (empty($productData)) {
                show404($this->lang->line('no_data_found'), base_url(''));
            }

            $articleData = $this->ProductSpecification->getch([
                'product_id' => $productId,
                'single_row' => true,
                'where' => ['articlecode' => $articleCode]
            ]);
            
            if (empty($articleCode)) {
                show404($this->lang->line('no_data_found'), base_url(''));
            }

            $technicalData = $this->ProductTechnicalData->get(['product_id' => $productId]);

            $this->data['technicalData'] = $technicalData;
            $this->data['productData'] = $productData;
            $this->data['articleData'] = $articleData;
            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            $this->data['projectRoomId'] = encryptDecrypt($projectRoomId);
            $this->data['productId'] = encryptDecrypt($productId);
            $this->data['mounting'] = $mounting;
            $this->data['articleCode'] = $articleCode;


            website_view('projects/article_details_edit', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('home/applications'));
        }
    }

    public function accessoryArticleDetail($projectId, $level, $roomId, $projectRoomId, $productId, $articleCode)
    {
        try {
            $this->activeSessionGuard();
            $this->load->helper('utility');
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('basic-with-font-awesome');

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");
            $productId = encryptDecrypt($productId, "decrypt");
            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'project_room_id' => $projectRoomId, 'room_id' => $roomId, 'product_id' => $productId, 'article_code' => $articleCode];

            $this->validateAccessoryArticleDetail();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'Application', 'Room', 'Product', 'ProductSpecification', 'ProductTechnicalData']);

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
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $productData = $this->Product->details([
                'product_id' => $productId
            ]);

            if (empty($productData)) {
                show404($this->lang->line('no_data_found'), base_url(''));
            }

            $articleData = $this->ProductSpecification->getch([
                'product_id' => $productId,
                'single_row' => true,
                'where' => ['articlecode' => $articleCode]
            ]);
            
            if (empty($articleCode)) {
                show404($this->lang->line('no_data_found'), base_url(''));
            }

            $technicalData = $this->ProductTechnicalData->get(['product_id' => $productId]);

            $articleData['accessory_data'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                'article_code' => $articleData['articlecode'],
                'product_id' => encryptDecrypt($productId),
                'project_room_id' => encryptDecrypt($projectRoomId),
                'project_id' => encryptDecrypt($projectId)
            ]);

            $selectedProduct = $this->UtilModel->selectQuery('id', 'project_room_products', [
                'where' => [
                    'product_id' => $productId, 'article_code' => $articleCode, 'project_room_id' => $projectRoomId
                ]
            ]);

            $this->data['isSelected']  = (bool)!empty($selectedProduct);
            
            $this->data['technicalData'] = $technicalData;
            $this->data['productData'] = $productData;
            $this->data['articleData'] = $articleData;
            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            $this->data['projectRoomId'] = encryptDecrypt($projectRoomId);
            $this->data['productId'] = encryptDecrypt($productId);
            $this->data['roomId'] = encryptDecrypt($roomId);
            $this->data['articleCode'] = $articleCode;

            $this->data['js'] = "accessory_article";




            website_view('projects/article_details_accessory', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('home/applications'));
        }
    }

    private function validateAccessoryArticleDetail()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'project_room_id',
                'label' => 'Project room id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'product_id',
                'label' => 'Product id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'room_id',
                'label' => 'Room id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'article_code',
                'label' => 'Article code',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
        ]);
    }

    private function validateArticleDetails()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project id',
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
                'label' => 'Room id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'product_id',
                'label' => 'Product id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'mounting',
                'label' => 'Mounting',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'article_code',
                'label' => 'Article code',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
        ]);
    }

    private function validateEditRoomArticleDetail()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'project_room_id',
                'label' => 'Project room id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'product_id',
                'label' => 'Product id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'mounting',
                'label' => 'Mounting',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'article_code',
                'label' => 'Article code',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
        ]);
    }

}