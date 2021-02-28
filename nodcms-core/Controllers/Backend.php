<?php
/**
 * NodCMS
 *
 * Copyright (c) 2015-2020.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2020 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Controllers;


use Config\Services;

abstract class Backend extends App
{
    /**
     * @var \NodCMS\Core\Libraries\Identity
     */
    protected $identity;

    public function __construct()
    {
        parent::__construct();
        $this->view->config->frameFile = $this->config->backend_template_frame;
        $this->view->config->viewPath .= "/{$this->config->backend_template}";

        $this->data['base_url'] = base_url()."admin/";

        $this->userdata = Services::identity()->getUserData();

        // Load language of a user
        $this->language = $this->model->languages()->getOne($this->userdata['language_id']);
        // Set language from database
        Services::language()->set($this->language);

        // Load settings from DB
//        $this->settings = Services::model()->settings()->getSettings($this->language['language_id']);

        Services::sidebar()->addLink(
            'dashboard',
            _l("Control Panel",$this),
            ADMIN_URL,
            'fas fa-th-large'
        );
        Services::sidebar()->addLink(
            'users',
            _l("Members",$this),
            ADMIN_URL.'user',
            'fas fa-users'
        );
        Services::sidebar()->addLink(
            'language',
            _l("Languages",$this),
            ADMIN_URL.'language',
            'fas fa-language'
        );
        Services::sidebar()->addLink(
            'menu',
            _l("Menus",$this),
            ADMIN_URL.'menu',
            'fas fa-link'
        );
        Services::sidebar()->addLink(
            'uploaded_images',
            _l("Images",$this),
            ADMIN_URL.'imagesLibrary',
            'far fa-images'
        );
        Services::sidebar()->addLink(
            'modules',
            _l("Modules", $this),
            ADMIN_URL.'modules',
            'fas fa-brain'
        );
        Services::sidebar()->addLink(
            'tcpp_setting',
            _l("T&C, Privacy Policy", $this),
            ADMIN_URL.'settings/tcpp',
            'fas fa-gavel'
        );
        Services::sidebar()->addLink(
            'settings',
            _l("Settings",$this),
            'javascript:;',
            'fas fa-cogs'
        );
        Services::sidebar()->addSubLink(
            'settings',
            'setting',
            _l("General", $this),
            ADMIN_URL.'settings'
        );
        Services::sidebar()->addSubLink(
            'settings',
            'advance_setting',
            _l("Advance", $this),
            ADMIN_URL.'settings/advance'
        );
        Services::sidebar()->addSubLink(
            'settings',
            'homepage_setting',
            _l("Homepage", $this),
            ADMIN_URL.'settingsHomepage'
        );
        Services::sidebar()->addSubLink(
            'settings',
            'date_setting',
            _l("Date & Time", $this),
            ADMIN_URL.'settings/date-and-time'
        );
        Services::sidebar()->addSubLink(
            'settings',
            'contact_setting',
            _l("Contact info", $this),
            ADMIN_URL.'settings/contact'
        );
        Services::sidebar()->addSubLink(
            'settings',
            'mail_setting',
            _l("Send mail", $this),
            ADMIN_URL.'settings/mail'
        );
        Services::sidebar()->addSubLink(
            'settings',
            'emails_texts',
            _l("Auto emails messages", $this),
            ADMIN_URL.'automaticEmailTexts'
        );
        Services::sidebar()->addSubLink(
            'settings',
            'social_links',
            _l("Social Links", $this),
            ADMIN_URL.'socialLinks'
        );

        // Add top menu
        Services::topMenu()->addLink(
            'view_site',
            _l("View Site", $this),
            "/{$this->language['code']}"
        );

        // Call backend of startup of all modules
        Services::modules()->backend();
    }
}