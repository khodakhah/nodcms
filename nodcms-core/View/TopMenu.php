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

class TopMenu extends LinkList
{
    /**
     * @return Link
     */
    protected function newLink(): Link
    {
        return new Link();
    }

    /**
     * Add a link to the link list
     *
     * @param string $key
     * @param string $title
     * @param string|null $url
     * @param string|null $icon
     */
    public function addLink(string $key, string $title, string $url = null)
    {
        $link = $this->newLink();
        $link->title = $title;
        if ($url !== null) {
            $link->uri = $url;
        }
        $this->add($key, $link);
    }
}
