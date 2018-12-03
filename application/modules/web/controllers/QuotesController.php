<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once "BaseController.php";

class QuotesController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->activeSessionGuard();
        $this->load->helper(['datetime']);
    }



    public function index()
    {
        $this->data['userInfo'] = $this->userInfo;
        if (!empty($this->userInfo['user_id']) &&
            isset($this->userInfo['status']) &&
            $this->userInfo['status'] != BLOCKED) {
            $this->activeSessionGuard();

            $this->userTypeHandling([BUSINESS_USER, PRIVATE_USER], base_url('home/applications'));

            $this->load->model(['ProjectRequest']);
            $get = $this->input->get();

            $search = isset($get['search'])&&is_string($get['search'])&&strlen(trim($get['search']))>0?trim($get['search']):'';
            $page = isset($get['page']) && (int)$get['page'] > 1 ? (int)$get['page'] : 1;
            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            $params['user_id'] = $this->userInfo['user_id'];
            $params['language_code'] = $this->languageCode;
            if (strlen($search) > 0) {
                $params['where']["(name LIKE '%{$search}%' OR projects.address LIKE '%{$search}%')"] = null;
            }

            $data = $this->ProjectRequest->customerRequests($params);

            $this->data['quotations'] = $data['data'];
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $data['count'], $params['limit']);
            $this->data['search'] = $search;

            website_view("quotes/main", $this->data);
        } else {
            website_view("quotes/main_inactive_session", $this->data);
        }
    }

    public function customerList()
    {

    }

    /**
     * Installer awaiting quotes listing
     *
     * @return void
     */
    public function awaiting()
    {
        try {
            $this->activeSessionGuard();
            $this->userTypeHandling([INSTALLER], base_url('home/applications'));

            $this->load->model(['ProjectRequest']);
            $get = $this->input->get();

            $search = isset($get['search'])&&is_string($get['search'])&&strlen(trim($get['search']))>0?trim($get['search']):'';
            $page = isset($get['page']) && (int)$get['page'] > 1 ? (int)$get['page'] : 1;
            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            $params['company_id'] = $this->userInfo['company_id'];
            $params['language_code'] = $this->languageCode;
            $params['lat'] = 0;
            $params['lng'] = 0;
            if (strlen($search) > 0) {
                $params['where']["(name LIKE '%{$search}%' OR projects.address LIKE '%{$search}%')"] = null;
            }

            $data = $this->ProjectRequest->awaitingRequest($params);

            $this->data['quotations'] = $data['data'];
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $data['count'], $params['limit']);
            $this->data['search'] = $search;

            website_view('quotes/awaiting', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }


    /**
     * Installer submitted quotes listing
     *
     * @return void
     */
    public function submitted()
    {
        try {
            $this->activeSessionGuard();
            $this->userTypeHandling([INSTALLER], base_url('home/applications'));

            $this->load->model(['ProjectRequest', 'ProjectRoomQuotation']);
            $get = $this->input->get();

            $search = isset($get['search'])&&is_string($get['search'])&&strlen(trim($get['search']))>0?trim($get['search']):'';
            $page = isset($get['page']) && (int)$get['page'] > 1 ? (int)$get['page'] : 1;
            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            $params['company_id'] = $this->userInfo['company_id'];
            $params['language_code'] = $this->languageCode;
            if (strlen($search) > 0) {
                $params['where']["(name LIKE '%{$search}%' OR projects.address LIKE '%{$search}%')"] = null;
            }

            $data = $this->ProjectRequest->submittedRequestList($params);

            if (!empty($data['data'])) {
                $projectIds = array_column($data['data'], 'project_id');
                $price = $this->ProjectRoomQuotation->quotationPrice($this->userInfo['company_id'], $projectIds);
                $this->load->helper(['db', 'utility']);
                $data['data'] = getDataWith($data['data'], $price, 'project_id', 'project_id', 'price');
                $data['data'] = $this->processPriceData($data['data']);
            }

            $this->data['quotations'] = $data['data'];
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $data['count'], $params['limit']);
            $this->data['search'] = $search;

            website_view('quotes/submitted', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * Installer approved quotation listing
     *
     * @return void
     */
    public function approved()
    {
        try {
            $this->activeSessionGuard();
            $this->userTypeHandling([INSTALLER], base_url('home/applications'));

            $this->load->model(['ProjectRequest', 'ProjectRoomQuotation']);
            $get = $this->input->get();

            $search = isset($get['search'])&&is_string($get['search'])&&strlen(trim($get['search']))>0?trim($get['search']):'';
            $page = isset($get['page']) && (int)$get['page'] > 1 ? (int)$get['page'] : 1;
            $params['limit'] = WEB_PAGE_LIMIT;
            $params['offset'] = ($page - 1) * WEB_PAGE_LIMIT;
            $params['company_id'] = $this->userInfo['company_id'];
            $params['language_code'] = $this->languageCode;
            if (strlen($search) > 0) {
                $params['where']["(name LIKE '%{$search}%' OR projects.address LIKE '%{$search}%')"] = null;
            }

            $data = $this->ProjectRequest->approvedRequests($params);

            if (!empty($data['data'])) {
                $projectIds = array_column($data['data'], 'project_id');
                $price = $this->ProjectRoomQuotation->quotationPrice($this->userInfo['company_id'], $projectIds);
                $this->load->helper(['db', 'utility']);
                $data['data'] = getDataWith($data['data'], $price, 'project_id', 'project_id', 'price');
                $data['data'] = $this->processPriceData($data['data']);
            }

            $this->data['quotations'] = $data['data'];
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $data['count'], $params['limit']);
            $this->data['search'] = $search;

            website_view('quotes/approved', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }

    /**
     * Process price data
     *
     * @param array $data
     * @return array
     */
    private function processPriceData($data) 
    {
        $priceData = array_map(function ($quotation) {
            $initialPrice = [
                "price_per_luminaries" => 0.00,
                "installation_charges" => 0.00,
                "discount_price" => 0.00,
                "subtotal" => 0.00,
                "total" => 0.00
            ];
            $quotation['price'] = array_reduce($quotation['price'], function ($carry, $item) {
                $sum = $item['price_per_luminaries'] + $item['installation_charges'];
                $carry['price_per_luminaries'] += $item['price_per_luminaries'];
                $carry['installation_charges'] += $item['installation_charges'];
                $carry['discount_price'] += $item['discount_price'];
                $carry['subtotal'] += $sum;
                $carry['total'] += get_percentage($sum, $item['discount_price']);
                return $carry;
            },$initialPrice);
            $sum = $quotation['price']['total'] + $quotation['additional_product_charges'];
            $quotation['subtotal_price'] = $sum;
            $quotation['total_price'] = sprintf("%.2f", get_percentage($sum, $quotation['discount']));

            return $quotation;
        }, $data);

        return $priceData;
    }

}
