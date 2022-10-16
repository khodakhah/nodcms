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

namespace NodCMS\Portfolio;

use Config\Services;
use NodCMS\Portfolio\Controllers\Portfolio;

class Bootstrap extends \NodCMS\Core\Modules\Bootstrap
{
    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return "Portfolio";
    }

    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return "This module is a way to create and manage your portfolios on your website.";
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
        return Portfolio::home();
    }

    /**
     * @throws \Exception
     */
    public function backend()
    {
        define('PORTFOLIO_ADMIN_URL', base_url("admin-portfolio").'/');
        if (Services::identity()->isAdmin(true)) {
            Services::sidebar()->addLink(
                'portfolio_posts',
                _l("Portfolio", $this),
                PORTFOLIO_ADMIN_URL.'posts',
                'fas fa-camera-retro'
            );
        }
    }
}
