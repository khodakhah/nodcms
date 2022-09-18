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

namespace NodCMS\Articles;

use Config\Services;
use NodCMS\Articles\Config\Models;
use NodCMS\Articles\Controllers\ArticlesAdmin;
use NodCMS\Core\Types\MenuLink;

class Bootstrap extends \NodCMS\Core\Modules\Bootstrap
{
    /**
     * @inheritdoc
     * @return string
     */
    public function title(): string
    {
        return "Articles";
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function description(): string
    {
        return "This module will allow you to create and manage your articles with NodCMS.";
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function hasDashboard(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function getDashboard(): string
    {
        return ArticlesAdmin::dashboard();
    }

    /**
     * @inheritDoc
     */
    public function hasMemberDashboard(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function hasHomePreview(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function backend()
    {
        define('ARTICLES_ADMIN_URL', base_url('admin-articles').'/');
        if (Services::identity()->isAdmin(true)) {
            Services::sidebar()->addLink(
                "article_list",
                _l("Articles", $this),
                ARTICLES_ADMIN_URL."article",
                "far fa-file-alt"
            );
        }
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function menuList(): array
    {
        $list = [];
        $data_list = Models::articles()->getAll();
        foreach ($data_list as $item) {
            $link = new MenuLink();
            $link->title = _l("Article", $this).": {$item['name']}";
            $link->uri = ARTICLES_ADMIN_URL."article/{$item['article_uri']}";
            $list[] = $link;
        }

        return $list;
    }
}
