<?php
require_once 'BaseController.php';

class TcoController extends BaseController
{
    private $requestData;

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['form_validation', 'tco']);
        $this->load->helper(['input_data']);
        $this->data['activePage'] = 'projects';
    }

    public function tco($projectId, $level, $projectRoomId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('basic-with-font-awesome');
            $this->data['js'] = 'tco';

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");
            $projectRoomId = encryptDecrypt($projectRoomId, "decrypt");

            $this->validationData = ['project_id' => $projectId, 'level' => $level, 'project_room_id' => $projectRoomId];

            $this->validateTco();

            $status = $this->validationRun();

            if (!$status) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            $this->userTypeHandling([INSTALLER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER], ['project_add'], base_url('home/applications'));

            $this->load->model(['UtilModel', 'ProjectRooms', 'ProjectRoomProducts']);
            $projectData = $this->UtilModel->selectQuery('*', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            $levelCheck = $this->UtilModel->selectQuery('id, status', 'project_levels', [
                'where' => ['project_id' => $projectId, 'level' => $level], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            if (empty($levelCheck)) {
                show404($this->lang->line('bad_request'), base_url(''));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            

            $roomData = $this->UtilModel->selectQuery('*', 'project_rooms', [
                'where' => ['id' => $projectRoomId], 'single_row' => true
            ]);

            $tcoData = $this->UtilModel->selectQuery('*', 'project_room_tco_values', [
                'where' => ['project_room_id' => $projectRoomId], 'single_row' => true
            ]);

            if ((int)$roomData['project_id'] !== (int)$projectData['id']) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $this->requestData = $this->input->post();
            $this->requestData = trim_input_parameters($this->requestData, false);
            if (!empty($this->requestData)) {
                $this->tcoFormHandler($this->requestData, (bool)empty($tcoData), $projectRoomId, $projectId, $level);
            }

            $productData = $this->UtilModel->selectQuery('lifetime_hours, wattage, system_wattage', 'project_room_products as prp', [
                'where' => ['project_room_id' => $roomData['id']],
                'join' => ['product_specifications as ps' => 'prp.product_id=ps.product_id AND prp.article_code=ps.articlecode'],
                'single_row' => true
            ]);

            $this->data['tcoData'] = $tcoData;
            $this->data['roomData'] = $roomData;
            $this->data['productData'] = $productData;

            website_view('tco/tco', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url(''));
        }
    }

    private function tcoFormHandler($requestData, $toInsert, $projectRoomId, $projectId, $level)
    {
        $this->tco->setTcoParams($requestData);
        $roi = $this->tco->returnOnInvestment();

        $this->validateTcoForm();

        $status = $this->validationRun();
        
        $tcoData = $requestData;
        $tcoData['company_id'] = $this->userInfo['company_id'];
        if ((bool)$status) {
            if ($toInsert) {
                $tcoData['project_room_id']  = $projectRoomId;
                $tcoData['roi'] = $roi;
                
                $this->load->model("ProjectRoomTcoValue");
                $this->ProjectRoomTcoValue->insert($tcoData);
            } else {
                $tcoData['roi'] = $roi;
                $tcoData['updated_at'] = $this->datetime;
                $tcoData['updated_at_timestamp'] = $this->timestamp;
                $this->UtilModel->updateTableData($tcoData, 'project_room_tco_values', [
                    'project_room_id' => $projectRoomId
                ]);
            }
            $this->session->set_flashdata("flash-message", $this->lang->line("tco_done"));
            $this->session->set_flashdata("flash-type", "success");
            $this->session->set_flashdata("tco_roi", $roi);
            redirect(base_url('home/projects/' . encryptDecrypt($projectId) . '/levels/' . $level . '/rooms/results'));
        }
    }

    public function validateTco()
    {
        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project id',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|is_natural_no_zero'
            ],
            [
                'field' => 'project_room_id',
                'label' => 'Project room id',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);
    }

    public function validateTcoForm()
    {
        $this->form_validation->reset_validation();

        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => 'existing_number_of_luminaries',
                'label' => 'Existing number of luminaries',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_wattage',
                'label' => 'Existing wattage',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_led_source_life_time',
                'label' => 'Existing led source life time',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_hours_per_year',
                'label' => 'Existing hours per year',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_energy_price_per_kw',
                'label' => 'Existing energy price per kw',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_number_of_light_source',
                'label' => 'Existing number of light source',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_price_per_light_source',
                'label' => 'Existing price per light source',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'existing_price_to_change_light_source',
                'label' => 'Existing price to change light source',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_number_of_luminaries',
                'label' => 'New number of luminaries',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_wattage',
                'label' => 'New wattageD',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_led_source_life_time',
                'label' => 'New led source life time',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_hours_per_year',
                'label' => 'New hours per year',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_energy_price_per_kw',
                'label' => 'New energy price per kw',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_number_of_light_source',
                'label' => 'New number of light source',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_price_per_light_source',
                'label' => 'New price per light source',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'new_price_to_change_light_source',
                'label' => 'New price to change light source',
                'rules' => 'trim|required'
            ]
        ]);
    }
}
