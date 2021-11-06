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

namespace NodCMS\Core\Types;


class MenuLink extends Link
{
    /**
     * @var int
     */
    public $order = 0;

    /**
     * @var MenuLink[]
     */
    public $subLinks = [];
}
