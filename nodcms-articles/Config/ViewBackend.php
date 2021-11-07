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

namespace NodCMS\Articles\Config;


class ViewBackend extends \Config\ViewBackend
{
    /**
     * @inheritdoc
     * @var string
     */
    public $namespacePieces = 'NodCMS\Articles/admin';
}
