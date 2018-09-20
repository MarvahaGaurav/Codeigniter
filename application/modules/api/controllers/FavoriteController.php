<?php
defined("BASEPATH") or exit("No direct script access allowed");
require 'BaseController.php';

use DatabaseExceptions\InsertException;

class FavoriteController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @SWG\Post(path="/company/favorite",
     *   tags={"Company"},
     *   summary="Favorite and unfavorite a company",
     *   description="",
     *   operationId="favorite_post",
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
     *     name="is_favorite",
     *     in="formData",
     *     description="is_favorite = 1 to favorite, is_favorite = 2 to unfavorite",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="company_id",
     *     in="formData",
     *     description="company id",
     *     type="string"
     *   ),
     * @SWG\Response(response=200, description="OK"),
     * @SWG\Response(response=422, description="missing parameter/invalid parameter"), 
     * @SWG\Response(response=500, description="Internal Server Error"), 
     * )
     */
    public function favorite_post()
    {
        $language_code = $this->langcode_validate();
        $userData = $this->accessTokenCheck();
        $postData = $this->post();
        $postData = trim_input_parameters($postData);
        $mandatoryFields = ["is_favorite", "company_id"];
        $validFavorite = [1, 2];
        $check = check_empty_parameters($postData, $mandatoryFields);

        if ($check["error"]) {
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

        if (!in_array($postData['is_favorite'], $validFavorite)) {
            $this->response(
                [
                'code' => HTTP_UNPROCESSABLE_ENTITY,
                'api_code_result' => 'UNPROCESSABLE_ENTITY',
                'msg' => $this->lang->line('invalid_parameter'),
                'extra_info' => "is_favorite"
                ]
            );
        }
        $isFavorite = (int)$postData['is_favorite'] === 1 ? 1 : 0;

        $this->load->model("Favorite_model");

        $where = ['user_id' => $userData['user_id'], 'company_id' => $postData['company_id']];
        $favoriteData = $this->UtilModel->selectQuery(
            'id',
            'ai_favorite',
            ['where' => $where, 'single_row' => true]
        );

        if (empty($favoriteData) ) {
            $this->Favorite_model->user_id = $userData['user_id'];
            $this->Favorite_model->company_id = $postData['company_id'];
            $this->Favorite_model->created_at = $this->datetime;
        }
        
        $this->Favorite_model->is_favorite = $isFavorite;
       
        try {
            if (empty($favoriteData) ) {
                $this->Favorite_model->save();
            } else {
                $this->Favorite_model->update($where);
            }
            $this->response(
                [
                'code' => HTTP_OK,
                'api_code_result' => 'OK',
                'msg' => $this->lang->line("favorite_list_updated")
                ]
            );
        } catch (InsertException $error) {
            $this->response(
                [
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line('internal_server_error'),
                ]
            );
        }
    }
}