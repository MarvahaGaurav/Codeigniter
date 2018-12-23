<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";
require_once APPPATH . "/libraries/Traits/LevelRoomCheck.php";
require_once APPPATH . "/libraries/Traits/TotalProjectPrice.php";
require_once APPPATH . "/libraries/Traits/TechnicianChargesCheck.php";
require_once APPPATH . "/libraries/Traits/InstallerPriceCheck.php";

class ProjectLevelsController extends BaseController
{

    use LevelRoomCheck, InstallerPriceCheck, TotalProjectPrice, TechnicianChargesCheck;

    public function __construct()
    {
        parent::__construct();
        $this->data['activePage'] = 'projects';
    }
    

    /**
     * Level lisitng in project create flow
     *
     * @return void
     */
    public function levelsListing($projectId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('project-levels');
            $this->data['js'] = 'level-listing';

            $this->userTypeHandling([INSTALLER, PRIVATE_USER, BUSINESS_USER, WHOLESALER, ELECTRICAL_PLANNER], base_url('home/applications'));

            $permissions = $this->handleEmployeePermission([INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], ['project_view'], base_url('home/applications'));

            $languageCode = "en";

            $projectId = encryptDecrypt($projectId, 'decrypt');
            $this->load->model("UtilModel");

            if (empty($projectId) || !is_numeric($projectId)) {
                show404($this->lang->line('bad_request'), base_url('/home/applications'));
            }

            $projectData = $this->UtilModel->selectQuery('id, user_id, company_id', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url('/home/applications'));
            } 

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) ||
                (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
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

            $this->data['projectId'] = encryptDecrypt($projectId);
            $this->data['permissions'] = $permissions;

            $activeLevels = array_filter($projectLevels, function ($projectLevel) {
                return (bool)$projectLevel['active'];
            });

            $activeLevels = array_column($activeLevels, 'level');
            $activeLevels = array_map(function ($level) {return (int)$level;}, $activeLevels);

            $projectLevels = array_map(function ($level) use ($activeLevels) {
                $level['level'] = (int)$level['level'];
                $level['data'] = json_encode([
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                    'project_id' => $this->data['projectId'],
                    'level' => $level['level']
                ]);
                $level['room_count'] = is_array($level['room_count']) &&
                    count($level['room_count']) &&
                    isset($level['room_count'][0]) ? (int)$level['room_count'][0] : 0;

                $level['cloneable_destinations'] = json_encode(array_values(array_filter($activeLevels, function ($activeLevel) use ($level) {
                    return (int)$activeLevel !== $level['level'] && (bool)$level['active'];
                })));
                return $level;
            }, $projectLevels);

            $this->data['csrf'] = json_encode([
                $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                'project_id' => $this->data['projectId']
            ]);
            
            $this->data['active_levels'] = $activeLevels;
            
            $this->data['projectLevels'] = $projectLevels;

            $this->data['quotationRequest'] = $this->UtilModel->selectQuery('id', 'project_requests', [
                'where' => ['project_id' => $projectId]
            ]);

            $this->data['all_levels_done'] = is_bool(array_search(0, array_column($projectLevels, 'status')));

            $this->data['hasAddedAllPrice'] = false;
            $this->data['projectRoomPrice'] = [];
            $this->data['hasAddedFinalPrice'] = false;
            $this->data['permission'] = $permissions;
            if (in_array((int)$this->userInfo['user_type'], [INSTALLER], true)) {
                $this->load->helper(['utility']);
                $this->data['hasAddedAllPrice'] = $this->projectCheckPrice($projectId);
                $this->data['projectRoomPrice'] = (array)$this->quotationTotalPrice((int)$this->userInfo['user_type'], $projectId);
                $this->data['hasAddedFinalPrice'] = $this->hasTechnicianAddedFinalPrice($projectId);
            }
            //pr($this->data);
            website_view('projects/levels-listing', $this->data);
        } catch (\Exception $error) {
        }
    }
}
