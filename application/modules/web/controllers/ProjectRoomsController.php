<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class ProjectRoomsController extends BaseController
{

    /**
     * Post Request Data
     *
     * @var array
     */
    private $postRequest;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    /**
     * Project Room Listing
     *
     * @param string $projectId
     * @param string $level
     * @return void
     */
    public function projectCreateRoomListing($projectId, $level)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level];

            $this->validateRoomsListing();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));
            $this->load->model(['UtilModel', 'ProjectRooms']);
            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            $levelCheck = $this->UtilModel->selectQuery('id', 'project_levels', [
                'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            if (empty($levelCheck)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) ||
                (in_array((int)$this->userInfo['user_type'], [INSTALLER,WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id']
                )
            ) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $page = $this->input->get('page');

            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = 0;
            if (is_numeric($page) && (int)$page > 0) {
                $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            }
            $params['where']['project_id'] = $projectId;
            $params['where']['language_code'] = $languageCode;

            $roomData = $this->ProjectRooms->get($params);

            $this->data['rooms'] = $roomData['data'];
            $this->data['projectId'] = encryptDecrypt($projectData['id']);
            $this->data['level'] = $level;

            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), (int)$roomData['count'], $params['limit']);

            website_view('projects/levels_room_list', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url('/home/applications'));
        }
    }

    /**
     * Lists Application
     *
     * @return string
     */
    public function applications($projectId, $level)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level];

            $this->validateRoomsListing();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));
            $this->load->model(['UtilModel', 'ProjectRooms']);
            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            $levelCheck = $this->UtilModel->selectQuery('id', 'project_levels', [
                'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            if (empty($levelCheck)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) ||
                (in_array((int)$this->userInfo['user_type'], [INSTALLER,WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id']
                )
            ) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $this->load->model('Application');

            $applicationType = $this->input->get("type");

            $params['language_code'] = 'en';
            $params['type'] = APPLICATION_RESIDENTIAL;
            $params['all_data'] = true;
            $params['where']['(EXISTS(SELECT id FROM rooms WHERE application_id=app.application_id))'] = null;

            if (is_numeric($applicationType) &&
                in_array((int) $applicationType, [APPLICATION_PROFESSIONAL, APPLICATION_RESIDENTIAL], true)
            ) {
                $params['type'] = (int) $applicationType;
            }

            $applications = $this->Application->get($params);
            $applications = array_map(function ($application) {
                $application['application_id'] = encryptDecrypt($application['application_id']);
                return $application;
            }, $applications);
            
            $this->data['applicationChunks'] = array_chunk($applications, 4);
            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            $this->data['type'] = $params['type'];

            website_view('projects/application', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * List Room Type based on applications
     *
     * @param string $projectId
     * @param string $level
     * @param string $applicationId
     * @return void
     */
    public function roomType($projectId, $level, $applicationId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $applicationId = encryptDecrypt($applicationId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'application' => $applicationId];

            $this->validateRoomType();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'Application', 'Room']);

            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            $levelCheck = $this->UtilModel->selectQuery('id', 'project_levels', [
                'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            if (empty($levelCheck)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) ||
                (in_array((int)$this->userInfo['user_type'], [INSTALLER,WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id']
                )
            ) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $params['application_id'] = $applicationId;
            $application = $this->Application->details($params);
            if (empty($application)) {
                show404($this->lang->line('bad_request'), base_url('home/applications'));
            }

            $this->data['encrypted_application_id']  = encryptDecrypt($application['application_id']);
            $params['where']['rooms.application_id'] = $applicationId;
            $rooms                                   = $this->Room->get($params);
            $rooms['result']                         = array_map(function ($data) {
                $data['encrypted_room_id'] = encryptDecrypt($data['room_id']);
                return $data;
            }, $rooms['result']);

            $rooms['result'] = array_chunk($rooms['result'], 4);
            $this->data['application'] = $application;
            $this->data['project_id'] = encryptDecrypt($projectId);
            $this->data['level'] = $level;
            $this->data['roomChunks']  = $rooms['result'];

            website_view('projects/rooms_type', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * Add rooms with dimensions
     */
    public function dimensions($projectId, $level, $applicationId, $roomId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['js'] = 'room_js';

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $applicationId = encryptDecrypt($applicationId, "decrypt");
            $roomId = encryptDecrypt($roomId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'application' => $applicationId, 'room_id' => $roomId];

            $this->validateDimensionsPage();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'Application', 'Room']);

            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            $levelCheck = $this->UtilModel->selectQuery('id', 'project_levels', [
                'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            if (empty($levelCheck)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) ||
                (in_array((int)$this->userInfo['user_type'], [INSTALLER,WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id']
                )
            ) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $this->postRequest = $this->input->post();

            if (!empty($this->postRequest)) {
                $this->roomDimensionPostHandler($projectId, $level, $roomId);
            }

            $option = ["room_id" => $roomId, "where" => ["application_id" => $applicationId]];

            $this->data['room_id']        = encryptDecrypt($roomId);
            $this->data['level']          = $level;
            $this->data['units']          = ["Meter", "Inch", "Yard"];
            $this->data['application_id'] = encryptDecrypt($applicationId);
            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['room']           = $this->Room->get($option, true);

            website_view('projects/add_room_dimensions', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    private function roomDimensionPostHandler($projectId, $level, $roomId)
    {
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($this->postRequest);

        $validData = (bool)$this->form_validation->run_validation();

        if ($validData) {
            $updateData = [

            ];
        }
    }

    /**
     * Validate room Listing
     *
     * @return void
     */
    private function validateRoomsListing()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    /**
     * Validate room type listing
     *
     * @return void
     */
    private function validateRoomType()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'application',
                'label' => 'Application',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    /**
     * Validate room type listing
     *
     * @return void
     */
    private function validateDimensionsPage()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'application',
                'label' => 'Application',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'room_id',
                'label' => 'Application',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }
}
