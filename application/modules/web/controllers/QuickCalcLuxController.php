<?php 
defined('BASEPATH') or exit('No direct script access allowed');
require_once "BaseController.php";

class QuickCalcLuxController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['UtilModel']);
    }

    public function luxValues()
    {
        try {
            $this->neutralGuard();
            $this->load->config('css_config');
            $this->data['css'] = $this->config->item('basic-with-font-awesome');
            $this->data['js'] = 'quickcalc-lux';

            $applicationData = $this->UtilModel->selectQuery(
                'application_id,type,title',
                'applications',
                ['where' => ['language_code' => $this->languageCode]]
            );
    
            // pr($applicationData);
            $this->data['applications'] = $applicationData;

            $this->data['units'] = ["Meter", "Inch", "Yard"];

            website_view('luxquickcalc/quickcalc', $this->data);
        } catch (\Exception $error) {
            show404($this->lang->line('internal_server_error'), base_url());
        }
    }
}

