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
 *  @since      Version 3.1.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Modules;


use NodCMS\Core\Types\Link;
use NodCMS\Core\Types\MenuLink;

interface I_Bootstrap
{
    /**
     * Returns Module title
     *
     * @return string
     */
    public function title(): string;

    /**
     * Returns Module description
     *
     * @return string
     */
    public function description(): string;

    /**
     * True means, this module has an admin dashboard
     *
     * @return bool
     */
    public function hasDashboard(): bool;

    /**
     * Return the admin dashboard content
     *
     * @return string
     */
    public function getDashboard(): string;

    /**
     * True means, this module has a member dashboard
     *
     * @return bool
     */
    public function hasMemberDashboard(): bool;

    /**
     * Return the member dashboard content
     *
     * @return string
     */
    public function getMemberDashboard(): string;

    /**
     * True means, this module has something to display in home
     *
     * @return bool
     */
    public function hasHomePreview(): bool;

    /**
     * Return the home preview content
     *
     * @return string
     */
    public function getHomePreview(): string;

    /**
     * A link list that will be able to add as header and footer menu
     *
     * @return MenuLink[]
     */
    public function menuList(): array;

    /**
     * General hooks on all frontend controllers
     *
     * @return void
     */
    public function frontend();

    /**
     * General hooks on all backend controllers
     *
     * @return void
     */
    public function backend();

    /**
     * General hooks on all membership controllers
     *
     * @return void
     */
    public function membership();

    /**
     * Returns database result of current module
     *
     * @return array
     */
    public function getData(): array;

    /**
     * Set database result of current module
     *
     * @param array $data
     */
    public function setData(array $data): void;
}