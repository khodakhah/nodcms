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

class View extends \CodeIgniter\Config\View
{
	/**
	 * When false, the view method will clear the data between each
	 * call. This keeps your data safe and ensures there is no accidental
	 * leaking between calls, so you would need to explicitly pass the data
	 * to each view. You might prefer to have the data stick around between
	 * calls so that it is available to all views. If that is the case,
	 * set $saveData to true.
	 */
	public $saveData = true;

	/**
	 * Parser Filters map a filter name with any PHP callable. When the
	 * Parser prepares a variable for display, it will chain it
	 * through the filters in the order defined, inserting any parameters.
	 * To prevent potential abuse, all filters MUST be defined here
	 * in order for them to be available for use within the Parser.
	 *
	 * Examples:
	 *  { title|esc(js) }
	 *  { created_on|date(Y-m-d)|esc(attr) }
	 */
	public $filters = [];

	/**
	 * Parser Plugins provide a way to extend the functionality provided
	 * by the core Parser by creating aliases that will be replaced with
	 * any callable. Can be single or tag pair.
	 */
	public $plugins = [];

    /**
     * NodCMS variable!
     * Path of view namespace for view files.
     * using Base::viewRender()
     *
     * @var string
     */
    public $namespacePieces = 'NodCMS\Layout';

    /**
     * NodCMS variable!
     * Path of view namespace for layout view file.
     *
     * @var string
     */
    public $namespaceLayout = 'NodCMS\Layout';

    /**
     * NodCMS variable!
     * The frame view file name. This is because of different frames such
     * as: backend, frontend, membership, etc.
     *
     * @var string
     */
    public $frameFile = 'nodcms-clean';
}
