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
