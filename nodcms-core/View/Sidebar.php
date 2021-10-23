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

namespace NodCMS\Core\View;


use Config\Services;
use NodCMS\Core\Types\SidebarLink;

class Sidebar extends LinkList
{
    /**
     * @var SidebarLink[]
     */
    protected $links = [];

    /**
     * @var string
     */
    private $view_file;

    /**
     * @return SidebarLink
     */
    protected function newLink(): SidebarLink
    {
        return new SidebarLink();
    }

    /**
     * Add a link to the link list
     *
     * @param string $key
     * @param string $title
     * @param string|null $url
     * @param string|null $icon
     */
    public function addLink(string $key, string $title, string $url = null, string $icon = null)
    {
        $link = $this->newLink();
        $link->title = $title;
        $link->uri = $url;
        $link->icon = $icon;
        $this->add($key, $link);
    }

    /**
     * Add a sub link to the link list
     *
     * @param string $key
     * @param string $sub_key
     * @param string $title
     * @param string|null $url
     * @param string|null $icon
     */
    public function addSubLink(string $key, string $sub_key, string $title, string $url = null, string $icon = null)
    {
        $link = $this->newLink();
        $link->title = $title;
        $link->uri = $url;
        $link->icon = $icon;

        if(!isset($this->links[$key]))
            return;

        $this->links[$key]->subLinks[$sub_key] = $link;
    }

    /**
     * Render all links of the sidebar
     *
     * @param string $view_file
     * @param array|null $options
     * @param bool|null $saveData
     * @return string
     */
    public function render(string $view_file = "sidebar", array $options = null, bool $saveData = null): string
    {
        $this->view_file = $view_file;
        return $this->renderLinks($this->links);
    }

    /**
     * Render links of the sidebar
     *
     * @param SidebarLink[] $links
     * @return string
     */
    private function renderLinks(array $links): string
    {
        $result = "";
        foreach ($links as $key=>$item) {
            $subLink = "";
            if(!empty($item->subLinks)) {
                $subLink .= $this->renderLinks($item->subLinks);
            }
            $result .= $this->renderLink($item, $key, $subLink);
        }

        return $result;
    }

    /**
     * Render a single link of the sidebar
     *
     * @param SidebarLink $link
     * @param string $key
     * @param string $subLink
     * @return string
     */
    private function renderLink(SidebarLink $link, string $key, string $subLink): string
    {
        $this->setVar("title", $link->title);
        $this->setVar("uri", $link->uri);
        $this->setVar("icon", $link->icon);
        $this->setVar("key", $key);
        $this->setVar("subLink", $subLink);
        return parent::render($this->view_file);
    }

}