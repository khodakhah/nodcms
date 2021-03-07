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

namespace NodCMS\Core\Modules;


use NodCMS\Core\Types\Link;

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
     * True means there this module has a dashboard
     *
     * @return bool
     */
    public function hasDashboard(): bool;

    /**
     * Addon admin sidebar
     *
     * @return Link[]
     */
    public function adminSidebar(): array;

    /**
     * Addon frontend top menu
     *
     * @return Link[]
     */
    public function topMenu(): array;

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
}