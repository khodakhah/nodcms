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

namespace NodCMS\Core\Libraries;


use Config\Services;

class Settings
{
    private $data;
    /**
     * @var bool
     */
    private $loaded = false;

    /**
     * Settings constructor.
     */
    public function __construct()
    {
        $config = new \Config\Settings();
        $this->data = $config->settings_default;
    }

    /**
     * Load settings data from database
     *
     * @param int $languageId
     */
    public function load(int $languageId = 0)
    {
        // Only one time merge
        if($this->loaded)
            return;

        // Set the merged flag
        $this->loaded = true;

        // Merge all default settings of modules
        $this->data = array_merge($this->data, Services::modules()->getModulesDefaultSettings());

        // Set the language id automatically
        if($languageId == 0) {
            $languageId = Services::language()->get()['language_id'];
        }

        // Merge not translated and translated settings from database
        $this->data = array_merge($this->data, Services::model()->settings()->getSettings(), Services::model()->settings()->getSettings($languageId));
    }

    /**
     * Returns settings data
     *
     * @return array
     */
    public function get(): array
    {
        return $this->data;
    }
}