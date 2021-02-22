<?php namespace Config;

use CodeIgniter\Config\Services as CoreServices;
use NodCMS\Core\Hooks\Hooks;
use NodCMS\Core\Libraries\Language;
use NodCMS\Core\Notification\EmailNotification;

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
     * @param bool $getShared
     * @return \NodCMS\Core\Libraries\Language
     */
    public static function language(string $locale = null, bool $getShared = true)
    {

        if ($getShared)
        {
            return static::getSharedInstance('language', $locale)
                ->setLocale($locale);
        }

        $locale = ! empty($locale) ? $locale : static::request()
            ->getLocale();

        return new \NodCMS\Core\Libraries\Language($locale);
    }

    /**
     * ModelMap is a luncher class to
     *
     * @param bool $getShared
     * @return \NodCMS\Core\Models\ModelMap
     */
    public static function model(bool $getShared = true): \NodCMS\Core\Models\ModelMap
    {
        if ($getShared)
        {
            return static::getSharedInstance('model');
        }

        return new \NodCMS\Core\Models\ModelMap();
    }

    /**
     * @param $controller
     * @param bool $getShared
     * @return \NodCMS\Core\Core\Modules
     */
    public static function modules(bool $getShared = true): \NodCMS\Core\Core\Modules
    {
        if ($getShared)
        {
            return static::getSharedInstance('modules');
        }

        return new \NodCMS\Core\Core\Modules();
    }
}
