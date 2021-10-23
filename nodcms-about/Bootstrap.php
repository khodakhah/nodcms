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
 *  @since      Version 3.2.0
 *  @filesource
 *
 */
namespace NodCMS\About;

use Config\Services;
use NodCMS\About\Config\Models;
use NodCMS\About\Controllers\About;
use NodCMS\Core\Types\MenuLink;

class Bootstrap extends \NodCMS\Core\Modules\Bootstrap
{
    /**
     * Bootstrap constructor.
     */
    public function __construct()
    {
        if(!defined('ABOUT_ADMIN_URL'))
            define('ABOUT_ADMIN_URL',base_url('admin-about').'/');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "About";
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return "A module to describe your business, company, or your crew.";
    }

    /**
     * @inheritDoc
     *
     * @return bool
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
     *
     * @return bool
     */
    public function hasHomePreview(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function getHomePreview(): string
    {
        return About::home();
    }

    /**
     * Backend setups
     */
    function backend()
    {
        parent::backend();

        if(Services::identity()->isAdmin(true)){
            Services::sidebar()->addLink("about_profiles",
                _l("About profiles", $this),
                ABOUT_ADMIN_URL."profiles",
                "far fa-id-card");
        }
    }

    /**
     * @return MenuLink[]
     */
    public function menuList(): array
    {
        $list = [];
        $data_list = Models::about()->getAll();
        foreach($data_list as $item) {
            $link = new MenuLink();
            $link->title = str_replace("{data}", $item['profile_name'], _l("Profile {data}", $this));
            $link->uri = ABOUT_ADMIN_URL."about-".$item['profile_uri'];
            $list[] = $link;
        }

        return $list;
    }
}