<?php

defined('BASEPATH') or exit('No direct script access allowed');

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
  |   example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |   https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |   $route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |   $route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |   $route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples: my-controller/index -> my_controller/index
  |       my-controller/my-method -> my_controller/my_method
 */
if (isset($_SERVER["REQUEST_URI"]) && preg_match('/.*\/(api)\/.*/', $_SERVER["REQUEST_URI"]) == true) {
    $route['404_override'] = 'NotFound/api';
} elseif (isset($_SERVER["REQUEST_URI"]) && preg_match('/.*\/admin\/.*/', $_SERVER["REQUEST_URI"]) == true) {
    // $route['404_override'] = 'admin/Page404';
} else {
    //$route['404_override'] = 'website/Page404';
}

$route['default_controller']   = 'web/QuickCalcController/applications';
$route['translate_uri_dashes'] = false;

$route['home/notifications']          = 'web/NotificationController/index';
$route['home/search'] = 'web/SearchController/index';
$route['home/profile/(.+)/edit']      = 'web/UserController/edit_profile/$1';
$route['home/profile/(.+)']           = 'web/UserController/profile/$1';
$route['home/settings/(.+)']          = 'web/UserController/settings/$1';
$route['home/inspirations']           = 'web/InspirationController';
$route['home/inspirations/add']       = 'web/InspirationController/add';
$route['home/inspirations/(.+)/edit'] = 'web/InspirationController/edit/$1';
$route['home/inspirations/(.+)']      = 'web/InspirationController/details/$1';

/**
 * Projects
 */
$route['home/projects'] = 'web/ProjectController';
$route['home/projects/create'] = 'web/ProjectController/create';
$route['home/projects/get-porduct'] = 'web/ProjectController/get_product/$1';
$route['home/projects/view-result/(:any)'] = 'web/ProjectController/view_result/$1';
$route['home/projects/(:any)/(:any)/quotations'] = 'web/QuotesController/customerQuotesList/$1/$2';
$route['home/projects/(:any)/edit'] = 'web/ProjectController/edit/$1';
$route['home/projects/(:any)/delete'] = 'web/ProjectController/delete/$1';
$route['home/projects/(:any)'] = 'web/ProjectController/project_details/$1';
$route['home/projects/(:any)/quotations'] = 'web/QuotesController/customerQuotesListViaProject/$1';
$route['home/projects/(:any)/levels'] = 'web/ProjectLevelsController/levelsListing/$1';
$route['home/projects/(:any)/levels/(:num)/rooms'] = 'web/ProjectRoomsController/projectCreateRoomListing/$1/$2';
$route['home/projects/(:any)/levels/(:num)/rooms/results'] = 'web/ProjectRoomsController/projectResultRoomListing/$1/$2';
$route['home/projects/(:any)/levels/(:num)/rooms/(:any)/edit'] = 'web/ProjectRoomsController/editDimensions/$1/$2/$3';
$route['home/projects/(:any)/levels/(:num)/rooms/(:any)/tco'] = 'web/TcoController/tco/$1/$2/$3';

$route['home/search'] = 'web/SearchArticlesController/search';
$route['home/fast-calc/lux'] = 'web/QuickCalcLuxController/luxValues';
$route['home/fast-calc/lux/view-result'] = 'web/QuickCalcLuxController/view_result';
$route['home/fast-calc/lux/applications/(:any)/rooms/(:any)/products'] = 'web/QuickCalcLuxController/selectProduct/$1/$2';
$route['home/fast-calc/lux/applications/(:any)/rooms/(:any)/products/(:any)'] = 'web/QuickCalcLuxController/selectArticle/$1/$2/$3';
$route['home/fast-calc/lux/applications/(:any)/rooms/(:any)/products/(:any)/articles/(:any)'] = 'web/QuickCalcLuxController/articleDetails/$1/$2/$3/$4';
$route['home/fast-calc/luminary'] = 'web/QuickCalcLuminaryController/luxValues';
$route['home/fast-calc/luminary/view-result'] = 'web/QuickCalcLuminaryController/view_result';
$route['home/fast-calc/luminary/applications/(:any)/rooms/(:any)/products'] = 'web/QuickCalcLuminaryController/selectProduct/$1/$2';
$route['home/fast-calc/luminary/applications/(:any)/rooms/(:any)/products/(:any)'] = 'web/QuickCalcLuminaryController/selectArticle/$1/$2/$3';
$route['home/fast-calc/luminary/applications/(:any)/rooms/(:any)/products/(:any)/articles/(:any)'] = 'web/QuickCalcLuminaryController/articleDetails/$1/$2/$3/$4';
$route['home/fast-calc/product/(:any)/article/(:any)'] = "web/SearchArticlesController/QuickCal/$1/$2";
$route['home/fast-calc/evaluation/(:any)/(:any)'] = 'web/SearchArticlesController/view_result/$1/$2';


