<?php

require_once 'BaseController.php';

class CompaniesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->activeSessionGuard();
    }

    /**
     * Fetch all companies list
     *
     * @return void
     */
    public function companies()
    {
        try {
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
            $css = $this->load->config('css_config');
            $this->data['css'] = $this->config->item('companies-listing');
            $this->data['js'] = 'company-listing';
            
            website_view('companies/favorite_companies', $this->data);
        } catch (\Exception $error) {
        }
    }
}
