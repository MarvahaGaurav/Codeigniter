<?php

trait Notifier
{
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
            $this->sendPushNotification($receiverId, $senderId, NOTIFICATION_PERMISSION_GRANTED, '', $message);
        }
    }

    private function sendPushNotification($userIds, $senderId, $type, $projectId='', $message='')
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
                'type' => $type
            ];

            if (!empty($message)) {
                $payLoad['message'] = $message;
            }

            if (!empty($projectId)) {
                $payLoad['project_id'] = $projectId;
            }

            $this->aws_push_notification->sendNotificationToUsers($userIds, $payLoad);
        }
    }

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
