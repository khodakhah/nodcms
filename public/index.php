<?php

// Valid PHP Version?
$minPHPVersion = '7.2';
if (phpversion() < $minPHPVersion)
{
	die("Your PHP version must be {$minPHPVersion} or higher to run CodeIgniter. Current version: " . phpversion());
}
unset($minPHPVersion);

// Acceptable values: development, testing, production
define('ENVIRONMENT', 'development');

// Allow run installer if the database connection has been failed
define("ALLOW_INSTALLER", true);

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Root directory
define("ROOTPATH", dirname(FCPATH).DIRECTORY_SEPARATOR);
// CodeIgniter core path
define("SYSTEMPATH", ROOTPATH."system".DIRECTORY_SEPARATOR);
// NodCMS core path
define("COREPATH", ROOTPATH."nodcms-core".DIRECTORY_SEPARATOR);

// Find the requested protocol
$protocol_status = intval(isset($_SERVER['HTTPS']));
define("SSL_PROTOCOL", $protocol_status);
define("URL_PROTOCOL", $protocol_status ? "https://" : "http://");

// Find the base url
define("CONFIG_BASE_URL", URL_PROTOCOL.$_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/");
define('ASSETS_BASE_URL', CONFIG_BASE_URL);
define('ASSETS_LOCAL_URL', CONFIG_BASE_URL);

define("DB_CONFIG_PATH", COREPATH.'Config/Database.php');

// Location of the NodCMS addon bootstrap file.
require COREPATH.'bootstrap.php';

// Location of the Paths config file.
// This is the line that might need to be changed, depending on your folder structure.
$pathsPath = realpath(COREPATH . 'Config/Paths.php');
// ^^^ Change this if you move your application folder

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// Ensure the current directory is pointing to the front controller's directory
chdir(__DIR__);

// Load our paths config file
require $pathsPath;
$paths = new NodCMS\Core\Config\Paths();

// Location of the framework bootstrap file.
$app = require rtrim($paths->systemDirectory, '/ ') . '/bootstrap.php';

/*
 *---------------------------------------------------------------
 * LAUNCH THE APPLICATION
 *---------------------------------------------------------------
 * Now that everything is setup, it's time to actually fire
 * up the engines and make this app do its thang.
 */
$app->run();
