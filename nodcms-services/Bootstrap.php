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

namespace NodCMS\Services;

use Config\Services;
use NodCMS\Services\Controllers\ServicesFrontend;

class Bootstrap extends \NodCMS\Core\Modules\Bootstrap
{
    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return "Services";
    }

    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return "This is module to create and mange you services on your website.";
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
        return ServicesFrontend::home();
    }

    /**
     * @throws \Exception
     */
    public function backend()
    {
        define('SERVICES_ADMIN_URL', base_url("admin-services").'/');
        if (Services::identity()->isAdmin(true)) {
            Services::sidebar()->addLink(
                "services",
                _l("Services", $this),
                null,
                "fas fa-concierge-bell"
            );
            Services::sidebar()->addSubLink(
                'services',
                'services_list',
                _l("Services' list", $this),
                SERVICES_ADMIN_URL."services"
            );
            Services::sidebar()->addSubLink(
                'services',
                'services_settings',
                _l("Display settings", $this),
                SERVICES_ADMIN_URL."settings"
            );
        }
    }
}
