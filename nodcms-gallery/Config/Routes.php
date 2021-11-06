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

$routes->match(['post', 'get'], 'admin-gallery/(.+)', "\NodCMS\Gallery\Controllers\GalleryAdmin::$1");
$routes->get('{locale}/album-([0-9a-z\-\.]+)', "\NodCMS\Gallery\Controllers\Gallery::album/$1");
$routes->get('{locale}/gallery', "\NodCMS\Gallery\Controllers\Gallery::gallery");
