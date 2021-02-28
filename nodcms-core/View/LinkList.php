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

namespace NodCMS\Core\View;


use NodCMS\Core\Types\Link;

class LinkList extends View
{
    /**
     * @var Link[]
     */
    private $links = [];
    private $subLinks = [];

    /**
     * Add a Link to the link list
     *
     * @param string $key
     * @param Link $item
     */
    public function add(string $key, Link $item)
    {
        $this->links[$key] = $item;
    }

    public function append(self $list)
    {
        $this->links = array_merge($this->links, $list->getLinks());
    }

    public function prepend(self $list)
    {
        $this->links = array_merge($list->getLinks(), $this->links);
    }

    public function putIn(self $list, int $index)
    {
        $the_count = count($this->links);
        $this->links = array_merge(array_slice($this->links, 0, $index), $list->getLinks(), array_slice($this->links, $index, $the_count-$index));
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
        $link = new Link();
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
        $link = new Link();
        $link->title = $title;
        $link->uri = $url;
        $link->icon = $icon;

        if(!isset($this->links[$key]))
            return;

        if(!isset($this->subLinks[$key][$sub_key]))
            $this->subLinks[$key]['sub_menu'] = array();

        $this->subLinks[$key][$sub_key] = $link;
    }

    /**
     * Return link list as array
     *
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }
}