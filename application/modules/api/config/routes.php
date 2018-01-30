<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$route['api/v1/password/change'] = 'Changepassword'; //update swagger
$route['api/v1/password/forgot'] = 'Forgot'; //update swagger
$route['api/v1/password/reset'] = 'Login'; //update swagger
$route['api/v1/otp/verify'] = 'Forgot/verifyotp'; //needs update on swagger
$route['api/v1/otp/resend'] = 'Forgot/resend/otp'; //needs update on swagger
$route['api/v1/user/profile'] = 'Profile'; //update swagger
$route['api/v1/user/login'] = 'Login'; //update swagger
$route['api/v1/user/signup'] = 'Signup';  //updatge swagger
$route['api/v1/user/logout'] = 'Logout'; //update swagger
// $route['api/v1/company'] = 'Profile/companylist'; //update swagger
$route['api/v1/company/favorite'] = 'managefavorite'; //update swagger
$route['api/v1/employee'] = 'Employee'; //update swagger
$route['api/v1/employee/request'] = 'Employee/myemployeereuestlist'; //update swagger
$route['api/v1/employee/action'] = 'Employee/actiononemployee'; //update swagger
$route['api/v1/employee/detail'] = 'Employee/employeedetail'; //update swagger
$route['api/v1/employee/permission'] = 'Employee/setpermissopnforemp'; //update swagger

$route['api/v1/company'] = 'CompanyController/company'; //update swagger
$route['api/v1/company/favorite'] = 'FavoriteController/favorite'; //update swagger