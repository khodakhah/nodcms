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

/**
 * @var \Config\Paths $paths
 */

define('COREPATH', $paths->appDirectory . DIRECTORY_SEPARATOR);

// Find the requested protocol
$protocol_status = intval(isset($_SERVER['HTTPS']));
define('SSL_PROTOCOL', $protocol_status);

// Location of the framework bootstrap file.
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
