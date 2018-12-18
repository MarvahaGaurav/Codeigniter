<?php

require_once 'BaseModel.php';

use DatabaseExceptions\SelectException;

class Notification extends BaseModel
{

    /**
     * Datetime string Y-m-d H:i:s
     *
     * @var string
     */
    private $datetime;
    private $timestamp;

    public function __construct()
    {
        parent::__construct();
        $this->datetime = date("Y-m-d H:i:s");
        $this->timestamp = time();
        $this->tableName = 'notifications';
    }

    public function getNotifications($params)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS *', false)
            ->from($this->tableName)
            ->where('receiver_id', $params['user_id'])
            ->order_by('id', 'DESC');

        if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
            $this->db->limit((int)$params['limit']);
        }

        if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
            $this->db->offset((int)$params['offset']);
        }

        if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
            foreach ($params['where'] as $tableColumn => $searchValue) {
                $this->db->where($tableColumn, $searchValue);
            }
        }

        $query = $this->db->get();

        $result['data'] = $query->result_array();
        $result['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row_array()['count'];

        if (!empty($result['data'])) {
            $this->load->helper(['db']);
            $this->load->model(['User']);
            $sender = array_unique(array_column($result['data'], 'sender_id'));
            $sender = $this->User->basicUserInfo($sender);
            $result['data'] = getDataWith($result['data'], $sender, 'sender_id', 'user_id', 'sender', '', true);
            $notificationMessages = $this->getNotificationMessages(array_column($result['data'], 'id'));
            $result['data'] = getDataWith($result['data'], $notificationMessages, 'id', 'notification_id', 'messages', '', true);
        }

        return $result;
    }

    public function saveNotification($data)
    {
        $notification = [];
        $notification['sender_id'] = $data['sender_id'];
        $notification['receiver_id'] = $data['receiver_id'];
        $notification['type'] = $data['type'];
        if (isset($data['project_id']) && is_numeric($data['project_id'])) {
            $notification['project_id'] = $data['project_id'];
        }

        $notification['created_at'] = $this->datetime;
        $notification['created_at_timestamp'] = $this->timestamp;

        $status = $this->db->set($notification)
            ->insert($this->tableName);

        if (!(bool)$status) {
            throw new \Exception('notification insert error');
        }

        return $this->db->insert_id();
    }

    public function saveNotificationInBatches($data)
    {
        $notifications = [];

        $notifications = array_map(function ($notificationData) {
            $notification['sender_id'] = $notificationData['sender_id'];
            $notification['receiver_id'] = $notificationData['receiver_id'];
            $notification['type'] = $notificationData['type'];
            if (isset($notificationData['project_id']) && is_numeric($notificationData['project_id'])) {
                $notification['project_id'] = $notificationData['project_id'];
            }

            $notification['created_at'] = $this->datetime;
            $notification['created_at_timestamp'] = $this->timestamp;
            return $notification;
        }, $data);


        $status = $this->db->insert_batch($this->tableName, $notifications);

        if (!(bool)$status) {
            throw new \Exception('notification insert error');
        }
    }

    public function saveNotificationMessage($notificationId, $message)
    {
        $status = $this->db->set([
            'notification_id' => $notificationId,
            'message' => $message
        ])->insert('notification_messages');

        if (!(bool)$status) {
            throw new \Exception('notification insert error');
        }
    }

    public function getNotificationMessages($notificationIds)
    {
        $this->db->select('*')
         ->from('notification_messages')
         ->where_in('notification_id', $notificationIds);
        
        $result = $this->db->get()->result_array();

        return $result;
    }
}
