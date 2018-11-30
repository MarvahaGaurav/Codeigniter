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
$route['xhttp/projects/levels/clone'] = 'ProjectLevelController/levelClone';
$route['xhttp/quick-calc/suggestions'] = 'QuickCalController/quickCalcSuggestion';
$route['xhttp/projects/rooms/add-price'] = 'ProjectRoomController/addRoomQuotation';
$route['xhttp/projects/article/remove'] = 'ProjectProductController/removeProductArticle';
$route['xhttp/projects/installer/price'] = 'ProjectPriceController/installerFinalPrice';
$route['xhttp/projects/rooms/decrement-count'] = 'ProjectRoomController/decrementRoomCount';
$route['xhttp/projects/rooms/increment-count'] = 'ProjectRoomController/incrementRoomCount';
