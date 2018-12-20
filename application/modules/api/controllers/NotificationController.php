<?php 
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';
require APPPATH . '/libraries/Traits/Notifier.php';

class NotificationController extends BaseController
{
    use Notifier;

    private $requestData;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(["Notification"]);
    }

    public function index_get()
    {
        try {
            $userData = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->requestData = $this->get();

            $params['offset'] =
                isset($this->requestData['offset']) && is_numeric($this->requestData['offset']) && (int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset'] : 0;
            $params['limit'] = API_RECORDS_PER_PAGE;
            $params['user_id'] = $userData['user_id'];

            $notifications = $this->Notification->getNotifications($params);
            $notificationCount = $notifications['count'];
            $notifications = $notifications['data'];

            if (empty($notifications)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line("no_notification_found")
                ]);
            }

            $notifications = array_map(function ($notification) {
                $notification['message'] = $this->getNotificationMessage($notification['type']);
                if ((int)$notification['type'] === NOTIFICATION_PERMISSION_GRANTED) {
                    $notification['message'] = sprintf(
                        $this->lang->line('notification_permission_granted'),
                        isset($notification['messages'], $notification['messages']['message'])? $notification['messages']['message']: ''
                    );
                } else if ((int)$notification['type'] === NOTIFICATION_ADMIN_NOTIFICATION && isset($notification['admin_messages'], $notification['admin_messages']['title'])) {
                    $notification['message'] = $notification['admin_messages']['title'];
                }

                if ((int)$notification['type'] === NOTIFICATION_ADMIN_NOTIFICATION) {
                    unset($notification['sender_id']);
                }
                $notification['sender'] = empty($notification['sender'])?(object)$notification['sender']:$notification['sender'];
                unset($notification['messages'], $notification['admin_messages']);
                return $notification;
            }, $notifications);

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$notificationCount > ($params['offset'] + API_RECORDS_PER_PAGE)) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + API_RECORDS_PER_PAGE;
            }

            $response = [
                'code' => HTTP_OK,
                'msg' => $this->lang->line('notification_fetched'),
                'data' => $notifications,
                'total' => $notificationCount,
                'has_more_pages' => $hasMorePages,
                'per_page_count' => $params['limit'],
                'next_count' => $nextCount
            ];

            $this->response($response);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }
}
