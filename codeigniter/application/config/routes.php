<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['search']					= 'search';
$route['posts']						= 'posts';
$route['snapshots']					= 'snapshots';
$route['openletters']				= 'openletters';
$route['tv']						= 'tv';
$route['pages']						= 'pages';
$route['users']						= 'users';
$route['timeline']					= 'user';
$route['updates/(:any)']			= 'updates';
$route['sitemap\.xml']				= 'sitemap';
$route['(:any)']					= 'user';
$route['(:any)/(:num)']				= 'user';
$route['(:any)/(:num)/(:num)']		= 'user';
$route['(:any)/followers']			= 'user';
$route['(:any)/following']			= 'user';
$route['(:any)/friends']			= 'user';
$route['default_controller'] 		= 'user';
//$route['(.+)']					= 'user';
$route['404_override'] 				= 'error';
$route['translate_uri_dashes'] 		= FALSE;
