<?php
/*
 *  This file is part of nodcms.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

$rules = [
  '@PSR12' => true,
];

$finder = PhpCsFixer\Finder::create()
  ->in(__DIR__)
  ->exclude(['vendor', 'writable', 'Views'])
;

$config = new PhpCsFixer\Config();
return $config
  ->setRules($rules)
  ->setFinder($finder)
  ->setUsingCache(false)
;
