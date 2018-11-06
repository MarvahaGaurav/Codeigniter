<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once "BaseController.php";

class InspirationController extends BaseController
{

    private $userData;
    public function __construct()
    {
        parent::__construct();
        $this->activeSessionGuard();
        $this->load->model("Inspiration");
        $this->data['userInfo'] = $this->userInfo;
        /*         if (!isset($this->userInfo['user_type']) ||
            !in_array($this->userInfo['user_type'], [INSTALLER, WHOLESALER, ARCHITECT, ELECTRICAL_PLANNER]) ||
            ROLE_OWNER !== (int)$this->userInfo['is_owner']) {
            error404();
            exit;
        } */
    }

    public function index()
    {
        $this->load->helper("input_data");
        $limit = 5;
        $get = $this->input->get();
        $get = trim_input_parameters($get);
        $page = isset($get['page'])&&!empty((int)$get['page'])?(int)$get['page']:1;
        $search = isset($get['search'])?$get['search']:"";

        $options['offset'] = ($page - 1) * $limit;
        $options['limit'] = $limit;
        $options['search'] = $search;
        $options['media'] = true;
        $options['products'] = true;
        $options['user_id'] = $this->userInfo['user_id'];

        $data = $this->Inspiration->get($options);
        
        $this->load->library("Commonfn");
        $technicianTypes = [INSTALLER => "Installer", ARCHITECT => "Architect", ELECTRICAL_PLANNER => "Electrical Planner", WHOLESALER => "Wholesaler"];
        $this->data['links'] = $this->commonfn->pagination("home/inspirations", $data['count'], $limit);
        $result = array_map(
            function ($row) use ($technicianTypes) {
                // $row['image'] = empty($row['image']) ? base_url("public/images/missing_avatar.svg") : $row['image'];
                $row['inspiration_id'] = encryptDecrypt($row['inspiration_id']);
                $row['title'] = strlen($row['title']) > 50 ? substr($row['title'], 0, 50) . "...": $row['title'];
                $row['description'] = strlen($row['description']) > 50 ? substr($row['description'], 0, 50) . "...": $row['description'];
                $row['products'] = json_decode("[{$row['products']}]", true);
                $row['media'] = json_decode("[{$row['media']}]", true);
                $row['media'] = !empty($row['media'])?$row['media']:base_url("public/images/logo.png");
                if (isset($this->userInfo['user_type'])
                    && in_array($this->userInfo['user_type'], [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER])
                    && (ROLE_OWNER === (int)$this->userInfo['is_owner'] || (isset($this->employeePermission['insp_edit']) && (int)$this->employeePermission['insp_edit'] === 1))
                    && ( (int)$this->userInfo['company_id'] === (int)$row['company_id'] )
                ) {
                    $row['edit_inspiration'] = true;
                } else {
                    $row['edit_inspiration'] = false;
                }
                return $row;
            },
            $data['result']
        );
        $this->data['js'] = "inspirations";
        $this->data['owl'] = true;
        $this->data['inspirations'] = $result;
        $this->data['search'] = $search;
        load_website_views("inspirations/main", $this->data);
    }

    public function details($inspiration_id = "")
    {
        $id = $inspiration_id;
        $inspiration_id = encryptDecrypt($inspiration_id, 'decrypt');

        if (! isset($inspiration_id) || empty($inspiration_id)) {
            error404("", base_url());
            exit;
        }

        $options['poster_details'] = true;
        $options['media'] = true;
        $options['products'] = true;
        $options['inspiration_id'] = (int)$inspiration_id;

        $data = $this->Inspiration->get($options);

        if (empty($data)) {
            error404("", base_url());
            exit;
        }

        $data['products'] = json_decode("[{$data['products']}]", true);
        $data['media'] = json_decode("[{$data['media']}]", true);
        $this->data['inspiration'] = $data;
        // pd($data);
        load_website_views("inspirations/details", $this->data);
    }

