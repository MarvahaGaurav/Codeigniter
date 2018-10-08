<?php
require_once 'BaseController.php';

/**
 * Comapany Controller
 */
class QuickCalcController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }


    public function quickcalc()
    {
        try {
            $this->data['js'] = 'quickcalc';
            
            website_view('quickcalc/quickcalc', $this->data);
        } catch (\Exception $error) {

        }
    }
}