<?php

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Traits/Notifier.php';
class Signup extends REST_Controller
{
    use Notifier;

    /**
     * Datatime string
     *
     * @var string
     */
    private $datetime;

    function __construct()
    {
        // error_reporting(-1);
        // ini_set('display_errors', 1);
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->load->library('commonfn');
        $this->datetime = date("Y-m-d H:i:s");
    }

    /**
     * @SWG\Post(path="/user/signup",
     *   tags={"User"},
     *   summary="Singup Information",
     *   description="Singup Information",
     *   operationId="signup_post",
     *   consumes ={"multipart/form-data"},
     *   produces={"application/json"},
     * @SWG\Parameter(
     *     name="first_name",
     *     in="formData",
     *     description="Architech",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="last_name",
     *     in="formData",
     *     description="employe",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="architectemployee@yopmail.com",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="123456",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="device_id",
     *     in="formData",
     *     description="6516516265265",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="device_token",
     *     in="formData",
     *     description="sdvdsdsfsdadc wv zsd zv56z5s ad ad ad35165a1 6as asv5as1v6asd5 1",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="Platform",
     *     in="formData",
     *     description="1 //1=Android, 2=IOS, 3-Web",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="User_type",
     *     in="formData",
     *     description="3 //1=Private User, 2=Technician, 3=Architect, 4=Electrical Planner, 5=Wholesaler,6=Business User",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="Is_owner",
     *     in="formData",
     *     description="1 //0=user, 1 =  employee , 2 = owner",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="profile_image",
     *     in="formData",
     *     description="image",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="company_name",
     *     in="formData",
     *     description="Architech Company",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="company_reg_number",
     *     in="formData",
     *     description="IT51651651",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="company_image",
     *     in="formData",
     *     description="company image",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="company_address",
     *     in="formData",
     *     description="Required when user type is Installer, with role owner",
     *     type="string"
     *   ),
     * 
     * @SWG\Parameter(
     *     name="latitude",
     *     in="formData",
     *     description="Required when user type is Installer, with role owner",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="longitude",
     *     in="formData",
     *     description="Required when user type is Installer, with role owner",
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="prm_user_countrycode",
     *     in="formData",
     *     description="91",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="phone",
     *     in="formData",
     *     description="9015417310",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="alt_user_countrycode",
     *     in="formData",
     *     description="91",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="alt_userphone",
     *     in="formData",
     *     description="9654379323",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="country",
     *     in="formData",
     *     description="DK, NL, SW, NO, etc..",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="state",
     *     in="formData",
     *     description="2184",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="city",
     *     in="formData",
     *     description="1210455",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="zipcode",
     *     in="formData",
     *     description="22215",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Parameter(
     *     name="company_id",
     *     in="formData",
     *     description="4",
     *     required=true,
     *     type="string"
     *   ),
     * @SWG\Response(response=200, description="Signup Success"),
     * @SWG\Response(response=206, description="Unauthorized request"),
     * @SWG\Response(response=207, description="Header is missing"),
     * @SWG\Response(response=421, description="File Upload Failed"),
     * @SWG\Response(response=418, description="Required Parameter Missing or Invalid"),
     * )
     */
    public function index_post()
    {
        $language_code = $this->langcode_validate();
        $postDataArr = $this->post();

        /*
         *   Singup form Validation
         */
        $required_fields_arr = array(
            array(
                'field' => 'first_name',
                'label' => 'First Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'device_id',
                'label' => 'Device Id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'user_type',
                'label' => 'User Type',
                'rules' => 'trim|required'
            )
        );

        if (in_array($postDataArr['user_type'] != '1')) {
            $required_fields_arr[] = array('field' => 'is_owner', 'label' => 'Owner', 'rules' => 'trim|required');

            if ($postDataArr['is_owner'] == '2') {
                $required_fields_arr[] = array('field' => 'company_name', 'label' => 'Company Name', 'rules' => 'trim|required');
                $required_fields_arr[] = array('field' => 'company_reg_number', 'label' => 'Company Registration Number', 'rules' => 'trim|required');
                if (in_array((int)$postDataArr['user_type'], [INSTALLER], true)) {
                    $required_fields_arr[] = array('field' => 'company_address', 'label' => 'Company Address', 'rules' => 'trim|required');
                    $required_fields_arr[] = array('field' => 'latitude', 'label' => 'Latitude', 'rules' => 'trim|required');
                    $required_fields_arr[] = array('field' => 'longitude', 'label' => 'Longitude', 'rules' => 'trim|required');
                }
                $required_fields_arr[] = array('field' => 'country', 'label' => 'Country', 'rules' => 'trim|required');
                // $required_fields_arr[] = array('field' => 'state','label' => 'State','rules' => 'trim|required');
                $required_fields_arr[] = array('field' => 'city', 'label' => 'City', 'rules' => 'trim|required');
                $required_fields_arr[] = array('field' => 'zipcode', 'label' => 'Zipcode', 'rules' => 'trim|required');
            }/*else{
                $required_fields_arr[] = array('field' => 'company_id','label' => 'Company','rules' => 'trim|required');
            }*/
        }
        
        //print_r($required_fields_arr);die;
        /*
         * Validate phone number
         */
        /*if (isset($postDataArr['phone']) && !empty($postDataArr['phone'])) {
            $this->validate_phone($postDataArr['phone']);
        }*/
        /*
         * Validate dob
         */
        /*if (isset($postDataArr['dob']) && !empty($postDataArr['dob'])) {
            $this->validate_dob($postDataArr['dob']);
        }*/

        /*
         * Setting Error Messages for rules
         */
        $this->form_validation->set_rules($required_fields_arr);
        $this->form_validation->set_message('is_unique', 'The %s is already registered with us');
        $this->form_validation->set_message('required', 'Please enter the %s');

        if ($this->form_validation->run()) {
            //print_r($required_fields_arr);die;
            try {
                /*
                 *  Check if email if already registered and is it blocked
                 */
                $whereArr = [];
                $whereArr['where'] = ['email' => $postDataArr['email']];
                $user_info = $this->Common_model->fetch_data('ai_user', ['email', 'status'], $whereArr, true);
                if (!empty($user_info) && $user_info['status'] == 2) {
                    $this->response(array('code' => ACCOUNT_BLOCKED, 'msg' => $this->lang->line('account_blocked'), 'result' => (object)[]));
                }

                $userEmailCheck = $this->Common_model->fetch_data('ai_user', 'email', ['where' => ['email' => trim($postDataArr['email'])]], true);

                if (!empty($userEmailCheck)) {
                    $this->response(array('code' => EMAIL_ALREADY_EXIST, 'msg' => $this->lang->line('account_exist'), 'result' => (object)[]));
                }

                $signupArr = [];
                $signupArr["first_name"] = trim($postDataArr["first_name"]);
                $signupArr["middle_name"] = isset($postDataArr["middle_name"]) ? trim($postDataArr["middle_name"]) : "";
                $signupArr["last_name"] = isset($postDataArr["last_name"]) ? trim($postDataArr["last_name"]) : "";
                $signupArr["email"] = trim($postDataArr["email"]);
                $signupArr['language'] = isset($postDataArr['language']) && preg_match('/^(en|da|nb|sv|fi|fr|nl|de)$/', $postDataArr['language']) ? $postDataArr['language'] : "en";
                //$signupArr["gender"] = isset($postDataArr["gender"]) ? trim($postDataArr["gender"]) : 0;
                //$signupArr["dob"] = isset($postDataArr["dob"]) ? date('Y-m-d', strtotime($postDataArr["dob"])) : "";
                //$signupArr["age"] = isset($postDataArr["age"]) ? trim($postDataArr["age"]) : "";
                $signupArr["prm_user_countrycode"] = isset($postDataArr["prm_user_countrycode"]) ? trim($postDataArr["prm_user_countrycode"]) : "";
                $signupArr["phone"] = isset($postDataArr["phone"]) ? trim($postDataArr["phone"]) : "";
                $signupArr["alt_user_countrycode"] = isset($postDataArr["alt_user_countrycode"]) ? trim($postDataArr["alt_user_countrycode"]) : "";
                $signupArr["alt_userphone"] = isset($postDataArr["alt_userphone"]) ? trim($postDataArr["alt_userphone"]) : "";
                //$signupArr["address"] = isset($postDataArr["address"]) ? trim($postDataArr["address"]) : "";
                //$signupArr["user_lat"] = isset($postDataArr["user_lat"]) ? $postDataArr["user_lat"] : "";
                //$signupArr["user_long"] = isset($postDataArr["user_long"]) ? $postDataArr["user_long"] : "";
                $signupArr["country_id"] = isset($postDataArr['country']) ? $postDataArr['country'] : "";
                // $signupArr["state_id"] = isset($postDataArr['state']) ? $postDataArr['state'] : "";
                $signupArr["city_id"] = isset($postDataArr['city']) ? $postDataArr['city'] : "";
                $signupArr["zipcode"] = isset($postDataArr['zipcode']) ? trim($postDataArr['zipcode']) : "";
                $signupArr["password"] = encrypt($postDataArr["password"]);
                $signupArr["registered_date"] = date('Y-m-d H:i:s');
                $signupArr["image"] = isset($postDataArr['profile_image']) ? $postDataArr['profile_image'] : "";;
                $signupArr["image_thumb"] = isset($postDataArr['profile_image_thumb']) ? $postDataArr['profile_image_thumb'] : "";;

                /*
                 *  upload profile pic option
                 */
                /*if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image'])) {
                    $config = [];
                    $config = getConfig(UPLOAD_IMAGE_PATH, 'jpeg|jpg|png', 3000, 1024, 768);
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('profile_image')) {
                        $upload_data = $this->upload->data();
                        $imageName = $upload_data['file_name'];
                        $thumbFileName = $upload_data['file_name'];
                        $fileSource = UPLOAD_IMAGE_PATH . $thumbFileName;
                        $targetPath = UPLOAD_THUMB_IMAGE_PATH;
                        $isSuccess = $this->commonfn->thumb_create($thumbFileName, $fileSource, $targetPath);
                        if ($isSuccess) {
                            $thumbName = $imageName;
                        }
                    } else {
                        $this->response(array('code' => ERROR_UPLOAD_FILE, 'msg' => strip_tags($this->upload->display_errors()), 'result' => $signupArr));
                    }
                    $signupArr["image"] = $imageName;
                    $signupArr["image_thumb"] = $thumbName;
                }*/

                $this->db->trans_begin();

                $signupArr["is_owner"] = $postDataArr['is_owner'];
                $signupArr["user_type"] = $postDataArr['user_type'];

                $companyArr = [];

                $this->db->trans_begin();
                if ($postDataArr['is_owner'] == '2' || (int)$postDataArr['user_type'] === BUSINESS_USER) {
                    $companyArr['company_name'] = isset($postDataArr['company_name']) ? $postDataArr['company_name'] : "";
                    $companyArr['company_reg_number'] = isset($postDataArr['company_reg_number']) ? $postDataArr['company_reg_number'] : "";
                    $companyArr['company_address'] = isset($postDataArr['company_address']) ? $postDataArr['company_address'] : "";
                    $companyArr['lat'] = isset($postDataArr['latitude']) ? $postDataArr['latitude'] : 0.00;
                    $companyArr['lng'] = isset($postDataArr['longitude']) ? $postDataArr['longitude'] : 0.00;
                    $companyArr['country'] = isset($postDataArr['country']) ? $postDataArr['country'] : "";
                    // $companyArr['state'] = isset($postDataArr['state']) ? $postDataArr['state'] : "";
                    $companyArr['city'] = isset($postDataArr['city']) ? $postDataArr['city'] : "";
                    $companyArr['zipcode'] = isset($postDataArr['zipcode']) ? $postDataArr['zipcode'] : "";
                    $companyArr['company_image'] = isset($postDataArr['company_image']) ? $postDataArr['company_image'] : "";
                    $companyArr['company_image_thumb'] = isset($postDataArr['company_image_thumb']) ? $postDataArr['company_image_thumb'] : "";
                    $companyArr["owner_type"] = $postDataArr['user_type'];
                    $companyArr['insert_date'] = date('Y-m-d H:i:s');

                    $companyId = $this->Common_model->insert_single('company_master', $companyArr);
                    if ($companyId) {
                        $signupArr["company_id"] = $companyId;
                    } else {
                        throw new Exception($this->lang->line('try_again'));
                    }
                } else {
                    $signupArr["company_id"] = isset($postDataArr['company_id']) ? $postDataArr['company_id'] : "";
                }

                $userId = $this->Common_model->insert_single('ai_user', $signupArr);
                if (!$userId) {
                    throw new Exception($this->lang->line('try_again'));
                }
                $postDataArr['user_id'] = $userId;
                
                /*
                 * Generate Public and Private Access Token
                 */
                $accessToken = create_access_token($userId, $signupArr['email']);

                $signupArr['accesstoken'] = $accessToken['public_key'] . '||' . $accessToken['private_key'];

                $signupArr["image"] = isset($signupArr['image']) ? $signupArr['image'] : "";
                $signupArr["image_thumb"] = isset($signupArr['image_thumb']) ? $signupArr['image_thumb'] : "";
                /*
                 *
                 */
                $sessionArr = setSessionVariables($postDataArr, $accessToken);

                /*
                 * Insert Session Data
                 */
                $whereArr = [];
                $device_id = isset($postDataArr['device_id']) ? $postDataArr['device_id'] : "";
                $whereArr['where'] = ['device_id' => $device_id];
                $isExist = $this->Common_model->fetch_data('ai_session', array('session_id'), $whereArr, true);
                /*
                 * If user has logged in previously with same device then update his detail
                 * or insert as a new row
                 */
                $sessionArr['login_status'] = 1;
                if (!empty($isExist)) {
                    $sessionId = $this->Common_model->update_single('ai_session', $sessionArr, $whereArr);
                } else {
                    $sessionId = $this->Common_model->insert_single('ai_session', $sessionArr);
                }
                if ((int)$postDataArr['is_owner'] === ROLE_EMPLOYEE) {
                    $whereArr['where'] = ['is_owner' => 2, 'company_id' => $signupArr['company_id']];
                    $companyowner_info = $this->Common_model->fetch_data('ai_user', 'user_id', $whereArr, true);
                }

                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();

                    if (!empty($sessionId) && !empty($userId)) {
                        unset($signupArr['password']);
                        $signupArr['full_name'] = $signupArr['first_name'];
                        unset($signupArr['first_name']);
                        unset($signupArr['middle_name']);
                        unset($signupArr['last_name']);
                        $mailData = [];
                        $mailData['name'] = $postDataArr['full_name'];
                        $mailData['email'] = $postDataArr['email'];
                        $this->sendWelcomeMail($mailData);
                        $employeePermission = [];
                        $employeePermission['quote_view'] = $signupArr['quote_view'] = "1";
                        $employeePermission['quote_add'] = $signupArr['quote_add'] = "0";
                        $employeePermission['quote_edit'] = $signupArr['quote_edit'] = "0";
                        $employeePermission['quote_delete'] = $signupArr['quote_delete'] = "0";
                        $employeePermission['insp_view'] = $signupArr['insp_view'] = "1";
                        $employeePermission['insp_add'] = $signupArr['insp_add'] = "0";
                        $employeePermission['insp_edit'] = $signupArr['insp_edit'] = "0";
                        $employeePermission['insp_delete'] = $signupArr['insp_delete'] = "0";
                        $employeePermission['project_view'] = $signupArr['project_view'] = "1";
                        $employeePermission['project_add'] = $signupArr['project_add'] = "0";
                        $employeePermission['project_edit'] = $signupArr['project_edit'] = "0";
                        $employeePermission['project_delete'] = $signupArr['project_delete'] = "0";
                        $signupArr['is_employee_approved'] = false;
                        $signupArr['is_employee_rejected'] = false;

                        if (ROLE_EMPLOYEE === (int)$signupArr["is_owner"] && !empty($companyowner_info)) {
                            $employeePermission['employee_id'] = $userId;
                            $employeePermission['user_id'] = $companyowner_info['user_id'];
                            $employeePermission['insert_date'] = $this->datetime;
                            $employeePermission['modify_date'] = $this->datetime;
                            $employeePermission['status'] = 1;

                            $this->load->library("PushNotification");
                            $this->load->model("UtilModel");

                            $this->UtilModel->insertTableData($employeePermission, 'user_employee_permission');
                        }

                        if ((int)$postDataArr['is_owner'] === ROLE_EMPLOYEE) {
                            if ($companyowner_info) {
                                // adding in notification master table
                                $requestedbyname = $postDataArr['first_name'] . ' ' . $postDataArr['middle_name'] . ' ' . $postDataArr['last_name'];
                                $this->notifyEmployeePermission($userId, $companyowner_info['user_id']);
                                $notificationId = $this->Common_model->insert_single('users_notification_master', $notifArr);
        
                                // adding in employee request tabel
                                $requestfArr['requested_by'] = $userId;
                                $requestfArr['requested_to'] = $companyowner_info['user_id'];
                                $requestfArr['request_time'] = date('Y-m-d H:i:s');
                                $requestfArr['company_id'] = $signupArr["company_id"];
                                $requestid = $this->Common_model->insert_single('employee_request_master', $requestfArr);
                            }
                        }

                        $signupArr['company'] = empty($companyArr) ? (object)[] : $companyArr;
                        $signupArr['country_name'] = $this->Common_model->fetch_data("country_list", "name", ["where" => ["country_code1" => $signupArr['country_id']]], true);
                        $signupArr['country_name'] = $signupArr['country_name']['name'];
                        $this->db->trans_commit();
                        $this->response(array('code' => SUCCESS_CODE, 'msg' => $this->lang->line('registration_success'), 'result' => $signupArr));
                    }
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                $error = $e->getMessage();
                $this->response(array('code' => TRY_AGAIN_CODE, 'msg' => $error, 'result' => (object)[]));
            }
        } else {
            $err = $this->form_validation->error_array();
            $arr = array_values($err);
            $this->response(array('code' => PARAM_REQ, 'msg' => $arr[0], 'result' => (object)[]));
        }
    }

