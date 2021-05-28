<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2020.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2020 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
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
$routes->setDefaultController('Dispatcher');
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
$routes->get('admin', "GeneralAdmin::dashboard", ['filter'=>"identityVerification"]);
$routes->match(['post', 'get'],'admin/(.+)', "GeneralAdmin::$1", ['filter'=>"identityVerification"]);
$routes->get('admin-provider', "Providers_admin::index");
$routes->get('admin-provider/(.+)', "Providers_admin::$1");

$routes->get('get-new-captcha', "General::resetCaptcha");

$routes->get('user/dashboard', "GeneralMembers::dashboard", ['filter'=>"identityVerification"]);
$routes->get('user/account', "GeneralMembers::account", ['filter'=>"identityVerification"]);
$routes->match(['post', 'get'],'user/account/personal-info', "GeneralMembers::accountPersonalInfo", ['filter'=>"identityVerification"]);
$routes->match(['post', 'get'],'user/account/change-password', "GeneralMembers::accountChangePassword", ['filter'=>"identityVerification"]);
$routes->match(['post', 'get'],'user/account/change-avatar', "GeneralMembers::accountChangeAvatar");
$routes->match(['post', 'get'],'user/account/remove-avatar', "GeneralMembers::accountRemoveAvatar");
$routes->match(['post', 'get'],'user/account/remove-avatar-confirmed', "GeneralMembers::accountRemoveAvatar/1", ['filter'=>"identityVerification"]);

$routes->match(['post', 'get'],'user/account-avatar-change', "GeneralMembers::accountAvatarChange", ['filter'=>"identityVerification"]);
$routes->match(['post', 'get'],'user/account-avatar-upload', "GeneralMembers::accountAvatarUpload", ['filter'=>"identityVerification"]);
//$routes->get('user-([A-Za-z\_]+)/dashboard', '$1/dashboard');
//General URLs
$routes->get('{locale}/(file|image)-([0-9]+)-([A-Za-z0-9\_]+)', 'General::$1/$2/$3', ['filter'=>"urlLocale"]);
$routes->get('{locale}/noimage-([0-9]+)-([0-9]+)-([A-Za-z0-9\_]+)', 'General::noimage/$1/$2/$3', ['filter'=>"urlLocale"]);
$routes->get('{locale}/noimage-([0-9]+)-([0-9]+)', 'General::noimage/$1/$2', ['filter'=>"urlLocale"]);
$routes->match(['post', 'get'],'remove-my-file/([0-9]+)-([A-Za-z0-9\_]+)', 'General::removeMyFile/$1/$2');

// General Pages
$routes->get('{locale}', 'General::index', ['filter'=>"urlLocale"]);
$routes->get('{locale}/([A-Za-z\_]+)-index', '$1::index', ['filter'=>"urlLocale"]);
$routes->match(['post', 'get'],'{locale}/contact', 'General::contact', ['filter'=>"urlLocale"]);
$routes->get('{locale}/contact-home', 'General::contact/home', ['filter'=>"urlLocale"]);
$routes->get('{locale}/(terms-and-conditions|privacy-policy)', "General::staticSettingsPages/$1", ['filter'=>"urlLocale"]);

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