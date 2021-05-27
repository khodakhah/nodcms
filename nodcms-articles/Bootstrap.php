<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
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
        define('ARTICLES_ADMIN_URL',base_url('admin-articles').'/');
        if(Services::identity()->isAdmin(true)) {
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
        foreach($data_list as $item) {
            $link = new MenuLink();
            $link->title = _l("Article", $this).": {$item['name']}";
            $link->uri = ARTICLES_ADMIN_URL."article/{$item['article_uri']}";
            $list[] = $link;
        }

        return $list;
    }
}