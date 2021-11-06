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
