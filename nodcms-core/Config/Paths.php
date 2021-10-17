<?php
/**
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
 *  @since      Version 3.1.0
 *  @filesource
 *
 */

namespace Config;

class Paths
{
	/*
	 *---------------------------------------------------------------
	 * SYSTEM FOLDER NAME
	 *---------------------------------------------------------------
	 *
	 * This variable must contain the name of your "system" folder.
	 * Include the path if the folder is not in the same directory
	 * as this file.
	 */
	public $systemDirectory = ROOTPATH . 'system';

	/*
	 *---------------------------------------------------------------
	 * APPLICATION FOLDER NAME
	 *---------------------------------------------------------------
	 *
	 * If you want this front controller to use a different "app"
	 * folder than the default one you can set its name here. The folder
	 * can also be renamed or relocated anywhere on your getServer. If
	 * you do, use a full getServer path. For more info please see the user guide:
	 * http://codeigniter.com/user_guide/general/managing_apps.html
	 *
	 * NO TRAILING SLASH!
	 */
	public $appDirectory = ROOTPATH . 'nodcms-core';

	/*
	 * ---------------------------------------------------------------
	 * WRITABLE DIRECTORY NAME
	 * ---------------------------------------------------------------
	 *
	 * This variable must contain the name of your "writable" directory.
	 * The writable directory allows you to group all directories that
	 * need write permission to a single place that can be tucked away
	 * for maximum security, keeping it out of the app and/or
	 * system directories.
	 */
	public $writableDirectory = ROOTPATH . 'writable';

	/*
	 * ---------------------------------------------------------------
	 * TESTS DIRECTORY NAME
	 * ---------------------------------------------------------------
	 *
	 * This variable must contain the name of your "tests" directory.
	 * The writable directory allows you to group all directories that
	 * need write permission to a single place that can be tucked away
	 * for maximum security, keeping it out of the app and/or
	 * system directories.
	 */
	public $testsDirectory = ROOTPATH . 'tests';

	/*
	 * ---------------------------------------------------------------
	 * VIEW DIRECTORY NAME
	 * ---------------------------------------------------------------
	 *
	 * This variable must contain the name of the directory that
	 * contains the view files used by your application. By
	 * default this is in `app/Views`. This value
	 * is used when no value is provided to `Services::renderer()`.
	 */
	public $viewDirectory = ROOTPATH . 'Views';
}
