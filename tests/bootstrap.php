<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
// Root directory
define("ROOTPATH", FCPATH.DIRECTORY_SEPARATOR);
// CodeIgniter core path
define("SYSTEMPATH", ROOTPATH."system".DIRECTORY_SEPARATOR);
// NodCMS core path
define("COREPATH", ROOTPATH."nodcms-core".DIRECTORY_SEPARATOR);
// NodCMS public path
define("SELF_PATH", FCPATH);
define("APPPATH", COREPATH);

// Find the requested protocol
$protocol_status = intval(isset($_SERVER['HTTPS']));
define("SSL_PROTOCOL", $protocol_status);
define("URL_PROTOCOL", $protocol_status ? "https://" : "http://");

// Find the base url
define("CONFIG_BASE_URL", "/");
define('ASSETS_BASE_URL', CONFIG_BASE_URL);
define('ASSETS_LOCAL_URL', CONFIG_BASE_URL);

define("DB_CONFIG_PATH", COREPATH.'Config/Database.php');

ini_set('error_reporting', E_ALL);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Make sure it recognizes that we're testing.
$_SERVER['CI_ENVIRONMENT'] = 'testing';
define('ENVIRONMENT', 'testing');

// Load framework paths from their config file
require CONFIGPATH . 'Paths.php';
$paths = new Config\Paths();

// Define necessary framework path constants
defined('APPPATH')       || define('APPPATH', realpath($paths->appDirectory) . DIRECTORY_SEPARATOR);
defined('WRITEPATH')     || define('WRITEPATH', realpath($paths->writableDirectory) . DIRECTORY_SEPARATOR);
defined('SYSTEMPATH')    || define('SYSTEMPATH', realpath($paths->systemDirectory) . DIRECTORY_SEPARATOR);
defined('ROOTPATH')      || define('ROOTPATH', realpath(APPPATH . '../') . DIRECTORY_SEPARATOR);
defined('CIPATH')        || define('CIPATH', realpath(SYSTEMPATH . '../') . DIRECTORY_SEPARATOR);
defined('FCPATH')        || define('FCPATH', realpath(PUBLICPATH) . DIRECTORY_SEPARATOR);
defined('TESTPATH')      || define('TESTPATH', realpath(HOMEPATH . 'tests/') . DIRECTORY_SEPARATOR);
defined('SUPPORTPATH')   || define('SUPPORTPATH', realpath(TESTPATH . '_support/') . DIRECTORY_SEPARATOR);
defined('COMPOSER_PATH') || define('COMPOSER_PATH', realpath(HOMEPATH . 'vendor/autoload.php'));
defined('VENDORPATH')    || define('VENDORPATH', realpath(HOMEPATH . 'vendor') . DIRECTORY_SEPARATOR);

// Load Common.php from App then System
if (file_exists(APPPATH . 'Common.php'))
{
	require_once APPPATH . 'Common.php';
}

require_once SYSTEMPATH . 'Common.php';

// Set environment values that would otherwise stop the framework from functioning during tests.
if (! isset($_SERVER['app.baseURL']))
{
	$_SERVER['app.baseURL'] = 'http://example.com';
}

// Load necessary components
require_once SYSTEMPATH . 'Config/AutoloadConfig.php';
require_once APPPATH . 'Config/Autoload.php';
require_once APPPATH . 'Config/Constants.php';
require_once SYSTEMPATH . 'Modules/Modules.php';
require_once APPPATH . 'Config/Modules.php';

require_once SYSTEMPATH . 'Autoloader/Autoloader.php';
require_once SYSTEMPATH . 'Config/BaseService.php';
require_once SYSTEMPATH . 'Config/Services.php';
require_once APPPATH . 'Config/Services.php';

// Use Config\Services as CodeIgniter\Services
if (! class_exists('CodeIgniter\Services', false))
{
	class_alias('Config\Services', 'CodeIgniter\Services');
}

// Launch the autoloader to gather namespaces (includes composer.json's "autoload-dev")
$loader = \CodeIgniter\Services::autoloader();
$loader->initialize(new Config\Autoload(), new Config\Modules());

// Register the loader with the SPL autoloader stack.
$loader->register();

require_once APPPATH . 'Config/Routes.php';
$routes->getRoutes('*');
