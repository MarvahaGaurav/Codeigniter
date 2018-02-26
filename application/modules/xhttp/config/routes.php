<?php 
defined("BASEPATH") or exit("No direct script access allowed");

$route['xhttp/cities'] = "LocationController/cities";
$route['xhttp/employee/remove'] = "EmployeeController/remove";
$route['xhttp/employee/action'] = "EmployeeController/request_action";