<?php
/**
 * Created by PhpStorm.
 * User: Mojtaba
 * Date: 11/20/2015
 * Time: 10:00 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

class Get_lang_in_array {
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

    function load($langfile = '', $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '') {

        $langfile = str_replace('.php', '', $langfile);

        if ($add_suffix == TRUE) {
            $langfile = str_replace('_lang.', '', $langfile) . '_lang';
        }

        $langfile .= '.php';

        if (in_array($langfile, $this->is_loaded, TRUE)) {
            return;
        }

        $config = & get_config();

        if ($idiom == '') {
            $deft_lang = (!isset($config['language'])) ? 'english' : $config['language'];
            $idiom = ($deft_lang == '') ? 'english' : $deft_lang;
        }

// Determine where the language file is and load it
        if ($alt_path != '' && file_exists($alt_path . 'language/' . $idiom . '/' . $langfile)) {
            include($alt_path . 'language/' . $idiom . '/' . $langfile);
        } else {
            $found = FALSE;

            foreach (get_instance()->load->get_package_paths(TRUE) as $package_path) {
                if (file_exists($package_path . 'language/' . $idiom . '/' . $langfile)) {
                    include($package_path . 'language/' . $idiom . '/' . $langfile);
                    $found = TRUE;
                    break;
                }
            }

            if ($found !== TRUE) {
                show_error('Unable to load the requested language file: language/' . $idiom . '/' . $langfile);
            }
        }


        if (!isset($lang)) {
            log_message('error', 'Language file contains no data: language/' . $idiom . '/' . $langfile);
            return;
        }

        if ($return == TRUE) {
            return $lang;
        }

        $this->is_loaded[] = $langfile;
        $this->language = array();
        $this->language = $lang;
        return $this->language;
        unset($lang);

        log_message('debug', 'Language file loaded: language/' . $idiom . '/' . $langfile);
        return TRUE;
    }
}