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

namespace Config;

use CodeIgniter\Config\BaseService;
use NodCMS\Core\Models\EmailMessages;
use NodCMS\Core\Models\Groups;
use NodCMS\Core\Models\Images;
use NodCMS\Core\Models\Languages;
use NodCMS\Core\Models\Menu;
use NodCMS\Core\Models\PackagesDashboard;
use NodCMS\Core\Models\Packages;
use NodCMS\Core\Models\Sessions;
use NodCMS\Core\Models\Settings;
use NodCMS\Core\Models\SocialLinks;
use NodCMS\Core\Models\Titles;
use NodCMS\Core\Models\Translates;
use NodCMS\Core\Models\Translations;
use NodCMS\Core\Models\UploadFiles;
use NodCMS\Core\Models\Users;
/**
 * Class ModelMap
 * @package NodCMS\Core\Models
 *
 * This is a launcher class for all model files to use model functions easily inline
 */
class Models extends BaseService
{
    /**
     * Set local variable to separate method names with the normal services
     *
     * @var array
     */
    static protected $instances = [];

    /**
     * Set local variable to separate method names with the normal services
     *
     * @var array
     */
    static protected $mocks = [];

    /**
     * The method that called/cashed with "getSharedInstance()" will be called from this value as the class name.
     * <br>This means, on Config\Models childes classes, this variable should be reset.
     *
     * @var string
     */
    static protected $serviceClass = self::class;

    /**
     * @return EmailMessages
     */
    public static function emailMessages(bool $getShared = true): EmailMessages
    {

        if ($getShared)
        {
            return self::getSharedInstance('emailMessages');
        }
        return new EmailMessages();
    }

    /**
     * @return Groups
     */
    public static function groups(bool $getShared = true): Groups
    {

        if ($getShared)
        {
            return self::getSharedInstance('groups');
        }
        return new Groups();
    }

    /**
     * @return Images
     */
    public static function images(bool $getShared = true): Images
    {

        if ($getShared)
        {
            return self::getSharedInstance('images');
        }
        return new Images();
    }

    /**
     * @return Languages
     */
    public static function languages(bool $getShared = true): Languages
    {

        if ($getShared)
        {
            return self::getSharedInstance('languages');
        }
        return new Languages();
    }

    /**
     * @return Menu
     */
    public static function menu(bool $getShared = true): Menu
    {

        if ($getShared)
        {
            return self::getSharedInstance('menu');
        }
        return new Menu();
    }

    /**
     * @return PackagesDashboard
     */
    public static function packagesDashboard(bool $getShared = true): PackagesDashboard
    {

        if ($getShared)
        {
            return self::getSharedInstance('packagesDashboard');
        }
        return new PackagesDashboard();
    }

    /**
     * @return Packages
     */
    public static function packages(bool $getShared = true): Packages
    {

        if ($getShared)
        {
            return self::getSharedInstance('packages');
        }
        return new Packages();
    }

    /**
     * @return Sessions
     */
    public static function session(bool $getShared = true): Sessions
    {

        if ($getShared)
        {
            return self::getSharedInstance('session');
        }
        return new Sessions();
    }

    /**
     * @return Settings
     */
    public static function settings(bool $getShared = true): Settings
    {

        if ($getShared)
        {
            return self::getSharedInstance('settings');
        }
        return new Settings();
    }

    /**
     * @return SocialLinks
     */
    public static function socialLinks(bool $getShared = true): SocialLinks
    {

        if ($getShared)
        {
            return self::getSharedInstance('socialLinks');
        }
        return new SocialLinks();
    }

    /**
     * @return Titles
     */
    public static function titles(bool $getShared = true): Titles
    {

        if ($getShared)
        {
            return self::getSharedInstance('titles');
        }
        return new Titles();
    }

    /**
     * @return Translates
     */
    public static function translates(bool $getShared = true): Translates
    {

        if ($getShared)
        {
            return self::getSharedInstance('translates');
        }
        return new Translates();
    }

    /**
     * @return Translations
     */
    public static function translations(bool $getShared = true): Translations
    {

        if ($getShared)
        {
            return self::getSharedInstance('translations');
        }
        return new Translations();
    }

    /**
     * @return UploadFiles
     */
    public static function uploadFiles(bool $getShared = true): UploadFiles
    {

        if ($getShared)
        {
            return self::getSharedInstance('uploadFiles');
        }
        return new UploadFiles();
    }

    /**
     * @return Users
     */
    public static function users(bool $getShared = true): Users
    {

        if ($getShared)
        {
            return self::getSharedInstance('users');
        }
        return new Users();
    }

    /**
     * Overwrite the method of the parent class "BaseService"
     *
     * $key must be a name matching a method of this class.
     *
     * @param mixed ...$params
     *
     * @return mixed
     */
    protected static function getSharedInstance(string $key, ...$params)
    {
        $key = strtolower($key);

        // Returns mock if exists
        if (isset(static::$mocks[$key])) {
            return static::$mocks[$key];
        }

        if (! isset(static::$instances[$key])) {
            // Make sure $getShared is false
            $params[] = false;

            static::$instances[$key] = static::$serviceClass::$key(...$params);

            // Reset the class name to the default
            static::$serviceClass = self::class;
        }

        return static::$instances[$key];
    }
}
