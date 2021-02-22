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


use CodeIgniter\Config\Services;
use NodCMS\Core\Response\Response;
use NodCMS\Core\Response\ResponseType;

class Identity
{

    private $controller;
    private $session;
    private $response;

    public function __constructor($controller)
    {
        $this->controller = $controller;
        $this->session = Services::session();
    }

    public function getUserData()
    {
        if($this->session->has("user_id")) {
            $userModel = new \NodCMS\Core\Models\Users_model();
            $user = $userModel->getOne($this->session->get("user_id"));
//            if($user["active"]!=1 && $this->router->methodName() != "logout"){
//                $this->accountLock();
//                return;
//            }
            if(!empty($user)) {
                $user['has_dashboard'] = $this->session->has("has_dashboard") ? $this->session->get("has_dashboard") : false;
                return $user;
            }
        }

        return null;
    }

    public function hasSession(): bool
    {
        return $this->session->has("user_id");
    }

    public function isValid(): bool
    {
        if($this->session->has("user_id")) {
            $userModel = new \NodCMS\Core\Models\Users_model();
            $user = $userModel->getOne($this->session->get("user_id"));
            return !empty($user);
        }

        return false;
    }

    public function isAdmin(bool $isDemoAdmin = false): bool{
        if($this->session->get('group') !== 1) {
            $reponse = new Response(new ResponseType(ResponseType::TYPES_ERROR_MESSAGE));
            $reponse->setMessage(_l("Unfortunately you do not have permission to this part of system.", $this));
            $reponse->setUrl("/");
            $this->response = $reponse->get();
            return false;
        }

        return true;
    }

    public function getResponse()
    {
        return $this->response;
    }
}