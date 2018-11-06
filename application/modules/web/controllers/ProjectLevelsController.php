<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

class ProjectLevelsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Level lisitng in project create flow
     *
     * @return void
     */
    public function levelsListing()
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('project-levels');

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));

            $languageCode = "en";

            $projectId = $this->session->userdata('project_id');
            $this->load->model("UtilModel");

            if (empty($projectId)) {
                show404($this->lang->line('bad_request'), base_url('/home/applications'));
            }

            $projectData = $this->UtilModel->selectQuery('id', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode]
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url('/home/applications'));
            }

            $this->load->model(["ProjectLevel", "ProjectRooms"]);
            $roomCount = $this->ProjectRooms->roomCountByLevel($projectId);

            $projectLevels = $this->ProjectLevel->projectLevelData([
                'where' => ['project_id' => $projectId]
            ]);

            $this->load->helper(['project', 'db']);

            $projectLevels = level_activity_status_handler($projectLevels);

            $projectLevels = 
            getDataWith($projectLevels, $roomCount, 'level', 'level', 'room_count', 'room_count');
            $projectLevels = array_map(function ($project) {
                $project['room_count'] = is_array($project['room_count'])&&
                    count($project['room_count'])&&
                    isset($project['room_count'][0])?(int)$project['room_count'][0]:0;
                return $project;
            }, $projectLevels);

            $this->data['projectLevels'] = $projectLevels;
            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['permissions'] = $permissions;

            $this->data['all_levels_done'] = is_bool(array_search(0, array_column($projectLevels, 'status')));

            website_view('projects/levels-listing', $this->data);
        } catch (\Exception $error) {
        }
    }
}
