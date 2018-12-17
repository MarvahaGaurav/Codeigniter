<?php

trait Notifier
{
    /**
     * Send notification when installers receive requests for quotations
     *
     * @param int $senderId
     * @param array $companyIds
     * @param int $projectId
     * @return void
     */
    private function notifyQuotationRequest($senderId, $companyIds, $projectId)
    {
        $this->load->model(['Notification', 'User']);

        $owners = $this->User->ownerByCompany($companyIds);

        $receiverIds = [];

        if (!empty(($owners))) {
            $receiverIds = array_column($owners, 'user_id');
        }

        $notificationData = array_map(function ($id) use ($senderId, $projectId) {
            $notification['sender_id'] = $senderId;
            $notification['receiver_id'] = $id;
            $notification['type'] = NOTIFICATION_RECEIVED_QUOTES;
            $notification['project_id'] = $projectId;
            return $notification;
        }, $receiverIds);

        $this->Notification->saveNotificationInBatches($notificationData);
        $this->sendPushNotification($receiverIds, $senderId, NOTIFICATION_RECEIVED_QUOTES, $projectId);
    }

    /**
     * Notification when an employee has requested to join a company
     *
     * @param int $senderId
     * @param int $receiverId
     * @return void
     */
    private function notifyEmployeePermission($senderId, $receiverId)
    {
        $this->load->model(['Notification']);

        $notification = [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'type' => NOTIFICATION_EMPLOYEE_REQUEST_RECEIVED
        ];

        $this->Notification->saveNotification($notification);
        $this->sendPushNotification($receiverId, $senderId, NOTIFICATION_EMPLOYEE_REQUEST_RECEIVED);
    }

    /**
     * Notification when an installer sends quote for a particular request
     *
     * @param int $senderId
     * @param int $receiverId
     * @param int $projectId
     * @return void
     */
    private function notifySendQuote($senderId, $receiverId, $projectId)
    {
        $this->load->model(['Notification']);

        $notification = [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'project_id' => $projectId,
            'type' => NOTIFICATION_SEND_QUOTES
        ];

        $this->Notification->saveNotification($notification);
        $this->sendPushNotification($receiverId, $senderId, NOTIFICATION_SEND_QUOTES, $projectId);
    }

    /**
     * Notification when a customer accepts a quote for a particular quotation
     *
     * @param int $senderId
     * @param int $approvedQuotationId
     * @param int $projectId
     * @return void
     */
    private function notifyAcceptedQuotes($senderId, $approvedQuotationId, $projectId)
    {
        $this->load->model(['Notification', 'ProjectQuotation']);

        $approvedUser = $this->ProjectQuotation->approvedOwner($approvedQuotationId);

        if (!empty($approvedUser)) {

            $receiverId = $approvedUser['user_id'];

            $notification = [
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'project_id' => $projectId,
                'type' => NOTIFICATION_ACCEPT_QUOTE
            ];

            $this->Notification->saveNotification($notification);
            $this->sendPushNotification($receiverId, $senderId, NOTIFICATION_ACCEPT_QUOTE, $projectId);
        }
    }

    /**
     * Notification when an employee's permission changes
     *
     * @param int $senderId
     * @param int $receiverId
     * @param array $oldPermissions
     * @param array $newPermissions
     * @return void
     */
    private function notifyPermissionGranted($senderId, $receiverId, $oldPermissions, $newPermissions)
    {
        $this->load->model(['Notification']);

        $notification = [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'type' => NOTIFICATION_PERMISSION_GRANTED
        ];

        $message = $this->comparePermissions($oldPermissions, $newPermissions);

        if (!empty(trim($message))) {
            $notificationId = $this->Notification->saveNotification($notification);
            $this->Notification->saveNotificationMessage($notificationId, $message);
            $message = sprintf($this->lang->line('notification_permission_granted'), $message);
            $params = [
                'permissions' => $newPermissions
            ];
            $this->sendPushNotification($receiverId, $senderId, NOTIFICATION_PERMISSION_GRANTED, '', $message, $params);
        } else {
            $payLoad = [
                'type' => NOTIFICATION_PERMISSION_GRANTED,
                'permissions' => $newPermissions,
                'is_silent' => true
            ];
            $this->silentPushNotification($receiverId, $senderId, $payLoad);
        }
    }

    private function approvePermission($senderId, $receiverId, $companyName)
    {
        $this->load->model(['Notification']);

        $notification = [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'type' => NOTIFICATION_EMPLOYEE_APPROVED
        ];

        $message = sprintf($this->lang->line('notification_request_approved'), $companyName);
        $this->Notification->saveNotification($notification);
        $this->sendPushNotification($receiverId, $senderId, NOTIFICATION_EMPLOYEE_APPROVED, '', $message);
    }

    /**
     * Send silent push notification
     *
     * @param string|array $userIds
     * @param int $senderId
     * @param array $payLoad
     * @return void
     */
    private function silentPushNotification($userIds, $senderId, $payLoad = [])
    {
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        $this->load->library(['aws_push_notification']);

        $this->load->model(['User']);
        
        $senderDetail = $this->User->basicUserInfo($senderId, true);

        if (!empty($senderDetail)) {
           $payLoad = $payLoad;

           $this->aws_push_notification->sendSilentPushNotification($userIds, $payLoad);
        }
    }

    /**
     * send push notifications
     *
     * @param int|array $userIds
     * @param int $senderId
     * @param int $type
     * @param string $projectId
     * @param string $message
     * @param array $params
     * @return void
     */
    private function sendPushNotification($userIds, $senderId, $type, $projectId='', $message='', $params = [])
    {
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        $this->load->library(['aws_push_notification']);

        $this->load->model(['User']);
        
        $senderDetail = $this->User->basicUserInfo($senderId, true);

        if (!empty($senderDetail)) {
            $payLoad = [
                'message' => sprintf($this->getNotificationMessage($type), $senderDetail['full_name']),
                'type' => $type,
                'is_silent' => false
            ];

            if (!empty($message)) {
                $payLoad['message'] = $message;
            }

            if (!empty($projectId)) {
                $payLoad['project_id'] = $projectId;
            }

            if (!empty($params)) {
                $payLoad = array_merge($payLoad, $params);
            }

            $this->aws_push_notification->sendNotificationToUsers($userIds, $payLoad);
        }
    }

    /**
     * Get message based on the type of notifications
     *
     * @param int $type
     * @return string
     */
    protected function getNotificationMessage($type)
    {
        $typeMessageMapping = [
            NOTIFICATION_EMPLOYEE_REQUEST_RECEIVED => $this->lang->line('notification_employee_request_received'),
            NOTIFICATION_RECEIVED_QUOTES => $this->lang->line('notification_received_quotes'),
            NOTIFICATION_PERMISSION_GRANTED => $this->lang->line('notification_permission_granted'),
            NOTIFICATION_SEND_QUOTES => $this->lang->line('notification_send_quotes'),
            NOTIFICATION_ACCEPT_QUOTE => $this->lang->line('notification_accept_quote'),
            NOTIFICATION_EDIT_QUOTE_PRICE => $this->lang->line('notification_edit_quote_price'),
        ];

        return isset($typeMessageMapping[$type])?$typeMessageMapping[$type]:'';
    }
}
