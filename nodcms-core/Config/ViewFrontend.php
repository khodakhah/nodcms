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


class ViewFrontend extends View
{
    /**
     * @inheritdoc
     * @var string
     */
    public $namespacePieces = 'NodCMS\Layout';

    /**
     * @inheritdoc
     * @var string
     */
    public $frameFile = 'nodcms-frontend';
}
