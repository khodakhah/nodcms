<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 8:45 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin-pricing-table/(.+)'] = "Pricing_table_admin/$1";
$route['([a-z]{2})/prices'] = "Pricing_table/prices/$1";