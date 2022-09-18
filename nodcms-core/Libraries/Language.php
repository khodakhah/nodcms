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

use CodeIgniter\Language\Language as CI_Language;
use NodCMS\Core\Models\Languages;

class Language extends CI_Language
{
    private const LANGUAGE_KEYS_PATTERN = '[A-Za-z0-9\s\_\-\.\,\:\;\|\/\!\'\?\&\@\#\€\{\}\(\)\[\]]+';

    /**
     * The row of the current(loaded) language form database.
     *
     * @var array
     */
    private $DBlanguage;

    /**
     * Set the row of current loaded language from database.
     *
     * @param array $language
     */
    public function set(array $language)
    {
        $this->DBlanguage = $language;
        parent::setLocale($language['code']);
    }

    /**
     * Returns the row of current language form database.
     *
     * @return array
     */
    public function get(): array
    {
        return $this->DBlanguage;
    }

    /**
     * Returns true if language from database has been set/loaded.
     *
     * @return bool
     */
    public function hasBeenSet(): bool
    {
        return !empty($this->DBlanguage);
    }

    /**
     * Fetch NodCMS old school translation file and line
     *
     * @param string $line
     * @param array $args
     * @return string|string[]
     */
    public function getLine(string $line, array $args = [])
    {
        if (!strpos($line, '.')) {
            $line = "app.{$line}";
        }

        return parent::getLine($line, $args);
    }

    /**
     * Find current used and unused translation keys all over the NodCMS
     *
     * @param bool $overwrite
     * @param bool $remove
     * @return array
     * @throws \Exception
     */
    public function currentLangLines(bool $overwrite = false, bool $remove = false): array
    {
        $unique_array = $this->uniqueUsedTranslations();
        $in_my_temp = $this->getTempFileKeys();

        // * Find removed labels
        $removed_keys = array();
        foreach ($in_my_temp as $keys) {
            if (!in_array($keys, $unique_array)) {
                array_push($removed_keys, $keys);
            }
        }

        // * Remove duplicates
        foreach ($unique_array as $key => $item) {
            if (in_array($item, $in_my_temp)) {
                unset($unique_array[$key]);
            }
        }

        // * Update temp_lang.php
        if ($overwrite) {
            $languages = array_filter(glob(COREPATH.'Language/*'), 'is_dir');
            foreach ($languages as $language_dir) {
                preg_match('/.*\/([A-Za-z]{2})/', $language_dir, $my_match);

                $locale = $my_match[1];
                $this->removeLines($removed_keys, $locale);
                $lines = $this->getLines($locale, "app");
                $_lines = array_combine($unique_array, $unique_array);
                foreach ($unique_array as $key) {
                    if (!isset($lines[$key])) {
                        $_lines[$key] = $key;
                    } else {
                        $_lines[$key] = $lines[$key];
                    }
                }
                $this->saveTranslations($_lines, $locale);
            }

            if ($remove) {
                $in_my_temp = array_diff($in_my_temp, $removed_keys);
            }
            $in_my_temp = array_merge($in_my_temp, $unique_array);
            $this->resetJustLanguageTemp($in_my_temp);
        }

        return array(
            'removed'=>$removed_keys,
            'new'=>$unique_array
        );
    }

    /**
     * Reset language translation files
     *
     * @param null $language_name
     * @throws \Exception
     */
    public function resetLanguageTempFile($language_name = null)
    {
        $unique_array = $this->uniqueUsedTranslations();

        // * Update temp_lang.php
        $this->resetJustLanguageTemp($unique_array);

        // Reset only one language
        if ($language_name != null) {
            $languagePath = COREPATH.'Language/'.$language_name;
            if (!file_exists($languagePath)) {
                throw new \Exception("\"{$languagePath}\" doesn't exists!");
            }
            $languages = array($languagePath);
        }
        // Reset all exists language
        else {
            $languages = array_filter(glob(COREPATH.'Language/*'), 'is_dir');
        }

        // Generate untranslated lines
        $untranslated_lines = array_combine($unique_array, $unique_array);

        foreach ($languages as $item) {
            if (preg_match('/.*\/([A-Za-z]{2}})/', $item, $my_match)) {
                $this->saveTranslations($untranslated_lines, $my_match[1], true);
            }
        }
    }

    /**
     * Update a specific line
     *
     * @param string $locale
     * @param string $key
     * @param string $value
     * @throws \Exception
     */
    public function updateLine(string $locale, string $key, string $value)
    {
        $lines = $this->getLines($locale, "app");
        $lines[$key] = $value;
        $this->saveTranslations($lines, $locale);
    }

    /**
     * Returns all lines of a file in a language
     *
     * @param string $locale
     * @param string $file
     * @return array
     */
    private function getLines(string $locale, string $file): array
    {
        if (!isset($this->language[$locale]) && !isset($this->language[$locale][$file])) {
            return [];
        }

        return $this->language[$locale][$file];
    }

