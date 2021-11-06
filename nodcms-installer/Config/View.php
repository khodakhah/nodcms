<?php namespace NodCMS\Installer\Config;

class View extends \Config\View
{
	/**
         * NodCMS variable!
         * Path of view file. This path will attached before view files with
         *
         * @var string
     */
    public $namespacePieces = 'NodCMS\Installer';

    /**
     * NodCMS variable!
     * Path of view namespace for layout view file.
     *
     * @var string
     */
    public $namespaceLayout = 'NodCMS\Installer';

    /**
         * NodCMS variable!
         * The frame view file name. This is because of different frames such
         * as: backend, frontend, membership, etc.
         *
         * @var string
     */
    public $frameFile = 'layout';
}
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


