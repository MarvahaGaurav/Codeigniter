<?php
require_once 'BaseController.php';

class CompaniesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fetch all companies list
     *
     * @return void
     */
    public function companies()
    {
        try {
            $this->neutralGuard();
            if (isset($this->userInfo, $this->userInfo['user_id']) && !empty($this->userInfo['user_id'])) {
                $this->data['userInfo'] = $this->userInfo;
            }
            $params['limit'] = 5;
            $page = $this->input->get('page');
            $search = $this->input->get('search');
            $search = trim($search);
            if (is_numeric($page) && (int)$page > 0) {
                $params['offset'] = ((int)$page - 1) * $params['limit'];
            }

            if (isset($search) && is_string($search) && strlen($search) > 0) {
                $params['where']['company_name LIKE'] = "%{$search}%";
            }

            //Company Data
            $this->load->model('Company');
            $this->load->library('Commonfn');
            if (isset($this->userInfo['user_id'])) {
                $params['user_id'] = $this->userInfo['user_id'];
                $companyData = $this->Company->companyWithFavorite($params);

                $companyData['data'] = array_map(function ($data) {
                    $data->favorite_data = [
                        $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                        'company_id' => encryptDecrypt($data->company_id),
                        'is_favorite' => $data->is_favorite
                    ];
                    $data->favorite_data = json_encode($data->favorite_data);
                    return $data;
                }, $companyData['data']);
            } else {
                $companyData = $this->Company->company($params);
            }

            $companies = $companyData['data'];
            $companyCount = $companyData['count'];

            $this->data['search'] = (string)$search;
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $companyCount, $params['limit']);
            $this->data['companies'] = $companies;
            $this->data['companyCount'] = $companyCount;
            $css = $this->load->config('css_config');
            $this->data['css'] = $this->config->item('companies-listing');
            $this->data['js'] = 'company-listing';
            
            website_view('companies/companies', $this->data);
        } catch (\Exception $error) {
        }
    }
    
    public function favoriteCompanies()
    {
        try {
            $this->activeSessionGuard();
            $this->data['userInfo'] = $this->userInfo;

            $params['limit'] = 5;
            $page = $this->input->get('page');
            $search = $this->input->get('search');
            $search = trim($search);
            if (is_numeric($page) && (int)$page > 0) {
                $params['offset'] = ((int)$page - 1) * $params['limit'];
            }

            if (isset($search) && is_string($search) && strlen($search) > 0) {
                $params['where']['company_name LIKE'] = "%{$search}%";
            }

            //Company Data
            $this->load->model('Company');
            $this->load->library('Commonfn');
            if (isset($this->userInfo['user_id'])) {
                $params['user_id'] = $this->userInfo['user_id'];
                $companyData = $this->Company->favoriteCompany($params);

                $companyData['data'] = array_map(function ($data) {
                    $data->favorite_data = [
                        $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                        $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                        'company_id' => encryptDecrypt($data->company_id),
                        'is_favorite' => $data->is_favorite
                    ];
                    $data->favorite_data = json_encode($data->favorite_data);
                    return $data;
                }, $companyData['data']);
            } else {
                $companyData = $this->Company->company($params);
            }

            $companies = $companyData['data'];
            $companyCount = $companyData['count'];

            $this->data['search'] = (string)$search;
            $this->data['links'] = $this->commonfn->pagination(uri_string(), $companyCount, $params['limit']);
            $this->data['companies'] = $companies;
            $this->data['companyCount'] = $companyCount;
            $css = $this->load->config('css_config');
            $this->data['css'] = $this->config->item('companies-listing');
            $this->data['js'] = 'favorite-company-listing';
            
            
            website_view('companies/favorite_companies', $this->data);
        } catch (\Exception $error) {
        }
    }

    public function companyDetails($companyId = '')
    {
        try {
            $this->neutralGuard();
            if (isset($this->userInfo, $this->userInfo['user_id']) && !empty($this->userInfo['user_id'])) {
                $this->data['userInfo'] = $this->userInfo;
                $params['user_id'] = $this->userInfo['user_id'];
            }
            $companyId = encryptDecrypt($companyId, 'decrypt');

            if (empty($companyId)) {
                show404('Data not found', base_url('home/companies'));
            }

            $params['limit'] = 5;
            $page = $this->input->get('page');
            if (is_numeric($page) && (int)$page > 0) {
                $params['offset'] = ((int)$page - 1) * $params['limit'];
            }

            $this->load->model('Company');
            $this->load->model("Inspiration");

            $company = $this->Company->companyDetails($companyId, $params);

            $search = $this->input->get('search');
            $search = trim($search);
            if (isset($search) && is_string($search) && strlen($search) > 0) {
                $params['where']['title LIKE'] = "%{$search}%";
            }
            $inspirations = $this->Inspiration->inspirationByCompany($params, $companyId);
            $this->load->library('Commonfn');
            $this->data['links'] = $this->commonfn->pagination(
                uri_string(),
                (int)$inspirations['count'],
                $params['limit']
            );

            if (isset($this->userInfo['user_id'])) {
                $company->favorite_data = [
                    $this->data["csrfName"] = $this->security->get_csrf_token_name() =>
                    $this->data["csrfToken"] = $this->security->get_csrf_hash(),
                    'company_id' => encryptDecrypt($company->company_id),
                    'is_favorite' => $company->is_favorite
                ];
                $company->favorite_data = json_encode($company->favorite_data);
            }

            $this->data['search'] = $search;
            $this->data['company'] = $company;
            $this->data['inspirations'] = $inspirations['data'];

            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('companies-profile');
            $this->data['js'] = 'company-listing';
            
            website_view('companies/company-profile', $this->data);
        } catch (\Exception $error) {
        }
    }
}
