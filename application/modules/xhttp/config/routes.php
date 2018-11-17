<?php 
defined("BASEPATH") or exit("No direct script access allowed");

$route['xhttp/cities'] = "LocationController/cities";
$route['xhttp/employee/remove'] = "EmployeeController/remove";
$route['xhttp/employee/action'] = "EmployeeController/request_action";
$route['xhttp/application'] = "ApplicationController/fetch";
$route['xhttp/application/(:num)'] = "ApplicationController/fetch/$1";
$route['xhttp/room/(:any)'] = "RoomController/fetch/$1";
$route['xhttp/companies'] = 'CompanyController/companyList';
$route['xhttp/companies/favorite'] = 'CompanyController/favoriteCompany';
$route['xhttp/projects/mark-as-done'] = 'ProjectLevelController/markAsDone';
$route['xhttp/projects/add/accessory-products'] = 'ProjectProductController/addProductArticle';
$route['xhttp/projects/clone'] = 'ProjectController/projectClone';

