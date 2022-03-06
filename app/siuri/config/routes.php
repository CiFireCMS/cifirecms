<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Siuri';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['siuri'] = 'error';
$route['Siuri'] = 'error';

$route['make'] = 'Siuri/make';

// Controller.
$route['make/controller'] = 'Siuri/controller';
$route['make/controller/(:any)'] = 'Siuri/controller/$1';
$route['make/controller/(:any)/(:any)'] = 'Siuri/controller/$1/$2';

// Model.
$route['make/model'] = 'Siuri/model';
$route['make/model/(:any)'] = 'Siuri/model/$1';
$route['make/model/(:any)/(:any)'] = 'Siuri/model/$1/$2';
$route['make/model/(:any)/(:any)/(:any)'] = 'Siuri/model/$1/$2/$3';

// Table.
$route['make/table/(:any)'] = 'Siuri/create_table/$1';