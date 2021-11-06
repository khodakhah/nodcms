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

namespace NodCMS\About\Config;

use NodCMS\About\Models\About;

class Models extends \Config\Models
{
    /**
     * @param bool $getShared
     * @return About
     */
    public static function about(bool $getShared = true): About
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return static::getSharedInstance('about');
        }
        return new About();
    }
}
