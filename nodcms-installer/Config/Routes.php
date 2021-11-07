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

$namespace = "\NodCMS\Installer\Controllers\\";

$routes->get('/installer', '\NodCMS\Installer\Controllers\Installer::start');
$routes->match(['post', 'get'], '/installer/(start|authorization|license|database|settings|complete)', '\NodCMS\Installer\Controllers\Installer::$1');
