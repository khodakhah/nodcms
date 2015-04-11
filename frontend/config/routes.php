<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "pages";
$route['404_override'] = '';

$route['([a-z][a-z])'] = 'pages/index/$1';
$route['([a-z][a-z])/page/([0-9]+)'] = 'pages/page/$1/$2';
$route['([a-z][a-z])/extension/([0-9]+)'] = 'pages/extension_detail/$1/$2';
$route['([a-z][a-z])/register'] = 'pages/register/$1';
$route['([a-z][a-z])/login'] = 'pages/login/$1';
$route['([a-z][a-z])/forget-password'] = 'pages/forget_password/$1';
$route['([a-z][a-z])/login/([a-zA-Z0-9-]+)'] = 'pages/login/$1/$2';
$route['([a-z][a-z])/active_account/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)'] = 'pages/reset_password/$1/$2/$3';
$route['([a-z][a-z])/reset-password/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)'] = 'pages/reset_password/$1/$2/$3';
$route['([a-z][a-z])/profile-password'] = 'pages/profile_password/$1';
$route['([a-z][a-z])/contact'] = 'pages/contact/$1';
$route['([a-z][a-z])/ajax/addcomment'] = 'pages/extension_addcomment/$1';
$route['([a-z][a-z])/search'] = 'pages/search/$1';
$route['([a-z][a-z])/new-feeds'] = 'pages/rss/$1';
$route['([a-z][a-z])/sitemap.xml'] = 'pages/sitemap_xml/$1';

//$route['([a-z][a-z])/(.*)']= $route['default_controller']."/$2/$1";
$route['admin']= $route['default_controller']."/admin";

/* End of file routes.php */
/* Location: ./application/config/routes.php */