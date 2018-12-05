<?php
require_once 'BaseController.php';

class SearchController extends BaseController
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
    public function index()
    {
        try {
            $this->neutralGuard();
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('search-page');
            
            website_view('search/search', $this->data);
        } catch (\Exception $error) {
        }
    }
}
