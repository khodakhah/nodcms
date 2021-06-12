<?php
/*
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
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use NodCMS\Core\Models\Languages;

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
        if(empty($localePrefix)) {
            $language = Models::languages()->getDefaultRecord();
        }
        // Set system language from URL language code (Language prefix)
        else{
            $language = Models::languages()->getByCode($localePrefix);
        }

        if(empty($language)){
            $language = Models::languages()->getOne(null);
        }

        if(empty($language)){
            throw new \Exception("Language \"{$localePrefix}\" not found in database.");
        }

        if($language['code'] != $localePrefix) {
            $request->uri->setSegment(1, $language['code']);
            return redirect()->to($request->uri->getPath());
        }

        // Set language from database
        Services::language()->set($language);

        // Load settings from database
        Services::settings()->load($language['language_id']);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}