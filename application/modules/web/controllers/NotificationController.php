<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";
require APPPATH . '/libraries/Traits/Notifier.php';

class NotificationController extends BaseController
{
    use Notifier;

    public function __construct()
    {
        parent::__construct();
        error_reporting(-1);
        ini_set('display_errors', 1);
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

            $notifications = array_map(function ($notification) {
                $notification['message'] = sprintf($this->getNotificationMessage($notification['type']), $notification['sender']['full_name']);
                if ((int)$notification['type'] === NOTIFICATION_PERMISSION_GRANTED) {
                    $notification['message'] = sprintf(
                        $this->lang->line('notification_permission_granted'),
                        isset($notification['messages'], $notification['messages']['message'])? $notification['messages']['message']: ''
                    );
                    unset($notification['messages']);
                }
                if ((int)$notification['type'] === NOTIFICATION_SEND_QUOTES) {
                    $notification['redirection'] = sprintf($this->redirectionHandler()[NOTIFICATION_SEND_QUOTES], encryptDecrypt($notification['project_id']));
                } else {
                    $notification['redirection'] = $this->redirectionHandler()[$notification['type']];
                }
                $notification['redirection'] = !empty($notification['redirection'])?base_url($notification['redirection']):null;
                return $notification;
            }, $notifications);
            
            $this->data['notifications'] = $notifications;
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $notificationCount, $params['limit']);

            website_view("notifications/notification", $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('home/applications'));
        }
    }

    private function redirectionHandler()
    {
        return [
            NOTIFICATION_EMPLOYEE_REQUEST_RECEIVED => "home/technicians/requests",
            NOTIFICATION_RECEIVED_QUOTES => "home/quotes/awaiting",
            NOTIFICATION_PERMISSION_GRANTED => null,
            NOTIFICATION_SEND_QUOTES => "/home/projects/%s/quotations",
            NOTIFICATION_ACCEPT_QUOTE => "home/quotes/approved",
            NOTIFICATION_EDIT_QUOTE_PRICE => null,
            NOTIFICATION_EMPLOYEE_APPROVED => null
        ];
    }
}
