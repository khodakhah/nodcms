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

namespace NodCMS\Core\Config;

use CodeIgniter\Config\AutoloadConfig;
use CodeIgniter\Router\RouteCollection;

class DynamicAutoload extends AutoloadConfig
{
    public function __construct()
    {
        // Add the NodCMS core namespace
        $this->psr4 = array_merge($this->psr4, array(
            'NodCMS\Core'      => ROOTPATH . 'nodcms-core',
        ));

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
    private static function modulesPaths(): array {
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

    /**
     * Include modules route files
     *
     * @param RouteCollection $routes
     */
    static function includeModulesRoutes(RouteCollection $routes) {
        $modules = self::modulesPaths();
        foreach($modules as $item) {
            $route_file_path = $item."/Config/Routes.php";
            if(file_exists($route_file_path)){
                include_once $route_file_path;
            }
        }
    }
}