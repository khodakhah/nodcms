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

if(!isset($routes)) throw new \Exception('$routes not defined.');

$namespace = "\NodCMS\Users\Controllers\\";

$routes->match(['post', 'get'],'{locale}/login', "{$namespace}Users::login");
$routes->get('account-locked', "{$namespace}Users::accountLocked");
$routes->get('{locale}/account-locked', "{$namespace}Users::accountLocked");
$routes->get('logout', "{$namespace}Users::logout");
$routes->get('{locale}/logout', "{$namespace}Users::logout");
$routes->match(['post', 'get'],'{locale}/user-registration', "{$namespace}Users::userRegistration");
$routes->match(['post', 'get'],'{locale}/return-password', "{$namespace}Users::returnPassword");
$routes->get('{locale}/user-registration/message', "{$namespace}Users::userRegistrationMessage");
$routes->match(['post', 'get'],'{locale}/user-registration/active/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)', "{$namespace}Users::activeAccount/$1/$2/");
$routes->match(['post', 'get'],'{locale}/set-new-password/([a-zA-Z0-9-]+)/([a-zA-Z0-9-]+)', "{$namespace}Users::setNewPassword/$1/$2/");