    public function add()
    {
        try {
            // if (!isset($this->userInfo['user_type'])
            //     || !in_array($this->userInfo['user_type'], [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER])
            //     || (ROLE_OWNER !== (int)$this->userInfo['is_owner'] && (!isset($this->employeePermission['insp_add']) || (int)$this->employeePermission['insp_add'] == 0))
            // ) {
            //     error404("", base_url());
            //     exit;
            // }
            
            $this->load->helper(['products']);
            $products = products('en');
            $this->data['products'] = $products;
            $post = $this->input->post();
            if (!empty($post)) {
                $this->load->library("form_validation");
                $this->form_validation->CI =& $this;
                $rules = $this->addInspirationValidation();
                $this->form_validation->set_rules($rules);
                $inspirationProducts = $this->input->post('products');
                if (!is_null($inspirationProducts) && is_array($inspirationProducts)) {
                    foreach ($inspirationProducts as $key => $product) {
                        $this->form_validation->set_rules('products[' . $key . ']', 'Products', 'trim|required|is_natural_no_zero');
                    }
                }
                $validateImage = false;
                $validImage = false;
                if (isset($_FILES['inspiration_image']) && is_array($_FILES['inspiration_image'])) {
                    $this->load->helper('image_validation');
                    $files = reArrayFiles($_FILES['inspiration_image']);
                    $files = array_filter($files, function ($data) {
                        if (
                            isset($data['tmp_name']) &&
                            strlen($data['tmp_name']) > 0 &&
                            $data['size'] > 0 &&
                            (int)$data['error'] === 0 &&
                            (bool) preg_match("/^(image|video)\/.+$/", mime_content_type($data['tmp_name']))
                        ) {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    if (!empty($files)) {
                        $validateImage = true;
                        $validImage = true;
                    }
                }

                if ($this->form_validation->run() && !($validateImage xor $validImage)) {
                    $this->load->helper("input_data");
                    $post = $this->input->post();
                    $post = trim_input_parameters($post);
                    $this->Inspiration->title = $post['title'];
                    $this->Inspiration->description = $post['description'];
                    $this->Inspiration->user_id = $this->userInfo['user_id'];
                    $this->Inspiration->company_id = $this->userInfo['company_id'];
                
                    $inspirationId = $this->Inspiration->save();
                    $inspirationProductData = array_map(function ($productId) use ($inspirationId){
                        $data['inspiration_id'] = $inspirationId;
                        $data['product_id'] = $productId;
                        return $data;
                    }, $inspirationProducts);

                    if (!empty($files)) {
                        $this->load->helper(['mime', 'images']);
                        $mediaData = array_map(function ($file) use ($inspirationId) {
                            $data['inspiration_id'] = $inspirationId;
                            $mime = mime_content_type($file['tmp_name']);
                            if (preg_match("/^(image)\/.+$/", $mime)) {
                                $data['media_type'] = 1;
                                $data['media'] = 
                                    s3_image_uploader($file['tmp_name'], 'sg/inspirations/' . encryptDecrypt($inspirationId) . '/' . time() . '.' . mime2ext($mime), $mime);
                            } elseif (preg_match("/^(video)\/.+$/", $mime)) {
                                $data['media_type'] = 2;
                                $data['media'] = 
                                    s3_image_uploader($file['tmp_name'], 'sg/inspirations/' . encryptDecrypt($inspirationId) . '/' . time() . '.' . mime2ext($mime), $mime);
                                // $data['video_thumbnail'] = generate_video_thumbnail($data['media']);
                            }
                            return $data;
                        }, $files);
                        $this->UtilModel->insertBatch('inspiration_media', $mediaData);
                    }

                    $this->UtilModel->insertBatch('inspiration_products', $inspirationProductData);
                    $this->session->set_flashdata("flash-message", $this->lang->line("inspiration_added"));
                    $this->session->set_flashdata("flash-type", "success");
                    redirect(base_url("home/inspirations"));
                } else {

                }
            }
            $this->data['js'] = 'inspiration-add';
            $this->data['custom_select'] = true;
            $this->data['image_video_uploader'] = true;
            load_website_views("inspirations/add", $this->data);
        } catch (\Exception $error) {
            // $this->session->set_flashdata("flash-message", $this->lang->line("something_went_Worng"));
            // $this->session->set_flashdata("flash-type", "danger");
        }
    }
    
    public function edit($inspiration_id = '')
    {
        // if (!isset($this->userInfo['user_type'])
        //     || !in_array($this->userInfo['user_type'], [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER])
        //     || (ROLE_OWNER !== (int)$this->userInfo['is_owner'] && (!isset($this->employeePermission['insp_edit']) || (int)$this->employeePermission['insp_edit'] == 0))
        // ) {
        //     error404("", base_url());
        //     exit;
        // }

        $inspiration_id = encryptDecrypt($inspiration_id, 'decrypt');

        if (! isset($inspiration_id) || empty($inspiration_id)) {
            error404("", base_url());
            exit;
        }

        $options['poster_details'] = true;
        $options['media'] = true;
        $options['products'] = true;
        $options['inspiration_id'] = (int)$inspiration_id;

        $data = $this->Inspiration->get($options);

        if (empty($data)) {
            error404("", base_url());
            exit;
        }

        $data['products'] = json_decode("[{$data['products']}]", true);
        $data['media'] = json_decode("[{$data['media']}]", true);

        if ((int)$data['company_id'] !== (int)$this->userInfo['company_id']) {
            error404("", base_url());
            exit;
        }
        $this->data['inspiration'] = $data;
        $this->data['inspiration_id'] =  encryptDecrypt($data['inspiration_id']);
        $post = $this->input->post();
        $this->load->helper(['products']);
        $products = products('en');
        $this->data['products'] = $products;

        $inspirationParams['where']['inspiration_id'] = $inspiration_id;

        $selectedProducts = $this->UtilModel->selectQuery('product_id', 'inspiration_products', $inspirationParams);

        $inspirationMedia = $this->UtilModel->selectQuery('*', 'inspiration_media', $inspirationParams);

        $this->data['inspirationMedia'] = $inspirationMedia;
        $this->data['selectedProducts'] = array_column($selectedProducts, 'product_id');
        if (!empty($post)) {
            $this->load->library("form_validation");
            $this->form_validation->CI =& $this;
            $rules = $this->addInspirationValidation();
            $this->form_validation->set_rules($rules);

            $validateImage = false;
            $validImage = false;
            if (isset($_FILES['inspiration_image']) && is_array($_FILES['inspiration_image'])) {
                $this->load->helper('image_validation');
                
                $files = reArrayFiles($_FILES['inspiration_image']);
                $files = array_filter($files, function ($data) {
                    if (
                        isset($data['tmp_name']) &&
                        strlen($data['tmp_name']) > 0 &&
                        $data['size'] > 0 &&
                        (int)$data['error'] === 0 &&
                        (bool) preg_match("/^(image|video)\/.+$/", mime_content_type($data['tmp_name']))
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                });
                if (!empty($files)) {
                    $validateImage = true;
                    $validImage = true;
                }
            }

            if ($this->form_validation->run()) {
                $this->load->helper("input_data");
                $post = $this->input->post();
                $post = trim_input_parameters($post);
                $this->Inspiration->title = $post['title'];
                $this->Inspiration->description = $post['description'];
                $this->Inspiration->updated_at = $this->datetime;
                $inspirationProducts = $this->input->post('products');
                if (!is_null($inspirationProducts) && is_array($inspirationProducts)) {
                    foreach ($inspirationProducts as $key => $product) {
                        $this->form_validation->set_rules('products[' . $key . ']', 'Products', 'trim|required|is_natural_no_zero');
                    }
                }

                try {
                    if (isset($post['old_images']) && is_array($post['old_images']) && !empty($post['old_images'])) {
                        $this->UtilModel->deleteData('inspiration_media', [
                            'where_in' => ['id' => $post['old_images']]
                        ]);
                    }

                    $this->Inspiration->update(['id' => $inspiration_id]);
                    $this->UtilModel->deleteData('inspiration_products', [
                        'where' => ['inspiration_id' => $inspiration_id]
                    ]);
                    $inspirationProductData = array_map(function ($productId) use ($inspiration_id){
                        $data['inspiration_id'] = $inspiration_id;
                        $data['product_id'] = $productId;
                        return $data;
                    }, $inspirationProducts);

                    $this->UtilModel->insertBatch('inspiration_products', $inspirationProductData);

                    if (!empty($files)) {
                        $this->load->helper(['mime', 'images']);
                        $mediaData = array_map(function ($file) use ($inspiration_id) {
                            $data['inspiration_id'] = $inspiration_id;
                            $mime = mime_content_type($file['tmp_name']);
                            if (preg_match("/^(image)\/.+$/", $mime)) {
                                $data['media_type'] = 1;
                                $data['media'] = 
                                    s3_image_uploader($file['tmp_name'], 'sg/inspirations/' . encryptDecrypt($inspiration_id) . '/' . time() . '.' . mime2ext($mime), $mime);
                            } elseif (preg_match("/^(video)\/.+$/", $mime)) {
                                $data['media_type'] = 2;
                                $data['media'] = 
                                    s3_image_uploader($file['tmp_name'], 'sg/inspirations/' . encryptDecrypt($inspiration_id) . '/' . time() . '.' . mime2ext($mime), $mime);
                                // $data['video_thumbnail'] = generate_video_thumbnail($data['media']);
                            }
                            return $data;
                        }, $files);
                        $this->UtilModel->insertBatch('inspiration_media', $mediaData);
                    }
                    $this->session->set_flashdata("flash-message", $this->lang->line("inspiration_updated"));
                    $this->session->set_flashdata("flash-type", "success");
                    redirect(base_url("home/inspirations"));
                } catch (\Exception $error) {
                    // $this->session->set_flashdata("flash-message", $this->lang->line("something_went_Worng"));
                    // $this->session->set_flashdata("flash-type", "danger");
                }
            }
        }

        $this->data['js'] = 'inspiration-edit';
        $this->data['custom_select'] = true;
        $this->data['image_video_uploader'] = true;

        load_website_views("inspirations/edit", $this->data);
    }

    private function addInspirationValidation()
    {
        $rules = [
                [
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required|max_length[255]'
                ],
                [
                    'field' => 'description',
                    'label' => 'Description',
                    'rules' => 'trim|required|max_length[255]'
                ],
                [
                    'field' => 'products[]',
                    'label' => 'Products', 
                    'rules' => 'trim|required'
                ]
        ];
        return $rules;
    }
}
