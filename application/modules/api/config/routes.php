<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['api/v1/user/password/change'] = 'Changepassword';
$route['api/v1/user/password/forgot'] = 'Forgot';
$route['api/v1/user/password/reset'] = 'Resetpass';
$route['api/v1/user/otp/verify'] = 'Forgot/verifyotp';
$route['api/v1/user/otp/resend'] = 'Forgot/resendotp';
$route['api/v1/user/login'] = 'Login';
$route['api/v1/user/signup'] = 'Signup';
$route['api/v1/user/logout'] = 'Logout';
$route['api/v1/user/settings'] = 'UserController/edit';

$route['api/v1/employee'] = 'EmployeeController/employee';

$route['api/v1/company/inspiration'] = 'InspirationController/inspiration';
$route['api/v1/company/inspiration/(:num)'] = 'InspirationController/inspiration/company_id/$1';
$route['api/v1/company/inspiration/(:num)/(:num)'] = 'InspirationController/inspiration/company_id/$1/inspiration_id/$2';

$route['api/v1/address'] = 'Address';
$route['api/v1/company'] = 'CompanyController/company';
$route['api/v1/company/favorite'] = 'FavoriteController/favorite';

//Products Route
$route['api/v1/applications'] = 'ApplicationController/application';
$route['api/v1/applications/(:num)/products'] = 'ProductController/application_products/application_id/$1';
$route['api/v1/products'] = 'ProductController/products';
// $route['api/v1/products/(:num)'] = 'ProductController/products/product_id/$1';
$route['api/v1/applications/(:num)/rooms'] = 'RoomController/rooms/application_id/$1';

$route['api/v1/products/mounting-types'] = 'ProductController/mountingTypes';
$route['api/v1/rooms/(:any)/mounting-types/(:any)/products'] =
                                           'ProductController/roomProducts/room_id/$1/mounting_type/$2';
$route['api/v1/products/(:any)'] = 'ProductController/details/product_id/$1';
