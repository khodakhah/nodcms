<?php namespace Config;

use CodeIgniter\Config\Services as CoreServices;
use NodCMS\Core\Libraries\Currency;
use NodCMS\Core\Libraries\Identity;
use NodCMS\Core\Libraries\Language;
use NodCMS\Core\Libraries\Settings as SettingsLibrary;
use NodCMS\Core\Libraries\Upload;
use NodCMS\Core\Notification\EmailNotification;
use NodCMS\Core\Notification\Notification;
use NodCMS\Core\Response\QuickResponse;
use NodCMS\Core\View\Form;
use NodCMS\Core\View\Layout;
use NodCMS\Core\View\LinkList;
use NodCMS\Core\Modules\Modules;
use NodCMS\Core\View\View;
use NodCMS\Core\View\Sidebar;
use NodCMS\Core\View\TopMenu;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends CoreServices
{

	//    public static function example($getShared = true)
	//    {
	//        if ($getShared)
	//        {
	//            return static::getSharedInstance('example');
	//        }
	//
	//        return new \CodeIgniter\Example();
	//    }

    /**
     * Load the CI customized Validation class from NodCMS core
     *
     * @param Validation|null $config
     * @param bool $getShared
     * @return \CodeIgniter\Validation\Validation|mixed|\NodCMS\Core\Validation\Validation
     */
    public static function validation(Validation $config = null, bool $getShared = true)
    {
        if ($getShared)
        {
            return static::getSharedInstance('validation', $config);
        }

        if (is_null($config))
        {
            $config = config('Validation');
        }

        return new \NodCMS\Core\Validation\Validation($config, static::renderer());
    }


    /**
     * Returns the NodCMS View class
     *
     * @param null $config
     * @param false $getShared
     * @return View
     */
    public static function formLayout($config = null, $getShared = true) : Form
    {
        if ($getShared)
        {
            return static::getSharedInstance('formLayout', $config);
        }

        return new Form($config);
    }

    /**
     * Returns the layout
     *
     * @param null $config
     * @param bool $getShared
     * @return Layout
     */
    public static function layout($config = null, bool $getShared = true): Layout
    {
        if ($getShared)
        {
            return static::getSharedInstance('layout', $config);
        }

        return new Layout($config);
    }

    /**
     * Returns the layout
     *
     * @param null $config
     * @param bool $getShared
     * @return View
     */
    public static function view($config = null, bool $getShared = true): View
    {
        if ($getShared)
        {
            return static::getSharedInstance('view', $config);
        }

        return new View($config);
    }

    /**
     * @param string $locale
     * @param bool $getShared
     * @return Language
     */
    public static function language(string $locale = null, bool $getShared = true): Language
    {
        if ($getShared)
        {
            return static::getSharedInstance('language', $locale)
                ->setLocale($locale);
        }

        $locale = ! empty($locale) ? $locale : static::request()
            ->getLocale();

        return new Language($locale);
    }

    /**
     * @param bool $getShared
     * @return SettingsLibrary
     */
    public static function settings(bool $getShared = true): SettingsLibrary
    {
        if ($getShared)
        {
            return static::getSharedInstance('settings');
        }

        return new SettingsLibrary();
    }

    /**
     * @param bool $getShared
     * @return Currency
     */
    public static function currency(bool $getShared = true): Currency
    {
        if ($getShared)
        {
            return static::getSharedInstance('currency');
        }

        return new Currency();
    }

    /**
     * Signed user identity
     *
     * @param $controller
     * @param bool $getShared
     * @return Identity
     */
    public static function identity(bool $getShared = true): Identity
    {
        if ($getShared)
        {
            return static::getSharedInstance('identity');
        }

        return new Identity();
    }

    /**
     * Email notification handle
     *
     * @param bool $getShared
     * @return EmailNotification
     */
    public static function emailNotification(bool $getShared = true): EmailNotification
    {
        if ($getShared)
        {
            return static::getSharedInstance('emailNotification');
        }

        return new EmailNotification();
    }

    /**
     * Notification handle
     *
     * @param string $key
     * @param int|null $languageId
     * @param bool $getShared
     * @return Notification
     */
    public static function notification(string $key, int $languageId = null, bool $getShared = true): Notification
    {
        if ($getShared)
        {
            return static::getSharedInstance('notification', $key, $languageId);
        }

        return new Notification($key, $languageId);
    }

    /**
     * ModelMap is a luncher class to
     *
     * @param bool $getShared
     * @return Models
     */
    public static function model(bool $getShared = true)
    {
        if ($getShared)
        {
            return static::getSharedInstance('model');
        }

        return new Models();
    }

    /**
     * @param $controller
     * @param bool $getShared
     * @return Modules
     */
    public static function modules(bool $getShared = true): Modules
    {
        if ($getShared)
        {
            return static::getSharedInstance('modules');
        }

        return new Modules();
    }

    /**
     * NodCMS responses
     *
     * @return QuickResponse
     */
    public static function quickResponse(): QuickResponse
    {
        return new QuickResponse();
    }

    /**
     * A link list for sidebar
     *
     * @param bool $getShared
     * @return Sidebar
     */
    public static function sidebar(bool $getShared = true): Sidebar
    {
        if ($getShared)
        {
            return static::getSharedInstance("sidebar");
        }

        return new Sidebar();
    }

    /**
     * A link list for top menu
     *
     * @param bool $getShared
     * @return LinkList
     */
    public static function topMenu(bool $getShared = true): LinkList
    {
        if ($getShared)
        {
            return static::getSharedInstance("topMenu");
        }

        return new TopMenu();
    }

    /**
     * NodCMS upload file library
     *
     * @return Upload
     */
    public static function upload(): Upload
    {
        return new Upload();
    }
}
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