    /**
     * Find all NodCMS language translation lines that currently are using in application
     *
     * @return string[]
     */
    private function uniqueUsedTranslations(): array
    {
        $unique_array = array(
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sun",
            "Mon",
            "Tue",
            "Wed",
            "Thu",
            "Fri",
            "Sat",
            "Su",
            "Mo",
            "Tu",
            "We",
            "Th",
            "Fr",
            "Sa",
            "AM",
            "am",
            "PM",
            "pm",
        );
        $lang_regex = '/\_l\([\'\"]('.self::LANGUAGE_KEYS_PATTERN.')[\'\"\&]\,[\s]?\$this(\-\>CI)?[\s]?\)/';
        $system_message_regex = '/\$this(\-\>CI)?\-\>(errorMessage|successMessage)\([\'\"]('.self::LANGUAGE_KEYS_PATTERN.')[\'\"]\,[\s]?.+[\s]?\)/';
        // * Find all paths
        $controllers= get_all_php_files(COREPATH."Controllers");
        $dirs = !empty($controllers) ? $controllers : [];
        $packages = \Config\Services::modules()->getPaths();
        $moduleFiles = ["Views", "Controllers"];
        foreach ($packages as $item) {
            foreach ($moduleFiles as $moduleFile) {
                $third_party = get_all_php_files("{$item}{$moduleFile}");
                if (is_array($third_party)) {
                    $dirs = array_merge($dirs, $third_party);
                }
            }
            if (file_exists("{$item}Bootstrap.php")) {
                array_push($dirs, "{$item}Bootstrap.php");
            }
        }

        // Find the patterns from all paths
        foreach ($dirs as $key=>$item) {
            $file_content = file_get_contents($item);
            preg_match_all($lang_regex, $file_content, $matches);
            if (count($matches[1])!=0) {
                $unique_array = array_unique(array_merge($unique_array, $matches[1]));
            }
            preg_match_all($system_message_regex, $file_content, $matches);
            if (count($matches[3])!=0) {
                $unique_array = array_unique(array_merge($unique_array, $matches[3]));
            }
        }

        return $unique_array;
    }

    /**
     * Returns all keys in the "temp" file as an array
     *
     * @return array
     * @throws \Exception
     */
    private function getTempFileKeys(): array
    {
        $temp_file = COREPATH.'Language/lang_temp.php';
        if (file_exists($temp_file)) {
            include $temp_file;
            $in_my_temp = isset($lang_temp) ? $lang_temp : array();
        } else {
            $myFile = fopen($temp_file, "w");
            if ($myFile === false) {
                throw new \Exception("Unable to open file!");
            }
            fwrite($myFile, "<?php\n");
            fclose($myFile);
            $in_my_temp = array();
        }

        return $in_my_temp;
    }

    /**
     * Update the language temp file
     * "lang_temp.php" stores unique NodCMS translation lines to compare
     *
     * @param array $unique_translations
     */
    private function resetJustLanguageTemp(array $unique_translations)
    {
        $new_content = "<?php\n" .
            "/**\n" .
            " * Made automatically by NodCMS\n" .
            " * Date: ".date("Y.m.d")."\n" .
            " * Time: ".date("H:i")."\n" .
            " * Find from ".count($unique_translations)." Files\n" .
            "*/\n" .
            '$lang_temp = array('."\n";
        $new_content .= '    "'.implode('",'."\n".'    "', $unique_translations).'"'."\n";

        $new_content .= ');'."\n";
        $temp_file = COREPATH.'Language/lang_temp.php';
        if (file_exists($temp_file)) {
            file_put_contents($temp_file, $new_content);
        } else {
            $myfile = fopen($temp_file, "w") or die("Unable to open file!");
            fwrite($myfile, $new_content);
            fclose($myfile);
        }
    }

    /**
     * Create/Update a language translation file
     *
     * @param array $lines
     * @param string $locale
     * @param bool $reset
     * @throws \Exception
     */
    private function saveTranslations(array $lines, string $locale, bool $reset = false)
    {
        $my_lang_file = COREPATH."Language/{$locale}/app.php";
        if (!$reset && file_exists($my_lang_file)) {
            $my_language_file_content = str_replace("];", "", file_get_contents($my_lang_file));
        } else {
            $my_language_file_content = "<?php\n" .
                "/**\n" .
                " * Made automatically by NodCMS\n" .
                " * Date: ".date("Y.m.d")."\n" .
                " * Time: ".date("H:i")."\n" .
                "*/\n" .
                "return [\n";
        }
        foreach ($lines as $key=>$value) {
            $my_language_file_content .= "\t".'"' . $key . '" => "' . $value . '",' . "\n";
        }

        $my_language_file_content .= "];";

        if (!$reset && file_exists($my_lang_file)) {
            file_put_contents($my_lang_file, $my_language_file_content);
        } else {
            $myFile = fopen($my_lang_file, "w");
            if ($myFile === false) {
                throw new \Exception("Unable to open file!");
            }
            fwrite($myFile, $my_language_file_content);
            fclose($myFile);
        }
    }

    /**
     * Remove lines from a language translation files
     *
     * @param array $lines
     * @param string $fileName
     */
    private function removeLines(array $lines, string $fileName)
    {
        $my_lang_file = COREPATH."Language/{$fileName}/app.php";
        if (!file_exists($my_lang_file)) {
            return;
        }
        $my_language_file_content = str_replace("];", "", file_get_contents($my_lang_file));
        foreach ($lines as $keys) {
            $pattern = '/\t\"'.$keys.'\"[\s]?\=\>[\s]\"(.*)\"\,\n/';
            $replace = '';
            $my_language_file_content = preg_replace($pattern, $replace, $my_language_file_content);
        }
        file_put_contents($my_lang_file, $my_language_file_content);
    }
}
