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

namespace NodCMS\Services\Config;


use NodCMS\Services\Models\Services;

class Models extends \Config\Models
{
    /**
     * @param bool $getShared
     * @return Services
     */
    public static function services(bool $getShared = true): Services
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('services');
        }
        return new Services();
    }
}
