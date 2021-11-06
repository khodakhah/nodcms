<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

namespace Config;
// Create a new instance of our RouteCollection class.

$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
    require_once SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('NodCMS\Core\Controllers');
$routes->setDefaultController('General');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Admin URLs
$routes->get('admin', "GeneralAdmin::dashboard");
$routes->match(['post', 'get'],'admin/(.+)', "GeneralAdmin::$1");
$routes->get('admin-provider', "Providers_admin::index");
$routes->get('admin-provider/(.+)', "Providers_admin::$1");

$routes->get('get-new-captcha', "General::resetCaptcha");

$routes->get('user/dashboard', "GeneralMembers::dashboard");
$routes->get('user/account', "GeneralMembers::account");
$routes->match(['post', 'get'],'user/account/personal-info', "GeneralMembers::accountPersonalInfo");
$routes->match(['post', 'get'],'user/account/change-password', "GeneralMembers::accountChangePassword");
$routes->match(['post', 'get'],'user/account/change-avatar', "GeneralMembers::accountChangeAvatar");
$routes->match(['post', 'get'],'user/account/remove-avatar', "GeneralMembers::accountRemoveAvatar");
$routes->match(['post', 'get'],'user/account/remove-avatar-confirmed', "GeneralMembers::accountRemoveAvatar/1");

$routes->match(['post', 'get'],'user/account-avatar-change', "GeneralMembers::accountAvatarChange");
$routes->match(['post', 'get'],'user/account-avatar-upload', "GeneralMembers::accountAvatarUpload");
//$routes->get('user-([A-Za-z\_]+)/dashboard', '$1/dashboard');
//General URLs
$routes->get('{locale}/(file|image)-([0-9]+)-([A-Za-z0-9\_]+)', 'General::$1/$2/$3');
$routes->get('{locale}/noimage-([0-9]+)-([0-9]+)-([A-Za-z0-9\_]+)', 'General::noimage/$1/$2/$3');
$routes->get('{locale}/noimage-([0-9]+)-([0-9]+)', 'General::noimage/$1/$2');
$routes->match(['post', 'get'],'{locale}/remove-my-file/([0-9]+)-([A-Za-z0-9\_]+)', 'General::removeMyFile/$1/$2');

// General Pages
$routes->get('[a-z]{2}', 'General::index');
$routes->get('{locale}/([A-Za-z\_]+)-index', '$1::index');
$routes->match(['post', 'get'],'{locale}/contact', 'General::contact');
$routes->get('{locale}/contact-home', 'General::contact/home');
$routes->get('{locale}/(terms-and-conditions|privacy-policy)', "General::staticSettingsPages/$1");

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
