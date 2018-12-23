<?php 
use function GuzzleHttp\json_decode;

defined('BASEPATH') or exit('No direct script access allowed');
require_once "BaseController.php";
require_once APPPATH . "/libraries/Traits/QuickCalc.php";

class QuickCalcLuminaryController extends BaseController
{

    use QuickCalc;

    private $postRequest;
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['UtilModel']);
        $this->neutralGuard();
        $this->data['activePage'] = 'quickcalc';
    }

    public function luxValues()
    {
        try {
            $this->load->config('css_config');
            // $this->data['css'] = $this->config->item('basic-with-font-awesome');
            $this->data['js'] = 'quickcalc-luminary';

            $applicationData = $this->UtilModel->selectQuery(
                'application_id,type,title',
                'applications',
                ['where' => ['language_code' => $this->languageCode]]
            );
    
            // pr($applicationData);
            $this->data['applications'] = $applicationData;

            $this->data['units'] = ["Meter", "Inch", "Yard"];

            $formData = [];
            $this->load->helper(['cookie']);
            $cookie_data = get_cookie("luminary_form_data");
            parse_str($cookie_data, $formData);
            $productData = [];
            $selectedProduct = get_cookie('luminary_selected_product', $productData);
            if (!empty($selectedProduct)) {
                $selectedProduct = json_decode($selectedProduct, true);
            }
            $this->data['application_id'] = '';
            $this->data['rooms'] = [];
            $this->data['redirectData'] = '';

            if (isset($formData['application'])) {
                $this->data['application_id'] = encryptDecrypt($formData['application'], 'decrypt');
                $this->data['rooms'] = $this->roomData($this->data['application_id']);
            }

            $this->data['room_id'] = '';
            if (isset($formData['application'], $formData['room'])) {
                $this->data['room_id'] = $formData['room_id'];
            }

            $this->data['cookieData'] = $formData;
            $this->data['selectedProduct'] = $selectedProduct;
            $this->data['showSuspensionHeight'] = false;

            $this->data['validation_errors'] = [];
            $this->data['validation_error_keys'] = [];
            $this->postRequest = $this->input->post();
            if (!empty($this->postRequest) && !empty($this->data['rooms'])) {
                $this->quickCalcFormHandler($this->data['rooms']);
            }

            if (isset($this->data['selectedProduct']['product_id'])) {
                $mountingTypes = $this->UtilModel->selectQuery('type', 'product_mounting_types', [
                    'where' => ['product_id' => $this->data['selectedProduct']['product_id'], 'type !=' => 0]
                ]);
                $mountingTypes = array_column($mountingTypes, 'type');
                $suspendedFilter = array_filter($mountingTypes, function ($type) {
                    return in_array((int)$type, [MOUNTING_SUSPENDED, MOUNTING_PENDANT], true);
                });
                $this->data['showSuspensionHeight'] = (bool)!empty($suspendedFilter);
            }

            $this->data['validation_error_keys'] = [];
            website_view('luminaryquickcalc/quickcalc', $this->data);
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
            $this->data['js'] = 'select_product_luminary';
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

            website_view('luminaryquickcalc/select_product', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    public function selectArticle($applicationId, $roomId, $productId)
    {
        try {
            $queryString = $applicationId . '/rooms/' . $roomId . '/products/' . $productId;

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

            $this->data['js'] = "article_luminary";
            $this->data['product'] = $productData;
            $this->data['product_id'] = $productId;
            $this->data['application_id'] = $applicationId;
            $this->data['room_id'] = $roomId;
            $this->data['technical_data'] = $productTechnicalData;
            $this->data['articles'] = $classifiedProductArticles;
            $this->data['related_products'] = $relatedProducts;
            $this->data['urlString'] = $queryString;

            website_view('luminaryquickcalc/select_article', $this->data);

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

            $this->data['js'] = "article_luminary";

            website_view('luminaryquickcalc/article_details', $this->data);

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

    /**
     *
     * @param type $project_room_id
     */
    public function view_result()
    {
        try {
            $roomData = $this->session->flashdata('luminaryRoomData');
            $productData = $this->session->flashdata("luminaryProduct");

            if (empty($roomData) || empty($productData)) {
                $this->session->set_flashdata("flash-message", $this->lang->line('calculate_again'));
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url(''));
            }

            $roomData = json_decode($roomData, true);
            $productData = json_decode($productData, true);

            $dialuxData = $this->fetchQuickCalcData($roomData, $productData['uld']);
            $dialuxData = json_decode($dialuxData, true);

            $roomData['front_view'] = $dialuxData['projectionFront'];
            $roomData['side_view'] = $dialuxData['projectionSide'];
            $roomData['top_view'] = $dialuxData['projectionTop'];
            
            $this->load->model("UtilModel");
            $this->data['specifications'] = $this->UtilModel->selectQuery(
                "*",
                "product_specifications",
                ["single_row" => true, "where" => ["articlecode" => $productData['articlecode'], "product_id" => $productData['product_id']]]
            );
//            echo $this->db->last_query();
            $this->data['room_data'] = $roomData;
            $this->data["csrfName"] = $this->security->get_csrf_token_name();
            $this->data["csrfToken"] = $this->security->get_csrf_hash();
            website_view('quickcalc/evaluation_result', $this->data);
        } catch (Exception $ex) {
        }
    }

    private function quickCalcFormHandler($room)
    {
        $this->load->library(['form_validation']);
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($this->postRequest);

        $this->form_validation->set_rules($this->validateQuickCalcForm());

        $validData = (bool)$this->form_validation->run();

        $uld = "";
        if (isset($this->postRequest['product_id'], $this->postRequest['article_code'])) {
            $uld = $this->UtilModel->selectQuery('uld', 'product_specifications', [
                'where' => [
                    'product_id' => $this->postRequest['product_id'],
                    'articlecode' => $this->postRequest['article_code']
                ],
                'single_row' => true
            ]);

            $uld = $uld['uld'];
        }
        if (empty($uld)) {
            $this->session->set_flashdata("flash-message", $this->lang->line('we_are_unable_to_get_data'));
            $this->session->set_flashdata("flash-type", "danger");
            redirect(base_url(uri_string()));
        }


        if ($validData) {
            $this->load->helper(['utility', 'input_data', 'quick_calc']);
            $length = convert_to_meter($this->postRequest['length_unit'], $this->postRequest['length']);
            $width = convert_to_meter($this->postRequest['width_unit'], $this->postRequest['width']);
            $height = convert_to_meter($this->postRequest['height_unit'], $this->postRequest['height']);
            $this->postRequest = trim_input_parameters($this->postRequest, false);
            $insert = [
                // "application_id" => $this->postRequest['application_id'],
                "room_id" => $this->postRequest['room_id'],
                "name" => $this->postRequest['name'],
                "length" => $length,
                "width" => $width,
                "height" => $height,
                "maintainance_factor" => isset($this->postRequest['maintainance_factor']) && !empty($this->postRequest['maintainance_factor']) ? $this->postRequest['maintainance_factor'] : $room['maintainance_factor'],
                "shape" => "Rectangular",
                "suspension_height" => isset($this->postRequest['pendant_length']) ? convert_to_meter($this->postRequest['pendant_length_unit'], $this->postRequest['pendant_length']) : 0.00,
                "working_plane_height" => isset($this->postRequest['room_plane_height']) ? $this->postRequest['room_plane_height'] / 100 : 0.00, //need to confirm
                "rho_wall" => isset($this->postRequest['rho_wall']) && !empty($this->postRequest['rho_wall']) ? $this->postRequest['rho_wall'] : $room['reflection_values_wall'],
                "rho_ceiling" => isset($this->postRequest['rho_ceiling']) && !empty($this->postRequest['rho_ceiling']) ? $this->postRequest['rho_ceiling'] : $room['reflection_values_ceiling'],
                "rho_floor" => isset($this->postRequest['rho_floor']) && !empty($this->postRequest['rho_floor']) ? $this->postRequest['rho_floor'] : $room['reflection_values_floor'],
                "lux_value" => isset($this->postRequest['lux_values']) && !empty($this->postRequest['lux_values']) ? $this->postRequest['lux_values'] : $room['lux_values'],
                "luminaries_count_x" => $this->postRequest['room_luminaries_x'],
                "luminaries_count_y" => $this->postRequest['room_luminaries_y'],
                "fast_calc_response" => '',
                "created_at" => $this->datetime,
                'type' => $this->postRequest['type'],
                'product_id' => $this->postRequest['product_id'],
                'article_code' => $this->postRequest['article_code']
            ];

            $response = $this->fetchQuickCalcData($insert, $uld);
            $quickCalcRoomData = json_encode($insert);
            $decodedResponse = json_decode($response, true);
            if (!isset($decodedResponse['projectionTop'], $decodedResponse['projectionSide'], $decodedResponse['projectionFront'])) {
                $this->session->set_flashdata("flash-message", $this->lang->line('we_are_unable_to_get_data'));
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url(uri_string()));
            }

            $luxProductData = json_encode([
                'product_id' => $this->postRequest['product_id'],
                'articlecode' => $this->postRequest['article_code'],
                'uld' => $uld
            ]);

            $this->session->set_flashdata("luminaryRoomData", $quickCalcRoomData);
            $this->session->set_flashdata("luminaryProduct", $luxProductData);

            $this->load->helper('cookie');
            delete_cookie('luminary_form_data');
            delete_cookie('luminary_selected_product');

            $this->session->set_flashdata("flash-message", $this->lang->line("room_calculated"));
            $this->session->set_flashdata("flash-type", "success");
            redirect(base_url('home/fast-calc/lux/view-result'));
        } else {
            $this->data['validation_errors'] = $this->form_validation->error_array();
            $this->data['validation_error_keys'] = array_keys($this->data['validation_errors']);
        }
    }

    private function roomData($application_id)
    {
        $roomData = $this->UtilModel->selectQuery(
            "application_id, body, icon, image, language_code, lux_values,
         maintainance_factor, reference_height, reflection_values_ceiling, reflection_values_floor,
        reflection_values_wall, room_id, slug, sub_title, title, ugr, uo",
            "rooms",
            ["where" => ['application_id' => $application_id]]
        );

        $roomData = array_map(
            function ($room) {
                $room['room_id'] = encryptDecrypt($room['room_id']);
                $room['application_id'] = encryptDecrypt($room['application_id']);
                return $room;
            },
            $roomData
        );

        return $roomData;
    }

    private function validateQuickCalcForm()
    {
        return [
            [
                'field' => "name",
                'label' => "Name",
                'rules' => 'trim|required'
            ],
            [
                'field' => "room_id",
                'label' => "Room id",
                'rules' => 'trim|required'
            ],
            [
                'field' => "length",
                'label' => "Length",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "length_unit",
                'label' => "Length unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "width",
                'label' => "Width",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "width_unit",
                'label' => "Width unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "height",
                'label' => "Height",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "height_unit",
                'label' => "Height unit",
                'rules' => 'trim|required|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "room_plane_height",
                'label' => "Room plane height",
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => "room_plane_height_unit",
                'label' => "Room plane height unit",
                'rules' => 'trim|required|regex_match[/^(cms)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "room_luminaries_x",
                'label' => "Room luminaries x",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "room_luminaries_y",
                'label' => "Room luminaries y",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "pendant_length",
                'label' => "Pendant length",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "pendant_length_unit",
                'label' => "Pendant length unit",
                'rules' => 'trim|regex_match[/^(meter|yard|inch)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line('invalid_measurement_unit')
                ]
            ],
            [
                'field' => "rho_wall",
                'label' => "Rho wall",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "rho_ceiling",
                'label' => "Rho ceiling",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "rho_floor",
                'label' => "Rho floor",
                'rules' => 'trim|numeric'
            ],
            [
                'field' => "maintainance_factor",
                'label' => "Maintainance factor",
                'rules' => 'trim|required|numeric'
            ], [
                'field' => "lux_values",
                'label' => "Lux values",
                'rules' => 'trim|numeric'
            ],
            // [
            //     'field' => "application_id",
            //     'label' => "Application id",
            //     'rules' => 'trim|required|is_natural_no_zero'
            // ],
            [
                'field' => "article_code",
                'label' => "Article code",
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => "type",
                'label' => "Type",
                'rules' => 'trim|required|regex_match[/^(1|2|3|4|5|6|7)$/]',
                'errors' => [
                    'regex_match' => $this->lang->line("bad_request")
                ]
            ],
            [
                'field' => "product_id",
                'label' => "Product id",
                'rules' => 'trim|required'
            ],
        ];
    }

}

