<?php
require_once 'BaseController.php';

/**
 * Comapany Controller
 */
class QuickCalcController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->neutralGuard();
        if (isset($this->userInfo, $this->userInfo['user_id']) && !empty($this->userInfo['user_id'])) {
            $this->data['userInfo'] = $this->userInfo;
        }
    }

    /**
     * Lists Application
     *
     * @return string
     */
    public function applications()
    {
        try {
            $this->load->model('Application');

            $applicationType = $this->input->get("type");

            $params['language_code'] = 'en';
            $params['type'] = APPLICATION_RESIDENTIAL;
            $params['all_data'] = true;
            $params['where']['(EXISTS(SELECT id FROM rooms WHERE application_id=app.application_id))'] = null;

            if (is_numeric($applicationType) &&
                in_array((int)$applicationType, [APPLICATION_PROFESSIONAL, APPLICATION_RESIDENTIAL], true)
            ) {
                $params['type'] = (int)$applicationType;
            }

            $applications = $this->Application->get($params);
            $this->data['applicationChunks'] = array_chunk($applications, 4);
            $this->data['type'] = $params['type'];

            website_view('quickcalc/application', $this->data);
        } catch (\Exception $error) {
        }
    }

    /**
     * Display Rooms by Application
     *
     * @return void
     */
    public function rooms($applicationId = '')
    {
        try {
            $applicationId = encryptDecrypt($applicationId, 'decrypt');

            $this->load->model(['Application', 'Room']);

            if (empty($applicationId)) {
                show404('Invalid Request', base_url('home/applications'));
            }
            
            $params['application_id'] = $applicationId;
            $application = $this->Application->details($params);
            if (empty($application)) {
                show404('Data Not Found', base_url('home/applications'));
            }

            $this->data['encrypted_application_id'] = encryptDecrypt($application['application_id']);
            $params['where']['rooms.application_id'] = $applicationId;
            $rooms = $this->Room->get($params);
            $rooms['result'] = array_map(function ($data) {
                $data['encrypted_room_id'] = encryptDecrypt($data['room_id']);
                return $data;
            }, $rooms['result']);
            $rooms['result'] = array_chunk($rooms['result'], 4);

            $this->data['application'] = $application;
            $this->data['roomChunks'] = $rooms['result'];
            
            website_view('quickcalc/rooms', $this->data);
        } catch (\Exception $error) {

        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function quickcalc($applicationId, $roomId)
    {
        try {
            $this->data['js'] = 'quickcalc';

            $this->data['applicationId'] = $applicationId;
            $this->data['roomId'] = $roomId;

            $applicationId = encryptDecrypt($applicationId, 'decrypt');
            $roomId = encryptDecrypt($roomId, 'decrypt');

            if (empty($applicationId) || empty($roomId)) {
                show404('Invalid Request', base_url('home/applications'));
            }

            $this->load->model(['Application', 'Room']);

            $params['application_id'] = $applicationId;

            $application = $this->Application->details($params);
            if (empty($application)) {
                show404('Data Not Found', base_url('home/applications'));
            }
            $params['room_id'] = $roomId;
            $room = $this->Room->get($params);
            $this->data['room'] = $room;
            
            website_view('quickcalc/quickcalc', $this->data);
        } catch (\Exception $error) {
        }
    }
}
