<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    | Pagination links are rendered out using views to configure their
    | appearance. This array contains aliases and the view names to
    | use when rendering the links.
    |
    | Within each view, the Pager object will be available as $pager,
    | and the desired group as $pagerGroup;
    |
    */
    public $templates = [
        'default_full'   => '\NodCMS\Layout\Views\common\pagination\default_full',
        'default_simple' => '\NodCMS\Layout\Views\common\pagination\default_simple',
        'default_head'   => '\NodCMS\Layout\Views\common\pagination\default_head',
    ];

    /*
    |--------------------------------------------------------------------------
    | Items Per Page
    |--------------------------------------------------------------------------
    |
    | The default number of results shown in a single page.
    |
    */
    public $perPage = 20;
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
