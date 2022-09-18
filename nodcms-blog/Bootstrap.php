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
    public function backend()
    {
        define('BLOG_ADMIN_URL', base_url("admin-blog").'/');
        $unread_comments_count = Models::blogComments()->getCount(array('comment_read'=>0));
        if (Services::identity()->isAdmin(true)) {
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
