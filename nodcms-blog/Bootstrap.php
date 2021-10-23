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
 *  @since      Version 3.2.0
 *  @filesource
 *
 */

namespace NodCMS\Blog;

use Config\Services;
use NodCMS\Blog\Config\Models;
use NodCMS\Blog\Controllers\Blog;
use NodCMS\Core\Types\MenuLink;

class Bootstrap extends \NodCMS\Core\Modules\Bootstrap
{
    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return "Blog";
    }

    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return "This module is a way to create and manage your blog.";
    }

    /**
     * @inheritDoc
     */
    public function hasDashboard(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function hasMemberDashboard(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function hasHomePreview(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function getHomePreview(): string
    {
        return Blog::home();
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    function backend()
    {
        define('BLOG_ADMIN_URL', base_url("admin-blog").'/');
        $unread_comments_count = Models::blogComments()->getCount(array('comment_read'=>0));
        if(Services::identity()->isAdmin(true)){
            Services::sidebar()->addLink(
                "blog",
                _l("Blog", $this),
                null,
                "far fa-window-maximize"
            );
            Services::sidebar()->addSubLink(
                "blog",
                "blog_comments",
                _l("Client's Comments", $this) .
                ($unread_comments_count>0 ? " <span class='badge badge-danger'>$unread_comments_count</span>" : ""),
                BLOG_ADMIN_URL.'comments'
            );
            Services::sidebar()->addSubLink(
                "blog",
                "blog_posts",
                _l("Posts", $this),
                BLOG_ADMIN_URL.'posts'
            );
            Services::sidebar()->addSubLink(
                "blog",
                "blog_categories",
                _l("Categories", $this),
                BLOG_ADMIN_URL.'categories'
            );
        }
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function menuList(): array
    {
        $list = [];
        $link = new MenuLink();
        $link->title = _l("Blog", $this);
        $link->uri = "blog";
        $list[] = $link;

        return $list;
    }
}