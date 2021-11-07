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

$routes->match(['post', 'get'], 'admin-blog/(.+)', "\NodCMS\Blog\Controllers\BlogAdmin::$1");
$routes->get('{locale}/blog', '\NodCMS\Blog\Controllers\Blog::posts');
$routes->get('{locale}/blog/([0-9]+)', '\NodCMS\Blog\Controllers\Blog::posts/$1');
$routes->get('{locale}/blog-post-([0-9]+)', '\NodCMS\Blog\Controllers\Blog::post/$1');
$routes->match(['post', 'get'], '{locale}/blog-comment-([0-9]+)', '\NodCMS\Blog\Controllers\Blog::comment/$1');
$routes->match(['post', 'get'], '{locale}/blog-comment-([0-9]+)-([0-9]+)', '\NodCMS\Blog\Controllers\Blog::comment/$1/$2');
$routes->get('{locale}/blog-cat-([0-9]+)', '\NodCMS\Blog\Controllers\Blog::posts/1/$1');
$routes->get('{locale}/blog-cat-([0-9]+)/([0-9]+)', '\NodCMS\Blog\Controllers\Blog::posts/$1/$3');
