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

namespace NodCMS\Core\Modules;

use Config\Autoload;
use Config\Services;
use Config\Models;
use NodCMS\Core\Types\MenuLink;

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
     * Execute backend bootstrap for all modules
     */
    public function executeMembership()
    {
        foreach($this->installedModules as $module) {
            if(method_exists($module, 'membership'))
                $module->membership();
        }
    }

    /**
     *
     *
     * @return MenuLink[]
     */
    public function getMenuList(): array
    {
        $list = [];
        foreach($this->installedModules as $item) {
            $list = array_merge($list, (array) $item->menuList());
        }

        return $list;
    }

    /**
     * Check if there is any dashboard panel from any modules
     *
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
     * Returns dashboard content of all active modules.
     *
     * @return array
     */
    public function getDashboards(): array
    {
        $result = [];
        foreach($this->activeModules as $key=>$module) {
            if($module->hasDashboard())
                $result[$key] = [
                    'package_id' => $module->getData()['package_id'],
                    'package_name' => $module->getData()['package_name'],
                    'active' => $module->getData()['active'],
                    'content' => $module->getDashboard(),
                ];
        }

        return $result;
    }

    /**
     * Check if there is any dashboard for member panel
     *
     * @return bool
     */
    public function hasMemberDashboard(): bool
    {
        foreach($this->activeModules as $module) {
            if(method_exists($module, 'hasMemberDashboard') && $module->hasMemberDashboard())
                return true;
        }

        return false;
    }

    /**
     * Returns dashboards ajax url of all modules.
     *
     * @return array
     */
    public function getMemberDashboards(): array
    {
        $result = [];
        foreach($this->activeModules as $key=>$module) {
            if($module->hasMemberDashboard())
                $result[$key] = $module->getMemberDashboard();
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
