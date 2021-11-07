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

namespace NodCMS\Core\Modules;

use NodCMS\Core\Types\MenuLink;

abstract class Bootstrap implements I_Bootstrap
{
    /**
     * @var array
     */
    private $_data;

    /**
     * Set empty admin dashboard content as default
     *
     * @return string
     */
    public function getDashboard(): string
    {
        return "";
    }

    /**
     * Set empty member dashboard content as default
     *
     * @return string
     */
    public function getMemberDashboard(): string
    {
        return "";
    }

    /**
     * Set empty home preview content as default
     *
     * @return string
     */
    public function getHomePreview(): string
    {
        return "";
    }

    /**
     * @return MenuLink[]
     */
    public function menuList(): array
    {
        // Default is empty
        return [];
    }

    public function frontend()
    {
        // Do notting by default
    }

    public function backend()
    {
        // Do notting by default
    }

    public function membership()
    {
        // Do notting by default
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function getData(): array
    {
        return $this->_data;
    }

    /**
     * @inheritDoc
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->_data = $data;
    }
}
