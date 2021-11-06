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
use Config\Services;
use function PHPUnit\Framework\throwException;

class IdentityVerification implements \CodeIgniter\Filters\FilterInterface
{

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $response = Services::quickResponse();

        $lang = Services::language()->getLocale();

        if(!Services::identity()->isValid()){
            return $response->getError(lang("Please login to access this page."), "/{$lang}/login");
        }

        if(!Services::identity()->isActive()) {
            if($request->uri !== "account-locked") {
                return Services::identity()->getResponse();
            }
        }

        if(!Services::identity()->isAdmin(true)) {
            return Services::identity()->getResponse();
        }

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