$route['home/projects/(:any)/levels/(:num)/rooms/(:any)/project-rooms/(:any)/accessory-products'] = 'web/ProjectProductController/AccessoryProduct/$1/$2/$3/$4';
$route['home/projects/(:any)/levels/(:num)/rooms/(:any)/project-rooms/(:any)/selected-products'] = 'web/ProjectProductController/selectedProjectProducts/$1/$2/$3/$4';
$route['home/projects/(:any)/levels/(:num)/rooms/(:any)/project-rooms/(:any)/accessory-products/(:any)'] = 'web/ProjectProductController/accessoryProductDetail/$1/$2/$3/$4/$5';
$route['home/projects/(:any)/levels/(:num)/rooms/(:any)/project-rooms/(:any)/accessory-products/(:any)/articles/(:any)'] = 'web/ProductArticlesController/accessoryArticleDetail/$1/$2/$3/$4/$5/$6';
$route['home/projects/(:any)/levels/(:num)/rooms/(:any)/edit/products'] = 'web/ProjectProductController/editRoomSelectProduct/$1/$2/$3';
$route['home/projects/(:any)/levels/(:num)/rooms/(:any)/edit/products/(:any)'] = 'web/ProjectProductController/productDetailsEdit/$1/$2/$3/$4';
$route['home/projects/(:any)/levels/(:num)/rooms/(:any)/edit/products/(:any)/mounting/(:num)'] = 'web/ProjectProductController/productDetailsEdit/$1/$2/$3/$4/$5';
$route['home/projects/(:any)/levels/(:num)/rooms/(:any)/edit/products/(:any)/mounting/(:num)/articles/(:any)'] = 'web/ProductArticlesController/editRoomArticleDetail/$1/$2/$3/$4/$5/$6';

$route['home/projects/(:any)/quotation/installers']                    = 'web/ProjectRequestController/installerListing/$1';
$route['home/projects/(:any)/levels/(:num)/rooms/applications']        = 'web/ProjectRoomsController/applications/$1/$2';
$route['home/projects/(:any)/levels/(:num)/rooms/applications/(:any)/rooms'] = 'web/ProjectRoomsController/roomType/$1/$2/$3';
$route['home/projects/(:any)/levels/(:num)/rooms/applications/(:any)/rooms/(:any)/dimensions'] = 'web/ProjectRoomsController/dimensions/$1/$2/$3/$4';
$route['home/projects/(:any)/levels/(:num)/rooms/applications/(:any)/rooms/(:any)/dimensions/products'] = 'web/ProjectProductController/selectProduct/$1/$2/$3/$4';
$route['home/projects/(:any)/levels/(:num)/rooms/applications/(:any)/rooms/(:any)/dimensions/products/(:any)/mounting/(:num)'] = 'web/ProjectProductController/productDetails/$1/$2/$3/$4/$5/$6';
$route['home/projects/(:any)/levels/(:num)/rooms/applications/(:any)/rooms/(:any)/dimensions/products/(:any)/mounting/(:num)/articles/(:any)'] = 'web/ProductArticlesController/articleDetails/$1/$2/$3/$4/$5/$6/$7';
// $route['home/projects/create_room']                                    = 'web/ProjectController/create_room';
// $route['home/projects/update_room']                                    = 'web/ProjectController/update_room';
// $route['home/projects/(:any)/room-edit/(:any)']                        = 'web/ProjectController/edit_room/$1/$2';
// $route['home/projects/(:any)/rooms/(:any)/articles/(:any)']            = 'web/ProjectController/articles/$1/$2/$3';
// $route['home/projects/(:any)/room-edit/(:any)/articles/(:any)']        = 'web/ProjectController/articles/$1/$2/$3';
// $route['home/projects/(:any)/room-edit/(:any)/articles/(:any)/(:any)'] = 'web/ProjectController/articles/$1/$2/$3/$4';
// $route['home/projects/application']                                    = 'web/ProjectController/applications';
// $route['home/projects/(:any)/rooms']                                   = 'web/ProjectController/rooms/$1';
// $route['home/projects/(:any)/rooms/(:any)/add-room']                   = 'web/ProjectController/add_rooms/$1/$2';
// $route['home/projects/(:any)/rooms/(:any)/select-porduct']             = 'web/ProjectController/select_product/$1/$2';
// $route['home/projects/(:any)/room-edit/(:any)/select-porduct/(:any)']  = 'web/ProjectController/select_product/$1/$2/$3';
// $route['home/projects/(:any)/select-room-type']                        = 'web/ProjectController/room_type/$1';


