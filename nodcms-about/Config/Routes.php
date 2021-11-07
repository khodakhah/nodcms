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

$routes->get('{locale}/about-([A-Za-z0-9\-\_]+)', '\NodCMS\About\Controllers\About::profile/$1');

$routes->match(['post', 'get'], 'admin-about/(.+)', "\NodCMS\About\Controllers\AboutAdmin::$1");

