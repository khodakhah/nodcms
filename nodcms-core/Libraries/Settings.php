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
        $this->data['datepicker_date_format'] = str_replace(array('d', 'm', 'y', 'Y'), array('dd', 'mm', 'y', 'yy'), $this->data['date_format']);
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

        // Currency setup
        Services::currency()->setup();
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
