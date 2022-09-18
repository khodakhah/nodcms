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

use Config\Services;
use Config\ViewBackend;

abstract class Backend extends App
{
    /**
     * @var \NodCMS\Core\Libraries\Identity
     */
    protected $identity;

    public function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new ViewBackend());
        Services::sidebar()->setConfig(new ViewBackend());
        $this->data['base_url'] = base_url()."admin/";

        $this->userdata = Services::identity()->getUserData();

        // Load language of a user
        $this->language = $this->model->languages()->getOne($this->userdata['language_id']);
        // Set language from database
        Services::language()->set($this->language);

        // Load settings from DB
        Services::settings()->load($this->language['language_id']);
        $this->settings = Services::settings()->get();

        // Add top menu
        Services::topMenu()->addLink(
            'view_site',
            _l("View Site", $this),
            "/{$this->language['code']}"
        );

        Services::sidebar()->addLink(
            'dashboard',
            _l("Control Panel", $this),
            ADMIN_URL,
            'fas fa-th-large'
        );

        // Call backend of startup of all modules
        Services::modules()->executeBackend();

        Services::sidebar()->addLink(
            'users',
            _l("Members", $this),
            ADMIN_URL.'user',
            'fas fa-users'
        );
        Services::sidebar()->addLink(
            'language',
            _l("Languages", $this),
            ADMIN_URL.'language',
            'fas fa-language'
        );
        Services::sidebar()->addLink(
            'menu',
            _l("Menus", $this),
            ADMIN_URL.'menu',
            'fas fa-link'
        );
        Services::sidebar()->addLink(
            'uploaded_images',
            _l("Images", $this),
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
            _l("Settings", $this),
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
    }
}
