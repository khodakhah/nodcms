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

namespace NodCMS\Core\Models;

use Cassandra\Set;

/**
 * Class ModelMap
 * @package NodCMS\Core\Models
 *
 * This is a launcher class for all model files to use model functions easily inline
 */
class ModelMap
{
    /**
     * @return Email_messages_model
     */
    public static function emailMessages(): Email_messages_model
    {
        return new Email_messages_model();
    }

    /**
     * @return Groups_model
     */
    public static function groups(): Groups_model
    {
        return new Groups_model();
    }

    /**
     * @return Images_model
     */
    public static function images(): Images_model
    {
        return new Images_model();
    }

    /**
     * @return Languages_model
     */
    public static function languages(): Languages_model
    {
        return new Languages_model();
    }

    /**
     * @return Menu_model
     */
    public static function menu(): Menu_model
    {
        return new Menu_model();
    }

    /**
     * @return Packages_dashboard_model
     */
    public static function packagesDashboard(): Packages_dashboard_model
    {
        return new Packages_dashboard_model();
    }

    /**
     * @return Packages_model
     */
    public static function packages(): Packages_model
    {
        return new Packages_model();
    }

    /**
     * @return Sessions_model
     */
    public static function session(): Sessions_model
    {
        return new Sessions_model();
    }

    /**
     * @return Settings_model
     */
    public static function settings(): Settings_model
    {
        return new Settings_model();
    }

    /**
     * @return Social_links_model
     */
    public static function socialLinks(): Social_links_model
    {
        return new Social_links_model();
    }

    /**
     * @return Titles_model
     */
    public static function titles(): Titles_model
    {
        return new Titles_model();
    }

    /**
     * @return Translates_model
     */
    public static function translates(): Translates_model
    {
        return new Translates_model();
    }

    /**
     * @return Translations_model
     */
    public static function translations(): Translations_model
    {
        return new Translations_model();
    }

    /**
     * @return Upload_files_model
     */
    public static function uploadFiles(): Upload_files_model
    {
        return new Upload_files_model();
    }

    /**
     * @return Users_model
     */
    public static function users(): Users_model
    {
        return new Users_model();
    }
}