$route['home/quotes'] = 'web/QuotesController';
$route['home/quotes/awaiting'] = 'web/QuotesController/awaiting';
$route['home/quotes/submitted'] = 'web/QuotesController/submitted';
$route['home/quotes/approved'] = 'web/QuotesController/approved';
$route['home/quotes/(:any)/delete'] = 'web/QuotesController/delete/$1';
$route['home/quotes/projects/(:any)/(:any)'] = 'web/QuotesController/project_details/$1/$2'; 
$route['home/quotes/projects/(:any)/(:any)/quotations/levels'] = 'web/QuotesController/levelsListing/$1/$2';
$route['home/quotes/projects/(:any)/(:any)/edit']='web/QuotesController/editProject/$1/$2';
$route['home/quotes/projects/(:any)/(:any)/levels/(:num)/rooms'] ='web/QuotesController/projectResultRoomListing/$1/$2/$3';
$route['home/quotes/projects/(:any)/(:any)/levels/(:num)/rooms/(:any)/tco'] = 'web/QuotesController/tco/$1/$2/$3/$4';
$route['home/quotes/projects/(:any)/(:any)/levels/(:num)/rooms/(:any)/project-rooms/(:any)/selected-products'] = 'web/QuotesController/selectedProjectProducts/$1/$2/$3/$4/$5';
$route['home/quotes/projects/(:any)/(:any)/view-result/(:any)'] = 'web/QuotesController/view_result/$1/$2/$3';
$route['home/technicians'] = 'web/TechnicianController';
$route['home/technicians/requests'] = 'web/TechnicianController/request_list';
$route['home/technicians/(.+)']     = 'web/TechnicianController/details/$1';
$route['home/companies']            = 'web/CompaniesController/companies';
$route['home/companies/favorites']  = 'web/CompaniesController/favoriteCompanies';
$route['home/companies/(:any)']     = 'web/CompaniesController/companyDetails/$1';

$route['home/fast-calc']                                        = 'web/QuickCalcController/quickcalc';
$route['home/applications']                                     = 'web/QuickCalcController/applications';
$route['home/applications/(:any)/rooms']                        = 'web/QuickCalcController/rooms/$1';
$route['home/applications/(:any)/rooms/(:any)/fast-calc']       = 'web/QuickCalcController/quickcalc/$1/$2';
$route['home/applications/(:any)/rooms/(:any)/select-porduct']  = 'web/QuickCalcController/select_product/$1/$2';
$route['home/applications/(:any)/rooms/(:any)/mounting/(:num)/articles/(:any)'] = 'web/QuickCalcController/articles/$1/$2/$3/$4';

$route['home/application-detail/(:any)/rooms/(:any)/mounting/(:num)/articles/(:any)/code/(:num)'] = 'web/QuickCalcController/articleDetail/$1/$2/$3/$4/$5';

