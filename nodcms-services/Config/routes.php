<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 11:39 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin-services/(.+)'] = "Services_admin/$1";
$route['([a-z]{2})/service-([a-z0-9\-\.]+)'] = 'Services/service/$1/$2';
