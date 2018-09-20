<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends MY_Controller
{

    function __construct() 
    {
        parent::__construct();
        $this->load->helper(['url', 'custom_cookie', 'encrypt_openssl']);
        $this->load->model('Common_model');
        $this->load->model('Admin_Model');
        $this->load->library('session');
        $this->lang->load('common', "english");
        $sessionData = validate_admin_cookie('rcc_appinventiv', 'admin');
        if ($sessionData) {
            $this->session->set_userdata('admininfo', $sessionData);
        }
        $this->admininfo = $this->session->userdata('admininfo');
        if (empty($this->admininfo)) {
            redirect(base_url() . 'admin');
        }
        $this->data = [];
        $this->data['admininfo'] = $this->admininfo;
    }

    public function index() 
    {
        $role_id = $this->admininfo['role_id'];
        /*
         * If logged user is sub admin check for his permission
         */
        $defaultPermission['addp'] = 1;
        $defaultPermission['editp'] = 1;
        $defaultPermission['deletep'] = 1;

        if ($role_id != 1) {
            $whereArr = [];
            $whereArr['where'] = array('admin_id' => $this->admininfo['admin_id'], 'access_permission' => 3, 'status' => 1);
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['addp', 'editp', 'deletep'], $whereArr, true);
        }
        $this->data['accesspermission'] = ($role_id == 2) ? $access_detail : $defaultPermission;

        $this->load->library('commonfn');
        $getDataArr = $this->input->get();
        $searchlike = isset($getDataArr['searchlike']) ? $getDataArr['searchlike'] : "";
        $limit = isset($getDataArr['limit']) ? $getDataArr['limit'] : 10;
        $page = isset($getDataArr['page']) ? $getDataArr['page'] : 1;
        $platform = isset($getDataArr['platform']) ? $getDataArr['platform'] : "";
        $startDate = isset($getDataArr['startDate']) ? $getDataArr['startDate'] : "";
        $endDate = isset($getDataArr['endDate']) ? $getDataArr['endDate'] : "";

        $params = [];
        $params['searchlike'] = $searchlike;
        $params['startDate'] = $startDate;
        $params['endDate'] = $endDate;
        $params['platform'] = $platform;

        $offset = ($page - 1) * $limit;
        $params['limit'] = $limit;
        $params['offset'] = $offset;
        $notiDetail = $this->Common_model->getNotifications($params);
        $pageurl = 'admin/notification';
        $totalrows = $notiDetail['totalRows'];
        $links = $this->commonfn->pagination($pageurl, $totalrows, $limit);
        $this->data['links'] = $links;
        $this->data['notiList'] = $notiDetail['totalRecords'];
        $this->data['searchlike'] = $searchlike;
        $this->data['limit'] = $limit;
        $this->data['platform'] = $platform;
        $this->data['startDate'] = $startDate;
        $this->data['endDate'] = $endDate;
        $this->data['page'] = $page;
        $this->data['totalrows'] = $totalrows;

        load_views("notification/index", $this->data);
    }

    public function add() 
    {

        /*
         * If logged user is sub admin check for his permission
         */
        $role_id = $this->admininfo['role_id'];
        if ($role_id != 1) {
            $whereArr = [];
            $whereArr['where'] = array('admin_id' => $this->admininfo['admin_id'], 'access_permission' => 3, 'status' => 1);
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['addp'], $whereArr, true);
            if (!$access_detail['addp']) {
                redirect('admin/notification');
            }
        }

        $this->load->helper('form');
        $postDataArr = $this->input->post();
        if (!empty($postDataArr)) {
            $isSuccess = $this->sendNotification($postDataArr);
        }
        load_views("notification/add", $this->data);
    }

    public function edit() 
    {
        /*
         * If logged user is sub admin check for his permission
         */
        $role_id = $this->admininfo['role_id'];
        if ($role_id != 1) {
            $whereArr = [];
            $whereArr['where'] = array('admin_id' => $this->admininfo['admin_id'], 'access_permission' => 3, 'status' => 1);
            $access_detail = $this->Common_model->fetch_data('sub_admin', ['editp'], $whereArr, true);
            if (!$access_detail['editp']) {
                redirect('admin/notification');
            }
        }
        $this->load->helper('form');
        $getDataArr = $this->input->get();
        $postDataArr = $this->input->post();
        $notiId = (isset($getDataArr['id']) && !empty($getDataArr['id'])) ? encryptDecrypt($getDataArr['id'], 'decrypt') : '';
        if (empty($notiId)) {
            show_404();
        }
        if (!empty($postDataArr)) {
            $this->sendNotification($postDataArr);
        } else if (!empty($notiId)) {
            $whereArr = [];
            $whereArr['where'] = array('id' => $notiId);
            $notiDetail = $this->Common_model->fetch_data('admin_notification', array(), $whereArr, true);
            if (empty($notiDetail)) {
                show_404();
            }
            $this->data['detail'] = $notiDetail;
            load_views("notification/edit", $this->data);
        } else {
            show_404();
        }
    }

    public function resendNotification() 
    {
        $notiId = $this->input->get('notiToken');
        $notiId = encryptDecrypt($notiId, 'decrypt');
        $whereArr = [];
        $whereArr['where'] = array('id' => $notiId);
        $notiDetail = $this->Common_model->fetch_data('admin_notification', array(), $whereArr, true);
        if (empty($notiDetail)) {
            show404();
        }
        $this->sendNotification($notiDetail, true);
    }

    private function sendNotification($dataArr, $isResend = false) 
    {

        $params = [];
        $params['platform'] = isset($dataArr['platform']) ? $dataArr['platform'] : "";
        $params['gender'] = isset($dataArr['gender']) ? $dataArr['gender'] : "";
        $params['regDate'] = isset($dataArr['regDate']) ? $dataArr['regDate'] : "";
        $title = isset($dataArr['title']) ? $dataArr['title'] : "";
        $message = isset($dataArr['message']) ? $dataArr['message'] : "";
        $link = isset($dataArr['link']) ? $dataArr['link'] : "";
        $notificationsList = $this->Common_model->sendNotification($params);

        $totalCounts = count($notificationsList);
        $androidArr = [];
        $iosArr = [];
        /*
         *  Make two array of android and iOS
         */
        if (!empty($notificationsList)) {
            foreach ($notificationsList as $list) {
                if ($list['platform'] == 1) {
                    $androidArr[] = $list;
                } else {
                    $iosArr[] = $list;
                }
            }
        }

        $payloadData = [];
        $payloadData['title'] = $title;
        $payloadData['link'] = $link;
        $payloadData['message'] = $message;

        if (!empty($androidArr)) {
            $newandroidArr = array_chunk($androidArr, 10);
            foreach ($newandroidArr as $arr) {
                $notiInsertArr['data'] = json_encode($arr);
                $notiInsertArr['payload_data'] = json_encode($payloadData);
                $notiInsertArr['chunk_type'] = 'android';
                $notiInsertArr['created_time'] = date('Y-m-d H:i:s');
                $chunkId = $this->Common_model->insert_single('ai_noti_chunk', $notiInsertArr);
                $this->sendNotiViaCurl($chunkId);
            }
        }

        if (!empty($iosArr)) {
            $newiosArr = array_chunk($iosArr, 10);

            foreach ($newiosArr as $arr) {
                $notiInsertArr['data'] = json_encode($arr);
                $notiInsertArr['payload_data'] = json_encode($payloadData);
                $notiInsertArr['chunk_type'] = 'ios';
                $notiInsertArr['created_time'] = date('Y-m-d H:i:s');
                $chunkId = $this->Common_model->insert_single('ai_noti_chunk', $notiInsertArr);
                $this->sendNotiViaCurl($chunkId);
            }
        }

        $pushInfoArr = [];
        $pushInfoArr['platform'] = isset($dataArr['platform']) ? $dataArr['platform'] : "";
        $pushInfoArr['gender'] = isset($dataArr['gender']) ? $dataArr['gender'] : "";
        $pushInfoArr['date_range'] = isset($dataArr['regDate']) ? $dataArr['regDate'] : "";
        $pushInfoArr['title'] = isset($dataArr['title']) ? $dataArr['title'] : "";
        $pushInfoArr['message'] = isset($dataArr['message']) ? $dataArr['message'] : "";
        $pushInfoArr['link'] = isset($dataArr['link']) ? $dataArr['link'] : "";
        $pushInfoArr['image'] = isset($dataArr['image']) ? $dataArr['image'] : "";
        $pushInfoArr['total_sents'] = $totalCounts;
        $pushInfoArr['created_at'] = date('Y-m-d H:i:s');

        if (isset($dataArr['id']) && !empty($dataArr['id'])) {
            $whereArr = [];
            $whereArr['where'] = array('id' => $dataArr['id']);
            $isSuccess = $this->Common_model->update_single('admin_notification', $pushInfoArr, $whereArr);
        } else {
            $isSuccess = $this->Common_model->insert_single('admin_notification', $pushInfoArr);
        }
        $alertMsg = [];
        if ($isSuccess) {
            $alertMsg['text'] = $this->lang->line('notification_added');
            $alertMsg['type'] = $this->lang->line('success');
            $this->session->set_flashdata('alertMsg', $alertMsg);
        } else {
            $alertMsg['text'] = $this->lang->line('try_again');
            $alertMsg['type'] = $this->lang->line('error');
            $this->session->set_flashdata('alertMsg', $alertMsg);
        }

        if ($isResend) {
            echo json_encode(array('code' => 200, 'msg' => 'Success'));
            die;
        } else {
            redirect('/admin/notification');
        }
    }

    public function sendNotiViaCurl($chunkId) 
    {

        $url = base_url() . 'admin/notify?chunkId=' . $chunkId;
        $header = array();
        $ch = curl_init();
        $timeout = 1;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        $data = curl_exec($ch);
        curl_close($ch);
        return;
    }

}
