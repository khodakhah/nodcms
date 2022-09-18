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

class InstalledVerification implements \CodeIgniter\Filters\FilterInterface
{
    /**
     * @inheritDoc
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $inInstallerPage = false;
        if (Services::uri()->getSegment(1) == "installer") {
            $inInstallerPage = true;
        }

        // Database config file exists
        if (file_exists(DB_CONFIG_PATH)) {
            $db = \Config\Database::connect();
            // Database connection works fine
            if (!empty($db->getDatabase())) {
                // If database exists and connection works fine, user shouldn't be in installer page.
                if ($inInstallerPage) {
                    return redirect()->to('/xx');
                }

                // If requested URL point to root.
                if (empty(Services::uri()->getSegment(1))) {
                    return redirect()->to('/xx');
                }

                // Database is fine and user is not in installer page
                return false;
            }
        }

        if ($inInstallerPage) {
            // Database file or connection is NOT OK and user is on installer wizard.
            return false;
        }

        // Redirect to the install wizard page.
        return redirect()->to('/installer');
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}
