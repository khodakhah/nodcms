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

namespace NodCMS\Gallery;

use Config\Services;
use NodCMS\Core\Types\MenuLink;

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
     * @inheritdoc
     * @throws \Exception
     */
    function backend()
    {
        define('GALLERY_ADMIN_URL',base_url('admin-gallery').'/');
        if(Services::identity()->isAdmin(true)){
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