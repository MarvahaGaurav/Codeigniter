<?php
defined("BASEPATH") or exit('No direct script access allowed');

require 'BaseController.php';

class ProjectProductController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    /**
     * @SWG\Get(path="/project-rooms/{project_room_id}/products/{product_id}",
     *   tags={"Products"},
     *   summary="Accessory Articles",
     *   description="Fetch accessory",
     *   operationId="mountingTypes_get",
     *   produces={"application/json"},
     *  * @SWG\Parameter(
     *     name="accesstoken",
     *     in="header",
     *     description="Access token received during signup or login",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="X-Language-Code",
     *     in="header",
     *     description="en ,da ,nb ,sv ,fi ,fr ,nl ,de",
     *     type="string",
     *     required=true
     * ),
     * @SWG\Parameter(
     *     name="project_room_id",
     *     in="path",
     *     description="Project room id",
     *     type="string",
     *     required = true
     *   ),
     * @SWG\Parameter(
     *     name="uld",
     *     in="query",
     *     description="1 - to fetch only products with uld, ignore this key to fetch all products",
     *     type="string",
     *   ),
     * @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     description="Search text",
     *     type="string",
     *   ),
     * @SWG\Parameter(
     *     name="room_id",
     *     in="query",
     *     description="Room Id",
     *     type="string",
     *   ),
     * @SWG\Parameter(
     *     name="mounting_type",
     *     in="query",
     *     description="mounting type 1,2,3,4,5,6,7",
     *     type="string",
     *   ),
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="",
     *     type="string",
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=401, description="Unauthorize"),
     * @SWG\Response(response=404, description="No data found"),
     * @SWG\Response(response=500, description="Internal server error"),
     * )
     */
    public function articles_get()
    {
        $user = $this->accessTokenCheck();
        $language_code = $this->langcode_validate();

        $this->requestData = $this->get();

        $this->validateArticles();

        $this->validationRun();

        $params['where']['language_code'] = $language_code;
        $params['offset'] =
            isset($this->requestData['offset']) && is_numeric($this->requestData['offset']) && (int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset'] : 0;
        $params['limit'] = API_RECORDS_PER_PAGE;

        if (isset($this->requestData['uld']) && (int)$this->requestData['uld'] === 1) {
            $params['where']['CHAR_LENGTH(uld) >'] = 0;
        }

        if (isset($this->requestData['search']) && strlen(trim($this->requestData['search'])) > 0) {
            $search = trim($this->requestData['search']);
            $params['where']['title LIKE'] = "%{$search}%";
        }

        if (isset($this->requestData['room_id'], $this->requestData['mounting_type'])) {
            $roomId = (int)$this->requestData['room_id'];
            $mountingType = (int)$this->requestData['mounting_type'];
            $params['where']["EXISTS(SELECT id FROM room_products WHERE room_products.product_id=product_specifications.product_id AND room_products.room_id='{$roomId}' AND room_products.type='{$mountingType}')"] = null;
        } else if (isset($this->requestData['room_id'])) {
            $roomId = (int)$this->requestData['room_id'];
            $params['where']["EXISTS(SELECT id FROM room_products WHERE room_products.product_id=product_specifications.product_id AND room_products.room_id='{$roomId}')"] = null;
        }

        $this->load->helper(['db']);
        $this->load->model(['ProjectRoomProducts','ProductMountingTypes']);

        $params['product_id'] = $this->requestData['product_id'];

        $data = $this->ProjectRoomProducts->fetchArticles($params);

        $articlesData = $data['data'];
        $count = $data['count'];

        if (empty($articlesData)) {
            $this->response([
                'code' => HTTP_NOT_FOUND,
                'msg' => $this->lang->line('no_data_found')
            ]);
        }

        $productIds = array_unique(array_column($articlesData, 'product_id'));

        $productMountingTypeData = $this->ProductMountingTypes->get($productIds);

        $articlesData = getDataWith(
            $articlesData,
            $productMountingTypeData,
            'product_id',
            'product_id',
            'mounting_types',
            'type'
        );

        $articlesData = array_map(function ($article) {
            $article['title'] = trim(strip_tags($article['title']));
            return $article;
        }, $articlesData);

        $projectRoomProductData = $this->UtilModel->selectQuery('product_id, article_code', 'project_room_products', [
            'where' => ['project_room_id' => $this->requestData['project_room_id']]
        ]);

        $articleCodes = array_unique(array_column($projectRoomProductData, 'article_code'));

        $articlesData = array_map(function ($article) use ($articleCodes) {
            $article['is_selected'] = (bool)in_array($article['articlecode'], $articleCodes);
            return $article;
        }, $articlesData);

        $hasMorePages = false;
        $nextCount = -1;

        if ((int)$count > ($params['offset'] + $params['limit'])) {
            $hasMorePages = true;
            $nextCount = $params['offset'] + $params['limit'];
        }

        $this->response([
            'code' => HTTP_OK,
            'msg' => $this->lang->line('success'),
            'data' => $articlesData,
            'next_count' => isset($nextCount) ? $nextCount : -1,
            'has_more_pages' => $hasMorePages,
            'per_page_count' => $params['limit'],
            'total' => isset($count) ? $count : 0,
        ]);
    }

    /**
     * Validate articles
     *
     * @return void
     */
    private function validateArticles()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'label' => 'Project Room',
                'field' => 'project_room_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'label' => 'Product',
                'field' => 'product_id',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }
}