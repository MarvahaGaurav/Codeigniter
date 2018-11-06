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
            $this->load->model("Room");
            $option                        = ["where" => ["room_id" => encryptDecrypt($roomId, 'decrypt'), "application_id" => encryptDecrypt($applicationId, 'decrypt')]];
            $this->data['room_id']         = $roomId;
            // $this->data['project_room_id'] = $project_room_id;
            $this->data['application_id']  = encryptDecrypt($applicationId, 'decrypt');
            $this->data['room']            = $this->Room->get($option, true);
            $this->data["csrfName"]        = $this->security->get_csrf_token_name();
            $this->data["csrfToken"]       = $this->security->get_csrf_hash();
            website_view('projects/select_product', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    public function productDetails()
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
}
