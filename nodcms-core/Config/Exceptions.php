<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Setup how the exception handler works.
 *
 * @package Config
 */
class Exceptions extends BaseConfig
{
    /*
     |--------------------------------------------------------------------------
     | LOG EXCEPTIONS?
     |--------------------------------------------------------------------------
     | If true, then exceptions will be logged
     | through Services::Log.
     |
     | Default: true
     */
    public $log = true;

    /*
     |--------------------------------------------------------------------------
     | DO NOT LOG STATUS CODES
     |--------------------------------------------------------------------------
     | Any status codes here will NOT be logged if logging is turned on.
     | By default, only 404 (Page Not Found) exceptions are ignored.
     */
    public $ignoreCodes = [ 404 ];

    /*
    |--------------------------------------------------------------------------
    | Error Views Path
    |--------------------------------------------------------------------------
    | This is the path to the directory that contains the 'cli' and 'html'
    | directories that hold the views used to generate errors.
    |
    | Default: APPPATH.'Views/errors'
    */
    public $errorViewPath = ROOTPATH . 'nodcms-layout/Views/errors';
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
