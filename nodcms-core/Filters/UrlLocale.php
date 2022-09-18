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

namespace NodCMS\Core\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Models;
use Config\Services;

/**
 * Class UrlLocale
 * Check the language prefix, and handle redirect if there is no language prefix has been set.
 *
 * @package NodCMS\Core\Filters
 */
class UrlLocale implements \CodeIgniter\Filters\FilterInterface
{
    /**
     * @inheritDoc
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $localePrefix = $request->uri->getSegment(1);

        // No prefix has been set
        if (empty($localePrefix)) {
            $language = Models::languages()->getDefaultRecord();
        }
        // Set system language from URL language code (Language prefix)
        else {
            $language = Models::languages()->getByCode($localePrefix);
        }

        if (empty($language)) {
            $language = Models::languages()->getOne(null);
        }

        if (empty($language)) {
            throw new \Exception("Language \"{$localePrefix}\" not found in database.");
        }

        if ($language['code'] != $localePrefix) {
            $request->uri->setSegment(1, $language['code']);
            return redirect()->to($request->uri->getPath());
        }

        // Set language from database
        Services::language()->set($language);

        // Load settings from database
        Services::settings()->load($language['language_id']);

        return false;
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}
