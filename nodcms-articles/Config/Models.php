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

namespace NodCMS\Articles\Config;

use NodCMS\Articles\Models\Articles;

class Models extends \Config\Models
{
    /**
     * @param bool $getShared
     * @return Articles
     */
    public static function articles(bool $getShared = true): Articles
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('articles');
        }
        return new Articles();
    }
}