    public function sendWelcomeMail($mailData)
    {

        $this->load->helper('url');
        $data = [];
        $data['url'] = base_url() . 'request/welcomeMail?email=' . $mailData['email'] . '&name=' . urlencode($mailData['name']);
        sendGetRequest($data);
    }

    /*
     * Custom Rule Validate Phone
     * @param: Phone number
     */

    public function validate_phone($phone)
    {

        if (isset($phone) && !preg_match("/^[0-9]{10}$/", $phone) && !empty($phone)) {
            $this->response(array('code' => PARAM_REQ, 'msg' => $this->lang->line('invalid_phone'), 'result' => (object)[]));
        } else {
            return true;
        }
    }

    /*
     * Custom Rule Validate Dob
     * @param: user dob
     */

    public function validate_dob($dob)
    {
        if (!(isValidDate($dob, 'm-d-Y'))) {
            $this->response(array('code' => PARAM_REQ, 'msg' => $this->lang->line('invalid_dob'), 'result' => (object)[]));
        } else {
            return true;
        }
    }
    private function langcode_validate()
    {
        $language_code = $this->head("X-Language-Code");
        $language_code = trim($language_code);
        $valid_language_codes = ["en", "da", "nb", "sv", "fi", "fr", "nl", "de"];

        if (empty($language_code)) {
            $this->response(
                [
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'api_code_result' => 'UNPROCESSABLE_ENTITY',
                    'msg' => $this->lang->line('header_missing'),
                    'extra_info' => [
                        "missing_parameter" => "language_code"
                    ]
                ]
            );
        }

        if (!in_array($language_code, $valid_language_codes)) {
            $this->response(
                [
                    'code' => HTTP_UNPROCESSABLE_ENTITY,
                    'api_code_result' => 'UNPROCESSABLE_ENTITY',
                    'msg' => $this->lang->line('invalid_header'),
                    'extra_info' => [
                        "missing_parameter" => $this->lang->line('invalid_language_code')
                    ]
                ]
            );
        }

        $language_map = [
            "en" => "english",
            "da" => "danish",
            "nb" => "norwegian",
            "sv" => "swedish",
            "fi" => "finnish",
            "fr" => "french",
            "nl" => "dutch",
            "de" => "german"
        ];

        $this->load->language("common", $language_map[$language_code]);
        $this->load->language("rest_controller", $language_map[$language_code]);

        return $language_code;
    }
}
