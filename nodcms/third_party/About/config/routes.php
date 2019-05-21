<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 15-Apr-19
 * Time: 3:20 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$route['([a-z]{2})/about-([A-Za-z0-9\-\_]+)'] = 'About/profile/$1/$2';

$route['admin-about/(.+)']= "About_admin/$1";

