<?php
/**
 * NodCMS
 *
 *  Copyright (c) 2015-2021.
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
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
            return self::getSharedInstance('blogPostsCategory');
        }

        return new BlogPostsCategory();
    }
}