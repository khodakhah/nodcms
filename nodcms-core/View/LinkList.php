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


use NodCMS\Core\Types\Link;

abstract class LinkList extends View
{
    protected $links = [];

    abstract protected function newLink();

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
     * Return link list as array
     *
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @return bool
     */
    public function hasLinks(): bool
    {
        return !empty($this->links);
    }
}
