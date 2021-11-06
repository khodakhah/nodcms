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

namespace NodCMS\Blog\Config;


use NodCMS\Blog\Models\BlogCategory;
use NodCMS\Blog\Models\BlogComments;
use NodCMS\Blog\Models\BlogPost;
use NodCMS\Blog\Models\BlogPostsCategory;

class Models extends \Config\Models
{
    /**
     * @param bool $getShared
     * @return BlogPost
     */
    public static function blogPost(bool $getShared = true): BlogPost
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('blogPost');
        }

        return new BlogPost();
    }

    /**
     * @param bool $getShared
     * @return BlogCategory
     */
    public static function blogCategory(bool $getShared = true): BlogCategory
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('blogCategory');
        }

        return new BlogCategory();
    }

    /**
     * @param bool $getShared
     * @return BlogComments
     */
    public static function blogComments(bool $getShared = true): BlogComments
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('blogComments');
        }

        return new BlogComments();
    }

    /**
     * @param bool $getShared
     * @return BlogPostsCategory
     */
    public static function blogPostsCategory(bool $getShared = true): BlogPostsCategory
    {
        if ($getShared)
        {
            // Reset the class name
            static::$serviceClass = self::class;

            // Call cashed class
            return self::getSharedInstance('blogPostsCategory');
        }

        return new BlogPostsCategory();
    }
}
