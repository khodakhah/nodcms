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

namespace NodCMS\Core\Core;

use Config\Autoload;
use Config\Services;
use NodCMS\Core\Models\ModelMap;

class Modules
{
    private $modulesDirs;
    private $dbModules;

    /**
     * @var Hooks[]
     */
    private $activeModules;

    public function __construct()
    {
        $this->modulesDirs = Autoload::modulesPaths();
        $this->dbModules = ModelMap::packages()->getAll();
        $this->activeModules = array();

        $modules = ModelMap::packages()->getAll(['active'=>1]);
        foreach($modules as $item) {
            $class = "NodCMS\\".ucfirst($item['package_name'])."\Startup";
            $this->activeModules[ucfirst($item['package_name'])] = new $class();
        }
    }

    public function preset()
    {
        foreach($this->activeModules as $module) {
            $module->preset();
        }
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
     * Update package in database
     */
    public function resort()
    {
        $model = Services::model();
        $max = 0;
        // Remove not exists packages
        foreach ($this->dbModules as $item){
            if(!key_exists($item['package_name'], $this->modulesDirs)){
                $model->packagesDashboard()->remove($item['package_id']);
                continue;
            }
            if($item['package_sort']>$max){
                $max = $item['package_sort'];
            }
        }
        // Add the packages that not exists in database
        foreach($this->modulesDirs as $item){
            $count = $model->packagesDashboard()->getCount(array('package_name'=>$item));
            if($count == 0){
                $model->packagesDashboard()->add(array('package_name'=>$item, 'package_sort'=>$max));
                $max++;
            }
        }
    }
}