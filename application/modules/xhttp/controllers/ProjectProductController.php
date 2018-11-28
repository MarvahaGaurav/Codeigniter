<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";
require_once APPPATH . "/libraries/Traits/ProjectRequestCheck.php";
require_once APPPATH . "/libraries/Traits/TechnicianChargesCheck.php";

class ProjectProductController extends BaseController
{
    use ProjectRequestCheck, TechnicianChargesCheck;
    private $requestData;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper("location");
    }

    /**
     * Add Product Article
     *
     * @return void
     */
    public function addProductArticle()
    {
        try {
            $this->activeSessionGuard();
            
            $this->requestData = $this->input->post();

            $this->decryptProductArticle();

            $this->validateAddAccessoryProduct();

            $projectData = $this->UtilModel->selectQuery('user_id, id, company_id', 'projects', [
                'where' => ['id' => $this->requestData['project_id']], 'single_row' => true
            ]);

            if (empty($projectData)) {
                json_dump(
                    [
                        "success" => false,
                        "error" => $this->lang->line('no_data_found')
                    ]
                );
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                json_dump(
                    [
                        "success" => false,
                        "error" => $this->lang->line('forbidden_action')
                    ]
                );
            }

            if (in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) {
                $this->handleRequestCheck($projectId, 'xhr');
            }

            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->handleTechnicianChargesCheck($projectId, 'xhr');
            }

            $articleCode = $this->requestData['article_code'];
            $productId = $this->requestData['product_id'];
            $projectRoomId = $this->requestData['project_room_id'];
            $projectId = $this->requestData['project_id'];

            $specificationData = $this->UtilModel->selectQuery('id', 'product_specifications', [
                'where' => ['product_id' => $productId, 'articlecode' => $articleCode], 'single_row' => true
            ]);

            if (empty($specificationData)) {
                json_dump(
                    [
                        "success" => false,
                        "error" => $this->lang->line('bad_request')
                    ]
                );
            }


            $this->load->model(['ProjectRoomProducts']);

            $projectRoomProduct = $this->ProjectRoomProducts->projectRoomProducts([
                'where' => ['product_id' => $productId, 'article_code' => $articleCode, 'project_room_id' => $projectRoomId], 'single_row' => true
            ]);

            if (!empty($projectRoomProduct)) {
                json_dump(
                    [
                        "success" => false,
                        "error" => $this->lang->line('product_already_added')
                    ]
                );
            }

            $this->UtilModel->insertTableData([
                'product_id' => $productId,
                'article_code' => $articleCode,
                'project_room_id' => $projectRoomId,
                'type' => PROJECT_ROOM_ACCESSORY_PRODUCT
            ], 'project_room_products');


            json_dump(
                [
                    "success" => true,
                    "message" => $this->lang->line('product_added_successfully')
                ]
            );

        } catch (\Exception $error) {
            json_dump(
                [
                    "success" => false,
                    "error" => "Internal Server Error",
                ]
            );
        }
    }

    /**
     * Remove product article for a given room
     *
     * @return void
     */
    public function removeProductArticle()
    {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->post();

            $this->decryptRemoveProductParams();

            $this->validateRemoveProducts();

            $this->load->model(['ProjectRooms']);

            $params['where']['pr.id'] = $this->requestData['project_room_id'];

            $projectRooms = $this->ProjectRooms->projectAndRoomData($params);

            if (empty($projectRooms)) {
                json_dump([
                    'success' => false,
                    'error' => $this->lang->line('bad_request')
                ]);
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectRooms['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectRooms['company_id'])) {
                json_dump(
                    [
                        "success" => false,
                        "error" => $this->lang->line('forbidden_action')
                    ]
                );
            }

            $productCheck = $this->UtilModel->selectQuery('id', 'project_room_products', [
                'where' => ['product_id' => $this->requestData['product_id'], 'article_code' => $this->requestData['article_code'], 'project_room_id' => $this->requestData['project_room_id']]
            ]);

            if (empty($productCheck)) {
                json_dump([
                    'success' => true,
                    'message' => $this->lang->line('product_removed')
                ]);
            }

            $this->UtilModel->deleteData('project_room_products', [
                'where' => ['product_id' => $this->requestData['product_id'], 'article_code' => $this->requestData['article_code'], 'project_room_id' => $this->requestData['project_room_id']]
            ]);

            json_dump([
                'success' => true,
                'message' => $this->lang->line('product_removed')
            ]);
        } catch (\Exception $error) {
            json_dump(
                [
                    "success" => false,
                    "error" => "Internal Server Error",
                ]
            );
        }
    }

    /**
     * Decrypt the values
     *
     * @return void
     */
    private function decryptProductArticle()
    {
        if (isset($this->requestData['product_id'])) {
            $this->requestData['product_id'] = encryptDecrypt($this->requestData['product_id'], 'decrypt');
        }
        if (isset($this->requestData['project_room_id'])) {
            $this->requestData['project_room_id'] = encryptDecrypt($this->requestData['project_room_id'], 'decrypt');
        }
        if (isset($this->requestData['project_id'])) {
            $this->requestData['project_id'] = encryptDecrypt($this->requestData['project_id'], 'decrypt');
        }
    }

      /**
     * Decrypts remove products params
     *
     * @return void
     */
    private function decryptRemoveProductParams()
    {
        if (isset($this->requestData['project_room_id'])) {
            $this->requestData['project_room_id'] = encryptDecrypt($this->requestData['project_room_id'], 'decrypt');
        }
        if (isset($this->requestData['product_id'])) {
            $this->requestData['product_id'] = encryptDecrypt($this->requestData['product_id'], 'decrypt');
        }
    }

    /**
     * Valdiate 
     *
     * @return void
     */
    private function validateAddAccessoryProduct()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'article_code',
                'label' => 'Article code',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'product_id',
                'label' => 'Product id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'project_room_id',
                'label' => 'Project room id',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);

        $status = $this->form_validation->run();

        if (!$status) {
            json_dump(
                [
                    "success" => false,
                    "error" => $this->lang->line('bad_request'),
                ]
            );
        }
    }

     /**
     * Validates remvoe products params
     *
     * @return void
     */
    private function validateRemoveProducts()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'article_code',
                'label' => 'Article code',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'product_id',
                'label' => 'Product id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'project_room_id',
                'label' => 'Project room id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
        ]);

        $status = $this->form_validation->run();

        if (!$status) {
            json_dump(
                [
                    "success" => false,
                    "error" => $this->lang->line('bad_request'),
                ]
            );
        }
    }
}
