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

namespace NodCMS\Portfolio\Config;


use NodCMS\Portfolio\Models\Portfolio;

class Models extends \Config\Models
{
    /**
     * @param bool $getShared
     * @return Portfolio
     */
    public static function portfolio(bool $getShared = true): Portfolio
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('portfolio');
        }

        return new Portfolio();
    }
}
