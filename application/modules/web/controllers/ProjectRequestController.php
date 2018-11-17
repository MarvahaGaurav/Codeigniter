<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";
require_once APPPATH . "/libraries/Traits/ProjectRequestCheck.php";

class ProjectRequestController extends BaseController
{
    use ProjectRequestCheck;
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
     * Installer listing
     *
     * @param string $projectId
     * @return void
     */
    public function installerListing($projectId)
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('installer-listing');
            $this->data['js'] = 'installer-company-listing';

            $this->userTypeHandling([PRIVATE_USER, BUSINESS_USER], base_url('home/applications'));

            $languageCode = "en";
            $projectId = encryptDecrypt($projectId, "decrypt");

            $this->validationData = ['project_id' => $projectId];

            $this->validateInstallerListing();

            $this->load->model(['UtilModel']);

            $projectData = $this->UtilModel->selectQuery('id, user_id, company_id, lat, lng', 'projects', [
                'where' => ['id' => $projectId, 'language_code' => $languageCode], 'single_row' => true
            ]);

            if (empty($projectData)) {
                show404($this->lang->line('project_not_found'), base_url(''));
            }

            $this->handleRequestCheck($projectId, 'web');
            $this->load->model(['ProjectLevel']);
            $projectLevels = $this->ProjectLevel->projectLevelData([
                'where' => ['project_id' => $projectId]
            ]);

            $allLevelsDone = is_bool(array_search(0, array_column($projectLevels, 'status')));

            if (!$allLevelsDone) {
                show404($this->lang->line('complete_level_data'), base_url('home/projects/' . encryptDecrypt($projectId) . '/levels'));
            }

            if ((in_array((int)$this->userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) &&
                (int)$this->userInfo['user_id'] !== (int)$projectData['user_id']) || (in_array((int)$this->userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true) &&
                (int)$this->userInfo['company_id'] !== (int)$projectData['company_id'])) {
                show404($this->lang->line('forbidden_action'), base_url(''));
            }

            $this->requestData = $this->input->get();
            $this->postRequest = $this->input->post();

            //handle post data
            $this->postRequestHandler($projectId, $languageCode);

            $searchRadius =
                isset($this->requestData['search_radius']) && (double)$this->requestData['search_radius'] > 0 ?
                (double)$this->requestData['search_radius'] : REQUEST_SEARCH_RADIUS;

            $search = isset($this->requestData['search']) && strlen(trim($this->requestData['search'])) > 0 ?
                trim($this->requestData['search']) : '';

            $this->load->model('User');

            $params['lat'] = $projectData['lat'];
            $params['lng'] = $projectData['lng'];
            $params['search_radius'] = $searchRadius;

            if (!empty($search)) {
                $params['where']['company_name LIKE'] = '%' . $search . '%';
            }

            if (isset($this->requestData['company_id']) && is_numeric($this->requestData['company_id'])) {
                $params['where']['company.company_id'] = (int)$this->requestData['company_id'];
            }

            $data = $this->User->installers($params);

            $this->data['installers'] = $data;
            $this->data['search'] = $search;

            website_view('projects/installer-listing', $this->data);
        } catch (\Exception $error) {
            $this->db->trans_rollback();
            show404($this->lang->line('internal_server_error'), base_url('/home/applications'));
        }
    }

    /**
     * Validate installer listing
     *
     * @return void
     */
    private function validateInstallerListing()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_data($this->validationData);

        $this->form_validation->set_rules([
            [
                'field' => 'project_id',
                'label' => 'Project ID',
                'rules' => 'trim|required|is_natural_no_zero'
            ]
        ]);

        $status = $this->form_validation->run();
        if (!$status) {
            show404($this->lang->line('bad_request'), base_url('/home/applications'));
        }
    }

    private function postRequestHandler($projectId, $languageCode)
    {
        if (!empty($this->postRequest)) {
            if (!isset($this->postRequest['selected_installers']) ||
                empty($this->postRequest['selected_installers']) ||
                !is_array($this->postRequest['selected_installers'])) {
                $this->session->set_flashdata("flash-message", $this->lang->line('something_went_wrong'));
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url(uri_string()));
            }

            if (count($this->postRequest['selected_installers']) > MAXIMUM_REQUEST_COUNTS_PER_PROJECT) {
                $this->session->set_flashdata(
                    "flash-message",
                    sprintf(
                        $this->lang->line('maximum_installer_selection_exceeded'),
                        MAXIMUM_REQUEST_COUNTS_PER_PROJECT
                    )
                );
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url(uri_string()));
            }
            
            $selectedInstallers = array_filter($this->postRequest['selected_installers'], function ($companyId) {
                return is_numeric($companyId);
            });
            
            if (empty($selectedInstallers)) {
                $this->session->set_flashdata("flash-message", $this->lang->line('something_went_wrong'));
                $this->session->set_flashdata("flash-type", "danger");
                redirect(base_url(uri_string()));
            }

            $this->db->trans_begin();
            $requestID = $this->UtilModel->insertTableData([
                'language_code' => $languageCode,
                'project_id' => $projectId,
                'created_at' => $this->datetime,
                'created_at_timestamp' => $this->timestamp,
                'updated_at' => $this->datetime,
                'updated_at_timestamp' => $this->timestamp
            ], 'project_requests', true);

            $installerRequestData = array_map(function ($companyId) use ($requestID) {
                $data = [
                    'request_id' => $requestID,
                    'company_id' => $companyId,
                    'created_at' => $this->datetime,
                    'created_at_timestamp' => $this->timestamp
                ];
                return $data;
            }, $selectedInstallers);

            $this->UtilModel->insertBatch('project_request_installers', $installerRequestData);
            $this->db->trans_commit();

            $this->session->set_flashdata("flash-message", $this->lang->line('quotation_sent'));
            $this->session->set_flashdata("flash-type", "success");
            redirect(base_url('home/projects'));

        }
    }
}
