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

namespace NodCMS\Core\Modules;

use Config\Autoload;
use Config\Services;
use Config\Models;

class Modules
{
    private $modulesDirs;
    private $dbModules;

    /**
     * @var I_Bootstrap[]
     */
    private $installedModules;

    /**
     * @var I_Bootstrap[]
     */
    private $activeModules;

    public function __construct()
    {
        $this->modulesDirs = Autoload::modulesPaths();
        $this->dbModules = Models::packages()->getAll();
        $this->installedModules = [];
        $this->activeModules = [];

        $modules = Models::packages()->getAll(null, null, 1, ['package_sort', 'ASC']);
        foreach($modules as $item) {
            $class = "\\".$this->getNameSpace($item['package_name'])."\Bootstrap";
            if(!class_exists($class))
                continue;

            $key = strtolower($item['package_name']);

            // Create a class
            $this->installedModules[$key] = new $class();

            // Set DB data on the class
            $this->installedModules[$key]->setData($item);

            if($item['active'] == 1)
                $this->activeModules[$key] = $this->installedModules[$key];
        }
    }

    /**
     * Returns a list of all modules directories
     *
     * @return array
     */
    public function getPaths(): array
    {
        return $this->modulesDirs;
    }

    /**
     * Returns a list of all modules names
     *
     * @return string[]
     */
    public function getNames() : array
    {
        return array_keys($this->modulesDirs);
    }

    /**
     * Check if module exists
     *
     * @param string $name
     * @return bool
     */
    public function packageExists(string $name): bool
    {
        return key_exists(strtolower($name), $this->modulesDirs);
    }

    /**
     * @param string $name
     * @return string
     */
    private function getPath(string $name): string
    {
        if(!$this->packageExists($name)) return "";

        return $this->modulesDirs[strtolower($name)];
    }

    /**
     * Convert module name to its namespace
     *
     * @param string $name
     * @return string
     */
    private function getNameSpace(string $name): string
    {
        $_module = ucfirst(str_replace("nodcms-", "", $name));
        return "NodCMS\\$_module";
    }

    /**
     * @param string $name
     * @return array
     */
    public function getTablesInfo(string $name): array
    {
        $path = $this->getPath($name);
        $tables = array();
        $models_paths = get_all_php_files("{$path}/Models/");
        if(!$models_paths) {
            return $tables;
        }
        foreach($models_paths as $model_path) {
            $model_name = "\\".$this->getNameSpace($name)."\Models\\".basename($model_path,".php");

            $theModel = new $model_name();
            if(!method_exists($theModel, 'tableName')) {
                continue;
            }

            $tables[] = array(
                'path' => $model_path,
                'table' => $theModel->tableName(),
                'exists' => $theModel->tableExists(),
                'model' => $theModel,
            );
        }

        return $tables;
    }

    /**
     * Returns a list of all modules names
     *
     * @return \NodCMS\Core\Modules\Bootstrap[]
     */
    public function getAllBootstraps() : array
    {
        $result = [];
        foreach($this->modulesDirs as $name=>$item) {
            $class = $this->getNameSpace($name)."\Bootstrap";
            if(class_exists($class))
                $result[$name] = new $class();
        }

        return $result;
    }

    /**
     * Return a entity of the config settings class in all modules
     *
     * @param string $entity
     * @return array
     */
    private function _getSettings(string $entity): array
    {
        $result = [];
        foreach($this->modulesDirs as $name=>$item) {
            $className = $this->getNameSpace($name)."\Config\Settings";
            if(class_exists($className)) {
                $class = new $className();
                if(!property_exists($class, $entity))
                    continue;

                $result = array_merge($result, $class->$entity);
            }
        }

        return $result;
    }

    /**
     * Returns autoMailMessages settings of all modules as a single array
     *
     * @return array
     */
    public function getAllAutoEmailMessages() : array
    {
        return $this->_getSettings("autoEmailMessages");
    }

    /**
     * Return default settings af all modules
     *
     * @return array
     */
    public function getModulesDefaultSettings() : array
    {
        return $this->_getSettings("settings_default");
    }

    /**
     * Returns all installed modules on NodCMS
     *
     * @return array|I_Bootstrap[]
     */
    public function getAllInstalled() : array
    {
        return $this->installedModules;
    }

    /**
     * @return string[]
     */
    public function getAllActiveNames() : array
    {
        return array_keys($this->activeModules);
    }

    /**
     * @return bool
     */
    public function hasDashboard(): bool
    {
        foreach($this->activeModules as $module) {
            if(method_exists($module, 'hasDashboard') && $module->hasDashboard())
                return true;
        }

        return false;
    }

    /**
     * Update package in database from the installed package list
     */
    public function resort()
    {
        $dashboard_modules = [];
        $modules = Models::packagesDashboard()->getAll(null, null, 1, ['package_sort', 'ASC']);
        $max = 0;
        // Remove not exists packages
        foreach ($modules as $item){
            if(!key_exists($item['package_name'], $this->installedModules)){
                Models::packagesDashboard()->remove($item['package_id']);
                continue;
            }
            if($item['package_sort']>$max){
                $max = $item['package_sort'];
            }
            $dashboard_modules[] = $item['package_name'];
        }

        // Add the packages that not exists in database
        foreach($this->installedModules as $key=>$item){
            if(!in_array($key, $dashboard_modules)){
                Models::packagesDashboard()->add(array('package_name'=>$key, 'package_sort'=>$max));
                $max++;
            }
        }
    }

    /**
     * Execute backend bootstrap for all modules
     */
    public function executeBackend()
    {
        foreach($this->installedModules as $module) {
            if(method_exists($module, 'backend'))
                $module->backend();
        }
    }

    /**
     * Returns dashboard content of all active modules.
     *
     * @return array
     */
    public function getDashboards(): array
    {
        $modules = Models::packagesDashboard()->getAll(null, null, 1, ['package_sort', 'ASC']);
        $result = [];
        foreach($modules as $item) {
            $class = "\\".$this->getNameSpace($item['package_name'])."\Bootstrap";
            if(!class_exists($class))
                continue;

            $key = strtolower($item['package_name']);

            // Create a class
            $module = new $class();

            if($module->hasDashboard())
                $result[$key] = [
                    'package_id' => $item['package_id'],
                    'package_name' => $item['package_name'],
                    'active' => $item['active'],
                    'content' => $module->getDashboard(),
                ];
        }
        return $result;
    }

    /**
     * Returns home preview content of all active modules.
     *
     * @return array
     */
    public function getHomePreviews(): array
    {
        $result = [];
        foreach($this->activeModules as $key=>$module) {
            if($module->hasHomePreview())
                $result[$key] = $module->getHomePreview();
        }
        return $result;
    }
}