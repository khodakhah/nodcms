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