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

namespace NodCMS\Gallery\Config;


use NodCMS\Gallery\Models\Gallery;
use NodCMS\Gallery\Models\GalleryImages;

class Models extends \Config\Models
{
    /**
     * @param bool $getShared
     * @return Gallery
     */
    public static function gallery(bool $getShared = true): Gallery
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('gallery');
        }

        return new Gallery();
    }
    /**
     * @param bool $getShared
     * @return GalleryImages
     */
    public static function galleryImages(bool $getShared = true): GalleryImages
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('galleryImages');
        }

        return new GalleryImages();
    }
}
