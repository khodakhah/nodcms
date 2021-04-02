<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 25-May-19
 * Time: 4:33 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin-gallery/(.+)'] = "Gallery_admin/$1";
$route['([a-z]{2})/album-([0-9a-z\-\.]+)'] = "Gallery/album/$1/$2";
$route['([a-z]{2})/gallery'] = "Gallery/gallery/$1";