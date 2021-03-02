<?php namespace Config;

use CodeIgniter\Config\Services as CoreServices;
use NodCMS\Core\Libraries\Identity;
use NodCMS\Core\Libraries\Language;
use NodCMS\Core\Models\ModelMap;
use NodCMS\Core\Notification\EmailNotification;
use NodCMS\Core\Response\QuickResponse;
use NodCMS\Core\View\LinkList;
use NodCMS\Core\Modules\Modules;
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
     * ModelMap is a luncher class to
     *
     * @param bool $getShared
     * @return ModelMap
     */
    public static function model(bool $getShared = true): ModelMap
    {
        if ($getShared)
        {
            return static::getSharedInstance('model');
        }

        return new ModelMap();
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
}
