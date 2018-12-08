<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';
require_once APPPATH . "/libraries/Traits/InstallerPriceCheck.php";
require_once APPPATH . "/libraries/Traits/TotalQuotationPrice.php";
require_once APPPATH . "/libraries/Traits/InstallerRequestCheck.php";

class RequestLevelsController extends BaseController
{
    use InstallerRequestCheck, InstallerPriceCheck, TotalQuotationPrice;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index_get()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER]);

            $this->handleEmployeePermission([INSTALLER], ['quote_view']);

            $this->requestData = $this->get();

            $this->validateRequestLevels();

            // $this->validationRun();

            $requestData = $this->isRequestedTo($this->requestData['request_id'], $user_data);

            if (empty($requestData)) {
                $this->response([
                    'code' => HTTP_FORBIDDEN,
                    'msg' => $this->lang->line('not_received_this_request')
                ]);
            }

            if ((int)$requestData['project_id'] !== (int)$this->requestData['project_id']) {
                $this->response([
                    'code' => HTTP_BAD_REQUEST,
                    'msg' => $this->lang->line('invalid_project')
                ]);
            }
            
            $this->load->model('ProjectLevel');

            $params['project_id'] = $this->requestData['project_id'];
            $projectId = $this->requestData['project_id'];
            $data = $this->ProjectLevel->get($params);
            $projectLevels = $data['data'];
            $totalPrice = (object)[];

            if (empty($projectLevels)) {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $this->load->helper(['project', 'db']);
            //add activity key to all project level data
            $projectLevels = level_activity_status_handler($projectLevels);

            // $totalPrice = $this->handleTotalPrice(
            //     (int)$user_data['user_type'],
            //     ['project_id' => $this->requestData['project_id'], 'company_id' => $user_data['company_id']]
            // );

            $this->load->model(['ProjectRooms']);
            $roomCount = $this->ProjectRooms->roomCountByLevel($this->requestData['project_id']);

            $projectLevels = getDataWith($projectLevels, $roomCount, 'level', 'level', 'room_count', 'room_count');
            $projectLevels = array_map(function ($project) {
                $project['room_count'] = is_array($project['room_count'])&&
                    count($project['room_count'])&&
                    isset($project['room_count'][0])?(int)$project['room_count'][0]:0;
                return $project;
            }, $projectLevels);
            
            $projectLevelStatus = is_bool(array_search(0, array_column($projectLevels, 'status')));

            $response = [
                'code' => HTTP_OK,
                'msg' => $this->lang->line('success'),
                'data' => $projectLevels,
                'project_level_status' => $projectLevelStatus,
                'total_price' => $totalPrice,
            ];

            $params['company_id'] = (int)$user_data['company_id'];

            $response['has_added_all_price'] = $this->projectCheckPrice($projectId);
            $companyData = $this->UtilModel->selectQuery('company_discount', 'company_master', [
                'where' => ['company_id' => $user_data['company_id']], 'single_row' => true
            ]);

            $response['company_discount'] = $companyData['company_discount'];

            $response['total_price'] = $this->quotationTotalPrice($user_data['company_id'], $projectId);
            
            $this->response($response);
        } catch (\Exception $error) {
            pd($error->getMessage());
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Validate request levels
     *
     * @return void
     */
    private function validateRequestLevels()
    {
        $this->form_validation->set_data($this->requestData);

        $this->form_validation->set_rules([
            [
                [
                    'label' => 'Request',
                    'field' => 'request_id',
                    'rules' => 'trim|required|is_natural_no_zero'
                ],
                [
                    'label' => 'Project',
                    'field' => 'project_id',
                    'rules' => 'trim|required|is_natural_no_zero'
                ],
            ]
        ]);
    }
}