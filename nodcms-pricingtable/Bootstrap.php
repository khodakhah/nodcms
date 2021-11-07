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

namespace NodCMS\Pricingtable;

use Config\Services;
use NodCMS\Pricingtable\Controllers\PricingTable;

class Bootstrap extends \NodCMS\Core\Modules\Bootstrap
{
    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return "Pricing Table";
    }

    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return "This is module to create and manage a pricing table on your website.";
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
        return PricingTable::home();
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    function backend()
    {
        define('PRICING_TABLE_ADMIN_URL',base_url('admin-pricing-table').'/');

        if(Services::identity()->isAdmin(true)) {
            Services::sidebar()->addLink(
                "pricing_tables",
                _l("Pricing Tables", $this),
                PRICING_TABLE_ADMIN_URL. "tables",
                "fas fa-table"
            );
        }
    }
}
