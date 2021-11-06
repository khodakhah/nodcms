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

namespace Config;


class ViewBackend extends View
{
    /**
     * @inheritdoc
     * @var string
     */
    public $namespacePieces = 'NodCMS\Layout/admin';

    /**
     * @inheritdoc
     * @var string
     */
    public $namespaceLayout = 'NodCMS\Layout/admin';

    /**
     * @inheritdoc
     * @var string
     */
    public $frameFile = 'layout';
}
