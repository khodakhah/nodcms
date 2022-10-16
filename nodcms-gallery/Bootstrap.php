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

namespace NodCMS\Gallery;

use Config\Services;
use NodCMS\Core\Types\MenuLink;
use NodCMS\Gallery\Controllers\Gallery;

class Bootstrap extends \NodCMS\Core\Modules\Bootstrap
{
    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return "Gallery";
    }

    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return "This is a module to create, manage, and display a gallery on your website.";
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
        return Gallery::home();
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function backend()
    {
        define('GALLERY_ADMIN_URL', base_url('admin-gallery').'/');
        if (Services::identity()->isAdmin(true)) {
            Services::sidebar()->addLink(
                'galleries',
                _l("Galleries", $this),
                GALLERY_ADMIN_URL.'galleries',
                'far fa-images'
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
        $link->title = _l("Gallery", $this);
        $link->uri = "gallery";
        $list[] = $link;

        return $list;
    }
}
