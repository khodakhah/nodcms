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
if(! HAS_DB_CONFIG) {
    $route['default_controller'] = $route['installer'] = "Installer/start";
    $route['installer/(start|authorization|eula|database|complete)'] = "Installer/$1";
    return;
}
else {
    $route['installer/(.*)'] = 'General/showError';
}
$route['default_controller'] = "General/setLanguagePrefix";
$route['404_override'] = 'General/showError';

// Admin URLs
$route['admin']= "General_admin/dashboard";
$route['admin/(.+)']= "General_admin/$1";
$route['admin-provider']= "Providers_admin/index";
$route['admin-provider/(.+)']= "Providers_admin/$1";

$route['get-new-captcha']= "General/resetCaptcha";

$route['user/dashboard'] = "General_members/dashboard";
$route['user/account']= "General_members/account";
$route['user/account/personal-info']= "General_members/accountPersonalInfo";
$route['user/account/change-password']= "General_members/accountChangePassword";
$route['user/account/change-avatar']= "General_members/accountChangeAvatar";
$route['user/account/remove-avatar']= "General_members/accountRemoveAvatar";
$route['user/account/remove-avatar-confirmed']= "General_members/accountRemoveAvatar/1";

$route['user/account-avatar-change']= "General_members/accountAvatarChange";
$route['user/account-avatar-upload']= "General_members/accountAvatarUpload";
//$route['user-([A-Za-z\_]+)/dashboard'] = '$1/dashboard';
//General URLs
$route['(file|image)-([0-9]+)-([A-Za-z0-9\_]+)'] = 'General/$1/$2/$3';
$route['noimage-([0-9]+)-([0-9]+)-([A-Za-z0-9\_]+)'] = 'General/noimage/$1/$2/$3';
$route['noimage-([0-9]+)-([0-9]+)'] = 'General/noimage/$1/$2';
$route['remove-my-file/([0-9]+)-([A-Za-z0-9\_]+)'] = 'General/removeMyFile/$1/$2';
// General Pages
$route['([a-z]{2})'] = 'General/index/$1';
$route['([a-z]{2})/([A-Za-z\_]+)-index'] = '$2/index/$1';
$route['([a-z]{2})/contact'] = 'General/contact/$1';
$route['([a-z]{2})/contact-home'] = 'General/contact/$1/home';
$route['([a-z][a-z])/(terms-and-conditions|privacy-policy)']= "General/staticSettingsPages/$1/$2";
// Registration
$route['admin-sign']= "Registration/login";
$route['account-locked']= "Registration/accountLocked";
$route['([a-z]{2})/account-locked']= "Registration/accountLocked/$1";
$route['(login|logout)']= "Registration/$1";
$route['([a-z][a-z])/(login|logout)']= "Registration/$2/$1";
$route['([a-z][a-z])/user-registration']= "Registration/userRegistration/$1";
$route['([a-z][a-z])/return-password']= "Registration/returnPassword/$1";
$route['([a-z][a-z])/user-registration/message']= "Registration/userRegistrationMessage/$1";
$route['([a-z][a-z])/user-registration/active/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)']= "Registration/activeAccount/$1/$2/$3";
$route['([a-z][a-z])/set-new-password/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)']= "Registration/setNewPassword/$1/$2/$3";

$route['translate_uri_dashes'] = FALSE;
