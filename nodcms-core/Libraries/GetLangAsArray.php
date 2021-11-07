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

class GetLangAsArray {
    var $language = array();

    /**
     * List of loaded language files
     *
     * @var array
     */
    var $is_loaded = array();

    function __construct() {
        log_message('debug', "Language Class Initialized");
    }

    function load($langfile = '', $idiom = '', $return = false, $add_suffix = false, $alt_path = COREPATH) {

        $langfile = str_replace('.php', '', $langfile);

        if ($add_suffix == TRUE) {
            $langfile = str_replace('_lang.', '', $langfile) . '_lang';
        }

        $langfile .= '.php';

        if (in_array($langfile, $this->is_loaded, TRUE)) {
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

        if ($return == TRUE) {
            return $lang;
        }

        $this->is_loaded[] = $langfile;
        $this->language = $lang;
        return $this->language;
    }
}
