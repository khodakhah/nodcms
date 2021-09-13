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

namespace NodCMS\Core\Libraries;


use Config\Services;
use NodCMS\Core\Response\QuickResponse;

class Identity
{

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
     * @param bool $isDemoAdmin
     * @return bool
     * @throws \Exception
     */
    public function isAdmin(bool $isDemoAdmin = false): bool
    {
        // Demo user welcomes
        if($isDemoAdmin && (int) Services::session()->get('group') === 100) {
            return true;
        }

        // Not an admin user not welcomes
        if((int) Services::session()->get('group') !== 1) {
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

    public function getResponse()
    {
        return $this->response;
    }
}