<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
if (isset($_SERVER["REQUEST_URI"]) && preg_match('/.*\/(api)\/.*/', $_SERVER["REQUEST_URI"] ) == TRUE) {
    //$route['404_override'] = 'api/Page404';
} else if (isset($_SERVER["REQUEST_URI"]) && preg_match('/.*\/admin\/.*/', $_SERVER["REQUEST_URI"] ) == TRUE) {
   // $route['404_override'] = 'admin/Page404';    
} else {
    //$route['404_override'] = 'website/Page404';
}

$route['default_controller'] = 'admin/Admin';
$route['translate_uri_dashes'] = FALSE;

/*Route for Admin*/
$route["admin"] = 'admin/Admin';
$route["admin/forget"] = 'admin/Admin/forget';
$route["admin/editMerchant"] = 'admin/Vendor_Management/merchant_edit_profile';
$route["admin/viewMerchant"] = 'admin/Vendor_Management/merchant_view_profile';
$route["admin/users"] = 'admin/User/index';
$route["admin/profile"] = 'admin/Admin_Profile/admin_profile';
$route["admin/change-password"] = 'admin/Admin_Profile/admin_change_password';
$route["admin/edit-profile"] = 'admin/Admin_Profile/edit_profile';
$route["admin/users/detail"] = 'admin/User/detail';


/*Add merchant Ajax prodilepicture*/

$route['req/upload/profile-picture'] = 'admin/AjaxUtil/profilePictureUpload';
$route['req/check-email-exists'] = 'admin/AjaxUtil/emailExistsAjax';
$route['req/check-mobile-exists'] = 'admin/AjaxUtil/mobileExistsAjax';
$route['req/block-user'] = 'admin/AjaxUtil/changestatus';
$route['req/delete-user'] = 'admin/AjaxUtil/deleteuser';
$route['req/check-edit-email-exists'] = 'admin/AjaxUtil/editemailExistsAjax';
$route['req/check-edit-mobile-exists'] = 'admin/AjaxUtil/editmobileExistsAjax';
$route['req/check-edit-passmatch-exists'] = 'admin/AjaxUtil/oldpasswordExistsAjax';
$route['req/getstatesbycountry'] = 'admin/AjaxUtil/getStatesByCountry';
$route['req/change-user-status'] = 'admin/AjaxUtil/changeUserStatus';
$route['req/manage-sidebar'] = 'admin/AjaxUtil/manageSideBar';

/*Api Routes*/

$route['api/manage-friend'] = 'api/managefriends';