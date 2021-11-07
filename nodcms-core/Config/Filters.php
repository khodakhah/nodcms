<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'csrf'     => \CodeIgniter\Filters\CSRF::class,
		'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
		'honeypot' => \CodeIgniter\Filters\Honeypot::class,
        'installedVerification' => \NodCMS\Core\Filters\InstalledVerification::class,
        'urlLocale' => \NodCMS\Core\Filters\UrlLocale::class,
		'identityVerification' => \NodCMS\Core\Filters\IdentityVerification::class,
	];

	// Always applied before every request
	public $globals = [
		'before' => [
            'installedVerification',
			//'honeypot'
			 'csrf',
		],
		'after'  => [
			'toolbar',
			//'honeypot'
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [
        'urlLocale' => ['before' => ['[a-z]{2}', '[a-z]{2}/*'], 'after' => []],
        'identityVerification' => ['before' => ['admin', 'admin/*', 'admin-*', 'user/*'], 'after' => []],
    ];
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


