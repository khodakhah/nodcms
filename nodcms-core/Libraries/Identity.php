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

namespace NodCMS\Core\Libraries;


use Config\Services;
use NodCMS\Core\Response\QuickResponse;

class Identity
{

    /**
     * @var \CodeIgniter\HTTP\RedirectResponse|string
     */
    private $response;

    public function getUserData()
    {
        if(Services::session()->has("user_id")) {
            $userModel = new \NodCMS\Core\Models\Users();
            $user = $userModel->getOne(Services::session()->get("user_id"));
//            if($user["active"]!=1 && $this->router->methodName() != "logout"){
//                $this->accountLock();
//                return;
//            }
            if(!empty($user)) {
                return $user;
            }
        }

        return null;
    }

    public function hasSession(): bool
    {
        return Services::session()->has("user_id");
    }

    public function isValid(): bool
    {
        if(Services::session()->has("user_id")) {
            $userModel = new \NodCMS\Core\Models\Users();
            $user = $userModel->getOne(Services::session()->get("user_id"));
            return !empty($user);
        }

        return false;
    }

    /**
     * Check the logged user is an admin
     *
     * @param bool $allowDemo
     * @return bool
     * @throws \Exception
     */
    public function isAdmin(bool $allowDemo = false): bool
    {
        // Demo user welcomes
        if($allowDemo && (int) Services::session()->get('group') === 100) {
            return true;
        }

        // Not an admin user not welcomes
        if((int) Services::session()->get('group') !== 1) {
            helper("core_helper");
            $reponse = new QuickResponse();
            if((int) Services::session()->get('group') === 100) {
                $reponse->setMessage(_l("DEMO account does not have access to this action.", $this));
                $reponse->setUrl( "/admin");
            }
            else {
                $reponse->setMessage(_l("Unfortunately you do not have permission to this part of system.", $this));
                $reponse->setUrl( "/");
            }
            $this->response = $reponse->getError();
            return false;
        }

        return true;
    }

    /**
     * Check if the user is a valid member
     *  * A valid member is a user that has access to the membership panel.
     *
     * @param bool $isDemoAdmin
     * @return bool
     * @throws \Exception
     */
    public function isValidMember(bool $isDemoAdmin = false): bool
    {
        // Demo user welcomes
        if($isDemoAdmin && (int) Services::session()->get('group') !== 100) {
            return true;
        }

        // Not a valid member
        if(!in_array((int) Services::session()->get('group'), [1, 20])) {
            helper("core_helper");
            $reponse = new QuickResponse();
            $reponse->setMessage(_l("Unfortunately you do not have permission to this part of system.", $this));
            $reponse->setUrl( "/");
            $this->response = $reponse->getError();
            return false;
        }

        return true;
    }

    /**
     * Check if the logged user is a DEMO account
     *
     * @return bool
     */
    public function isDemo(): bool
    {
        return (int) Services::session()->get('group') === 100;
    }

    /**
     * Check if the logged user is active
     *
     * @return bool
     * @throws \Exception
     */
    public function isActive(): bool
    {
        $userdata = $this->getUserData();
        if(!boolval($userdata['active'])) {
            helper("core_helper");
            $reponse = new QuickResponse();
            $reponse->setMessage(_l("Your account has been suspended!", $this));
            $lang = Services::language()->getLocale();
            $reponse->setUrl( "/{$lang}/account-locked");
            $this->response = $reponse->getError();
            return false;
        }
        return boolval($userdata['active']);
    }

    /**
     * Returns the error response
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function getResponse()
    {
        return $this->response;
    }
}
