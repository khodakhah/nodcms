<?php
/**
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
 *  @since      Version 3.1.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Controllers;

use Config\Models;
use Config\Services;
use Config\ViewMembership;

abstract class Membership extends App
{
    public function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new ViewMembership());
        Services::sidebar()->setConfig(new ViewMembership());

        $this->userdata = Services::identity()->getUserData();

        // Load language of a user
        $this->language = Models::languages()->getOne($this->userdata['language_id']);
        // Set language from database
        Services::language()->set($this->language);

        // Load settings from DB
        Services::settings()->load($this->language['language_id']);
        $this->settings = Services::settings()->get();

        Services::sidebar()->addLink(
            'dashboard',
            _l("Dashboard", $this),
            base_url('user/dashboard'),
            'fas fa-th-large'
        );

        Services::sidebar()->addLink(
            'dashboard',
            _l("Account", $this),
            base_url('user/account'),
            'fas fa-user-cog'
        );

        // Call membership of startup of all modules
        Services::modules()->executeMembership();
    }
}