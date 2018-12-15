<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';

class ProjectTcoController extends BaseController
{
    private $requestData;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function saveTco_post()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->requestData = $this->post();

            $this->validateTcoData();

            $this->validationRun();

            $this->requestData = trim_input_parameters($this->requestData, false);

            $this->roomDataCheck($this->requestData['project_room_id']);

            $this->tcoCheck($this->requestData['project_room_id']);

            $this->load->model(['ProjectRoomTcoValue']);

            $this->requestData['company_id'] = $user_data['company_id'];

            $this->ProjectRoomTcoValue->insert($this->requestData);

            $this->response([
                'code' => HTTP_OK,
                'msg' => $this->lang->line('tco_value_successfully_updated')
            ]);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    private function tcoCheck($projectRoomId)
    {
        $tcoData = $this->UtilModel->selectQuery('id', 'project_room_tco_values', [
            'where' => ['project_room_id' => $projectRoomId], 'single_row' => true
        ]);

        if (!empty($tcoData)) {
            $this->response([
                'code' => HTTP_BAD_REQUEST,
                'msg' => $this->lang->line('tco_already_done')
            ]);
        }
    }

    /**
     * @todo - include checks for project and quotes seperatley
     *
     * @param [type] $projectRoomId
     * @return void
     */
    private function roomDataCheck($projectRoomId)
    {
        $roomData = $this->UtilModel->selectQuery('pr.id, p.user_id, p.company_id', 'project_rooms as pr', [
            'where' => ['pr.id' => $projectRoomId], 'single_row' => true,
            'join' => ['projects as p' => 'p.id=pr.project_id']
        ]);

        if (empty($roomData)) {
            $this->response([
                'code' => HTTP_NOT_FOUND,
                'msg' => $this->lang->line('no_room_found')
            ]);
        }

        // if ((int)$roomData['company_id'] !== (int)$this->user['company_id']) {
        //     $this->response([
        //         'code' => HTTP_FORBIDDEN,
        //         'msg' => $this->lang->line('forbidden_action')
        //     ]);
        // } 
    }

    /**
     * Validate TCO data
     */
    private function validateTcoData()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                'field' => "project_room_id",
                'label' => "Project room id",
                'rules' => 'trim|required'
            ],
            [
                'field' => "existing_number_of_luminaries",
                'label' => "Existing number of luminaries",
                'rules' => 'trim|required'
            ],
            [
                'field' => "existing_wattage",
                'label' => "Existing wattage",
                'rules' => 'trim|required'
            ],
            [
                'field' => "existing_led_source_life_time",
                'label' => "Existing led source life time",
                'rules' => 'trim|required'
            ],
            [
                'field' => "existing_hours_per_year",
                'label' => "Existing hours per year",
                'rules' => 'trim|required'
            ],
            [
                'field' => "existing_energy_price_per_kw",
                'label' => "Existing energy price per kw",
                'rules' => 'trim|required'
            ],
            [
                'field' => "existing_number_of_light_source",
                'label' => "Existing number of light source",
                'rules' => 'trim|required'
            ],
            [
                'field' => "existing_price_per_light_source",
                'label' => "Existing price per light source",
                'rules' => 'trim|required'
            ],
            [
                'field' => "existing_price_to_change_light_source",
                'label' => "Existing price to change light source",
                'rules' => 'trim|required'
            ],
            [
                'field' => "new_number_of_luminaries",
                'label' => "New number of luminaries",
                'rules' => 'trim|required'
            ],
            [
                'field' => "new_wattage",
                'label' => "New wattage",
                'rules' => 'trim|required'
            ],
            [
                'field' => "new_led_source_life_time",
                'label' => "New led source life time",
                'rules' => 'trim|required'
            ],
            [
                'field' => "new_hours_per_year",
                'label' => "New hours per year",
                'rules' => 'trim|required'
            ],
            [
                'field' => "new_energy_price_per_kw",
                'label' => "New energy price per kw",
                'rules' => 'trim|required'
            ],
            [
                'field' => "new_number_of_light_source",
                'label' => "New number of light source",
                'rules' => 'trim|required'
            ],
            [
                'field' => "new_price_per_light_source",
                'label' => "New price per light source",
                'rules' => 'trim|required'
            ],
            [
                'field' => "new_price_to_change_light_source",
                'label' => "New price to change light source",
                'rules' => 'trim|required'
            ],
            [
                'field' => "roi",
                'label' => "Roi",
                'rules' => 'trim|required'
            ],
        ]);
    }

}
