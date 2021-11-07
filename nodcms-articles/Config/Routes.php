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

$routes->match(['post', 'get'], 'admin-articles/(.+)', "\NodCMS\Articles\Controllers\ArticlesAdmin::$1");

$routes->get('{locale}/article/([A-Za-z0-9\-\_]+)', '\NodCMS\Articles\Controllers\Articles::article/0/$1');
$routes->get('{locale}/pa-([0-9]+)', '\NodCMS\Articles\Controllers\Articles::article/$1');
//$routes->get('([a-z][a-z])/articles', 'NodCMS\Articles\Controllers\Articles::articles/$1');
