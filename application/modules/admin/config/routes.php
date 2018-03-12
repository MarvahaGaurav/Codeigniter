<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/inspiration/detail'] = 'InspirationController/details';
$route['admin/templates'] = 'TemplateController/index';
$route['admin/templates/add'] = 'TemplateController/add';
$route['admin/templates/(:any)/edit'] = 'TemplateController/edit';
$route['admin/templates/(:any)'] = 'TemplateController/details/$1';
