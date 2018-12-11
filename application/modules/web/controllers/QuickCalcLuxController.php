<?php 
defined('BASEPATH') or exit('No direct script access allowed');
require_once "BaseController.php";

class QuickCalcLuxController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['UtilModel']);
        $this->neutralGuard();
    }

    public function luxValues()
    {
        try {
            $this->load->config('css_config');
            // $this->data['css'] = $this->config->item('basic-with-font-awesome');
            $this->data['js'] = 'quickcalc-lux';

            $applicationData = $this->UtilModel->selectQuery(
                'application_id,type,title',
                'applications',
                ['where' => ['language_code' => $this->languageCode]]
            );
    
            // pr($applicationData);
            $this->data['applications'] = $applicationData;

            $this->data['units'] = ["Meter", "Inch", "Yard"];

            $this->data['validation_error_keys'] = [];
            $this->data['showSuspensionHeight'] = false;
            website_view('luxquickcalc/quickcalc', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    public function selectProduct($applicationId, $roomId)
    {
        try {
            if (isset($this->userInfo, $this->userInfo['user_id']) && !empty($this->userInfo['user_id'])) {
                $this->data['userInfo'] = $this->userInfo;
            }
            $this->data['js'] = 'select_product_lux';
            $this->load->model("Room");
            $option = ["where" => ["room_id" => encryptDecrypt($roomId, 'decrypt'), "application_id" => encryptDecrypt($applicationId, 'decrypt')]];
            $this->data['room_id'] = $roomId;
            $this->data['application_id'] = $applicationId;
            $this->data['room'] = $this->Room->get($option, true);
            // pd($this->data['room']);
            $this->data['searchData'] = json_encode([
                'room_id' => $roomId
            ]);
            $this->data["csrfName"] = $this->security->get_csrf_token_name();
            $this->data["csrfToken"] = $this->security->get_csrf_hash();

            website_view('luxquickcalc/select_product', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    public function selectArticle($applicationId, $roomId, $productId)
    {
        try {
            $queryString = $applicationId . '/rooms/' . $roomId .'/products/' . $productId;

            $this->neutralGuard();
            if (isset($this->userInfo, $this->userInfo['user_id']) && !empty($this->userInfo['user_id'])) {
                $this->data['userInfo'] = $this->userInfo;
            }
            $this->load->helper('utility');
            $productId = encryptDecrypt($productId, 'decrypt');
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
            // $productSpecifications         = array_strip_tags($productSpecifications, ['title']);
            // $productTechnicalData          = array_strip_tags($productTechnicalData, ['title', 'info']);
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


            $productSpecifications = array_map(function ($article) {
                $article['image'] = preg_replace("/^\/home\/forge\//", "https://", $article['image']);
                // $article['title'] = trim(strip_tags($article['title']));
                return $article;
            }, $productSpecifications);

            $this->data['js'] = "article_lux";
            $this->data['product'] = $productData;
            $this->data['product_id'] = $productId;
            $this->data['application_id'] = $applicationId;
            $this->data['room_id'] = $roomId;
            $this->data['technical_data'] = $productTechnicalData;
            $this->data['articles'] = $classifiedProductArticles;
            $this->data['related_products'] = $relatedProducts;
            $this->data['urlString'] = $queryString;

            website_view('luxquickcalc/select_article', $this->data);

        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    public function articleDetails($applicationId, $roomId, $productId, $articleCode)
    {
        try {
            $this->load->helper('utility');
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('basic-with-font-awesome');

            $languageCode = "en";
            $productId = encryptDecrypt($productId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");

            $this->validationData = ['room_id' => $roomId, 'product_id' => $productId, 'article_code' => $articleCode];

            $this->validateAccessoryArticleDetail();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }
            // Load models
            $this->load->model(['ProductTechnicalData', 'ProductSpecification', 'Product']);


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
            ]);

            $selectedProduct = $this->UtilModel->selectQuery('id', 'project_room_products', [
                'where' => [
                    'product_id' => $productId, 'article_code' => $articleCode
                ]
            ]);

            $this->data['isSelected'] = (bool)!empty($selectedProduct);

            $this->data['technicalData'] = $technicalData;
            $this->data['productData'] = $productData;
            $this->data['articleData'] = $articleData;
            $this->data['applicationId'] = $applicationId;
            $this->data['productId'] = encryptDecrypt($productId);
            $this->data['roomId'] = encryptDecrypt($roomId);
            $this->data['articleCode'] = $articleCode;
            $this->data['mounting'] = 1;

            $this->data['js'] = "article_quick_cal";

            website_view('luxquickcalc/article_details', $this->data);

        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    private function validateAccessoryArticleDetail()
    {
        $this->load->library(['form_validation']);

        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([

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

}

