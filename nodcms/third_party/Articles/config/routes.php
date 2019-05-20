<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 05/05/2017
 * Time: 10:10 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin-articles/(.+)'] = "Articles_admin/$1";

$route['([a-z]{2})/article/([A-Za-z0-9\-\_]+)'] = 'Articles/article/$1/$2';
$route['([a-z][a-z])/pa-([0-9]+)'] = 'Articles/article/$1/$2';
//$route['([a-z][a-z])/articles'] = 'Articles/articles/$1';
