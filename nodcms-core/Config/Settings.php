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

namespace Config;

class Settings extends \CodeIgniter\Config\BaseConfig
{
    /**
     * Display the product copyright sign and info in footer
     */
    public $production_copyright = true;

    /**
     * Clean page clean frame template
     */
    public $clean_template_frame =  'nodcms-clean';

    /**
     * Maximum upload file size for whole system
     * *    It shall be under your server configured limit.
     */
    public $max_upload_size =  20000; // KG

    /**
     * General auto loading models to load the same models for all type of controllers
     */
    public $general_models = array(
        'Email_messages_model',
        'Groups_model',
        'Images_model',
        'Languages_model',
        'Menu_model',
        'Packages_model',
        'Public_model',
        'Settings_model',
        'Social_links_model',
        'Titles_model',
        'Translates_model',
        'Translations_model',
        'Upload_files_model',
        'Users_model',
    );

    /**
     * Backend auto loading models
     */
    public $backend_models =  array('Nodcms_admin_model', 'Packages_model', 'Packages_dashboard_model', 'Languages_model', 'Email_messages_model');

    /**
     * Backend pages auto loading helpers
     */
    public $backend_helpers =  array('nodcms_general');

    /**
     * Frontend auto loading models
     */
    public $frontend_models =  array('Nodcms_general_model', 'Packages_model', 'Languages_model', 'Email_messages_model');

    /**
     * Frontend auto loading helpers
     */
    public $frontend_helpers =  array('nodcms_general');

    /**
     * Membership auto loading models
     */
    public $membership_models =  array('Nodcms_general_model', 'Languages_model', 'Email_messages_model');

    /**
     * Membership auto loading helpers
     */
    public $membership_helpers =  array('nodcms_general');

    public $api_models =  array('Nodcms_general_model', 'Languages_model', 'Email_messages_model');

    public $api_helpers =  array('nodcms_general');

    /**
     * System automatic out going emails
     *
     * | All content of this array will be display on your admin side-> settings -> Auto emails messages
     * | as an option to enter a text/HTML code as the template of the outgoing email message.
     * | This items of this array should set like the bellow temp:
     * |    $config['autoEmailMessages'] = array(
     * |        [the key of the message template to save in database]=>array(
     * |            'label'=>"[The label of this option that will display in admin side as describe this option]"
     * |            'keys'=>array(
     * |                // All replace keys that will be replace with a dynamic value.
     * |                // The replace keys will defined in the send_notification_email() to replace with the dynamic values.
     * |                // We define the same keys to display in admin side as describe the key.
     * |                array(
     * |                    'label'=>"[Describe of the value that you will replace instead of key]",
     * |                    'value'=>"[--$[The key that will replace with a dynamic value]--]",
     * |                );
     * |        );
     * |    );
     */
    public $autoEmailMessages = array(
        'contact_form'=> array(
            'label'=>'Contact form',
            'keys'=>array(
                array('label'=>'Username','value'=>'[--$username--]'),
                array('label'=>'Email','value'=>'[--$email--]'),
                array('label'=>'Date','value'=>'[--$date--]'),
                array('label'=>'Name','value'=>'[--$name--]'),
                array('label'=>'Message','value'=>'[--$message--]'),
            ),
        ),
        'registration_confirm'=> array(
            'label'=>'Registration confirmation email',
            'keys'=>array(
                array('label'=>'Company Name','value'=>'[--$company--]'),
                array('label'=>'Username','value'=>'[--$username--]'),
                array('label'=>'Email','value'=>'[--$email--]'),
                array('label'=>'Date','value'=>'[--$date--]'),
                array('label'=>'First name','value'=>'[--$first_name--]'),
                array('label'=>'Last name','value'=>'[--$last_name--]'),
                array('label'=>'Request reference','value'=>'[--$refurl--]'),
            ),
        ),
        'reset_password'=> array(
            'label'=>'Reset password',
            'keys'=>array(
                array('label'=>'Company Name','value'=>'[--$company--]'),
                array('label'=>'Username','value'=>'[--$username--]'),
                array('label'=>'Email','value'=>'[--$email--]'),
                array('label'=>'Date','value'=>'[--$date--]'),
                array('label'=>'First name','value'=>'[--$firstname--]'),
                array('label'=>'Last name','value'=>'[--$lastname--]'),
                array('label'=>'Set new password link','value'=>'[--$reference_url--]'),
            )
        ),
    );

    /**
     * Default settings value
     *
     * | The settings of system will save in the database whit using admin side.
     * | For a new installed system, that didn't configuration, these default settings will help the system to run without
     * | any problem and/or error.
     */
    public $settings_default = array(
        'default_image'=>"upload_file/no-images.png",
        'company'=>"NodCMS",
        'logo'=>"upload_file/logo/logo.png",
        'logo_light'=>"upload_file/logo/logo-light.png",
        'index_logo'=>"upload_file/logo/logo.png",
        'home_page_title_box'=>1,
        'home_page_title_bg'=>"",
        'home_page_title_bg_blur'=>1,
        'custom_view_path_home'=>"",
        'fav_icon'=>"upload_file/logo/fav.png",
        'site_title'=>"NodCMS is a Content Management System based on Codeigniter",
        'site_author'=>"Mojtaba Khodakhah",
        'site_description'=>"",
        'site_keyword'=>"",
        'timezone'=>"Europe/Vienna",
        'date_format'=>"d.m.Y",
        'time_format'=>"H:i",
        'currency_format'=>"1,234.56",
        'currency_code'=>"EUR",
        'currency_sign'=>"€",
        'currency_sign_before'=>"1",
        'invoice_tax_number'=>"",
        'invoice_vat_type'=>0,
        'protect_paid_invoices'=>1,
        'invoice_vat'=>0,
        'invoice_discount'=>0,
        'invoice_account_holder'=>"",
        'invoice_iban'=>"",
        'invoice_bic'=>"",
        'invoice_prepaid_fix'=>"",
        'invoice_description_fix'=>"",
        'invoice_rules_fix'=>"",
        'appointment_non_repeating'=>"0",
        'simple_mode'=>"1",
        'registration'=>"1",
        'prefooter'=>"1",
        'prefooter_title'=>"",
        'prefooter_description'=>"",
        'provider_style'=>"imagic",
        'use_smtp'=>"0",
        'smtp_host'=>"",
        'smtp_port'=>"0",
        'smtp_username'=>"",
        'smtp_password'=>"",
        'send_email'=>"do-not-replay@chictheme.com",
        'google_map_url'=>"",
        'google_map'=>"",
        'address'=>"Vienna",
        'email'=>"info@chictheme.com",
        'phone'=>"+43 0123 1234567",
        'fax'=>"+43 0123 1234567",
        'contact_form'=>1,
        'add_on_header'=>"",
        'add_on_script'=>"",
        'dynamic_timezone'=>0,
        'captcha'=>0,
        'terms_accept_required'=>0,
        'terms_and_conditions_title'=>"Terms & Conditions",
        'terms_and_conditions_content'=>"At the moment there is no content for this page.",
        'privacy_policy_title'=>"Privacy Policy",
        'privacy_policy_content'=>"At the moment there is no content for this page.",
        'homepage_type'=>"default",
        'homepage_redirect'=>"",
        'homepage_display_file'=>"",
        'homepage_display_page'=>"",
        'reset_password_tries_limit'=>5,
    );
}
