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

namespace NodCMS\Pricingtable\Config;


use NodCMS\Pricingtable\Models\PricingTable;
use NodCMS\Pricingtable\Models\PricingTableRecord;

class Models extends \Config\Models
{
    /**
     * @param bool $getShared
     * @return PricingTable
     */
    public static function pricingTable(bool $getShared = true): PricingTable
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('pricingTable');
        }

        return new PricingTable();
    }

    /**
     * @param bool $getShared
     * @return PricingTableRecord
     */
    public static function pricingTableRecord(bool $getShared = true): PricingTableRecord
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('pricingTableRecord');
        }

        return new PricingTableRecord();
    }
}