$route['home/applications/quick_cal']                           = "web/QuickCalcController/quick_cal";
$route['home/applications/view-result/(:any)']                  = "web/QuickCalcController/view_result/$1";

$route['logout']   = 'web/Logout';
$route['login']    = 'web/index/index';
$route['register'] = 'web/index/signup';

/* Route for Admin */
$route["admin"]                 = 'admin/Admin';
$route["admin/forget"]          = 'admin/Admin/forget';
$route["admin/editMerchant"]    = 'admin/Vendor_Management/merchant_edit_profile';
$route["admin/viewMerchant"]    = 'admin/Vendor_Management/merchant_view_profile';
$route["admin/users"]           = 'admin/User/index';
$route["admin/profile"]         = 'admin/Admin_Profile/admin_profile';
$route["admin/change-password"] = 'admin/Admin_Profile/admin_change_password';
$route["admin/edit-profile"]    = 'admin/Admin_Profile/edit_profile';
$route["admin/users/detail"]    = 'admin/User/detail';
$route["admin/users/detail/project-levels/(:any)/(:any)"]    = 'admin/User/projectLevels/$1/$2';
$route["admin/users/detail/project-results/(:any)/(:any)/(:any)"]    = 'admin/User/projectResults/$1/$2/$3';

$route['admin/users/project-response'] = 'admin/AjaxUtil/getProjectDetailOfUser';

// $route['admin/room-detail/(:any)'] = "admin/User/roomDetail/$1";
$route['admin/room-detail/(:any)/(:any)/(:any)/(:any)'] = "admin/User/roomDetail/$1/$2/$3/$4";
$route['admin/product-detail'] = "admin/AjaxUtil/getProductDetail";



$route['admin/technician/detail/project-levels/(:any)/(:any)'] = 'admin/Technician/projectLevels/$1/$2';

$route["admin/technician/detail/project-results/(:any)/(:any)/(:any)"]    = 'admin/Technician/projectResults/$1/$2/$3';

$route['admin/technician/room-detail/(:any)/(:any)/(:any)/(:any)'] = "admin/Technician/roomDetail/$1/$2/$3/$4";


/* Add merchant Ajax prodilepicture */

$route['req/upload/profile-picture']      = 'admin/AjaxUtil/profilePictureUpload';
$route['req/check-email-exists']          = 'admin/AjaxUtil/emailExistsAjax';
$route['req/check-mobile-exists']         = 'admin/AjaxUtil/mobileExistsAjax';
$route['req/block-user']                  = 'admin/AjaxUtil/changestatus';
$route['req/delete-user']                 = 'admin/AjaxUtil/deleteuser';
$route['req/check-edit-email-exists']     = 'admin/AjaxUtil/editemailExistsAjax';
$route['req/check-edit-mobile-exists']    = 'admin/AjaxUtil/editmobileExistsAjax';
$route['req/check-edit-passmatch-exists'] = 'admin/AjaxUtil/oldpasswordExistsAjax';
$route['req/getstatesbycountry']          = 'admin/AjaxUtil/getStatesByCountry';
$route['req/change-user-status']          = 'admin/AjaxUtil/changeUserStatus';
$route['req/manage-sidebar']              = 'admin/AjaxUtil/manageSideBar';

/* Api Routes */
$route['api/v1/user/profile']['GET']         = 'api/Profile'; //update swagger
$route['api/v1/user/profile']['put']         = 'api/Profile/profileupdate'; //update swagger
$route['api/manage-friend']                  = 'api/managefriends';
$route['api/v1/employee/request']["GET"]     = 'api/EmployeeController/request'; //update swagger
$route['api/v1/employee/request']["POST"]    = 'api/Employee/actiononemployee'; //update swagger
$route['api/v1/employee/permission']["POST"] = 'api/Employee/setpermissopnforemp';
$route['api/v1/employee/permission']["GET"]  = 'api/EmployeeController/employeePermissions';

$route['warranty']       = 'website/warranty';
$route['transfer-image'] = 'xhttp/ImageController';
