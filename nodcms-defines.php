<?php
/**
 * NodCMS
 *
 * Copyright (c) 2015-2020.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2020 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

// Allow a test url to check online payment process
define("ALLOW_TEST_PAYMENT", false);

// Allow run installer if the database connection has been failed
define("ALLOW_INSTALLER", true);

// Directory name of resources name
define("RESOURCES_DIR_NAME", "resources");

// Find the requested protocol
$protocol_status = intval(isset($_SERVER['HTTPS']));
$protocols = array('protocol0' => "http://", 'protocol1' => "https://");
define("URL_PROTOCOL", $protocols["protocol$protocol_status"]);
define("SSL_PROTOCOL", $protocol_status);
// Find the base url
$host = URL_PROTOCOL.$_SERVER['HTTP_HOST'];
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
define("CONFIG_BASE_URL", URL_PROTOCOL.$_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/");
define('ASSETS_BASE_URL', CONFIG_BASE_URL);
define('ASSETS_LOCAL_URL', CONFIG_BASE_URL);

define("DB_CONFIG_PATH", APPPATH.'config/database.php');
define("HAS_DB_CONFIG", intval(file_exists(DB_CONFIG_PATH) && filesize(DB_CONFIG_PATH) > 0));
