<?php
defined('BASEPATH') or exit('No direct script access allowed');

// $route['404_override'] = 'NotFound404';

$route['api/v1/user/password/change'] = 'Changepassword';
$route['api/v1/user/password/forgot'] = 'Forgot';
$route['api/v1/user/password/reset'] = 'Resetpass';
$route['api/v1/user/otp/verify'] = 'Forgot/verifyotp';
$route['api/v1/user/otp/resend'] = 'Forgot/resendotp';
$route['api/v1/user/login'] = 'Login';
$route['api/v1/user/signup'] = 'Signup';
$route['api/v1/user/logout'] = 'Logout';
$route['api/v1/user/settings'] = 'UserController/edit';
$route['api/v1/user/location'] = 'UserController/location';

$route['api/v1/employee'] = 'EmployeeController/employee';

$route['api/v1/inspirations'] = 'InspirationController/inspiration';
$route['api/v1/inspirations/all'] = 'InspirationController/companyInspiration';
$route['api/v1/companies/(:any)/inspirations'] = 'InspirationController/companyInspiration/company_id/$1';

$route['api/v1/address'] = 'Address';
$route['api/v1/company'] = 'CompanyController/company';
$route['api/v1/company/favorite'] = 'FavoriteController/favorite';

//Products Route
$route['api/v1/applications'] = 'ApplicationController/application';
$route['api/v1/applications/(:num)/products'] = 'ProductController/application_products/application_id/$1';
$route['api/v1/products'] = 'ProductController/products';
$route['api/v1/products/articles'] = 'ProductController/productArticles';
// $route['api/v1/products/(:num)'] = 'ProductController/products/product_id/$1';
$route['api/v1/applications/(:num)/rooms'] = 'RoomController/rooms/application_id/$1';
$route['api/v1/rooms/(:num)/products'] = 'ProductController/accessoryProducts/room_id/$1';

$route['api/v1/products/mounting-types'] = 'ProductController/mountingTypes';
$route['api/v1/rooms/(:any)/mounting-types/(:any)/products'] =
'ProductController/roomProducts/room_id/$1/mounting_type/$2';

$route['api/v1/requests/rooms/(:any)/fast-calc'] = 'ProjectRoomController/roomQuickCalcResponse/project_room_id/$1';
$route['api/v1/requests/(:any)/projects/(:any)/levels'] =  'RequestLevelsController/index/request_id/$1/project_id/$2';
$route['api/v1/requests/(:any)/projects/(:any)/levels/(:any)/rooms'] =  'RequestRoomsController/quotationRooms/request_id/$1/project_id/$2/levels/$3';
$route['api/v1/products/(:any)'] = 'ProductController/details/product_id/$1';
$route['api/v1/projects'] = 'ProjectController/index';
$route['api/v1/projects/rooms/tco'] = 'ProjectTcoController/saveTco';
$route['api/v1/projects/rooms'] = 'ProjectRoomController/projectRooms';
$route['api/v1/projects/quotation-request'] = 'ProjectController/sendQuotationRequest';
$route['api/v1/projects/rooms/quotations'] = 'QuotationController/roomsQuotation';
$route['api/v1/projects/rooms/(:any)/fast-calc'] = 'ProjectRoomController/roomQuickCalcResponse/project_room_id/$1';
$route['api/v1/projects/levels'] = 'ProjectLevelsController';
$route['api/v1/projects/technician/charges'] = 'TechnicianChargesController';
$route['api/v1/projects/(:any)/levels/(:any)/rooms'] = 'ProjectRoomController/projectRoomsFetch/project_id/$1/levels/$2';
$route['api/v1/projects/(:any)/levels'] = 'ProjectLevelsController/projectLevels/project_id/$1';
$route['api/v1/projects/(:any)'] = 'ProjectController/details/project_id/$1';
$route['api/v1/project-rooms/products'] = 'ProjectController/projectRoomProducts';
$route['api/v1/project-rooms/(:any)/products/(:any)'] = 'ProjectProductController/articles/project_room_id/$1/product_id/$2';
$route['api/v1/quotations'] = 'QuotationController';
$route['api/v1/requests'] = 'RequestController';
$route['api/v1/installers/companies'] = 'RequestController/installerCompanies';
$route['api/v1/users/projects'] = 'UserProjectsController/clone';
