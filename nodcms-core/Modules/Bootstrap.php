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