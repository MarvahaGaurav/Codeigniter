<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";
require_once APPPATH . "/libraries/Traits/ProjectRequestCheck.php";
require_once APPPATH . "/libraries/Traits/TechnicianChargesCheck.php";

class ProjectProductController extends BaseController
{
    use ProjectRequestCheck, TechnicianChargesCheck;
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

    /**
     * Select product
     *
     * @param string $projectId
     * @param int $level
     * @param string $applicationId
     * @param string $roomId
     * @return void
     */
    public function selectProduct($projectId, $level, $applicationId, $roomId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->data['js'] = 'select_product';
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('search-page');

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
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $this->load->model("Room");
            $this->data['searchData'] = json_encode([
                'room_id' => encryptDecrypt($roomId)
            ]);
            $option = ["room_id" => $roomId, "where" => ["application_id" => $applicationId]];
            $this->data['room_id'] = encryptDecrypt($roomId);
            $this->data['project_id'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            // $this->data['project_room_id'] = $project_room_id;
            $this->data['application_id'] = encryptDecrypt($applicationId);
            $this->data['room'] = $this->Room->get($option, true);
            $this->data["csrfName"] = $this->security->get_csrf_token_name();
            $this->data["csrfToken"] = $this->security->get_csrf_hash();
            website_view('projects/select_product', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    /**
     * Product Details Page
     *
     * @param string $projectId
     * @param int $level
     * @param string $applicationId
     * @param string $roomId
     * @param string $productId
     * @return void
     */
    public function productDetails($projectId, $level, $applicationId, $roomId, $productId, $mounting)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->helper('utility');
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('product-detail');

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $applicationId = encryptDecrypt($applicationId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");
            $productId = encryptDecrypt($productId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'application' => $applicationId, 'room_id' => $roomId, 'product_id' => $productId, 'mounting' => $mounting];

            $this->validateRoomArticles();

            $status = $this->validationRun();

            // dd($this->form_validation->error_array());

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
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }
            
            //Loading Models
            $this->load->model('Product');
            $this->load->model('ProductTechnicalData');
            $this->load->model('ProductSpecification');
            $this->load->model('ProductRelated');
            $this->load->model('ProductGallery');
            $params = [
                'product_id' => $productId
            ];
            $productData = $this->Product->details($params);
            $productTechnicalData = $this->ProductTechnicalData->get($params);
            $productSpecifications = $this->ProductSpecification->get($params);
            $relatedProducts = $this->ProductRelated->get($params);
            $productSpecifications = array_strip_tags($productSpecifications, ['title']);
            $productTechnicalData = array_strip_tags($productTechnicalData, ['title', 'info']);
            $productData['body'] = trim(strip_tags($productData['body']));
            $productData['how_to_specity'] = trim(strip_tags($productData['how_to_specity']));
            $this->data['images'] = $this->ProductGallery->get($productId);

            $classifiedProductArticles = [];

            foreach ($productSpecifications as $article) {
                if (!isset($classifiedProductArticles[$article['colour_temperature']])) {
                    $classifiedProductArticles[$article['colour_temperature']] = [$article];
                } else {
                    array_push($classifiedProductArticles[$article['colour_temperature']], $article);
                }
            }

            $this->data['js'] = "article";
            if ("room-edit" == $this->uri->segment(4)) {
                $this->data['js'] = 'article_edit';
            }
            $this->data['project_room_id'] = 1;
            $this->data['product'] = $productData;
            $this->data['product_id'] = $productId;
            $this->data['room_id'] = encryptDecrypt($roomId);
            $this->data['project_id'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            $this->data['mounting'] = $mounting;
            // $this->data['project_room_id'] = $project_room_id;
            $this->data['application_id'] = encryptDecrypt($applicationId);
            $this->data['technical_data'] = $productTechnicalData;
            $this->data['specifications'] = $productSpecifications;
            $this->data['related_products'] = $relatedProducts;
            $this->data['articles'] = $classifiedProductArticles;

            website_view('projects/select_article', $this->data);
        } catch (Exception $ex) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    /**
     * Select product
     *
     * @param string $projectId
     * @param int $level
     * @param string $roomId
     * @return void
     */
    public function editRoomSelectProduct($projectId, $level, $projectRoomId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->data['js'] = 'select_product_edit';
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('search-page');

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'room_id' => $projectRoomId];

            $this->validateEditRoomProducts();

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

            $projectRoom = $this->UtilModel->selectQuery('id, room_id', 'project_rooms', [
                'where' => ['id' => $projectRoomId], 'single_row' => true
            ]);

            if (empty($projectRoom)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }
 
            $this->data['searchData'] = json_encode([
                'room_id' => encryptDecrypt($projectRoom['room_id'])
            ]);

            $this->load->model("Room");
            $option = ["room_id" => $projectRoom['room_id']];
            $this->data['project_room_id'] = encryptDecrypt($projectRoom['id']);
            $this->data['room_id'] = encryptDecrypt($projectRoom['room_id']);
            $this->data['project_id'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            // $this->data['project_room_id'] = $project_room_id;
            $this->data['room'] = $this->Room->get($option, true);
            $this->data["csrfName"] = $this->security->get_csrf_token_name();
            $this->data["csrfToken"] = $this->security->get_csrf_hash();
            website_view('projects/select_product_edit', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    /**
     * Product Details Page
     *
     * @param string $projectId
     * @param int $level
     * @param string $applicationId
     * @param string $roomId
     * @param string $productId
     * @return void
     */
    public function productDetailsEdit($projectId, $level, $projectRoomId, $productId, $mounting)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->helper('utility');
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('product-detail');

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");
            $productId = encryptDecrypt($productId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'room_id' => $projectRoomId, 'product_id' => $productId, 'mounting' => $mounting];

            $this->validateEditRoomArticles();

            $status = $this->validationRun();

            // dd($this->form_validation->error_array());

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

            $projectRoom = $this->UtilModel->selectQuery('id, room_id', 'project_rooms', [
                'where' => ['id' => $projectRoomId], 'single_row' => true
            ]);

            if (empty($projectRoom)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }
            
            //Loading Models
            $this->load->model('Product');
            $this->load->model('ProductTechnicalData');
            $this->load->model('ProductSpecification');
            $this->load->model('ProductRelated');
            $this->load->model('ProductGallery');
            $params = [
                'product_id' => $productId
            ];
            $productData = $this->Product->details($params);
            $productTechnicalData = $this->ProductTechnicalData->get($params);
            $productSpecifications = $this->ProductSpecification->get($params);
            $relatedProducts = $this->ProductRelated->get($params);
            $productSpecifications = array_strip_tags($productSpecifications, ['title']);
            $productTechnicalData = array_strip_tags($productTechnicalData, ['title', 'info']);
            $productData['body'] = trim(strip_tags($productData['body']));
            $productData['how_to_specity'] = trim(strip_tags($productData['how_to_specity']));
            $this->data['images'] = $this->ProductGallery->get($productId);

            $classifiedProductArticles = [];

            foreach ($productSpecifications as $article) {
                if (!isset($classifiedProductArticles[$article['colour_temperature']])) {
                    $classifiedProductArticles[$article['colour_temperature']] = [$article];
                } else {
                    array_push($classifiedProductArticles[$article['colour_temperature']], $article);
                }
            }

            $this->data['js'] = "article_edit";

            $this->data['project_room_id'] = 1;
            $this->data['product'] = $productData;
            $this->data['product_id'] = $productId;
            $this->data['room_id'] = encryptDecrypt($projectRoom['room_id']);
            $this->data['project_id'] = encryptDecrypt($projectId);
            $this->data['project_room_id'] = encryptDecrypt($projectRoomId);
            $this->data['projectRoomId'] = $projectRoomId;
            $this->data['level'] = $level;
            // $this->data['project_room_id'] = $project_room_id;
            $this->data['technical_data'] = $productTechnicalData;
            $this->data['specifications'] = $productSpecifications;
            $this->data['related_products'] = $relatedProducts;
            $this->data['articles'] = $classifiedProductArticles;
            $this->data['mounting'] = (int)$mounting;

            website_view('projects/select_article_edit', $this->data);
        } catch (Exception $ex) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function AccessoryProduct($projectId, $level, $roomId, $projectRoomId)
    {
        try {
            $this->activeSessionGuard();

            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('basic-with-font-awesome');

            $projectId = encryptDecrypt($projectId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");
            $languageCode = $this->languageCode;

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'room_id' => $roomId, 'project_room_id' => $projectRoomId];

            $this->validateAccessoryProduct();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view', 'project_edit', 'project_add'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'Product']);

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

            $projectRoom = $this->UtilModel->selectQuery('id, room_id', 'project_rooms', [
                'where' => ['id' => $projectRoomId], 'single_row' => true
            ]);

            if (empty($projectRoom)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            if (in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $this->handleRequestCheck($projectId, 'web');
            }

            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->handleTechnicianChargesCheck($projectId, 'web');
            }

            $params['room_id'] = $roomId;

            $data = $this->Product->roomProducts($params);

            if (isset($projectRoomId) && !empty($projectRoomId)) {
                $projectRoomProductData = $this->UtilModel->selectQuery('product_id', 'project_room_products', [
                    'where' => ['project_room_id' => $projectRoomId]
                ]);

                $projectRoomProductIds = array_unique(array_column($projectRoomProductData, 'product_id'));

                $data = array_map(function ($product) use ($projectRoomProductIds) {
                    $product['is_selected'] = (bool)in_array($product['product_id'], $projectRoomProductIds);
                    return $product;
                }, $data);
            }

            $this->data['products'] = $data;
            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['roomId'] = encryptDecrypt($roomId);
            $this->data['projectRoomId'] = encryptDecrypt($projectRoomId);
            $this->data['level'] = $level;

            website_view('projects/accessory_products', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    /**
     * Accessory product detail
     *
     * @param string $projectId
     * @param integer $level
     * @param string $roomId
     * @param string $projectRoomId
     * @param string $productId
     * @return void
     */
    public function accessoryProductDetail($projectId, $level, $roomId, $projectRoomId, $productId)
    {
        try {
            $this->activeSessionGuard();
            $this->load->helper('utility');
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('product-detail');

            $projectId = encryptDecrypt($projectId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");
            $languageCode = $this->languageCode;
            $productId = encryptDecrypt($productId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'room_id' => $roomId, 'project_room_id' => $projectRoomId, 'product_id' => $productId];

            $this->validateAccessoryProductDetails();

            $status = $this->validationRun();

            // dd($this->form_validation->error_array());

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

            $projectRoom = $this->UtilModel->selectQuery('id, room_id', 'project_rooms', [
                'where' => ['id' => $projectRoomId], 'single_row' => true
            ]);

            if (empty($projectRoom)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            if (in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $this->handleRequestCheck($projectId, 'web');
            }

            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->handleTechnicianChargesCheck($projectId, 'web');
            }
            
            //Loading Models
            $this->load->model('Product');
            $this->load->model('ProductTechnicalData');
            $this->load->model('ProductSpecification');
            $this->load->model('ProductRelated');
            $this->load->model('ProductGallery');
            $params = [
                'product_id' => $productId
            ];
            $productData = $this->Product->details($params);
            $productTechnicalData = $this->ProductTechnicalData->get($params);
            $productSpecifications = $this->ProductSpecification->getch($params);
            $relatedProducts = $this->ProductRelated->get($params);
            $productSpecifications = array_strip_tags($productSpecifications, ['title']);
            $productTechnicalData = array_strip_tags($productTechnicalData, ['title', 'info']);
            $productData['body'] = trim(strip_tags($productData['body']));
            $productData['how_to_specity'] = trim(strip_tags($productData['how_to_specity']));
            $this->data['images'] = $this->ProductGallery->get($productId);

            $encProductId = encryptDecrypt($productId);
            $encProjectRoomId = encryptDecrypt($projectRoomId);
            $encProjectId = encryptDecrypt($projectId);


            $productSpecifications = array_map(function ($article) use ($encProductId, $encProjectRoomId, $encProjectId) {
                $article['accessory_data'] = json_encode([
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                    'article_code' => $article['articlecode'],
                    'product_id' => $encProductId,
                    'project_room_id' => $encProjectRoomId,
                    'project_id' => $encProjectId
                ]);
                return $article;
            }, $productSpecifications);

            $classifiedProductArticles = [];

            foreach ($productSpecifications as $article) {
                if (!isset($classifiedProductArticles[$article['colour_temperature']])) {
                    $classifiedProductArticles[$article['colour_temperature']] = [$article];
                } else {
                    array_push($classifiedProductArticles[$article['colour_temperature']], $article);
                }
            }

            $projectRoomProducts = $this->UtilModel->selectQuery('*', 'project_room_products', [
                'where' => ['project_room_id' => $projectRoomId, 'type' => PROJECT_ROOM_ACCESSORY_PRODUCT]
            ]);

            $selectedArticles = array_filter(array_column($projectRoomProducts, 'article_code'));

            $this->data['js'] = "accessory_article";

            $this->data['product'] = $productData;
            $this->data['product_id'] = $productId;
            $this->data['room_id'] = encryptDecrypt($projectRoom['room_id']);
            $this->data['project_id'] = encryptDecrypt($projectId);
            $this->data['project_room_id'] = encryptDecrypt($projectRoomId);
            $this->data['projectRoomId'] = $projectRoomId;
            $this->data['level'] = $level;
            // $this->data['project_room_id'] = $project_room_id;
            $this->data['technical_data'] = $productTechnicalData;
            $this->data['specifications'] = $productSpecifications;
            $this->data['related_products'] = $relatedProducts;
            $this->data['articles'] = $classifiedProductArticles;

            $projectRoomProducts = $this->UtilModel->selectQuery('*', 'project_room_products', [
                'where' => ['project_room_id' => $projectRoomId, 'type' => PROJECT_ROOM_ACCESSORY_PRODUCT]
            ]);

            $this->data['selected_articles'] = $selectedArticles;

            website_view('projects/select_accessory_article', $this->data);
        } catch (Exception $ex) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    /**
     * Displays selected 
     *
     * @return void
     */
    public function selectedProjectProducts($projectId, $level, $roomId, $projectRoomId)
    {
        try {
            $this->activeSessionGuard();

            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('basic-with-font-awesome');

            $projectId = encryptDecrypt($projectId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");
            $languageCode = $this->languageCode;

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'room_id' => $roomId, 'project_room_id' => $projectRoomId];

            $this->validateAccessoryProduct();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view', 'project_edit', 'project_add'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'ProjectRoomProducts']);

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

            $projectRoom = $this->UtilModel->selectQuery('id, room_id', 'project_rooms', [
                'where' => ['id' => $projectRoomId], 'single_row' => true
            ]);

            if (empty($projectRoom)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $get = $this->input->get();

            $params['project_room_id'] = $projectRoomId;

            $this->data['search'] = '';
            if (isset($get['search']) && strlen(trim($get['search'])) > 0) {
                $params['search'] = trim($get['search']);
                $this->data['search'] = $params['search'];
            }

            $data = $this->ProjectRoomProducts->selectedProducts($params);

            $params['room_id'] = $roomId;

            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['roomId'] = encryptDecrypt($roomId);
            $this->data['projectRoomId'] = encryptDecrypt($projectRoomId);
            $this->data['level'] = $level;

            $data = array_map(function ($product) {
                $product['remove_data'] = json_encode([
                    'product_id' => encryptDecrypt($product['product_id']),
                    'article_code' => $product['articlecode'],
                    'project_room_id' => $this->data['projectRoomId'],
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash()
                ]);
                return $product;
            }, $data);

            $this->data['products'] = $data;

            $this->data['quotationRequest'] = [];
            $this->data['hasAddedFinalPrice'] = false;
            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->load->helper(['utility']);
                $this->data['hasAddedFinalPrice'] = $this->hasTechnicianAddedFinalPrice($projectId);
            }
            
            if (in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $this->data['quotationRequest'] = $this->UtilModel->selectQuery('id', 'project_requests', [
                    'where' => ['project_id' => $projectId]
                ]);
            }

            website_view('projects/project_selected_products', $this->data);
        } catch (\Exception $error) {
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
    /**
     * Validate edit room products
     *
     * @return void
     */
    private function validateEditRoomProducts()
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
                'field' => 'room_id',
                'label' => 'Application',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    /**
     * Validate accessory product
     *
     * @return void
     */
    public function validateAccessoryProduct()
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
                'field' => 'room_id',
                'label' => 'Room',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'project_room_id',
                'label' => 'Room',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    /**
     * Validate accessory product details
     *
     * @return void
     */
    private function validateAccessoryProductDetails()
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
                'field' => 'room_id',
                'label' => 'Room',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'project_room_id',
                'label' => 'Room',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'product_id',
                'label' => 'Product',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    /**
     * Validate room articles
     *
     * @return void
     */
    private function validateRoomArticles()
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
                'label' => 'Room',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'product_id',
                'label' => 'Product',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'mounting',
                'label' => 'Mounting',
                'rules' => 'trim|required|regex_match[/^(1|2|3|4|5|6|7)$/]'
            ]
        ]);
    }
    /**
     * Validate room articles Edit
     *
     * @return void
     */
    private function validateEditRoomArticles()
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
                'field' => 'room_id',
                'label' => 'Room',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'product_id',
                'label' => 'Product',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'mounting',
                'label' => 'Mounting',
                'rules' => 'trim|required|regex_match[/^(1|2|3|4|5|6|7)$/]'
            ],

        ]);
    }
}
