<?php
/**
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
 *  @since      Version 3.2.0
 *  @filesource
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