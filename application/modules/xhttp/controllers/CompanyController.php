<?php
defined("BASEPATH") or exit("No direct script access allowed");

require_once "BaseController.php";

/**
 * Company Controller
 */
class CompanyController extends BaseController
{

    /**
     * Request Data
     *
     * @var array
     */
    private $request;

    public function __construct($config = 'rest')
    {
        parent::__construct();
    }


    /**
     * List companies
     *
     * @return string
     */
    public function companyList()
    {
        try {
            $data = $this->UtilModel->selectQuery(['company_id', 'company_name', 'company_image'], 'company_master');

            json_dump([
                "success" => true,
                "data" => $data
            ]);
        } catch (\Exception $error) {
            json_dump(
                [
                    "success" => false,
                    "error" => "Internal Server Error",
                ]
            );
        }
    }

    /**
     * Favorite Company handler
     * adds or removes company from favorite
     *
     * @return string
     */
    public function favoriteCompany()
    {
        try {
            $this->request = $this->input->post();
            $this->request = trim_input_parameters($this->request, false);
            $this->favoriteCompanyValidate();

            $status = 1;
            if ((int)$this->request['is_favorite'] === 1) {
                $status = 0;
            }

            $favoriteData = $this->UtilModel->selectQuery(['id'], 'ai_favorite', [
                'single_row' => true,
                'where' => [
                    'user_id' => $this->request['user_id'],
                    'company_id' => $this->request['company_id']
                ]
            ]);

            if (empty($favoriteData)) {
                $this->UtilModel->insertTableData([
                    'user_id' => $this->request['user_id'],
                    'company_id' => $this->request['company_id'],
                    'is_favorite' => $status,
                    'created_at' => $this->datetime
                ], 'ai_favorite');
            } else {
                $this->UtilModel->updateTableData([
                    'is_favorite' => $status
                ], 'ai_favorite', [
                    'id' => $favoriteData['id']
                ]);
            }
            json_dump(
                [
                    "success" => true,
                    "message" => $this->lang->line('favorite_selected'),
                    'status' => $status
                ]
            );
        } catch (\Exception $error) {
            print_r($error);
            die;
            json_dump(
                [
                    "success" => false,
                    "error" => "Internal Server Error",
                ]
            );
        }
    }
        
    /**
     * Set validation rules
     *
     * @return void
     */
    private function favoriteCompanyValidate()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules([
            [
                'field' => 'company_id',
                'label' => 'company_id',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => $this->lang->line('please_try_again')
                ]
            ],
            [
                'field' => 'is_favorite',
                'label' => 'is_favorite',
                'rules' => 'trim|required|regex_match[/^(0|1)$/]',
                 'errors' => [
                    'required' => $this->lang->line('please_try_again'),
                    'regex_match' => $this->lang->line('please_try_again')
                 ]
            ]
        ]);

        $this->form_validation->set_data($this->request);
        if (! (bool)$this->form_validation->run()) {
            $error = $this->form_validation->error_array();
            $error = array_shift($error);
            json_dump(
                [
                    "success" => false,
                    "error" => $error,
                ]
            );
        }

        $this->request['company_id'] = encryptDecrypt($this->request['company_id'], 'decrypt');
        if (empty($this->request['company_id'])) {
            json_dump(
                [
                    "success" => false,
                    "error" => $error,
                ]
            );
        }
        $this->request['user_id'] = $this->session_data['user_id'];
    }
}
