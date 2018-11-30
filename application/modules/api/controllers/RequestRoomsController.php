<?php
defined("BASEPATH") or exit("No direct script access allowed");

require 'BaseController.php';
require_once APPPATH . "/libraries/Traits/InstallerPriceCheck.php";
require_once APPPATH . "/libraries/Traits/TotalQuotationPrice.php";
require_once APPPATH . "/libraries/Traits/InstallerRequestCheck.php";

class RequestRoomsController extends BaseController
{
    use InstallerRequestCheck, InstallerPriceCheck, TotalQuotationPrice;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function quotationRooms_get()
    {
        try {
            $user_data = $this->accessTokenCheck('u.user_type, is_owner, u.company_id');
            $language_code = $this->langcode_validate();

            $this->user = $user_data;

            $this->userTypeHandling([INSTALLER]);

            $this->handleEmployeePermission([INSTALLER], ['quotation_view']);

            $this->requestData = $this->get();


            $this->validateRequestRooms();

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

            $params['offset'] =
                isset($this->requestData['offset']) && is_numeric($this->requestData['offset']) && (int)$this->requestData['offset'] > 0 ? (int)$this->requestData['offset'] : 0;

            $this->load->model(['ProjectRooms']);
            $projectId = $this->requestData['project_id'];
            $params['where']['level'] = $this->requestData['levels'];
            $params['where']['project_id'] = $this->requestData['project_id'];
            $params['where']['language_code'] = $language_code;
            $params['limit'] = API_RECORDS_PER_PAGE;
            $roomData = $this->ProjectRooms->get($params);

            $rooms = $roomData['data'];
            $roomCount = (int)$roomData['count'];
            $totalPrice = (object)[];
            $this->load->helper('utility');
            if (!empty($rooms)) {
                $roomIds = array_column($rooms, 'project_room_id');
                $this->load->model(['ProjectRoomProducts', 'ProductMountingTypes']);
                $roomProductParams['where']['project_room_id'] = $roomIds;
                $roomProducts = $this->ProjectRoomProducts->get($roomProductParams);
                $roomProducts = $roomProducts['data'];
                $productIds = array_column($roomProducts, 'product_id');
                $productMountingTypeData = $this->ProductMountingTypes->get($productIds);
                $roomProducts = getDataWith($roomProducts, $productMountingTypeData, 'product_id', 'product_id', 'mounting_type', 'type');
                $this->load->helper('db');
                $rooms = getDataWith($rooms, $roomProducts, 'project_room_id', 'project_room_id', 'products');

                $this->load->model(['ProjectRoomQuotation']);
                $projectRoomIds = array_column($rooms, 'project_room_id');
                $roomPrice = $this->ProjectRoomQuotation->quotationInfo([
                    'where_in' => ['project_room_id' => $projectRoomIds]
                ]);
                $this->load->helper('utility');
                $rooms = getDataWith($rooms, $roomPrice, 'project_room_id', 'project_room_id', 'price');
                $rooms = array_map(function ($room) {
                    if (empty($room['price'])) {
                        $room['has_price'] = false;
                        $room['price'] = (object)[];
                    } else {
                        $room['has_price'] = true;
                        $room['price'] = $room['price'][0];
                        $room['price']['total'] = get_percentage(
                            $room['price']['price_per_luminaries'] + $room['price']['installation_charges'],
                            $room['price']['discount_price']
                        );
                    }
                    return $room;
                }, $rooms);
            } else {
                $this->response([
                    'code' => HTTP_NOT_FOUND,
                    'msg' => $this->lang->line('no_data_found')
                ]);
            }

            $hasMorePages = false;
            $nextCount = -1;

            if ((int)$roomCount > ($params['offset'] + API_RECORDS_PER_PAGE)) {
                $hasMorePages = true;
                $nextCount = $params['offset'] + API_RECORDS_PER_PAGE;
            }

            $response = [
                'code' => HTTP_OK,
                'msg' => $this->lang->line('project_rooms_fetched_successfully'),
                'data' => $rooms,
                'total' => $roomCount,
                'has_more_pages' => $hasMorePages,
                'per_page_count' => $params['limit'],
                'next_count' => $nextCount
            ];

            $response['has_added_all_price'] = $this->projectCheckPrice($projectId);

            $response['price'] = $this->quotationTotalPrice($user_data['company_id'], $projectId, $this->requestData['levels']);

            $this->response($response);
        } catch (\Exception $error) {
            $this->response([
                'code' => HTTP_INTERNAL_SERVER_ERROR,
                'api_code_result' => 'INTERNAL_SERVER_ERROR',
                'msg' => $this->lang->line("internal_server_error")
            ]);
        }
    }

    /**
     * Validate request rooms
     *
     * @return void
     */
    private function validateRequestRooms()
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
                [
                    'label' => 'Level',
                    'field' => 'levels',
                    'rules' => 'trim|required|is_natural_no_zero'
                ]
            ]
        ]);
    }
}