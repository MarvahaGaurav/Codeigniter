<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Managefavorite extends REST_Controller
{

    function __construct() 
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Favorite_model');
        $this->load->library('form_validation');
    }

    public function index_post() 
    {

        $postDataArr = $this->post();
        $config = [];

        $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            ),
            array(
                'field' => 'company_id',
                'label' => 'Company Id',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {
            try {
                $respArr = $this->Common_model->getUserInfo($postDataArr['accesstoken'], ['u.user_id', 'status', 'CONCAT(first_name," ",last_name) as name']);
                $user_info = [];
                /*
                 * Response is not success if session expired or invalid access token
                 */
                if ($respArr['code'] != SUCCESS_CODE) {
                    $this->response($respArr);
                } else {
                    $user_info = $respArr['userinfo'];
                }

                /*
                 * Validate if user is not blocked
                 */
                if ($user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => []));
                }

                $whereArr = [];
                $whereArr['where'] = ['user_id' => $user_info['user_id'], 'company_id' => $postDataArr['company_id']];
                $isAlreadyFavorited = $this->Common_model->fetch_data('ai_favorite', ['id'], $whereArr, true);
                if (empty($isAlreadyFavorited)) {
                    /*
                     * Request Array
                     */
                    $favoriteInsertArr = [];
                    $favoriteInsertArr['user_id'] = $user_info['user_id'];
                    $favoriteInsertArr['company_id'] = $postDataArr['company_id'];
                    $favoriteInsertArr['created_at'] = datetime();
                    $this->db->trans_begin();
                    $isRequestSuccess = $this->Common_model->insert_single('ai_favorite', $favoriteInsertArr);
                    if (!$isRequestSuccess) {
                        throw new Exception($this->lang->line('try_again'));
                    }
                    if ($this->db->trans_status() === true) {
                        $this->db->trans_commit();
                        /*
                         * Create Android Payload
                         */
                        //                        $msg = "You profile has been set to favorite by " . $user_info['name'] . "";
                        //                        $androidPayload = [];
                        //                        $androidPayload['message'] = $msg;
                        //                        $androidPayload['user_id'] = $postDataArr['user_id'];
                        //                        $androidPayload['type'] = FAVORITE_PUSH;
                        //                        $androidPayload['time'] = time();
                        //                        /*
                        //                         * Create Ios Payload
                        //                         */
                        //                        $iosPayload = [];
                        //                        $iosPayload['alert'] = array('title' => $msg, 'user_id' => $postDataArr['user_id']);
                        //                        $iosPayload['badge'] = 0;
                        //                        $iosPayload['type'] = FAVORITE_PUSH;
                        //                        $iosPayload['sound'] = 'beep.mp3';
                        //
                        //                        $pushData = [];
                        //                        $pushData['receiver_id'] = $postDataArr['user_id'];
                        //                        $pushData['androidPayload'] = $androidPayload;
                        //                        $pushData['iosPayload'] = $iosPayload;
                        //
                        //                        $this->load->library('commonfn');
                        //                        $this->commonfn->sendPush($pushData);
                        $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('favorite_success'), 'result' => []));
                    }
                } else {
                    $this->response(array('code' => ALREADY_FAVORITE, 'msg' => $this->lang->line('already_favorite'), 'result' => []));
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $error, 'result' => []));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => []));
        }
    }

    public function index_get() 
    {

        $getDataArr = $this->input->get();

        $config = [];
        /*
         * Req type 1 pending request,2 sent pending request and empty for getting friends list
         */
        $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            )
        );

        $set_data = array(
            'accesstoken' => $this->input->get('accesstoken')
        );

        $this->form_validation->set_data($set_data);
        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {
            try {
                /*
                 * Get user id with public and private key
                 */
                $respArr = $this->Common_model->getUserInfo($getDataArr['accesstoken'], ['u.user_id', 'status']);
                $user_info = [];
                /*
                 * Response is not success if session expired or invalid access token
                 */
                if ($respArr['code'] != SUCCESS_CODE) {
                    $this->response($respArr);
                } else {
                    $user_info = $respArr['userinfo'];
                }

                /*
                 * Validate if user is not blocked
                 */
                if ($user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => []));
                }

                $page = isset($getDataArr['page']) ? $getDataArr['page'] : 1;
                $searchlike = isset($getDataArr['searchlike']) ? $getDataArr['searchlike'] : "";
                $limit = 20;
                $offset = ($page - 1) * $limit;
                $params = [];
                $params['user_id'] = $user_info['user_id'];
                $params['limit'] = $limit;
                $params['offset'] = $offset;
                $params['searchlike'] = $searchlike;

                $usersList = $this->Favorite_model->getFavorites($params);
                $msg = $this->lang->line('favorite_list_fetched');

                /*
                 * fetching recieved pending requests
                 */
                if (($usersList['count'] > ($page * $limit))) {
                    $page++;
                } else {
                    $page = 0;
                }
                
                if (!empty($usersList['result'])) {
                    $this->response(array('code' => SUCCESS_CODE, 'msg' => $msg, 'next_page' => $page, 'total_rows' => $usersList['count'], 'result' => $usersList['result']));
                } else {
                    $this->response(array('code' => NO_DATA_FOUND, 'msg' => $this->lang->line('no_favorite_found'), 'result' => []));
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                list($msg, $code) = explode(" || ", $error);
                $this->response(array('code' => $code, 'msg' => $msg, 'result' => []));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => []));
        }
    }

    public function index_delete() 
    {
        $deleteDataArr = $this->delete();
        $config = [];
        /*
         * Req type 1 if wants to reject the request 2 if wants to cancel pending request
         */
        $config = array(
            array(
                'field' => 'accesstoken',
                'label' => 'Access Token',
                'rules' => 'required'
            ),
            array(
                'field' => 'company_id',
                'label' => 'Company Id',
                'rules' => 'required'
            )
        );

        $set_data = array(
            'company_id' => $this->delete('company_id'),
            'accesstoken' => $this->delete('accesstoken')
        );

        $this->form_validation->set_data($set_data);
        $this->form_validation->set_rules($config);
        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {
            try {

                /*
                 * Get user id with public and private key
                 */
                $respArr = $this->Common_model->getUserInfo($deleteDataArr['accesstoken'], ['u.user_id', 'status']);
                $user_info = [];
                /*
                 * Response is not success if session expired or invalid access token
                 */
                if ($respArr['code'] != SUCCESS_CODE) {
                    $this->response($respArr);
                } else {
                    $user_info = $respArr['userinfo'];
                }

                /*
                 * Validate if user is not blocked
                 */
                if ($user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => []));
                }
                $this->db->trans_begin();
                $whereArr = [];
                $whereArr['where'] = ['company_id' => $deleteDataArr['company_id'], 'user_id' => $user_info['user_id']];
                $isDeleteSuccess = $this->Common_model->delete_data('ai_favorite', $whereArr);

                if (!$isDeleteSuccess) {
                    throw new Exception($this->lang->line('try_again'));
                }
                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('unfavorite_success'), 'result' => []));
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $error, 'result' => []));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => []));
        }
    }

}
