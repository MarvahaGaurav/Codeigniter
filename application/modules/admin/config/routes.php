<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/inspiration/detail'] = 'InspirationController/details';
$route['admin/templates'] = 'Templates/index';
$route['admin/templates/index'] = 'Templates/index';
$route['admin/templates/add'] = 'Templates/add';
$route['admin/templates/(:any)/edit'] = 'Templates/edit/$1';
$route['admin/templates/(:any)'] = 'Templates/details/$1';
