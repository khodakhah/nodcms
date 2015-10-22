<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes with
| underscores in the controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = "NodCMS_general";
$route['404_override'] = '';

$route['([a-z][a-z])'] = 'NodCMS_general/index/$1';
$route['([a-z][a-z])/page/([0-9]+)'] = 'NodCMS_general/page/$1/$2';
$route['([a-z][a-z])/extension/([0-9]+)'] = 'NodCMS_general/extension_detail/$1/$2';
$route['([a-z][a-z])/register'] = 'NodCMS_general/register/$1';
$route['([a-z][a-z])/login'] = 'NodCMS_general/login/$1';
$route['([a-z][a-z])/forget-password'] = 'NodCMS_general/forget_password/$1';
$route['([a-z][a-z])/login/([a-zA-Z0-9-]+)'] = 'NodCMS_general/login/$1/$2';
$route['([a-z][a-z])/active_account/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)'] = 'NodCMS_general/reset_password/$1/$2/$3';
$route['([a-z][a-z])/reset-password/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)'] = 'NodCMS_general/reset_password/$1/$2/$3';
$route['([a-z][a-z])/profile-password'] = 'NodCMS_general/profile_password/$1';
$route['([a-z][a-z])/contact'] = 'NodCMS_general/contact/$1';
$route['([a-z][a-z])/ajax/addcomment'] = 'NodCMS_general/extension_addcomment/$1';
$route['([a-z][a-z])/search'] = 'NodCMS_general/search/$1';
$route['([a-z][a-z])/new-feeds'] = 'NodCMS_general/rss/$1';
$route['([a-z][a-z])/sitemap.xml'] = 'NodCMS_general/sitemap_xml/$1';

//$route['([a-z][a-z])/(.*)']= $route['default_controller']."/$2/$1";
$route['admin-sign']= "NodCMS_admin_sign/index";
$route['admin-sign/login']= "NodCMS_admin_sign/login";
$route['admin-sign/logout']= "NodCMS_admin_sign/logout";
$route['admin']= "NodCMS_general_admin/index";
$route['admin/(.*)']= "NodCMS_general_admin/$1";
$route['translate_uri_dashes'] = FALSE;
