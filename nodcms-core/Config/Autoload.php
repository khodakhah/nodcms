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

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTO-LOADER
 * -------------------------------------------------------------------
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 * the values in this file will overwrite the framework's values.
 */
class Autoload extends AutoloadConfig
{

	/**
	 * -------------------------------------------------------------------
	 * Namespaces
	 * -------------------------------------------------------------------
	 * This maps the locations of any namespaces in your application to
	 * their location on the file system. These are used by the autoloader
	 * to locate files the first time they have been instantiated.
	 *
	 * The '/app' and '/system' directories are already mapped for you.
	 * you may change the name of the 'App' namespace if you wish,
	 * but this should be done prior to creating any namespaced classes,
	 * else you will need to modify all of those classes for this to work.
	 *
	 * Prototype:
	 *
	 *   $psr4 = [
	 *       'CodeIgniter' => SYSTEMPATH,
	 *       'App'	       => APPPATH
	 *   ];
	 *
	 * @var array
	 */
	public $psr4 = [
		APP_NAMESPACE => APPPATH, // For custom app namespace
		'Config'      => APPPATH . 'Config',
	];

	/**
	 * -------------------------------------------------------------------
	 * Class Map
	 * -------------------------------------------------------------------
	 * The class map provides a map of class names and their exact
	 * location on the drive. Classes loaded in this manner will have
	 * slightly faster performance because they will not have to be
	 * searched for within one or more directories as they would if they
	 * were being autoloaded through a namespace.
	 *
	 * Prototype:
	 *
	 *   $classmap = [
	 *       'MyClass'   => '/path/to/class/file.php'
	 *   ];
	 *
	 * @var array
	 */
	public $classmap = [];

    public function __construct()
    {
        // Add the NodCMS modules namespace
        $modules = self::modulesPaths();
        foreach($modules as $module=>$path) {
            $_module = ucfirst(str_replace("nodcms-", "", $module));
            $this->psr4 = array_merge($this->psr4, array(
                "NodCMS\\$_module"      => $path,
            ));
        }

        parent::__construct();
    }

    /**
     * Feth all modules paths
     *
     * @return array
     */
    public static function modulesPaths(): array {
        if(!is_dir(ROOTPATH)) {
            return array();
        }
        $paths = array();
        $dir = scandir(ROOTPATH);
        unset($dir[0]);
        unset($dir[1]);
        foreach ($dir as $item) {
            if(preg_match('~([^/]+)\.[A-Za-z0-9]+~', $item))
                continue;
            if(!preg_match('/^nodcms\-[a-z0-9]+$/', $item))
                continue;
            if($item == "nodcms-core")
                continue;
            $paths[$item] = ROOTPATH.$item."/";
        }

        return $paths;
    }
}
