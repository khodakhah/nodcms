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
