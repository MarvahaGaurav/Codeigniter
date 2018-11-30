<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$route['api/v1/user/password/change'] = 'Changepassword'; //update swagger
$route['api/v1/user/password/forgot'] = 'Forgot'; //update swagger
$route['api/v1/user/password/reset'] = 'Resetpass'; //update swagger
$route['api/v1/user/otp/verify'] = 'Forgot/verifyotp'; //needs update on swagger
$route['api/v1/user/otp/resend'] = 'Forgot/resendotp'; //needs update on swagger
$route['api/v1/user/login'] = 'Login'; //update swagger
$route['api/v1/user/signup'] = 'Signup';  //updatge swagger
$route['api/v1/user/logout'] = 'Logout'; //update swagger
// $route['api/v1/company'] = 'Profile/companylist'; //update swagger
// $route['api/v1/company/favorite'] = 'managefavorite'; //update swagger
$route['api/v1/employee'] = 'EmployeeController/employee'; //update swagger
// $route['api/v1/employee/detail'] = 'Employee/employeedetail'; //update swagger
$route['api/v1/company/inspiration'] = 'InspirationController/inspiration';
$route['api/v1/company/inspiration/(:num)'] = 'InspirationController/inspiration/company_id/$1';
$route['api/v1/company/inspiration/(:num)/(:num)'] = 'InspirationController/inspiration/company_id/$1/inspiration_id/$2';

$route['api/v1/address'] = 'Address';
$route['api/v1/company'] = 'CompanyController/company'; //update swagger
$route['api/v1/company/favorite'] = 'FavoriteController/favorite'; //update swagger

$route['api/v1/applications'] = 'ApplicationController/application';
$route['api/v1/applications/(:num)/products'] = 'ProductController/application_products/application_id/$1';
$route['api/v1/products'] = 'ProductController/products';
$route['api/v1/products/(:num)'] = 'ProductController/products/product_id/$1';