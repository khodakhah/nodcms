<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 20-May-19
 * Time: 3:13 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin-blog/(.+)'] = "Blog_admin/$1";
$route['([a-z]{2})/blog'] = 'Blog/posts/$1';
$route['([a-z]{2})/blog/([0-9]+)'] = 'Blog/posts/$1/$2';
$route['([a-z]{2})/blog-post-([0-9]+)'] = 'Blog/post/$1/$2';
$route['([a-z]{2})/blog-comment-([0-9]+)'] = 'Blog/comment/$1/$2';
$route['([a-z]{2})/blog-comment-([0-9]+)-([0-9]+)'] = 'Blog/comment/$1/$2/$3';
$route['([a-z]{2})/blog-cat-([0-9]+)'] = 'Blog/posts/$1/1/$2';
$route['([a-z]{2})/blog-cat-([0-9]+)/([0-9]+)'] = 'Blog/posts/$1/$3/$2';
