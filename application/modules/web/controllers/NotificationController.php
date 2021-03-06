<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class NotificationController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['activePage'] = 'notifications';
    }

    public function index()
    {
        try {
            $this->activeSessionGuard();

            $this->requestData = $this->input->get();
            $this->load->helper(['datetime']);
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('basic-with-font-awesome');

            $page = isset($this->requestData['page'])?abs((int)$this->requestData['page']):1;

            $params['limit'] = RECORDS_PER_PAGE;
            $params['offset'] = ($page - 1) * RECORDS_PER_PAGE;
            $params['user_id'] = $this->userInfo['user_id'];

            $this->load->model(['Notification']);

            $notifications = $this->Notification->getNotifications($params);
            $notificationCount = $notifications['count'];
            $notifications = $notifications['data'];

            $notifications = $this->processNotifications($notifications);

            $this->data['notifications'] = $notifications;
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $notificationCount, $params['limit']);

            website_view("notifications/notification", $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('home/applications'));
        }
    }

}
