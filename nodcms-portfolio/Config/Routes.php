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

if (!isset($routes)) {
    throw new \Exception('$routes not defined.');
}

$routes->match(['post', 'get'], 'admin-portfolio/(.+)', "\NodCMS\Portfolio\Controllers\PortfolioAdmin::$1");
$routes->get('{locale}/portfolio-([0-9]+)', "\NodCMS\Portfolio\Controllers\Portfolio::portfolio/$1");
