<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";


class SearchArticlesController extends BaseController
{

    const SEARCH_PRODUCT_PAGINATION_LIMIT = 12;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model("UtilModel");
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->data['activePage'] = 'search';
        $this->neutralGuard();
    }


    /**
     * get product / artical listing
     * @param array $params
     * @return array
     */
    public function search()
    {
        try {
            $this->load->model('Product'); // load product model
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('search-page');
            $search_text = $this->input->get('search'); // get key value from URL
            $page = $this->input->get('page'); // get key value from URL
            $param['limit'] = self::SEARCH_PRODUCT_PAGINATION_LIMIT; // pagination data limit

            if (is_numeric($page) && (int)$page > 0) {
                $param['offset'] = ((int)$page - 1) * $param['limit'];
            } else {
                $param['offset'] = 0;
            }

            $param['like'] = [
                'pro_sp.title' => $search_text,
            ];
            $param['where'] = [
                'pro_sp.language_code' => $this->languageCode,
            ];
            $this->data['search'] = $search_text;
            $productData = $this->Product->searchProduct($param);
            $this->data['data'] = $productData['data'];
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $productData['count'], $param['limit']);

            // pr($this->data);
            website_view('search/search', $this->data);
        } catch (Exception $e) {

        }
    }


    /**
     * Quick Calculation on Article
     * @return array
     */
    public function QuickCal($productId = null, $articleId = null)
    {
        $productId = encryptdecrypt($productId, 'decrypt'); // decrypted productId
        $articleId = encryptdecrypt($articleId, 'decrypt'); // decrypted articleId

        $this->data['validation_error_keys'] = [];
        $this->data['showSuspensionHeight'] = false;
        $this->data['js'] = 'quickcalc-search';

        // $this->load->model("UtilModel");
        $productData = $this->UtilModel->selectQuery('articlecode, products.product_id , products.title', 'product_specifications', [
            'where' => ['products.product_id' => $productId, 'articlecode' => $articleId],
            'single_row' => true,
            'join' => ['products' => 'products.product_id = product_specifications.product_id']
        ]);

        $applicationData = $this->UtilModel->selectQuery(
            'application_id,type,title',
            'applications',
            ['where' => ['language_code' => $this->languageCode]]
        );

        // pr($applicationData);
        $this->data['applications'] = $applicationData;
        $this->data['title'] = $productData['title'];
        $this->data['article_code'] = $productData['articlecode'];
        $this->data['product_id'] = $productData['product_id'];

        if (isset($productId)) {
            $mountingTypes = $this->UtilModel->selectQuery('type', 'product_mounting_types', [
                'where' => ['product_id' => $productId, 'type !=' => 0]
            ]);

            $mountingTypes = array_column($mountingTypes, 'type');

            $suspendedFilter = array_filter($mountingTypes, function ($type) {
                return in_array((int)$type, [MOUNTING_SUSPENDED, MOUNTING_PENDANT], true);
            });

            $this->data['showSuspensionHeight'] = (bool)!empty($suspendedFilter);
        }
        // pr($this->data);
        $this->data['units'] = ["Meter", "Inch", "Yard"];

        $this->postRequest = $this->input->post();
        if (!empty($this->postRequest)) {
            $this->quickCalcFormHandler();
        }


        website_view('search/quick-cal', $this->data);
    }


    /**
     * Room dimesnin post handler
     *
     * @param array $room
     * @return void
     */
    private function quickCalcFormHandler()
    {

        $this->form_validation->reset_validation();
        $this->form_validation->set_data($this->postRequest);

        $this->form_validation->set_rules($this->validateQuickCalcForm());

        $validData = (bool)$this->form_validation->run();

        $uld = "";
        $article_detail = [];

        if (isset($this->postRequest['product_id'], $this->postRequest['article_code'])) {
            $uld = $this->UtilModel->selectQuery('uld , colour_temperature , beam_angle , colour_rendering', 'product_specifications', [
                'where' => [
                    'product_id' => $this->postRequest['product_id'],
                    'articlecode' => $this->postRequest['article_code']
                ],
                'single_row' => true
            ]);
            $article_additional_detail = [
                'colour_temperature' => $uld['colour_temperature'],
                'beam_angle' => $uld['beam_angle'],
                'colour_rendering' => $uld['colour_rendering'],
            ];
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

                "maintainance_factor" => isset($this->postRequest['maintainance_factor']) && !empty($this->postRequest['maintainance_factor']) ? $this->postRequest['maintainance_factor'] : '',


                "shape" => "Rectangular",

                "suspension_height" => isset($this->postRequest['pendant_length']) ? convert_to_meter($this->postRequest['pendant_length_unit'], $this->postRequest['pendant_length']) : 0.00,

                "working_plane_height" => isset($this->postRequest['room_plane_height']) ? $this->postRequest['room_plane_height'] / 100 : 0.00, //need to confirm

                "rho_wall" => isset($this->postRequest['rho_wall']) && !empty($this->postRequest['rho_wall']) ? $this->postRequest['rho_wall'] : '',

                "rho_ceiling" => isset($this->postRequest['rho_ceiling']) && !empty($this->postRequest['rho_ceiling']) ? $this->postRequest['rho_ceiling'] : '',

                "rho_floor" => isset($this->postRequest['rho_floor']) && !empty($this->postRequest['rho_floor']) ? $this->postRequest['rho_floor'] : '',

                "lux_value" => isset($this->postRequest['lux_values']) && !empty($this->postRequest['lux_values']) ? $this->postRequest['lux_values'] : '',

                "luminaries_count_x" => $this->postRequest['room_luminaries_x'],

                "luminaries_count_y" => $this->postRequest['room_luminaries_y'],

                "fast_calc_response" => '',

                "created_at" => $this->datetime,

                'type' => $this->postRequest['type'],

                'product_id' => $this->postRequest['product_id'],

                'article_code' => $this->postRequest['article_code']
            ];

            $response = $this->fetchQuickCalcData($insert, $uld);

            $decodedResponse = json_decode($response, true);
            // pr($decodedResponse);
            if (!isset($decodedResponse['projectionTop'], $decodedResponse['projectionSide'], $decodedResponse['projectionFront'])) {
                $this->session->set_flashdata("flash-message", $this->lang->line('we_are_unable_to_get_data'));
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url(uri_string()));
            }


            $serachProductData = json_encode([
                'product_id' => $this->postRequest['product_id'],
                'articlecode' => $this->postRequest['article_code']
            ]);

            // setcookie('serach_Product_Dialux_Response',$response, time() + (86400 * 30) , '/');
            // setcookie('serachProductData',$serachProductData, time() + (86400 * 30) ,  '/');

            $this->session->set_flashdata('serach_Product_Dialux_Response', $response);
            $this->session->set_flashdata('serachProductData', $serachProductData);
            $this->session->set_flashdata('article_additional_detail', $article_additional_detail);

            $this->session->set_flashdata("flash-message", $this->lang->line("room_calculated"));
            $this->session->set_flashdata("flash-type", "success");



            redirect(
                base_url('home/fast-calc/evaluation/' . encryptDecrypt($this->postRequest['product_id']) . '/' . encryptDecrypt($this->postRequest['article_code']))
            );
        } else {
            $this->data['validation_errors'] = $this->form_validation->error_array();
            $this->data['validation_error_keys'] = array_keys($this->data['validation_errors']);
        }
    }


    /**
     * Validate data to be inserted into project rooms
     *
     * @return array
     */
    private function validateQuickCalcForm()
    {




        return [
            /*[
                'field' => "name",
                'label' => "Name",
                'rules' => 'trim|required'
            ],*/

            /*[
                'field' => "room_id",
                'label' => "Room id",
                'rules' => 'trim|required'
            ],*/

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

            [
                'field' => "article_code",
                'label' => "Article code",
                'rules' => 'trim|required|is_natural_no_zero'
            ],

            [
                'field' => "product_id",
                'label' => "Product id",
                'rules' => 'trim|required'
            ],
        ];
    }



    private function fetchQuickCalcData($data, $uld)
    {
        $request_data = [
            "authToken" => "28c129e0aca88efb6f29d926ac4bab4d",
            "roomLength" => floatval($data['length']),
            "roomWidth" => floatval($data['width']),
            "roomHeight" => floatval($data['height']),
            "roomType" => $data['name'],
            "workingPlaneHeight" => floatval($data['working_plane_height']),
            "suspension" => isset($data['suspension_height']) ? floatval($data['suspension_height']) : 0,
            "illuminance" => $data['lux_value'],
            "luminaireCountInX" => floatval($data['luminaries_count_x']),
            "luminaireCountInY" => floatval($data['luminaries_count_y']),
            "rhoCeiling" => floatval($data['rho_ceiling']),
            "rhoWall" => floatval($data['rho_wall']),
            "rhoFloor" => floatval($data['rho_floor']),
            "maintenanceFactor" => floatval($data['maintainance_factor']),
            "uldUri" => $uld
        ];

        $response = hitCulrQuickCal($request_data);

        return $response;
    }


    public function view_result($productId, $articleId)
    {
        try {
            $productId = encryptdecrypt($productId, 'decrypt'); // decrypted productId
            $articleId = encryptdecrypt($articleId, 'decrypt'); // decrypted articleId

            $this->data['serachProductData'] = $this->session->flashdata('serachProductData');
            $this->data['serach_Product_Dialux_Response'] = $this->session->flashdata('serach_Product_Dialux_Response');
            $this->data['article_additional_detail'] = $this->session->flashdata('article_additional_detail');


            if (!isset($this->data['serachProductData']) && !isset($this->data['serach_Product_Dialux_Response']) && !isset($this->data['article_additional_detail'])) {
                $this->session->set_flashdata("flash-message", $this->lang->line('calculate_again'));
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url('home/search'));
            }

            website_view('search/search-evaluation-result', $this->data);

        } catch (Exception $ex) {

        }
    }
}
