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

use Config\App;

class GetLangAsArray
{
    public $language = array();

    /**
     * List of loaded language files
     *
     * @var array
     */
    public $is_loaded = array();

    public function __construct()
    {
        log_message('debug', "Language Class Initialized");
    }

    public function load($langfile = '', $idiom = '', $return = false, $add_suffix = false, $alt_path = COREPATH)
    {
        $langfile = str_replace('.php', '', $langfile);

        if ($add_suffix == true) {
            $langfile = str_replace('_lang.', '', $langfile) . '_lang';
        }

        $langfile .= '.php';

        if (in_array($langfile, $this->is_loaded, true)) {
            return [];
        }


        if ($idiom == '') {
            $idiom = App::get("defaultLocale");
        }

        if (file_exists($alt_path . 'Language/' . $idiom . '/' . $langfile)) {
            $lang = include($alt_path . 'Language/' . $idiom . '/' . $langfile);
        }

        if (!isset($lang)) {
            throw new \Exception('Language file contains no data: '.$alt_path.'/language/' . $idiom . '/' . $langfile);
        }

        if ($return == true) {
            return $lang;
        }

        $this->is_loaded[] = $langfile;
        $this->language = $lang;
        return $this->language;
    }
}
