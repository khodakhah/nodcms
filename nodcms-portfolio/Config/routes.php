<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 3:34 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin-portfolio/(.+)'] = "Portfolio_admin/$1";
$route['([a-z]{2})/portfolio-([0-9]+)'] = "Portfolio/portfolio/$1/$2";