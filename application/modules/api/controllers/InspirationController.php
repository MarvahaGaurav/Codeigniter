<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once 'BaseController.php';

use DatabaseExceptions\UpdateException;
use DatabaseExceptions\SelectException;
use DatabaseExceptions\InsertException;
use DatabaseExceptions\DeleteException;
use GuzzleHttp\Client as GuzzleClient;

class InspirationController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Inspiration');
    }

    /**
     * @SWG\Post(path="inspirations",
     *   tags={"Inspirations"},
     *   summary="Post inspiration",
     *   description="Post inspiration",
     *   operationId="inspiration_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="company_id",
     *     in="formData",
     *     description="Company Id",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="title",
     *     in="formData",
     *     description="Title",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="description",
     *     in="formData",
     *     description="",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="product_ids",
     *     in="formData",
     *     description="array of ids",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="media",
     *     in="formData",
     *     description="json array [{'media':'http//image/url', 'type':1}, {'media':'http//image/url', 'type':1}] 1-image, 2-video, 3-pdf",
     *     type="string",
     *     required=true
     *   ),
     * @SWG\Response(response=422, description="Missing parameters"),
     * @SWG\Response(response=200, description="Inspiration added"),
     * @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function inspiration_post()
    {
        $user_data = $this->accessTokenCheck("u.company_id, u.user_type");
        $language_code = $this->langcode_validate();
        $request_data = $this->post();
        $request_data = trim_input_parameters($request_data);

        $mandatoryFields = ["title", "description", "media", "address", "lat", "lng"];

        $check = check_empty_parameters($request_data, $mandatoryFields);

        if ($check['error']) {
            $this->response(
                [
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'api_code_result' => 'UNPROCESSABLE_ENTITY',
                    'msg' => $this->lang->line('missing_parameter'),
                    'extra_info' => [
                        "missing_parameter" => $check['parameter']
                    ]
                ]
            );
        }

        if (isset($request_data['media']) && !empty($request_data['media'])) {
            // $request_data['media'] = trim($this->post("media"));
            $this->load->model("InspirationMedia");
            $media = json_decode($request_data['media'], true);
            // pd($media);
        }

        if (in_array((int)$user_data['user_type'], [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER, WHOLESALER], true)) {
            $this->Inspiration->company_id = $user_data['company_id'];
        }
        $this->Inspiration->language_code = $language_code;
        $this->Inspiration->user_id = $user_data['user_id'];
        $this->Inspiration->title = $request_data['title'];
        $this->Inspiration->description = $request_data['description'];
        $this->Inspiration->address = $request_data['address'];
        $this->Inspiration->lat = $request_data['lat'];
        $this->Inspiration->lng = $request_data['lng'];
        $this->Inspiration->created_at = $this->datetime;
        $this->Inspiration->updated_at = $this->datetime;
        $this->Inspiration->updated_at_timestamp = $this->timestamp;
        $this->Inspiration->created_at_timestamp = $this->timestamp;

        $add_products = false;
        if (isset($request_data['product_ids'])) {
            $request_data['product_ids'] = json_decode($request_data['product_ids']);
            if (is_array($request_data['product_ids'])) {
                $add_products = true;
            }
        }

        try {
            $this->db->trans_begin();
            $inspirationId = $this->Inspiration->save();
            if ($add_products) {
                $this->load->model("InspirationProduct");
                foreach ($request_data['product_ids'] as $product_id) {
                    $this->InspirationProduct->batch_data[] = [
                        "inspiration_id" => $inspirationId,
                        "product_id" => $product_id
                    ];
                }

                $this->InspirationProduct->batch_save();
            }
            //if media data is available, prepare batch
            if (!empty($media) && is_array($media)) {
                foreach ($media as $key => $value) {
                    if (!is_array($value)) {
                        $value = [];
                    }
                    $value = trim_input_parameters($value);
                    $mandatory_json = ["type", "media"];
                    $check_json = check_empty_parameters($value, $mandatory_json);
                    if ($check_json['error']) {
                        $this->response(
                            [
                                'code' => HTTP_UNPROCESSABLE_ENTITY,
                                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                                'msg' => $this->lang->line('missing_json_parameter'),
                                'extra_info' => [
                                    "missing_json_parameter" => $check_json['parameter']
                                ]
                            ]
                        );
                    }

                    $this->load->helper("images");
                    $content = [
                        "inspiration_id" => $inspirationId,
                        "media_type" => $value['type'],
                        "media" => $value['media'],
                        "video_thumbnail" => ""
                    ];
                    if (CONTENT_TYPE_VIDEO === (int)$value['type'] && !isset($value['video_thumbnail'])) {
                        $content['video_thumbnail'] = generate_video_thumbnail($value['media']);
                    } elseif (CONTENT_TYPE_VIDEO === (int)$value['type'] && isset($value['video_thumbnail'])) {
                        $content['video_thumbnail'] = $value['video_thumbnail'];
                    }
                    $this->InspirationMedia->batch_data[] = $content;
                }
                $this->InspirationMedia->batch_save();
            }
            $this->db->trans_commit();
            $this->response(
                [
                    'code' => HTTP_OK,
                    'api_code_result' => 'OK',
                    'msg' => $this->lang->line("inspiration_added")
                ]
            );
        } catch (InsertException $error) {
            $this->db->trans_rollback();
            $this->response(
                [
                    'code' => HTTP_INTERNAL_SERVER_ERROR,
                    'api_code_result' => 'INTERNAL_SERVER_ERROR',
                    'msg' => $this->lang->line('internal_server_error'),
                    'debug' => [
                        'info' => 'Insert Error',
                        'query' => $error->getMessage()
                    ]
                ],
                HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            $this->response(
                [
                    'code' => HTTP_INTERNAL_SERVER_ERROR,
                    'api_code_result' => 'INTERNAL_SERVER_ERROR',
                    'msg' => $this->lang->line('internal_server_error')
                ],
                HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @SWG\Get(path="inspirations",
     *   tags={"Inspirations"},
     *   summary="Inspiration List and detail",
     *   description="Inspiration List and detail",
     *   operationId="employee_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="inspiration_id",
     *     in="query",
     *     description="Inspiration id is optional and can be passed to fetch inspiration details",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="offset value to be passed back to paginate, if offset is -1, there is no further data",
     *     type="string"
     *   ),
     * @SWG\Response(response=202, description="No data found"),
     * @SWG\Response(response=200, description="Inspiration added"),
     * @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function inspiration_get()
    {
        try {
            $userData = $this->accessTokenCheck('company_id, user_type');
            $language_code = $this->langcode_validate();
            $this->requestData = $this->get();

            $params = [];
            
            if (in_array((int)$userData['user_type'], [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER, WHOLESALER], true)) {
                $params['company_id'] = $userData['company_id'];
            } else {
                $params['user_id'] = $userData['user_id'];
            }

            $params['offset'] =
                isset($this->requestData['offset'])&&is_numeric($this->requestData['offset'])&&(int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset']: 0;
            $params['limit'] = API_RECORDS_PER_PAGE;
            $params['language_code'] = $language_code;

            $data = $this->getInspirationData($params);

            $inspirations = $data['data'];
            $inspirationCount = $data['count'];

            if (empty($inspirations)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$inspirationCount > ($params['offset'] + API_RECORDS_PER_PAGE)) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + API_RECORDS_PER_PAGE;
            }

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('success'),
                'data' => $inspirations,
                'total' => $inspirationCount,
                'next_count' => $nextCount
            ]);
        } catch (\Exception $error) {
            $this->response(
                [
                    'code' => HTTP_INTERNAL_SERVER_ERROR,
                    'api_code_result' => 'INTERNAL_SERVER_ERROR',
                    'msg' => $this->lang->line('internal_server_error')
                ],
                HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

     /**
     * @SWG\Get(path="/companies/{company_id}/inspirations ",
     *   tags={"Company"},
     *   summary="Get Company Inspiration",
     *   description="Get Company Inspiration",
     *   operationId="employee_get",
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="inspiration_id",
     *     in="query",
     *     description="Inspiration id is optional and can be passed to fetch inspiration details",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="company_id",
     *     in="path",
     *     description="Company Id",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="offset value to be passed back to paginate, if offset is -1, there is no further data",
     *     type="string"
     *   ),
     * @SWG\Response(response=202, description="No data found"),
     * @SWG\Response(response=200, description="Inspiration added"),
     * @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function companyInspiration_get()
    {
        try {
            $language_code = $this->langcode_validate();
            
            $this->requestData = $this->get();

            if (isset($this->requestData['company_id'])) {

                $companyData = $this->UtilModel->selectQuery('company_id', 'company_master', [
                    'where' => ['company_id' => $this->requestData['company_id']], 'single_row' => true
                ]);

                if (empty($companyData)) {
                    $this->response([
                        'code' => HTTP_NOT_FOUND,
                        'msg' => $this->lang->line('company_not_found')
                    ]);
                }
                $params['company_id'] = $this->requestData['company_id'];
            }

            $params['offset'] =
                isset($this->requestData['offset'])&&is_numeric($this->requestData['offset'])&&(int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset']: 0;
            $params['limit'] = API_RECORDS_PER_PAGE;
            $params['language_code'] = $language_code;

            $data = $this->getInspirationData($params);

            $inspirations = $data['data'];
            $inspirationCount = $data['count'];

            if (empty($inspirations)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$inspirationCount > ($params['offset'] + API_RECORDS_PER_PAGE)) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + API_RECORDS_PER_PAGE;
            }

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('success'),
                'data' => $inspirations,
                'total' => $inspirationCount,
                'next_count' => $nextCount
            ]);
        } catch (\Exception $error) {
            $this->response(
                [
                    'code' => HTTP_INTERNAL_SERVER_ERROR,
                    'api_code_result' => 'INTERNAL_SERVER_ERROR',
                    'msg' => $this->lang->line('internal_server_error')
                ],
                HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function getInspirationData($params)
    {
        $options = [];
        if (isset($params['company_id'])) {
            $options['where']['inspirations.company_id'] = $params['company_id'];
        }

        if (isset($params['user_id'])) {
            $options['where']['inspirations.user_id'] = $params['user_id'];
        }

        $options['where']['language_code'] = $params['language_code'];
        $options['offset'] = $params['offset'];
        $options['limit'] = $params['limit'];

        if ($this->uri->segment(4) === "all") {
            $options['where']['inspirations.company_id !=']  = null;
        }

        $this->load->model('Inspiration');

        $data = $this->Inspiration->inspirations($options);

        return $data;
    }

    /**
     * @SWG\Put(path="inspirations",
     *   tags={"Inspirations"},
     *   summary="Put inspiration",
     *   description="Put inspiration",
     *   operationId="inspiration_put",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="inspiration_id",
     *     in="formData",
     *     description="Inspiration Id",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="title",
     *     in="formData",
     *     description="Title",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="description",
     *     in="formData",
     *     description="",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="product_ids",
     *     in="formData",
     *     description="array of ids",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="remove_product_ids",
     *     in="formData",
     *     description="array of ids to remove",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="media_to_delete",
     *     in="formData",
     *     description="JSON array string of media ids [1,2,3]",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="media",
     *     in="formData",
     *     description="json array [{'media':'http//image/url', 'type':1}, {'media':'http//image/url', 'type':1}] 1-image, 2-video, 3-pdf",
     *     type="string"
     *   ),
     * @SWG\Response(response=422, description="Missing parameters/Nothing to update"),
     * @SWG\Response(response=200, description="Inspiration updated"),
     * @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function inspiration_put()
    {
        $user_data = $this->accessTokenCheck('company_id, user_type');
        $language_code = $this->langcode_validate();
        $request_data = $this->put();
        $request_data = trim_input_parameters($request_data);
        $remove_media = false;
        $add_media = false;
        $media_remove_list = [];
        $add_media_data = [];

        if (isset($request_data['media_to_delete'])) {
            $media_remove_list = json_decode($request_data['media_to_delete'], true);
            if (isset($media_remove_list) && !empty($media_remove_list)) {
                $remove_media = true;
            }
        }

        if (isset($request_data['media'])) {
            $add_media_data = json_decode($request_data['media'], true);
            if (isset($add_media_data) && !empty($add_media_data)) {
                $add_media = true;
            }
        }

        $mandatoryFields = ["inspiration_id"];

        $check = check_empty_parameters($request_data, $mandatoryFields);

        if ($check['error']) {
            $this->response(
                [
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'api_code_result' => 'UNPROCESSABLE_ENTITY',
                    'msg' => $this->lang->line('missing_parameter'),
                    'extra_info' => [
                        "missing_parameter" => $check['parameter']
                    ]
                ]
            );
        }
        $check = $request_data;
        unset($check['inspiration_id']);

        if (empty($check)) {
            $this->response(
                [
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'api_code_result' => 'UNPROCESSABLE_ENTITY',
                    'msg' => $this->lang->line('nothing_to_update')
                ]
            );
        }

        $inspirationData = $this->UtilModel->selectQuery(
            'company_id, user_id',
            'inspirations',
            [
                "where" => ['id' => $request_data['inspiration_id']],
                "single_row" => true
            ]
        );

        if (!$inspirationData) {
            $this->response(
                [
                    "code" => NO_DATA_FOUND,
                    "api_code_result" => "NO_DATA_FOUND",
                    "msg" => $this->lang->line("no_records_found")
                ]
            );
        }

        if ((in_array((int)$user_data['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && (int)$user_data['user_id'] !== (int)$inspirationData['user_id']) || (in_array((int)$user_data['user_type'], [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER, WHOLESALER], true) && (int)$user_data['company_id'] !== (int)$inspirationData['user_id'])) {
            $this->response(
                [
                    "code" => HTTP_FORBIDDEN,
                    "api_code_result" => "FORBIDDEN",
                    "msg" => $this->lang->line("cannot_update_inspiration")
                ],
                HTTP_FORBIDDEN
            );
        }

        $inspiration_update_map = [
            "title" => "title",
            "description" => "description"
        ];

        foreach ($inspiration_update_map as $request_key => $db_key) {
            if (isset($request_data[$request_key]) && !empty($request_data[$request_key])) {
                $this->Inspiration->$db_key = $request_data[$request_key];
            }
        }

        $this->load->model("InspirationMedia");
        if ($remove_media) {
            $remove_media_where = ['id' => $media_remove_list];
        }

        if ($add_media) {
            foreach ($add_media_data as $key => $value) {
                if (!is_array($value)) {
                    $value = [];
                }

                $value = trim_input_parameters($value);
                $mandatory_json = ["type", "media"];
                $check_json = check_empty_parameters($value, $mandatory_json);
                if ($check_json['error']) {
                    $this->response(
                        [
                            'code' => HTTP_UNPROCESSABLE_ENTITY,
                            'api_code_result' => 'UNPROCESSABLE_ENTITY',
                            'msg' => $this->lang->line('missing_json_parameter'),
                            'extra_info' => [
                                "missing_json_parameter" => $check_json['parameter']
                            ]
                        ]
                    );
                }

                $this->load->helper("images");
                $content = [
                    "inspiration_id" => $request_data['inspiration_id'],
                    "media_type" => $value['type'],
                    "media" => $value['media'],
                    "video_thumbnail" => ""
                ];
                if (CONTENT_TYPE_VIDEO === (int)$value['type'] && !isset($value['video_thumbnail'])) {
                    $content['video_thumbnail'] = generate_video_thumbnail($value['media']);
                } elseif (CONTENT_TYPE_VIDEO === (int)$value['type'] && isset($value['video_thumbnail'])) {
                    $content['video_thumbnail'] = $value['video_thumbnail'];
                }

                $this->InspirationMedia->batch_data[] = $content;
            }
        }

        $where = [
            'id' => $request_data['inspiration_id']
        ];

        //add more products
        $add_products = false;
        if (isset($request_data['product_ids'])) {
            $request_data['product_ids'] = json_decode($request_data['product_ids']);
            if (is_array($request_data['product_ids'])) {
                $add_products = true;
            }
        }

         //add more products
        $remove_products = false;
        if (isset($request_data['remove_product_ids'])) {
            $request_data['remove_product_ids'] = json_decode($request_data['remove_product_ids']);
            if (is_array($request_data['remove_product_ids'])) {
                $remove_products = true;
            }
        }

        try {
            $this->db->trans_begin();
            if (isset($request_data['title']) || isset($request_data['description'])) {
                $this->Inspiration->update($where);
            }
            if ($add_media) {
                $this->InspirationMedia->batch_save();
            }

            if ($remove_products || $add_products) {
                $this->load->model("InspirationProduct");
            }

            if ($remove_products) {
                $this->InspirationProduct->delete(
                    ["inspiration_id" => $request_data['inspiration_id']],
                    ["product_id" => $request_data['remove_product_ids']]
                );
            }

            if ($add_products) {
                foreach ($request_data['product_ids'] as $product_id) {
                    $this->InspirationProduct->batch_data[] = [
                        "inspiration_id" => $request_data['inspiration_id'],
                        "product_id" => $product_id
                    ];
                }

                $this->InspirationProduct->batch_save();
            }

            if ($remove_media) {
                $this->InspirationMedia->delete(['inspiration_id' => $request_data['inspiration_id']], $remove_media_where);
            }
            $this->db->trans_commit();
            $this->response(
                [
                    'code' => HTTP_OK,
                    'api_code_result' => 'OK',
                    'msg' => $this->lang->line("inspiration_updated")
                ]
            );
        } catch (UpdateException $error) {
            $this->db->rollback();
            if ($error->code == 101) { // zero rows affected
                //success
                $this->response(
                    [
                        'code' => HTTP_OK,
                        'api_code_result' => 'OK',
                        'msg' => $this->lang->line("inspiration_updated")
                    ]
                );
            } else {
                $this->response(
                    [
                        'code' => HTTP_INTERNAL_SERVER_ERROR,
                        'api_code_result' => 'INTERNAL_SERVER_ERROR',
                        'msg' => $this->lang->line('internal_server_error'),
                        'debug' => [
                            'info' => 'Update Error',
                            'query' => $error->getMessage()
                        ]
                    ]
                );
            }
        } catch (\Exception $error) {
            $this->db->rollback();
            $this->response(
                [
                    'code' => HTTP_INTERNAL_SERVER_ERROR,
                    'api_code_result' => 'INTERNAL_SERVER_ERROR',
                    'msg' => $this->lang->line('internal_server_error')
                ]
            );
        }
    }

    /**
     * @SWG\Delete(path="inspirations",
     *   tags={"Inspirations"},
     *   summary="Delete inspiration",
     *   description="Delete inspiration",
     *   operationId="inspiration_delete",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="inspiration_id",
     *     in="formData",
     *     description="Inspiration Id",
     *     type="string"
     *   ),
     * @SWG\Response(response=422, description="Missing parameters/Nothing to update"),
     * @SWG\Response(response=200, description="Inspiration updated"),
     * @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function inspiration_delete()
    {
        $userData = $this->accessTokenCheck('company_id');
        $language_code = $this->langcode_validate();
        try {
            $request_data = $this->delete();
            $request_data = trim_input_parameters($request_data);

            $mandatory = ['inspiration_id'];

            $check = check_empty_parameters($request_data, $mandatory);

            if ($check['error']) {
                $this->response(
                    [
                        'code' => HTTP_UNPROCESSABLE_ENTITY,
                        'api_code_result' => 'UNPROCESSABLE_ENTITY',
                        'msg' => $this->lang->line('missing_parameter'),
                        'extra_info' => [
                            "missing_parameter" => $check['parameter']
                        ]
                    ]
                );
            }

            $inspirationData = $this->UtilModel->selectQuery(
                'company_id',
                'inspirations',
                [
                    "where" => ['id' => $request_data['inspiration_id']],
                    "single_row" => true
                ]
            );

            if (!$inspirationData) {
                $this->response(
                    [
                        "code" => NO_DATA_FOUND,
                        "api_code_result" => "NO_DATA_FOUND",
                        "msg" => $this->lang->line("no_records_found")
                    ]
                );
            }
            // print_r((int)$inspirationData);
            // print_r((int)$userData);die;
            if ((int)$inspirationData['company_id'] !== (int)$userData['company_id']) {
                $this->response(
                    [
                        "code" => HTTP_FORBIDDEN,
                        "api_code_result" => "FORBIDDEN",
                        "msg" => $this->lang->line("cannot_delete_inspiration")
                    ],
                    HTTP_FORBIDDEN
                );
            }

            $where = [
                "id" => $request_data['inspiration_id']
            ];

            $this->load->model("InspirationMedia");
            $media = $this->InspirationMedia->get($request_data['inspiration_id']);

            $media_to_delete = array_map(
                function ($data) {
                    return $data['media'];
                },
                $media
            );

            $this->db->trans_begin();

            $this->Inspiration->delete($where);

            $this->db->trans_commit();

            $this->load->helper(['url']);
            //
            $http_client = new GuzzleClient(
                [
                    'base_uri' => base_url("api/")
                ]
            );

            $response = $http_client->request(
                'POST',
                'UtilityController/delete_media',
                [
                    'auth' => [AUTH_USER, AUTH_PASS],
                    'form_params' => [
                        'media' => $media_to_delete,
                        'single_traverse' => true
                    ]
                ]
            );

            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();
                $content = $body->getContents();
                // print_r($content);die;
            } else {
            }
            $this->response(
                [
                    'code' => HTTP_OK,
                    'api_code_result' => 'OK',
                    'msg' => $this->lang->line("inspiration_removed")
                ]
            );
        } catch (DeleteException $error) {
            $this->db->trans_rollback();
            $this->response(
                [
                    'code' => HTTP_INTERNAL_SERVER_ERROR,
                    'api_code_result' => 'INTERNAL_SERVER_ERROR',
                    'msg' => $this->lang->line('internal_server_error'),
                    'debug' => [
                        'info' => 'Delete Error',
                        'query' => $error->getMessage()
                    ]
                ]
            );
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            $this->response(
                [
                    'code' => HTTP_INTERNAL_SERVER_ERROR,
                    'api_code_result' => 'INTERNAL_SERVER_ERROR',
                    'msg' => $this->lang->line('internal_server_error'),
                    'debug' => [
                        'info' => $error->getMessage()
                    ]
                ]
            );
        }
    }
}
