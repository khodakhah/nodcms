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

use Config\Locales;
use Config\Services;
use NodCMS\Core\Libraries\Ajaxlist;
use NodCMS\Core\Libraries\Form;
use NodCMS\Core\Libraries\GetLangAsArray;

class GeneralAdmin extends Backend
{
    private $image_library_types = array(
        'logo'=>array(
            'dir'=>"logo",
            'encrypt_name'=>false,
        ),
        'lang'=>array(
            'dir'=>"lang",
            'encrypt_name'=>false,
        ),
        'images-library'=>array(
            'dir'=>"images-library",
            'encrypt_name'=>true,
        ),
    );

    public function dashboard()
    {
        // * Update Sort
        $post_data = $this->request->getPost('data');
        if ($post_data != null) {
            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }
            foreach ($post_data as $i=>$item) {
                $update_data = array(
                    'package_sort'=>$i,
                );
                Services::model()->packagesDashboard()->edit($item, $update_data);
            }
            return $this->successMessage("The packages has been successfully sorted.", ADMIN_URL);
        }
        // Update package list
        Services::modules()->resort();
        $this->data['dashboards'] = Services::modules()->getDashboards();

        $emailNotification = Services::emailNotification();
        $missed = $emailNotification->getEmptyMessages();
        if ($missed>0) {
            $this->data['auto_email_messages_badge'] = $missed;
        }
        $this->data['title'] = _l("Dashboard", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Dashboard", $this))
        );
        $this->data['keyword'] = "";
        $this->data['page'] = "dashboard";
        return $this->viewRender('dashboards');
    }

    /**
     * System basic settings
     *
     * @param string $sub_page
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function settings(string $sub_page='general')
    {
        $forms = array(
            'general'=>array(
                'notes'=>array(),
                'page'=>"setting",
                'sub_title'=>_l('General settings', $this),
                'config'=>array(
                    array(
                        'type'=>"h3",
                        'label'=>_l("Basic info", $this),
                    ),
                    array(
                        'field'=>"company",
                        'label'=>_l('Company Name', $this),
                        'rules'=>"required",
                        'type'=>"text",
                        'default'=>$this->settings['company'],
                    ),
                    array(
                        'field'=>"logo",
                        'label'=>_l('Logo', $this),
                        'rules'=>"",
                        'type'=>"image-library",
                        'default'=>$this->settings['logo'],
                    ),
                    array(
                        'field'=>"logo_light",
                        'label'=>_l("Logo light", $this),
                        'rules'=>"",
                        'type'=>"image-library",
                        'default'=>$this->settings['logo_light'],
                        'help'=>_l("Logo on dark background like admin side.", $this),
                    ),
                    array(
                        'field'=>"fav_icon",
                        'label'=>_l('Fav Icon', $this),
                        'rules'=>"",
                        'type'=>"image-library",
                        'default'=>$this->settings['fav_icon'],
                    ),
                )
            ),
            'contact'=>array(
                'notes'=>array(),
                'page'=>"contact_setting",
                'sub_title'=>_l('Contact Info', $this),
                'config'=>array(
                    array(
                        'field'=>"email",
                        'label'=>_l("Email", $this),
                        'rules'=>"valid_email",
                        'type'=>"text",
                        'default'=>$this->settings['email'],
                    ),
                    array(
                        'field'=>"phone",
                        'label'=>_l("Phone number", $this),
                        'rules'=>"validPhone",
                        'type'=>"text",
                        'default'=>$this->settings['phone'],
                    ),
                    array(
                        'field'=>"fax",
                        'label'=>_l("Fax number", $this),
                        'rules'=>"validPhone",
                        'type'=>"text",
                        'default'=>$this->settings['fax'],
                    ),
                    array(
                        'field'=>"address",
                        'label'=>_l("Address", $this),
                        'rules'=>"",
                        'type'=>"text",
                        'default'=>$this->settings['address'],
                    ),
                    array(
                        'field'=>"google_map",
                        'label'=>_l("Google map", $this),
                        'rules'=>"validGoogleMapIframe",
                        'type'=>"textarea",
                        'default'=>$this->settings['google_map'],
                    ),
                    array(
                        'field'=>"contact_form",
                        'label'=>_l("Contact form", $this),
                        'rules'=>"required|in_list[0,1]",
                        'type'=>"switch",
                        'default'=>$this->settings['contact_form'],
                    ),
                ),
            ),
            'date-and-time'=>array(
                'notes'=>array(),
                'page'=>"date_setting",
                'sub_title'=>_l('Date and time settings', $this),
                'config'=>array(
                    array(
                        'field'=>"timezone",
                        'label'=>_l('Timezone', $this),
                        'rules'=>"",
                        'type'=>"select-array",
                        'default'=>$this->settings['timezone'],
                        'options'=>\DateTimeZone::listIdentifiers(),
                        'class'=>"select2me",
                    ),
                    array(
                        'field'=>"date_format",
                        'label'=>_l('Date Format', $this),
                        'rules'=>"",
                        'type'=>"select",
                        'default'=>$this->settings['date_format'],
                        'options'=>array(
                            array("format"=>"d.m.Y", "name"=>"dd.mm.yy"),
                            array("format"=>"m/d/Y", "name"=>"mm/dd/yy"),
                            array("format"=>"Y-m-d", "name"=>"yy-mm-dd"),
                        ),
                        'option_name'=>'name',
                        'option_value'=>'format',
                    ),
                    array(
                        'field'=>"time_format",
                        'label'=>_l('Time Format', $this),
                        'rules'=>"",
                        'type'=>"select",
                        'default'=>$this->settings['time_format'],
                        'options'=>array(
                            array("format"=>"H:i", "name"=>"24"),
                            array("format"=>"h:i A", "name"=>"12"),
                        ),
                        'option_name'=>'name',
                        'option_value'=>'format',
                    ),
//                array(
//                    'field' => 'dynamic_timezone',
//                    'label' => _l('Dynamic Timezone',$this),
//                    'rules' => 'required|in_list[0,1]',
//                    'type' => "switch",
//                    'default'=>$this->settings["dynamic_timezone"],
//                    'help'=>_l("This option lets any one to pick system timezone.", $this)
//                ),
                )
            ),
            'mail'=>array(
                'notes'=>array(),
                'page'=>"mail_setting",
                'sub_title'=>_l('Send mail settings', $this),
                'config'=>array(
                    array(
                        'field'=>"send_email",
                        'label'=>_l('Email', $this),
                        'rules'=>"",
                        'type'=>"email",
                        'default'=>$this->settings['send_email'],
                        'help'=>_l("All system message will send from this email address.", $this),
                    ),
                    array(
                        'field'=>"use_smtp",
                        'label'=>_l('Use SMTP', $this),
                        'rules'=>"",
                        'type'=>"checkbox",
                        'sub_items'=>array(
                            array(
                                'field'=>"use_smtp",
                                'label'=>"",
                                'rules'=>"",
                                'class'=>"",
                                'input_class'=>"",
                                'default'=>$this->settings['use_smtp'],
                                'attr'=>array(
                                    'onchange'=>"$('.smtp_options').toggleClass('hidden');"
                                )
                            ),
                        ),
                    ),
                    array(
                        'field'=>"smtp_host",
                        'label'=>_l('SMTP host name', $this),
                        'rules'=>"",
                        'type'=>"text",
                        'default'=>$this->settings['smtp_host'],
                        'group_class'=>"smtp_options ".($this->settings['use_smtp']!=1 ? 'hidden' : ''),
                    ),
                    array(
                        'field'=>"smtp_port",
                        'label'=>_l('SMTP port', $this),
                        'rules'=>"",
                        'type'=>"number",
                        'default'=>$this->settings['smtp_port'],
                        'group_class'=>"smtp_options ".($this->settings['use_smtp']!=1 ? 'hidden' : ''),
                    ),
                    array(
                        'field'=>"smtp_username",
                        'label'=>_l('SMTP username', $this),
                        'rules'=>"",
                        'type'=>"text",
                        'default'=>$this->settings['smtp_username'],
                        'group_class'=>"smtp_options ".($this->settings['use_smtp']!=1 ? 'hidden' : ''),
                    ),
                    array(
                        'field'=>"smtp_password",
                        'label'=>_l('SMTP password', $this),
                        'rules'=>"",
                        'type'=>"text",
                        'default'=>$this->settings['smtp_password'],
                        'group_class'=>"smtp_options ".($this->settings['use_smtp']!=1 ? 'hidden' : ''),
                    ),
                )
            ),
            'advance'=>array(
                'notes'=>array(),
                'page'=>"advance_setting",
                'sub_title'=>_l('Advance settings', $this),
                'config'=>array(
                    array(
                        'field'=>"google_captcha_site_key",
                        'label'=>_l('Google invisible reCaptcha API site key', $this),
                        'rules'=>"",
                        'type'=>"password",
                        'attr'=>array(
                            'placeholder'=>isset($this->settings['google_captcha_site_key']) ? substr($this->settings['google_captcha_site_key'], 0, 3).'***'.substr($this->settings['google_captcha_site_key'], -3, 3) : '',
                        ),
                    ),
                    array(
                        'field'=>"google_captcha_secret_key",
                        'label'=>_l('Google invisible reCaptcha API secret key', $this),
                        'rules'=>"",
                        'type'=>"password",
                        'attr'=>array(
                            'placeholder'=>isset($this->settings['google_captcha_secret_key']) ? substr($this->settings['google_captcha_secret_key'], 0, 3).'***'.substr($this->settings['google_captcha_secret_key'], -3, 3) : '',
                        ),
                    ),
                    array(
                        'field'=>"google_captcha_active",
                        'label'=>_l('Google captcha', $this),
                        'rules'=>"required|in_list[0,1]",
                        'type'=>"switch",
                        'default'=>isset($this->settings['google_captcha_active']) ? $this->settings['google_captcha_active'] : '',
                    ),
//                array(
//                    'field' => 'simple_mode',
//                    'label' => _l('Simple mode',$this),
//                    'rules' => 'required|in_list[0,1]',
//                    'type' => "switch",
//                    'default'=>$this->settings["simple_mode"],
//                    'help'=>_l("This option lets you to make many providers page with separate dashboard, admin and staffs.", $this)
//                ),
                    array(
                        'field' => 'registration',
                        'label' => _l('Registration', $this),
                        'rules' => 'required|in_list[0,1]',
                        'type' => "switch",
                        'default'=>$this->settings["registration"],
                        'help'=>_l("This option lets any one to make an account in your system.", $this)
                    ),
                    array(
                        'field' => 'add_on_header',
                        'label' => _l('Add on scripts at header', $this),
                        'rules' => '',
                        'type' => "textarea",
                        'default'=>isset($this->settings["add_on_header"]) ? $this->settings["add_on_header"] : "",
                        'help'=>_l("If you would like to use some script such as statistics or analytics tool on your website, put it here.", $this).
                            '<br>'._l("This code would be in head tag in all pages even backend pages.", $this)
                    ),
                    array(
                        'field' => 'add_on_script',
                        'label' => _l('Add on scripts at footer', $this),
                        'rules' => '',
                        'type' => "textarea",
                        'default'=>$this->settings["add_on_script"],
                        'help'=>_l("If you would like to use some script such as statistics or analytics tool on your website, put it here.", $this).
                            '<br>'._l("This code would be after footer in all pages even backend pages.", $this)
                    ),
                )
            ),
            // Terms & Conditions and Privacy Policy edit page
            'tcpp'=>array(
                'notes'=>array(
                    array(
                        'type'=>"warning",
                        'title'=>"<a target='_blank' href='https://gdpr-info.eu/'>GDPR</a> Compliant",
                        'content'=>"To make your website <a target='_blank' href='https://gdpr-info.eu/'><strong>General Data Protection Regulation(GDPR)</strong></a> Compliant " .
                            "you shall to active the bellow feature: <strong>" .
                            "<a target='_blank' href='".base_url($this->language['code']."/terms-and-conditions")."'>Accept T&C</a> and " .
                            "<a target='_blank' href='".base_url($this->language['code']."/privacy-policy")."'>Privacy Policy</a> required</strong> " .
                            "and enter your business Terms & Conditions and Privacy Policy into the defined fields." .
                            "<br>The Terms & Conditions of and  Privacy Policy's contents will display on your website as separate pages. <br>" .
                            "<br><strong>NOTE:</strong><br>" .
                            "Because you must clearly identify each party for which the consent is being granted, " .
                            "please note that, this software is using below APIs if you already activated them: <br>" .
                            "<ul>" .
                            "<li>Google invisible reCaptcha service</li>" .
                            "<li>PayPal Express Checkout service</li>" .
                            "</ul>" .
                            "",
                    ),
                ),
                'page'=>"tcpp_setting",
                'sub_title'=>_l('T&C, Privacy Policy', $this),
                'config'=>array(
                    array(
                        'field'=>"terms_accept_required",
                        'label'=>_l('Accept T&C and Privacy Policy required', $this),
                        'rules'=>"required|in_list[0,1]",
                        'type'=>"switch",
                        'help'=>_l("It will add required checkbox field on every field of your system to accept your Terms & Conditions and  Privacy Policy", $this),
                        'default'=>$this->settings['terms_accept_required'],
                        'attr'=>array(
                            'onchange'=>"$('.tcpp_options').toggleClass('hidden');"
                        )
                    ),
                )
            ),
        );

        $languages = Services::model()->languages()->getAll();
        foreach ($languages as $language) {
            $setting = Services::model()->settings()->getSettings($language['language_id']);
            $language_head = array(
                'label'=>$language['language_title'],
                'type'=>"h4",
                'prefix_language'=>$language,
            );
            $prefix = "options[$language[language_id]]";
            // Add translation field to the Terms & Conditions and Privacy Policy edit page
            $forms['tcpp']['config'] = array_merge($forms['tcpp']['config'], array(
                array(
                    'label'=>$language['language_title'],
                    'type'=>"h2",
                    'prefix_language'=>$language,
                    'class'=>"tcpp_options".($this->settings['terms_accept_required']!=1 ? " hidden" : ""),
                ),
                array(
                    'label'=> _l('Terms & Conditions Page', $this),
                    'type'=>"h3",
                    'class' => "tcpp_options".($this->settings['terms_accept_required']!=1 ? " hidden" : ""),
                ),
                array(
                    'field'=>$prefix."[terms_and_conditions_title]",
                    'label' => _l("Page title", $this),
                    'rules' => "",
                    'type' => "text",
                    'group_class' => "tcpp_options".($this->settings['terms_accept_required']!=1 ? " hidden" : ""),
                    'default'=>isset($setting['terms_and_conditions_title']) ? $setting['terms_and_conditions_title'] : '',
                ),
                array(
                    'field'=>$prefix."[terms_and_conditions_content]",
                    'label' => _l("Page content", $this),
                    'rules' => "",
                    'type' => "texteditor",
                    'group_class' => "tcpp_options".($this->settings['terms_accept_required']!=1 ? " hidden" : ""),
                    'default'=>isset($setting['terms_and_conditions_content']) ? $setting['terms_and_conditions_content'] : '',
                ),
                array(
                    'label'=>_l("Privacy Policy Page", $this),
                    'type'=>"h3",
                    'class' => "tcpp_options".($this->settings['terms_accept_required']!=1 ? " hidden" : ""),
                ),
                array(
                    'field'=>$prefix."[privacy_policy_title]",
                    'label' => _l("Page title", $this),
                    'rules' => "",
                    'type' => "text",
                    'group_class' => "tcpp_options".($this->settings['terms_accept_required']!=1 ? " hidden" : ""),
                    'default'=>isset($setting['privacy_policy_title']) ? $setting['privacy_policy_title'] : '',
                ),
                array(
                    'field'=>$prefix."[privacy_policy_content]",
                    'label' => _l("Page content", $this),
                    'rules' => "",
                    'type' => "texteditor",
                    'group_class' => "tcpp_options".($this->settings['terms_accept_required']!=1 ? " hidden" : ""),
                    'default'=>isset($setting['privacy_policy_content']) ? $setting['privacy_policy_content'] : '',
                )
            ));
        }

        if (!key_exists($sub_page, $forms)) {
            return $this->errorMessage("Page not found.", ADMIN_URL.'settings');
        }

        $myform = new Form($this);
        $myform->config($forms[$sub_page]['config'], ADMIN_URL."settings/$sub_page", 'post', 'ajax', $forms[$sub_page]['notes']);
        // * Submit form
        if ($myform->ispost()) {
            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }

            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }
            // Email templates and messages save
            if (isset($data["auto_messages"])) {
                $auto_emails = $this->config->item('autoEmailMessages');
                // Load auto messages from packages directories
                $packages = Services::modules()->getNames();
                foreach ($packages as $item) {
                    $package_auto_emails = $this->config->item($item.'_autoEmailMessages');
                    if (is_array($package_auto_emails)) {
                        $auto_emails = array_merge($auto_emails, $package_auto_emails);
                    }
                }
                foreach ($data["auto_messages"] as $language_id=>$value) {
                    foreach ($auto_emails as $code_key=>$msg_val) {
                        $item = $value[$code_key];
                        $message = Services::model()->emailMessages()->getOne(null, array('code_key'=>$code_key, 'language_id'=>$language_id));
                        if ($message!=null) {
                            Services::model()->emailMessages()->edit($message['msg_id'], $item);
                        }
                        // Add new message
                        else {
                            $item['language_id'] = $language_id;
                            $item['code_key'] = $code_key;
                            Services::model()->emailMessages()->add($item);
                        }
                    }
                }
                unset($data["auto_messages"]);
            }
            // Options in all languages save
            if (isset($data["options"])) {
                foreach ($data["options"] as $language_id=>$item) {
                    if (!Services::model()->settings()->updateSettings($item, $language_id)) {
                        return $this->errorMessage("A settings options could not be saved.", $this);
                    }
                }
                unset($data["options"]);
            }
            if (isset($data['google_captcha_site_key']) && $data['google_captcha_site_key']=="") {
                unset($data['google_captcha_site_key']);
            }
            if (isset($data['google_captcha_secret_key']) && $data['google_captcha_secret_key']=="") {
                unset($data['google_captcha_secret_key']);
            }
            if (!empty($data['google_map'])) {
                $matches = array();
                preg_match('/(.+)src="([^\s]+)"(.+)/i', $data['google_map'], $matches);
                $data['google_map_url'] = $matches[2];
            } else {
                $data['google_map_url'] = "";
            }

            // Encode html special chars
            if (isset($data['add_on_header']) && !empty($data['add_on_header'])) {
                $data['add_on_header'] = htmlspecialchars(Services::request()->getPost('add_on_header'));
            }
            if (isset($data['add_on_script']) && !empty($data['add_on_script'])) {
                $data['add_on_script'] = htmlspecialchars(Services::request()->getPost('add_on_script'));
            }

            // The settings without language_id
            Services::model()->settings()->updateSettings($data);
            return $this->successMessage("Your Setting has been updated successfully!", ADMIN_URL."settings/$sub_page");
        }

        $this->data['page'] = $forms[$sub_page]['page'];
        $this->data['sub_title'] = $forms[$sub_page]['sub_title'];

        $this->data['title'] = _l('Settings', $this);
        $this->data['breadcrumb'] = array(array('title'=>$this->data['title']),
            array('title'=>$this->data['sub_title']));
        return $this->viewRenderString($myform->fetch());
    }

    /**
     * Automatic email text management
     *
     * @return string
     */
    public function automaticEmailTexts()
    {
        $this->data['page'] = "emails_texts";
        // Get auto messages keys from config
        $auto_emails = $this->config->autoEmailMessages;
        // Load auto messages from packages directories
        $auto_emails = array_merge($auto_emails, Services::modules()->getAllAutoEmailMessages());
        $languages = Services::model()->languages()->getCount();
        foreach ($auto_emails as $key=>&$item) {
            $_missed = Services::model()->emailMessages()->getCount(array('code_key'=>$key));
            $item['form_url'] = ADMIN_URL."automaticEmailTextForm/$key";
            if ($_missed<$languages) {
                $item['form_badge'] = str_replace("{data}", $languages-$_missed, _l("{data} Empty", $this));
            }
        }
        $this->data['data_list'] = $auto_emails;

        $this->data['title'] = _l('Settings', $this);
        $this->data['sub_title'] = _l('Auto email messages', $this);
        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title']),
            array('title'=>$this->data['sub_title'])
        );
        return $this->viewRender("auto_emails");
    }

    /**
     * Automatic email text submit form
     *
     * @param string $email_key
     * @return \CodeIgniter\HTTP\RedirectResponse|false|mixed|string
     * @throws \Exception
     */
    public function automaticEmailTextForm(string $email_key)
    {
        $self_url = ADMIN_URL."automaticEmailTextForm/$email_key";
        $back_url = ADMIN_URL."automaticEmailTexts";
        // Get auto messages keys from config
        $auto_emails = $this->config->autoEmailMessages;
        // Load auto messages from packages directories
        $auto_emails = array_merge($auto_emails, Services::modules()->getAllAutoEmailMessages());
        if (!key_exists($email_key, $auto_emails)) {
            return $this->errorMessage("The email message isn't exists.", $back_url);
        }
        $languages = Services::model()->languages()->getAll();
        // Form configuration
        $config = array(
            array(
                'type'=>'h2',
                'label'=>$auto_emails[$email_key]['label']
            ),
        );
        foreach ($languages as $item) {
            // Get contents from database
            $autoMsgData = Services::model()->emailMessages()->getOne(null, array('code_key'=>$email_key, 'language_id'=>$item['language_id']));
            $config = array_merge($config, array(
                array(
                    'field'=>$email_key."[$item[language_id]][subject]",
                    'type'=>"text",
                    'rules'=>"",
                    'label'=>_l("Subject", $this),
                    'prefix_language'=>$item,
                    'default'=>$autoMsgData!=null ? $autoMsgData['subject'] : '',
                ),
                array(
                    'field'=>$email_key."[$item[language_id]][content]",
                    'type'=>"texteditor-quick",
                    'rules'=>"",
                    'label'=>_l("Content", $this),
                    'prefix_language'=>$item,
                    'default'=>$autoMsgData!=null ? $autoMsgData['content'] : '',
                    'shortkeys'=>$auto_emails[$email_key]['keys'],
                ),
            ));
        }
        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');
        if ($myform->ispost()) {
            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }

            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }
            $language_ids = array_column($languages, 'language_id');
            foreach ($data[$email_key] as $key=>$item) {
                if (!in_array($key, $language_ids)) {
                    continue;
                }
                // Edit message
                $message = Services::model()->emailMessages()->getOne(null, array('code_key'=>$email_key, 'language_id'=>$key));
                if ($message!=null) {
                    Services::model()->emailMessages()->edit($message['msg_id'], $item);
                }
                // Add new message
                else {
                    $item['language_id'] = $key;
                    $item['code_key'] = $email_key;
                    Services::model()->emailMessages()->add($item);
                }
            }
            // Options in all languages save
            return $this->successMessage("The message has been edited successfully.", $back_url);
        }
        return $myform->fetch('', array(), true);
    }

    /**
     * Menu manager page
     */
    public function menu()
    {
        $this->data['title'] = _l("Menu Manager", $this);
        $this->data['sub_title'] = _l("Menu List", $this);

        $this->data['menu_types']=array(
            array('title'=>_l('Top menu', $this), 'key'=>"top_menu"),
//            array('title'=>_l('Footer menu',$this), 'description'=>_l("This menu will display in the pre footer.", $this), 'key'=>"footer_menu"),
        );
        foreach ($this->data['menu_types'] as &$val) {
            $val['data_list'] = Services::model()->menu()->getAll(array('menu_key'=>$val['key'], 'sub_menu'=>0), null, 1, ["menu_order", "ASC"]);
            foreach ($val['data_list'] as &$item) {
                $item['sub_menu_data'] = Services::model()->menu()->getAll(array('menu_key'=>$val['key'], 'sub_menu'=>$item['menu_id']));
            }
        }
        $this->data['breadcrumb']=array(
            array('title'=>_l('Menu', $this)),
        );
        $this->data['page'] = "menu";
        return $this->viewRender("menu_manager");
    }

    /**
     * Menu add and edit form
     *
     * @param int $id
     * @param string $menu_type
     * @return \CodeIgniter\HTTP\RedirectResponse|false|mixed|string
     * @throws \Exception
     */
    public function menuForm(int $id, string $menu_type)
    {
        if ($id!=0) {
            $current_data = Services::model()->menu()->getOne($id);
            if (count($current_data)==0) {
                return $this->errorMessage("The menu couldn't find.", ADMIN_URL."menuForm");
            }
            $this->data["form_title"] = _l("Edit", $this);
        } else {
            $this->data["form_title"] = _l("Add", $this);
        }

        $languages = Services::model()->languages()->getAll();
        $config = array(
            array(
                'field'=>"menu_name",
                'label'=>_l("Title", $this),
                'rules'=>"required",
                'type'=>"text",
                'default'=>isset($current_data) ? $current_data['menu_name'] : '',
            ),
        );
        foreach ($languages as $item) {
            if (isset($current_data)) {
                $translations = Services::model()->menu()->getTranslations($id, $item['language_id']);
            } else {
                $translations = [];
            }
            $config[] = array(
                'field'=>"titles[$item[language_id]][menu_name]",
                'label'=>_l("Title", $this),
                'rules'=>"required",
                'type'=>"text",
                'prefix_language'=>$item,
                'default'=>key_exists("menu_name", $translations) ? $translations['menu_name'] : '',
            );
        }

        // General URLs
        $menu_list = [
            ['title'=>_l("Home", $this), 'url'=>""],
            ['title'=>_l("Contact Us", $this), 'url'=>"contact"],
            ['title'=>_l("Terms & Conditions", $this), 'url'=>"terms-and-conditions"],
            ['title'=>_l("Privacy-Policy", $this), 'url'=>"privacy-policy"],
        ];
        // URLs of the modules
        $menuList = Services::modules()->getMenuList();
        foreach ($menuList as $menu) {
            $menu_list[] = [
                'title'=>$menu->title,
                'url'=>$menu->uri,
            ];
        }

        $config[] = array(
            'field'=>"menu_url",
            'label'=>_l("URL", $this),
            'rules'=>"",
            'type'=>"select-text",
            'options'=>$menu_list,
            'option_name'=>"title",
            'option_value'=>"url",
            'default'=>isset($current_data) ? $current_data['menu_url'] : '',
        );
        $config[] = array(
            'field'=>"public",
            'label'=>_l("Public", $this),
            'rules'=>"required|in_list[0,1]",
            'type'=>"switch",
            'default'=>isset($current_data) ? $current_data['public'] : '',
        );
        $config[] = array(
            'field'=>"menu_key",
            'label'=>_l("Menu type", $this),
            'rules'=>"required|in_list[top_menu,footer_menu]",
            'type'=>"hidden",
            'default'=>$menu_type,
        );
        $myform = new Form($this);
        $myform->config($config, ADMIN_URL."menuForm/$id/$menu_type", 'post', 'ajax');
        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }

            $titles = $data['titles'];
            unset($data['titles']);

            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }

            if ($id != 0) {
                Services::model()->menu()->edit($id, $data);
                Services::model()->menu()->updateTranslations($id, $titles, $languages);
                return $this->successMessage("Menu has been successfully updated.", ADMIN_URL."menu");
            }

            $new_id = Services::model()->menu()->add($data);
            Services::model()->menu()->updateTranslations($new_id, $titles, $languages);
            return $this->successMessage("Menu has been successfully added.", ADMIN_URL."menu");
        }

        if (Services::request()->isAJAX()) {
            return $myform->fetch('', array('data-redirect'=>1));
        }

        $this->data['title'] = _l("Menu Manager", $this);
        $this->data['breadcrumb']=array(
            array('title'=>_l('Menu', $this),'url'=>ADMIN_URL.'menu'),
            array('title'=>$this->data['sub_title'])
        );
        $this->data['page'] = "menu_edit";
        return $this->viewRenderString($myform->fetch('', array('data-redirect'=>1)));
    }

    /**
     * Change visibility of a menu item
     *
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     */
    public function menuVisibility($id)
    {
        $data = Services::model()->menu()->getOne($id);
        if (count($data)==0) {
            return $this->errorMessage("Couldn't find the menu item.", ADMIN_URL."menu");
        }
        $public = Services::request()->getPost('data');
        if ($public == 1) {
            $public = 0;
        } elseif ($public == 0) {
            $public = 1;
        } else {
            return $this->errorMessage("Visibility value isn't correct. Please reload the page to solve this problem.", ADMIN_URL."menu");
        }
        $update_data = array(
            'public'=>$public
        );
        Services::model()->menu()->edit($id, $update_data);
        return $this->successMessage("Success", ADMIN_URL."menu");
    }

    /**
     * Save the sort of menu items
     *
     * @param string $menu_key
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function menuSort($menu_key='')
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $i = 0;
        $index = 0;
        $sub_menu = array(0);
        $children = array(Services::request()->getPost('data'));
        $children[$index] = (object) json_decode($children[$index]);
        do {
            $data = $children[$index];
            foreach ($data as $key=>$item) {
                $i++;
                $update_data = array(
                    'menu_order'=>$i,
                    'sub_menu'=>$sub_menu[$index],
                    'menu_key'=>$menu_key,
                );
                Services::model()->menu()->edit($item->id, $update_data);
                if (isset($item->children)) {
                    $sub_menu[$index+1] = $item->id;
                    $children[$index+1] = $item->children;
                }
            }
            $index++;
        } while (isset($children[$index]));
        return $this->successMessage("Your menu items has been successfully sorted.", ADMIN_URL."menu");
    }

    /**
     * Remove a menu item
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function menuDelete(int $id=0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        Services::model()->menu()->remove($id);
        return $this->successMessage("Menu item has been successfully removed.", ADMIN_URL."menu");
    }

    /**
     * Languages management page
     * @throws \Exception
     */
    public function language()
    {
        $this->data['title'] = _l("Languages", $this);
        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title'])
        );
        $this->data['data_list']=Services::model()->languages()->getAll(null, null, 1, array('sort_order','asc'));
        $this->data['key_changes'] = Services::language()->currentLangLines();
        $this->data['page'] = "language";
        return $this->viewRender("language_sort");
    }

    /**
     * Language add and edit form
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function languageSubmit(int $id=0)
    {
        $this->data['title'] = _l("Languages", $this);
        if ($id != 0) {
            $current_data = Services::model()->languages()->getOne($id);
            if ($current_data==null || count($current_data)==0) {
                return $this->errorMessage("Language not found.", ADMIN_URL."language");
            }
            $form_attr = array();
            $this->data['sub_title'] = _l("Edit", $this);
        } else {
            $form_attr = array('data-redirect'=>1);
            $this->data['sub_title'] = _l("Add", $this);
        }

        $language_codes = (new Locales())->list;
        $language_codes['?'] = array('code'=>'?', 'rtl'=>0, 'name'=>'', 'title' => 'Other');
        $config = array(
            array(
                'field'=>"image",
                'label'=>_l("Flag", $this),
                'type'=>"image-library",
                'rules'=>"",
                'library_type'=>"lang",
                'default'=>isset($current_data['image']) ? $current_data['image'] : "",
            ),
            array(
                'field'=>"languages",
                'label'=>_l("Language", $this),
                'type'=>"select",
                'class'=>"select2me",
                'options'=>$language_codes,
                'option_name'=>"title",
                'option_value'=>"code",
                'rules'=>"required|in_list[".join(',', array_column($language_codes, 'code'))."]",
                'default'=>isset($current_data['code']) ? $current_data['code'] : "",
            ),
            array(
                'field'=>"language_title",
                'label'=>_l("Title", $this),
                'type'=>"text",
                'rules'=>"required",
                'default'=>isset($current_data['language_title']) ? $current_data['language_title'] : "",
            ),
            array(
                'field'=>"code",
                'label'=>_l("Language Code", $this),
                'type'=>"text",
                'rules'=>"required|exact_length[2]|alpha",
                'default'=>isset($current_data['code']) ? $current_data['code'] : "",
            ),
            array(
                'field'=>"rtl",
                'label'=>_l("Direction RTL", $this),
                'type'=>"switch",
                'rules'=>"required|in_list[0,1]",
                'default'=>isset($current_data['rtl']) ? $current_data['rtl'] : "",
            ),
            array(
                'field'=>"public",
                'label'=>_l("Public", $this),
                'type'=>"switch",
                'rules'=>"required|in_list[0,1]",
                'default'=>isset($current_data['public']) ? $current_data['public'] : "",
            ),
            array(
                'field'=>"default",
                'label'=>_l("Default", $this),
                'type'=>"switch",
                'rules'=>"required|in_list[0,1]",
                'default'=>isset($current_data['default']) ? $current_data['default'] : "",
            ),
        );

        $myform = new Form($this);
        $myform->config($config, ADMIN_URL."languageSubmit/{$id}", 'post', 'ajax');
        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }
            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }

            unset($data['languages']);
            if ($id != 0) {
                Services::model()->languages()->edit($id, $data);
            } else {
                $inserted_id = Services::model()->languages()->add($data);
            }

            $dir = COREPATH."Language/{$data['code']}/";
            // Make directory
            if (!file_exists($dir)) {
                mkdir($dir);
            }
            // Create the translation file.
            if (!file_exists($dir."app.php")) {
                copy(COREPATH . "Language/en/app.php", $dir . "app.php");
            }

            if ($id != 0) {
                return $this->successMessage("Your language has been successfully updated.", ADMIN_URL."language");
            } else {
                // Fix language translations
                Services::language()->resetLanguageTempFile($data['code']);
                return $this->successMessage("A new language was successfully added.", ADMIN_URL."language");
            }
        }

        $this->data['language_codes'] = $language_codes;
        $this->data['submit_form'] = $myform->fetch("", $form_attr);

        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title'], 'url'=>ADMIN_URL."language"),
            array('title'=>$this->data['sub_title'])
        );
        $this->data['page'] = "language";
        return $this->viewRender("language_submit");
    }

    /**
     * Language sort submit action
     */
    public function languageSortSubmit()
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }
        $post_data = Services::request()->getPost("data");
        if ($post_data == null) {
            return $this->errorMessage("Sort data shouldn't be empty.", ADMIN_URL."language");
        }
        $post_data = json_decode($post_data);
        foreach ($post_data as $i=>$item) {
            $update_data = array(
                'sort_order'=>$i,
            );
            Services::model()->languages()->edit($item->id, $update_data);
        }
        return $this->successMessage("Languages have been successfully sorted.", ADMIN_URL."language");
    }

    /**
     * Language remove action
     *
     * @param int $id
     * @param int $confirm
     * @throws \Exception
     */
    public function languageDelete($id, $confirm = 0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $current_data = Services::model()->languages()->getOne($id);
        if (count($current_data)==0) {
            return $this->errorMessage("Language not found!", ADMIN_URL."language");
        }

        $back_url = ADMIN_URL."language";
        $self_url = ADMIN_URL."languageDelete/$id";

        if ($confirm!=1) {
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">' .
                    str_replace("{data}", "<strong>$current_data[language_title]</strong>", _l("This action will delete the language '{data}' from database.", $this)) .' '.
                    _l("You can't restore it after this action.", $this) .
                    '</p>'.
                    '<p class="text-center bold">' .
                    _l("Are you sure to delete the file?", $this) .
                    '</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, delete it.", $this),
                'confirmUrl'=>$self_url."/1",
                'redirect'=>1,
            ));
        }

        Services::model()->languages()->remove($id);
        return $this->successMessage("Language has been deleted successfully.", $back_url);
    }

    /**
     * Edit languages translation files
     *
     * @param $id
     * @param $file_name
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function languageEditFile($id, $file_name)
    {
        $this->data['data']=Services::model()->languages()->getOne($id);
        if ($this->data['data']==null || !file_exists(SELF_PATH.'nodcms/language/'.$this->data['data']['language_name'].'/'.$file_name.'_lang.php')) {
            $this->session->set_flashdata('error', _l('URL-Request was not exists!', $this));
            return redirect(ADMIN_URL."language");
        }
        $this->load->library('Get_lang_in_array');
        $CI = new Get_lang_in_array();
        $this->data['lang_list'] = $CI->load($file_name, $this->data['data']['language_name']);
        if (count($this->data['lang_list'])==0) {
            $defaultLangFileName = strlen($file_name)==2 ? $_SESSION['language']['code'] : $file_name;
            $this->data['lang_list'] = $CI->load($defaultLangFileName, $_SESSION['language']['language_name']);
        }
        if (Services::request()->getPost('data')) {
            if ($this->session->userdata['group']==1) {
                $post_data = Services::request()->getPost('data');
                $i=0;
                $fileContent = "<?php\n";
                foreach ($this->data['lang_list'] as $key=>&$val) {
                    $label = str_replace('"', '&quot;', $post_data[$i]);
                    $fileContent .= '$lang["'.$key.'"] = "'.$label.'";'."\n";
                    $val = $post_data[$i];
                    $i++;
                }
                $file = SELF_PATH.'nodcms/language/'.$this->data['data']['language_name'].'/'.$file_name.'_lang.php';
                if (file_exists($file)) {
                    file_put_contents($file, $fileContent);
                }
                $this->session->set_flashdata('success', _l('Edit language file successfully!', $this));
                return redirect(ADMIN_URL."edit_lang_file/".$id.'/'.$file_name);
            } else {
                $this->session->set_flashdata('error', _l('This request is just fore real admin.', $this));
                return redirect(ADMIN_URL."language");
            }
        }
        $this->data['file_name'] = $file_name;
        $this->data['languages']=Services::model()->languages()->getAll();
        $this->data['title'] = _l("Edit language file", $this);
        $this->data['page'] = "edit lang file";
        return $this->viewRender("language_edit_file");
    }

    /**
     * Edit languages translation files
     *
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function languageTranslation($id)
    {
        include COREPATH."Language/lang_temp.php";
        if (!isset($lang_temp)) {
            return $this->errorMessage("lang_temp.php file not found.", ADMIN_URL."language");
        }
        $data = Services::model()->languages()->getOne($id);
        if (empty($data)) {
            return $this->errorMessage("The language was not found.", ADMIN_URL."language");
        }

        $file = COREPATH."Language/{$data['code']}/app.php";
        if (!file_exists($file)) {
            return $this->errorMessage("System couldn't find the language file.", ADMIN_URL."language");
        }

        $this->data['title'] = _l("Edit Translation File", $this);
        $this->data['sub_title'] = $data['language_name'];
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Languages", $this), 'url'=>ADMIN_URL."language"),
            array('title'=>$this->data['sub_title'])
        );

        $langArray = new GetLangAsArray();
        $this->data['lang_list'] = $langArray->load("app", $data['code']);
        $key = Services::request()->getPost('key');
        $value = Services::request()->getPost('value');
        if ($key != null) {
            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }

            // Update the translation line.
            Services::language()->updateLine($data['code'], $key, $value);

            return $this->successMessage("Edit language file successfully!", ADMIN_URL."languageTranslation/$id");
        }
        $this->data['data'] = $data;
        $this->data['file_path'] = $file;
        $this->data['languages'] = Services::model()->languages()->getAll();
        $this->data['title'] = _l("Edit Translation File", $this);
        $this->data['sub_title'] = $data['language_name'];
        $this->data['page'] = "language_translation";
        return $this->viewRender("language_edit_file");
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function languageUpdateTranslation()
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }
        Services::language()->currentLangLines(true, true);
        return $this->successMessage("The translation file successfully updated.", ADMIN_URL."language");
    }

    /**
     * Social links manager page
     *
     * @param int $page
     */
    public function socialLinks($page = 1)
    {
        $theList = new Ajaxlist();
        $config = array(
            'total_rows'=>0,
            'listID'=>"my-data-list",
            'headers'=>array(
                array(
                    'label'=>_l("Title", $this),
                    'content'=>"title",
                ),
                array(
                    'label'=>_l("Icon", $this),
                    'function'=>function ($_data) {
                        return "<i class='fab fa-{$_data['class']}'></i>";
                    }
                ),
                array(
                    'label'=>_l("URL", $this),
                    'function'=>function ($_data) {
                        return "<a target='_blank' href='{$_data['url']}'>{$_data['url']}</a>";
                    }
                ),
                array(
                    'label'=>"",
                    'theme'=>"edit_btn",
                    'url'=>ADMIN_URL.'socialLinksForm/$content',
                    'content'=>"id",
                ),
                array(
                    'label'=>"",
                    'theme'=>"delete_btn",
                    'url'=>ADMIN_URL.'socialLinksDelete/$content',
                    'content'=>"id",
                ),
            ),
            'ajaxURL'=>ADMIN_URL."socialLinks",
            'per_page'=>15,
            'page'=>$page,
        );
        $conditions = null;
        $config['total_rows'] = Services::model()->socialLinks()->getCount($conditions);
        $theList->setOptions($config);
        if (Services::request()->isAJAX()) {
            $data = Services::model()->socialLinks()->getAll($conditions, $config['per_page'], $config['page']);
            return $theList->ajaxData($data);
        }
        $this->data['title'] = _l("Social Links", $this);
        $this->data['sub_title'] = _l("List", $this);
        $this->data['data_list'] = Services::model()->socialLinks()->getAll();
        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title']),
        );
        $this->data['actions_buttons'] = array(
            'add' => ADMIN_URL."socialLinksForm",
        );
        $this->data['page'] = "social_links";
        $this->data['the_list'] = $theList->getPage();
        return $this->viewRender("data_list");
    }

    /**
     * Social links add and edit form
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function socialLinksForm($id = 0)
    {
        $back_url = ADMIN_URL."socialLinks";
        $self_url = ADMIN_URL."socialLinksForm";
        if ($id > 0) {
            $current_data = Services::model()->socialLinks()->getOne($id);
            if (!is_array($current_data) || count($current_data) == 0) {
                return $this->errorMessage("Social link not found.", $back_url);
            }
            $self_url .= "/$id";
            $this->data["sub_title"] = _l("Edit Item", $this);
            $isUniqueValidationRules = "|is_unique[social_links.class,id,$id]";
        } else {
            $this->data["sub_title"] = _l("Insert New Item", $this);
            $isUniqueValidationRules = '|is_unique[social_links.class]';
        }

        $social_types = array(
            array('title'=>"Amazon", 'class'=>'amazon'),
            array('title'=>"Behance", 'class'=>'behance'),
            array('title'=>"Blogger", 'class'=>'blogger'),
            array('title'=>"Deviantart", 'class'=>'deviantart'),
            array('title'=>"Dribbble", 'class'=>'dribbble'),
            array('title'=>"Dropbox", 'class'=>'dropbox'),
            array('title'=>"Facebook", 'class'=>'facebook'),
            array('title'=>"Forrst", 'class'=>'forrst'),
            array('title'=>"Github", 'class'=>'github'),
            array('title'=>"Googleplus", 'class'=>'googleplus'),
            array('title'=>"Jolicloud", 'class'=>'jolicloud'),
            array('title'=>"Last", 'class'=>'last-fm'),
            array('title'=>"Linkedin", 'class'=>'linkedin'),
            array('title'=>"Picasa", 'class'=>'picasa'),
            array('title'=>"Pintrest", 'class'=>'pintrest'),
            array('title'=>"Skype", 'class'=>'skype'),
            array('title'=>"Spotify", 'class'=>'spotify'),
            array('title'=>"Stumbleupon", 'class'=>'stumbleupon'),
            array('title'=>"Tumblr", 'class'=>'tumblr'),
            array('title'=>"Twitter", 'class'=>'twitter'),
            array('title'=>"Vimeo", 'class'=>'vimeo'),
            array('title'=>"Wordpress", 'class'=>'wordpress'),
            array('title'=>"Xing", 'class'=>'xing'),
            array('title'=>"Yahoo", 'class'=>'yahoo'),
            array('title'=>"Youtube", 'class'=>'youtube'),
            array('title'=>"VK", 'class'=>'vk'),
            array('title'=>"Instagram", 'class'=>'instagram'),
            array('title'=>"Reddit", 'class'=>'reddit'),
            array('title'=>"Aboutme", 'class'=>'aboutme'),
            array('title'=>"Flickr", 'class'=>'flickr'),
            array('title'=>"Foursquare", 'class'=>'foursquare'),
            array('title'=>"Gravatar", 'class'=>'gravatar'),
            array('title'=>"Klout", 'class'=>'klout'),
            array('title'=>"Myspace", 'class'=>'myspace'),
            array('title'=>"Quora", 'class'=>'quora'),
        );

        $config = array(
            array(
                'label' => _l('Type', $this),
                'field' => 'class',
                'type'=>'select',
                'options'=> $social_types,
                'option_name'=> "title",
                'option_value'=> "class",
                'rules' => 'required|in_list['.join(',', array_column($social_types, 'class')).']'.$isUniqueValidationRules,
                'default'=>isset($current_data) ? $current_data['class'] : ''
            ),
            array(
                'label' => _l('URL', $this),
                'field' => 'url',
                'type' => 'text',
                'rules' => 'required|valid_url',
                'default'=>isset($current_data) ? $current_data['url'] : ''
            ),
        );

        $myform = new Form($this);
        $myform->config($config, $self_url, 'post', 'ajax');
        if ($myform->ispost()) {
            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }

            $titles = array_combine(array_column($social_types, 'class'), array_column($social_types, 'title'));
            $data['title'] = $titles[$data['class']];
            if ($id > 0) {
                Services::model()->socialLinks()->edit($id, $data);
                return $this->successMessage("Social link has been updated.", $back_url);
            }
            Services::model()->socialLinks()->add($data);
            return $this->successMessage("Social link has been inserted.", $back_url);
        }

        $this->data['title'] = _l("Social Links", $this);
        $this->data['breadcrumb']=array(
            array('title'=>_l('Social Links', $this),'url'=>$back_url),
            array('title'=>$this->data['sub_title'])
        );
        $this->data['page'] = "social_links_edit";

        return $this->viewRenderString($myform->fetch('', array('data-redirect'=>1)));
    }

    /**
     * Remove a menu item
     *
     * @param int $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function socialLinksDelete($id, $confirm = 0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $current_data = Services::model()->socialLinks()->getOne($id);
        if (count($current_data)==0) {
            return $this->errorMessage("Link not found!", ADMIN_URL."user");
        }

        $back_url = ADMIN_URL."socialLinks";
        $self_url = ADMIN_URL."socialLinksDelete/$id";

        if ($confirm!=1) {
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">' .
                    str_replace("{data}", "<strong>$current_data[url]</strong>", _l("This action will delete the social link '{data}' from database.", $this)) .' '.
                    _l("This action cannot be restored!", $this) .
                    '</p>'.
                    '<p class="text-center bold">' .
                    _l("Are you sure to delete the file?", $this) .
                    '</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, delete it.", $this),
                'confirmUrl'=>$self_url."/1",
                'redirect'=>1,
            ));
        }

        Services::model()->socialLinks()->remove($id);
        return $this->successMessage("The social link has been deleted successfully.", $back_url);
    }

    /**
     * Site members list
     *
     * @param int $page
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     * @throws \Exception
     */
    public function user(int $page = 1)
    {
        $this->data['title'] = _l("Users", $this);
        $this->data['sub_title'] = _l("User's list", $this);
        $this->data['page'] = "users";
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $config = array();
        $config['base_url'] = ADMIN_URL.'user';
        $config['query_string_segment'] = '';
        $config['reuse_query_string'] = true;
        $config['total_rows'] = Services::model()->users()->getCount();
        $config['uri_segment'] = 3;
        $config['per_page'] = 10;
        $this->mkPagination($config);

        $this->data['data_list'] = Services::model()->users()->getAllWithGroups(null, $config['per_page'], $page);
        return $this->viewRender("user");
    }

    /**
     * A user profile
     *
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function userProfile($id)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $user = Services::model()->Users()->getOne($id);
        if (!is_array($user) || count($user)==0) {
            return $this->errorMessage("The user is not exists.", ADMIN_URL."user");
        }
        $user_group = Services::model()->Groups()->getOne($user['group_id']);
        if (!is_array($user) || count($user)==0) {
            $user['group_name'] = "undefined";
        } else {
            $user['group_name'] = $user_group['group_name'];
        }

        if ($user['avatar']=='') {
            $user['avatar'] = 'upload_file/images/user.png';
        }

        if ($user['language_id']!=-0) {
            $user_language = Services::model()->languages()->getOne($user['language_id']);
            if (count($user_language)!=0) {
                $user['language'] = $user_language;
            }
        }

        $this->data['data'] = $user;

        $this->data['uploaded_files_size'] = human_file_size(Services::model()->uploadFiles()->getSum("size", array('user_id'=>$user['user_id'])));

        $this->data['title'] = _l("Members", $this);
        $this->data['sub_title'] = _l("Profile", $this);
        $this->data['page'] = "user_profile";
        if (Services::request()->isAJAX()) {
            return json_encode(array(
                'status'=>'success',
                'content'=>$this->view->setData($this->data)->render("user_profile_ajax"),
                'title'=>$user['username'],
                'closeBtnLable'=>_l("Close", $this),
                'footerLinks'=>array(
                    array('color'=>"blue", 'url'=>ADMIN_URL."userProfile/$id", 'caption'=>_l("More info", $this)),
                    array('color'=>"blue", 'url'=>ADMIN_URL."userEdit/$id", 'caption'=>_l("Edit", $this)),
                ),
            ));
        }

        // Data from add-ons
        $this->packageHooks("userProfile", $user);

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Members", $this), 'url'=>ADMIN_URL."user/$id"),
            array('title'=>$this->data['sub_title'])
        );
        $this->data['breadcrumb_options'] = array(
            array('title'=>_l("Profile", $this),'url'=>ADMIN_URL."userProfile/$id",'active'=>1),
            array('title'=>_l("Edit", $this),'url'=>ADMIN_URL."userEdit/$id"),
        );
        return $this->viewRender("user_profile");
    }

    /**
     * List of uploaded files of a user
     *
     * @param $id
     * @param int $page
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function userUploadedFiles($id, $page = 1)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $user = Services::model()->users()->getOne($id);
        if (count($user)==0) {
            return $this->errorMessage("User not found!", ADMIN_URL."user");
        }

        $theList = new Ajaxlist();
        $config = array(
            'total_rows'=>0,
            'listID'=>"my-data-list",
            'headers'=>array(
                array(
                    'label'=>_l("File Name", $this),
                    'content'=>"name",
                ),
                array(
                    'label'=>_l("Size", $this),
                    'content'=>"size",
                    'function'=>"human_file_size"
                ),
                array(
                    'label'=>_l("Upload", $this),
                    'content'=>"created_date",
                    'function'=>"my_int_date"
                ),
                array(
                    'label'=>_l("Using", $this),
                    'theme'=>"check_icon",
                    'content'=>"file_using",
                ),
                array(
                    'label'=>"",
                    'theme'=>"ajax_open_btn",
                    'url'=>ADMIN_URL.'uploadedFile/$content',
                    'content'=>"file_id",
                ),
                array(
                    'label'=>"",
                    'theme'=>"delete_btn",
                    'url'=>ADMIN_URL.'uploadedFileDelete/$content',
                    'content'=>"file_id",
                ),
            ),
            'ajaxURL'=>ADMIN_URL."userUploadedFiles/$id",
            'ajaxData'=>array('record_result'=>1),
            'ajaxMethod'=>"post",
            'per_page'=>15,
            'page'=>$page,
        );
        $conditions = null;
        $config['total_rows'] = Services::model()->uploadFiles()->getCount($conditions);
        $theList->setOptions($config);
        if (Services::request()->getPost('record_result')==1) {
            $data = Services::model()->uploadFiles()->getAll($conditions, $config['per_page'], $config['page']);
            return $theList->ajaxData($data);
        }

        if (Services::request()->isAJAX()) {
            $this->data['the_list'] = $theList->getPage();
            return json_encode(array(
                'status'=>"success",
                'content'=> $this->view->setData($this->data)->render("data_list"),
            ));
        }

        $this->data['title'] = _l('Members', $this);
        $this->data['sub_title'] = _l('Uploaded files', $this);
        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title'], 'url'=>ADMIN_URL."user"),
            array('title'=>$user['username'], 'url'=>ADMIN_URL."userProfile/$user[user_id]"),
            array('title'=>$this->data['sub_title']));
        $this->data['the_list'] = $theList->getPage();
        $this->data['page'] = "user_uploaded_files";
        return $this->viewRender("data_list");
    }

    /**
     * Display details of an uploaded file
     *
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function uploadedFile($id)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $current_data = Services::model()->uploadFiles()->getOne($id);
        if (count($current_data)==0) {
            return $this->errorMessage("File not found!", ADMIN_URL."user");
        }
        $this->data['title'] = _l('An Uploaded file details ', $this);
        $this->data['data'] = $current_data;
        if ($current_data['user_id']!=0) {
            $this->data['user'] = Services::model()->users()->getOne($current_data['user_id']);
        }
        if (Services::request()->isAJAX()) {
            return json_encode(array(
                'status'=>"success",
                'title'=>$this->data['title'],
                'content'=> $this->view->setData($this->data)->render("uploaded_file"),
            ));
        }

        $this->data['page'] = "uploaded_file";
        return $this->viewRender("uploaded_file");
    }

    /**
     * Delete an uploaded file
     *
     * @param $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string|void
     * @throws \Exception
     */
    public function uploadedFileDelete($id, $confirm = 0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $current_data = Services::model()->uploadFiles()->getOne($id);
        if (count($current_data)==0) {
            return $this->errorMessage("File not found!", ADMIN_URL."user");
        }

        $back_url = ADMIN_URL."user";
        $self_url = ADMIN_URL."uploadedFileDelete/$id";

        if ($confirm!=1) {
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">' .
                    str_replace("{data}", "<strong>$current_data[name]</strong>", _l("This action will delete the file '{data}' from your host.", $this)) .' '.
                    _l("If it's using some where it can show an error there.", $this) .
                    '</p>'.
                    '<p class="text-center bold">' .
                    _l("Are you sure to delete the file?", $this) .
                    '</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, delete it.", $this),
                'confirmUrl'=>$self_url."/1",
                'redirect'=>1,
            ));
        }

        $myform = new Form($this);
        $myform->removeFiles($id);
        $this->successMessage("The file has been deleted successfully.", $back_url);
    }

    /**
     * Delete a user account
     *
     * @param $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function userDelete($id, $confirm = 0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $user = Services::model()->users()->getOne($id);
        if (count($user)==0) {
            return $this->errorMessage("User not found!", ADMIN_URL."user");
        }

        if ($user['avatar']=='') {
            $user['avatar'] = 'upload_file/images/user.png';
        }

        if ($user['user_id']==$this->userdata['user_id']) {
            return $this->errorMessage("You can't delete your account with yourself.", ADMIN_URL."user");
        }

        $back_url = ADMIN_URL."user";
        $self_url = ADMIN_URL."userDelete/$id";

        if ($confirm!=1) {
            return json_encode(array(
                'status'=>'success',
                'content'=>$this->view->setData($user)->render("user_delete_confirm"),
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>str_replace("{data}", $user['username'], _l("Yes, delete {data}'s account.", $this)),
                'confirmUrl'=>$self_url."/1",
                'redirect'=>1,
            ));
        }

        Services::model()->users()->remove($id);

        return $this->successMessage("The user account has been deleted successfully.", $back_url);
    }

    /**
     * Add or edit a user
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function userEdit(int $id = 0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }
        if ($id != 0) {
            $data = Services::model()->users()->getOne($id);
            if ($data==null) {
                $this->errorMessage("The user couldn't find.", ADMIN_URL."user");
            }
            $this->data['sub_title'] = _l("Edit a user", $this);
            $form_attr = array();
            $this->data['breadcrumb_options'] = array(
                array('title'=>_l("Profile", $this),'url'=>ADMIN_URL."userProfile/$id"),
                array('title'=>_l("Edit", $this),'url'=>ADMIN_URL."userEdit/$id",'active'=>1),
            );
        } else {
            $this->data['sub_title'] = _l("Add new user", $this);
            $form_attr = array('data-reset'=>1);
        }
        $config = array(
            array(
                'field' => 'language_id',
                'label' => _l("Language", $this),
                'rules' => 'required',
                'type' => "select",
                'options' => Services::model()->languages()->getAll(),
                'option_value'=>"language_id",
                'option_name'=>"language_name",
                'default'=>isset($data) ? $data["language_id"] : ''
            ),
            array(
                'field' => 'group_id',
                'label' => _l("Group", $this),
                'rules' => 'required',
                'type' => "select",
                'options' => Services::model()->groups()->getAll(),
                'option_value'=>"group_id",
                'option_name'=>"group_name",
                'default'=>isset($data) ? $data["group_id"] : ''
            ),
            array(
                'field' => 'email',
                'label' => _l("Email", $this),
                'rules' => 'required|valid_email|emailUnique'.(isset($data) ? '['.$data['user_id'].']' : ''),
                'type' => "email",
                'default'=>isset($data) ? $data["email"] : ''
            ),
            array(
                'field' => 'username',
                'label' => _l("Username", $this),
                'rules' => 'required|validateUsername'.(isset($data) ? '['.$data['user_id'].']' : ''),
                'type' => "text",
                'default'=>isset($data) ? $data["username"] : ''
            ),
            array(
                'field' => 'password',
                'label' => _l("Password", $this),
                'rules' => (isset($data) ? '' : 'required|').'formRulesPassword',
                'type' => "password",
                'default'=>''
            ),
            array(
                'field' => 'firstname',
                'label' => _l("First Name", $this),
                'rules' => 'formRulesName',
                'type' => "text",
                'default'=>isset($data) ? $data["firstname"] : ''
            ),
            array(
                'field' => 'lastname',
                'label' => _l("Last Name", $this),
                'rules' => 'formRulesName',
                'type' => "text",
                'default'=>isset($data) ? $data["lastname"] : ''
            ),
            array(
                'field' => 'mobile',
                'label' => _l("Phone Number", $this),
                'rules' => 'validPhone',
                'type' => "text",
                'default'=>isset($data) ? $data["mobile"] : ''
            ),
            array(
                'field' => 'website',
                'label' => _l("Website", $this),
                'rules' => 'valid_url',
                'type' => "url",
                'default'=>isset($data) ? $data["website"] : ''
            ),
            array(
                'field' => 'status',
                'label' => _l("Email confirmation", $this),
                'rules' => 'required|in_list[0,1]',
                'type' => "switch-check",
                'default'=>isset($data) ? $data["status"] : '',
                'help'=>_l("This option display user email confirmation status.", $this).
                    ' '._l("Also you can change the email confirmation for this account manually.", $this)
            ),
        );

        $myform = new Form($this);
        $myform->config($config, ADMIN_URL."userEdit/$id", 'post', 'ajax');
        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }
            $data["fullname"] = $data["firstname"]." ".$data["lastname"];
            if ($id != 0) {
                Services::model()->users()->edit($id, $data);
                return $this->successMessage("The users has successfully updated", ADMIN_URL . "userEdit/$id");
            }

            $newId = Services::model()->users()->add($data);
            return $this->successMessage("The users has successfully added", ADMIN_URL . "userEdit/$newId");
        }

        $this->data['breadcrumb']=array(
            array('title'=>_l('Members', $this),'url'=>ADMIN_URL.'user'),
            array('title'=>$this->data['sub_title'])
        );
        $this->data['title'] = _l("Members", $this);
        $this->data['page'] = "user";
        return $this->viewRenderString($myform->fetch('', $form_attr));
    }

    /**
     * Make a user active or de-active
     *
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function userDeactive($id)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $user = Services::model()->users()->getOne($id);
        if (count($user)==0) {
            return $this->errorMessage("User not found!", ADMIN_URL."user");
        }

        if ($user["user_id"] == $this->userdata["user_id"]) {
            return $this->errorMessage("You can't ban yourself.", ADMIN_URL."user");
        }

        // * Deactive the user
        if ($user["active"]==1) {
            Services::model()->users()->edit($user["user_id"], array('active'=>0));
            return $this->successMessage("The user has been successfully banned!", ADMIN_URL."user");
        }
        // * Active the user
        Services::model()->users()->edit($user["user_id"], array('active'=>1));
        return $this->successMessage("The user has been successfully activated!", ADMIN_URL."user");
    }

    /**
     * Load images library for ajax requests
     *
     * @param $input_id
     * @param null $type
     */
    public function getImagesLibrary($input_id, $type = null)
    {
        if ($type==null) {
            $type = "images-library";
        }
        if (!key_exists($type, $this->image_library_types)) {
            return json_encode(array(
                'status'=>"error",
                'error'=>_l("Library type is undefined.", $this)
            ));
        }
        $this->data["upload_url"] = ADMIN_URL."uploadImage/$type";
        $this->data['input_id'] = $input_id;
        $this->data['images'] = Services::model()->Images()->getAll(array('folder'=>$this->image_library_types[$type]['dir']), null, 1, array('image_id','DESC'));
        $data = array(
            'status'=>"success",
            'content'=>$this->view->setData($this->data)->render("images_library"),
            'title'=>_l("Image Library", $this),
            'closeBtnLable'=>_l("Close", $this),
        );
        return json_encode($data);
    }

    public function uploaded_images()
    {
        $this->data["data_list"] = Services::model()->images()->getAll();
        return $this->view->setData($this->data)->render("uploaded_images");
    }
    public function imagesLibrary()
    {
        $this->data["data_list"] = Services::model()->Images()->getAll(null, null, 1, array('image_id','DESC'));
        $this->data['upload_url'] = ADMIN_URL."uploadImage/images-library";
        $this->data['page'] = "uploaded_images";
        $this->data['title'] = _l("Images Library", $this);
        return $this->viewRender("uploaded_images_manager");
    }

    /**
     * @param int $id
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function imageDelete($id=0, $confirm = 0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $current_data = Services::model()->Images()->getOne($id);
        if (!is_array($current_data) || count($current_data)==0) {
            return $this->errorMessage("Image not found.", ADMIN_URL."imagesLibrary");
        }

        $back_url = ADMIN_URL."imagesLibrary";
        $self_url = ADMIN_URL."imageDelete/$id";

        if ($confirm!=1) {
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">' .
                    str_replace("{data}", "<strong>$current_data[name]</strong>", _l("This action will delete the image file '{data}' and record from database.", $this)) .' '.
                    _l("You can't restore it after this action.", $this) .
                    '</p>'.
                    '<p class="text-center bold">' .
                    _l("Are you sure to delete the file?", $this) .
                    '</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, delete it.", $this),
                'confirmUrl'=>$self_url."/1",
                'redirect'=>1,
            ));
        }

        if (file_exists(SELF_PATH.$current_data["image"])) {
            unlink(SELF_PATH.$current_data["image"]);
        }
        Services::model()->Images()->remove($id);
        return $this->successMessage("Language has been deleted successfully.", $back_url, array('removed'=>$id));
    }

    /**
     * Upload images on images library
     *
     * @param null|string $type
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function uploadImage(string $type=null)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }
        $types = $this->image_library_types;
        if (!key_exists($type, $types)) {
            return json_encode(array("status"=>"error","errors"=>str_replace("{data}", "'<strong>$type</strong>'", _l("The {data} upload type is undefined.", $this))));
        }

        $type_dir = $types[$type]['dir'];
        $dir = SELF_PATH."upload_file/$type_dir/";
        // Make directory
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        // Create index file
        $file = $dir.'index.php';
        if (!file_exists($file)) {
            $myfile = fopen($file, "w") or die("Unable to open file!");
            $txt = "<?php\n http_response_code(404); ";
            fwrite($myfile, $txt);
            fclose($myfile);
        }

        $inputFile = Services::request()->getFile('file');
        if (!$inputFile->isValid()) {
            return $this->errorMessage($inputFile->getErrorString(), ADMIN_URL."imagesLibrary");
        }

        $fileType = $inputFile->guessExtension();
        if (!in_array($fileType, ['jpg', 'gif', 'png'])) {
            return $this->errorMessage("The file type \"{$fileType}\" is not able to upload as image.", ADMIN_URL."imagesLibrary");
        }

        $clientName = $inputFile->getClientName();
        $newName = $inputFile->getRandomName();
        $fileSize = $inputFile->getSize();
        $uri = "upload_file/$type_dir/";
        if (! $inputFile->move(SELF_PATH.$uri, $newName)) {
            return $this->errorMessage($inputFile->getError(), ADMIN_URL."imagesLibrary");
        }

        $image = Services::image()->withFile(SELF_PATH.$uri.$newName);

        $data_image = array(
            "image"=>$uri.$newName,
            "width"=>$image->getWidth(),
            "height"=>$image->getHeight(),
            "name"=>$clientName,
            "root"=>SELF_PATH.$uri,
            "folder"=>$type_dir,
            "size"=>$fileSize,
            'user_id'=>$this->userdata['user_id']
        );
        $image_id = Services::model()->Images()->add($data_image);
        if ($image_id!=0) {
            return json_encode(array(
                "status" => "success",
                "file_patch" => $uri.$newName,
                "file_url" => base_url($uri.$newName),
                "width" => $image->getWidth(),
                "height" => $image->getHeight(),
                "image_id" => $image_id,
                "image_name" => $clientName,
                "size" => $fileSize));
        }
        unlink(SELF_PATH.$data_image["image"]);
        return json_encode(array("status"=>"error","errors"=>_l("Could not save images data in database.", $this)));
    }

    /**
     * Homepage settings
     */
    public function settingsHomepage()
    {
        $homepage_types = array(
            array(
                'title'=>_l("System default", $this),
                'key'=>"default",
                'attr'=>array(
                    'data-role'=>"toggle-hidden",
                    'data-target'=>".inputs_default"
                ),
            ),
            array(
                'title'=>_l("Redirect to", $this),
                'key'=>"redirect",
                'attr'=>array(
                    'data-role'=>"toggle-hidden",
                    'data-target'=>".inputs_redirect"
                ),
            ),
            array(
                'title'=>_l("Display file", $this),
                'key'=>"display_file",
                'attr'=>array(
                    'data-role'=>"toggle-hidden",
                    'data-target'=>".inputs_display_file"
                ),
            ),
            array(
                'title'=>_l("Display a web page", $this),
                'key'=>"display_page",
                'attr'=>array(
                    'data-role'=>"toggle-hidden",
                    'data-target'=>".inputs_display_page"
                ),
            ),
        );
        $unable_urls = array(base_url(),substr(base_url(), 0, -1));
        $languages = Services::model()->languages()->getAll();
        foreach ($languages as $language) {
            $unable_urls[] = base_url($language['code']);
            $unable_urls[] = base_url($language['code'])."/";
        }

        $title_box_classes = "inputs_default_title_box inputs_default inputs_custom_view";
        if (!$this->settings['home_page_title_box'] || !in_array($this->settings['homepage_type'], array("default", "custom_view"))) {
            $title_box_classes .= " hidden";
        }

        $config = array(
            array(
                'field'=>"homepage_type",
                'label'=>_l('One homepage', $this),
                'rules'=>"required|in_list[".join(',', array_column($homepage_types, "key"))."]",
                'type'=>"select-radio",
                'options'=>$homepage_types,
                'option_name'=>"title",
                'option_value'=>"key",
                'default'=>$this->settings['homepage_type'],
            ),
            array(
                'field'=>"home_page_title_box",
                'label'=>_l('Homepage title box', $this),
                'rules'=>"required|in_list[0,1]",
                'type'=>"checkbox",
                'default'=>$this->settings['home_page_title_box'],
                'group_class'=>"inputs_default inputs_custom_view ".(!in_array($this->settings['homepage_type'], array("default", "custom_view")) ? "hidden" : ""),
                'attr'=>array(
                    'data-role'=>"toggle-hidden",
                    'data-target'=>".inputs_default_title_box"
                ),
            ),
            array(
                'field'=>"index_logo",
                'label'=>_l('Homepage logo', $this),
                'rules'=>"",
                'type'=>"image-library",
                'default'=>$this->settings['index_logo'],
                'group_class'=>$title_box_classes,
            ),
            array(
                'field'=>"home_page_title_bg",
                'label'=>_l('Homepage background', $this),
                'rules'=>"",
                'type'=>"image-library",
                'default'=>$this->settings['home_page_title_bg'],
                'group_class'=>$title_box_classes,
            ),
            array(
                'field'=>"home_page_title_bg_blur",
                'label'=>_l('Homepage background blur', $this),
                'rules'=>"required|in_list[0,1]",
                'type'=>"switch",
                'default'=>$this->settings['home_page_title_bg_blur'],
                'group_class'=>$title_box_classes,
            ),
            array(
                'field'=>"homepage_redirect",
                'label'=>_l('Redirect URL', $this),
                'rules'=>"validateRequiredIf[homepage_type,redirect]|validateNotEqual[".join(',', $unable_urls)."]",
                'type'=>"text",
                'default'=>$this->settings['homepage_redirect'],
                'group_class'=>"inputs_redirect ".($this->settings['homepage_type']!="redirect" ? "hidden" : ""),
            ),
            array(
                'field'=>"homepage_display_file",
                'label'=>_l('File patch', $this),
                'rules'=>"validateRequiredIf[homepage_type,display_file]|validateFileExists[".SELF_PATH."]",
                'type'=>"text",
                'input_prefix'=>SELF_PATH,
                'default'=>$this->settings['homepage_display_file'],
                'group_class'=>"inputs_display_file ".($this->settings['homepage_type']!="display_file" ? "hidden" : ""),
            ),
            array(
                'field'=>"homepage_display_page",
                'label'=>_l('Page URL', $this),
                'rules'=>"validateRequiredIf[homepage_type,display_page]",
                'type'=>"text",
                'default'=>$this->settings['homepage_display_page'],
                'group_class'=>"inputs_display_page ".($this->settings['homepage_type']!="display_page" ? "hidden" : ""),
            ),
        );

        if (count($this->homepageSortedPackages()) > 0) {
            $config[] = array(
                'field'=>"homepage_sort",
                'label'=>_l('Home preview sort', $this),
                'type'=>"static",
                'value'=>'Loading',
                'tag'=>'div',
                'class'=>"bg-grey-cararra padding-10",
                'attr'=>array(
                    'style'=>"width:100%;padding:10px;",
                    'data-role'=>"auto-load",
                    'data-url'=>ADMIN_URL."settingsHomepageSort",
                ),
                'group_class'=>"inputs_default inputs_custom_view ".(!in_array($this->settings['homepage_type'], array("default", "custom_view")) ? "hidden" : ""),
            );
        }

        $seo_group_class = "inputs_default inputs_custom_view ".(in_array($this->settings['homepage_type'], array("default", "custom_view")) ? "" : "hidden");
        foreach ($languages as $language) {
            $setting = Services::model()->settings()->getSettings($language['language_id']);
            $language_head = array(
                'label'=>$language['language_title'],
                'type'=>"h4",
                'prefix_language'=>$language,
                'group_class'=>$seo_group_class,
            );
            array_push($config, $language_head);
            $prefix = "options[$language[language_id]]";
            array_push($config, array(
                'field'=>$prefix."[site_author]",
                'label'=>_l("Page author", $this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($setting['site_author']) ? $setting['site_author'] : '',
                'group_class'=>$seo_group_class,
            ));
            array_push($config, array(
                'field'=>$prefix."[site_title]",
                'label'=>_l("Page title", $this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($setting['site_title']) ? $setting['site_title'] : '',
                'group_class'=>$seo_group_class,
            ));
            array_push($config, array(
                'field'=>$prefix."[site_description]",
                'label'=>_l("Page description", $this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($setting['site_description']) ? $setting['site_description'] : '',
                'group_class'=>$seo_group_class,
            ));
            array_push($config, array(
                'field'=>$prefix."[site_keyword]",
                'label'=>_l("Page keywords", $this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($setting['site_keyword']) ? $setting['site_keyword'] : '',
                'group_class'=>$seo_group_class,
            ));
        }

        $myform = new Form($this);
        $myform->config($config, ADMIN_URL."settingsHomepage", 'post', 'ajax');
        // * Submit form
        if ($myform->ispost()) {
            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }
            // Options in all languages save
            if (isset($data["options"])) {
                foreach ($data["options"] as $language_id=>$item) {
                    Services::model()->settings()->updateSettings($item, $language_id);
                }
                unset($data["options"]);
            }
            // The settings without language_id
            Services::model()->settings()->updateSettings($data);
            return $this->successMessage("Your Setting has been updated successfully!", ADMIN_URL."settingsHomepage");
        }

        $this->data['page'] = "homepage_setting";
        $this->data['sub_title'] = _l("Homepage", $this);

        $this->data['title'] = _l('Settings', $this);
        $this->data['breadcrumb'] = array(array('title'=>$this->data['title']),
            array('title'=>$this->data['sub_title']));
        return $this->viewRenderString($this->view->setData($this->data)->render('homepage_sort_includes').$myform->fetch());
    }

    /**
     * Homepage sort
     */
    public function settingsHomepageSort()
    {
        // * Update Sort
        $post_data = Services::request()->getPost("data");
        if ($post_data != null) {
            if (!Services::identity()->isAdmin()) {
                return Services::identity()->getResponse();
            }
            $post_data = json_decode($post_data);
            foreach ($post_data as $i=>$item) {
                $update_data = array(
                    'package_sort'=>$i,
                );
                Services::model()->packages()->edit($item->id, $update_data);
            }
            return $this->successMessage("The packages has been successfully sorted.", ADMIN_URL."settingsHomepageSort");
        }

        $this->data['data_list'] = $this->homepageSortedPackages();

        $this->data['page'] = "settings_homepage_sort";
        $this->data['sub_title'] = _l("Sort homepage", $this);
        $this->data['title'] = _l('Settings', $this);

        if (Services::request()->isAJAX()) {
            return json_encode(array(
                'status'=>"success",
                'content'=>$this->view->setData($this->data)->render("homepage_sort"),
                'title'=>$this->data['sub_title'],
            ));
        }

        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title']),
            array('title'=>_l("Homepage", $this), 'url'=>ADMIN_URL."settingsHomepage"),
            array('title'=>$this->data['sub_title']));
        return $this->viewRender("homepage_sort");
    }

    /**
     * Toggle active status of a package for dashboard
     *
     * @param $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function packageToggleActive($id)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }
        $current_data = Services::model()->packagesDashboard()->getOne($id);
        if ($current_data==null || count($current_data)==0) {
            return $this->errorMessage("The package not found.", ADMIN_URL);
        }

        if ($current_data['active']==1) {
            Services::model()->packagesDashboard()->edit($id, array('active'=>0));
            return $this->successMessage("The package has been successfully deactivated.", ADMIN_URL);
        }
        Services::model()->packagesDashboard()->edit($id, array('active'=>1));
        return $this->successMessage("The package has been successfully activated.", ADMIN_URL);
    }

    /**
     * Toggle active status of a package for dashboard
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function settingsHomepageToggleActive(int $id)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }
        $current_data = Services::model()->packages()->getOne($id);
        if ($current_data==null || count($current_data)==0) {
            return $this->errorMessage("The package not found.", ADMIN_URL);
        }

        if ($current_data['active']==1) {
            Services::model()->packages()->edit($id, array('active'=>0));
            return $this->successMessage("The package has been successfully deactivated.", ADMIN_URL);
        }
        Services::model()->packages()->edit($id, array('active'=>1));
        return $this->successMessage("The package has been successfully activated.", ADMIN_URL);
    }

    /**
     * Return list of packages that are able to home preview
     *
     * @return array|mixed
     */
    private function homepageSortedPackages()
    {
        $result = [];
        // Get packages from DB
        $packages = Services::modules()->getAllInstalled();
        // Check packages home method exists.
        foreach ($packages as $key=>$item) {
            if ($item->hasHomePreview() && !empty($item->getData())) {
                $result[$key] = $item->getData();
            }
        }
        return $result;
    }

    /**
     * List of all modules
     *
     * @param int $page
     * @return string
     */
    public function modules($page = 1): string
    {
        $_packages = Services::modules()->getAllBootstraps();
        $activePackages = Services::modules()->getAllActiveNames();
        $packages = array();
        foreach ($_packages as $package_name=>$bootstrap) {
            $packages[] = array(
                'name'=>$package_name,
                'title'=>$bootstrap->title(),
                'description'=>$bootstrap->description(),
                'installed'=>intval(in_array($package_name, $activePackages)),
            );
        }

        $theList = new Ajaxlist();
        $config = array(
            'listID'=>"my-data-list",
            'headers'=>array(
                array(
                    'label'=>_l("Title", $this),
                    'content'=>"title",
                ),
                array(
                    'label'=>_l("Description", $this),
                    'content'=>"description",
                ),
                array(
                    'label'=>_l("Installed", $this),
                    'content'=>"installed",
                    'theme'=>"check_icon",
                ),
                array(
                    'label'=>"",
                    'function'=>function ($data) {
                        if ($data['installed']) {
                            return '<button type="button" onclick="$.loadConfirmModal(\''.ADMIN_URL."moduleUninstall/{$data['name']}".'\', function(){ location.reload() })" class="btn btn-sm btn-danger">'._l("Uninstall", $this).'</button>';
                        }
                        return '<button type="button" onclick="$.loadConfirmModal(\''.ADMIN_URL."moduleInstall/{$data['name']}".'\', function(){ location.reload() })" class="btn btn-sm btn-success">'._l("Install", $this).'</button>';
                    },
                    'url'=>ADMIN_URL.'module/$content',
                    'content'=>"name",
                ),
            ),
            'type'=>"static",
            'per_page'=>15,
            'data'=>$packages,
            'total_rows'=>count($packages),
            'page'=>$page,
        );

        if (count(Services::modules()->getAllInstalled()) > 0) {
            $this->data['actions_buttons'] = [
                'all-uninstall'=>ADMIN_URL."moduleUninstallAll",
            ];
        }

        $conditions = null;
        $theList->setOptions($config);
        $this->data['title'] = _l("Social Links", $this);
        $this->data['sub_title'] = _l("List", $this);
        $this->data['data_list'] = Services::model()->socialLinks()->getAll();
        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title']),
        );
        $this->data['page'] = "modules";
        $this->data['the_list'] = $theList->getPage();
        return $this->viewRender("data_list");
    }

    public function module($package)
    {
        $back_url = ADMIN_URL."modules";
        if (!Services::modules()->packageExists($package)) {
            return $this->errorMessage("Module not found.", $back_url);
        }
        $current_data = Services::model()->packages()->getOne(null, array('package_name'=>$package));
        if (is_array($current_data) && count($current_data) > 0) {
            $this->data['data'] = $current_data;
        }

        $this->data['tables'] = Services::modules()->getTablesInfo($package);

        $this->data['package'] = $package;
        $this->data['title'] = _l("Module", $this);
        $this->data['sub_title'] = $package;
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Modules", $this))
        );
        $this->data['page'] = "module";
        return $this->viewRender("module");
    }

    /**
     * Install a module/package
     *
     * @param $name
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function moduleInstall($name, $confirm = 0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $back_url = ADMIN_URL."modules";

        if (!in_array($name, Services::modules()->getNames())) {
            return $this->errorMessage("Module not found.", $back_url);
        }
        $current_data = Services::model()->packages()->getOne(null, array('package_name'=>$name));
        if (is_array($current_data) && count($current_data)==0) {
            return $this->errorMessage("Module has been installed before.", $back_url);
        }

        $self_url = ADMIN_URL."moduleInstall/$name";

        $tables = Services::modules()->getTablesInfo($name);
        if ($confirm!=1) {
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-left">' .
                    str_replace("{data}", "<strong>$name</strong>", _l("This action will create or repair bellow database tables that required to run module {data}.", $this)) .
                    '</p>'.
                    '<ul class="text-left">'.
                    '<li>'.join('</li><li>', array_column($tables, 'table')).'</li>'.
                    '</ul>'.
                    '<p class="text-center font-weight-bold alert alert-warning">' .
                    str_replace("{data}", "\"$name\"", _l("Are you sure to install {data} module?", $this)) .
                    '</p>',
                'title'=>_l("Install confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, Install", $this),
                'confirmUrl'=>$self_url."/1",
                'redirect'=>1,
            ));
        }

        foreach ($tables as $item) {
            if (!$item['exists']) {
                if (!$item['model']->installTable()) {
                    return $this->errorMessage("There is some error in module table install.", $back_url);
                }
                continue;
            }
            if (!$item['model']->repairTable()) {
                return $this->errorMessage("There is some error in module table repair.", $back_url);
            }
        }

        $max = Services::model()->packages()->getMax('package_sort');
        Services::model()->packages()->add(array('package_name'=>$name, 'package_sort'=>$max+1, 'active'=>1));

        return $this->successMessage("The module has been successfully installed.", $back_url);
    }

    /**
     * Uninstall a module/package
     *
     * @param $name
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function moduleUninstall($name, $confirm = 0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $back_url = ADMIN_URL."modules";

        $current_data = Services::model()->packages()->getOne(null, array('package_name'=>$name));
        if (!is_array($current_data) || count($current_data)==0) {
            return $this->errorMessage("Module not found.", $back_url);
        }

        $self_url = ADMIN_URL."moduleUninstall/$name";

        $tables = Services::modules()->getTablesInfo($name);
        if ($confirm!=1) {
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-left">' .
                    str_replace("{data}", "<strong>$name</strong>", _l("This action only make the module {data} de-active.", $this)) .
                    ' '._l("Database tables of this module wouldn't remove.", $this).
                    ' '._l("If you want to remove them, you should remove the bellow tables manually from your database.", $this).
                    '</p>'.
                    '<ul class="text-left">'.
                    '<li>'.join('</li><li>', array_column($tables, 'table')).'</li>'.
                    '</ul>'.
                    '<p class="text-center font-weight-bold alert alert-warning">' .
                    str_replace("{data}", "\"$name\"", _l("Are you sure to uninstall {data} module?", $this)) .
                    '</p>',
                'title'=>_l("Uninstall confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, uninstall!", $this),
                'confirmUrl'=>$self_url."/1",
                'redirect'=>1,
            ));
        }

        // Remove all records with the current name!
        Services::model()->packages()->clean(array('package_name'=>$name));

        return $this->successMessage("The module has been successfully uninstalled.", $back_url);
    }

    /**
     * Uninstall all modules/packages
     *
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function moduleUninstallAll(int $confirm = 0)
    {
        if (!Services::identity()->isAdmin()) {
            return Services::identity()->getResponse();
        }

        $back_url = ADMIN_URL."modules";
        $self_url = ADMIN_URL."moduleUninstallAll";
        $installedModules = Services::modules()->getAllInstalled();
        $tables = [];
        foreach ($installedModules as $name=>$item) {
            $tables = array_merge($tables, Services::modules()->getTablesInfo($name));
        }

        if ($confirm!=1) {
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-left">' .
                    _l("This action only make all modules de-active.", $this) .
                    ' '._l("Database tables of these modules wouldn't remove.", $this).
                    ' '._l("If you want to remove them, you should remove the bellow tables manually from your database.", $this).
                    '</p>'.
                    '<ul class="text-left">'.
                    '<li>'.join('</li><li>', array_column($tables, 'table')).'</li>'.
                    '</ul>'.
                    '<p class="text-center font-weight-bold alert alert-warning">' .
                    _l("Are you sure to uninstall all modules?", $this) .
                    '</p>',
                'title'=>_l("Uninstall confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, uninstall ALL", $this),
                'confirmUrl'=>$self_url."/1",
                'redirect'=>1,
            ));
        }

        // De-Active all modules
        Services::model()->packages()->clean();
        return $this->successMessage("The module has been successfully uninstalled.", $back_url);
    }

    /**
     * Get the list of a module/package tables
     *
     * @param $package
     * @return array
     */
    private function getModuleTables($package)
    {
        $tables = array();
        $models_paths = get_all_php_files(APPPATH."third_party/$package/models/");
        if ($models_paths) {
            foreach ($models_paths as $model_path) {
                include_once $model_path;
                $model_name = basename($model_path, ".php");
                $theModel = new $model_name();
                if (!method_exists($theModel, 'tableName')) {
                    continue;
                }

                $tables[] = array(
                    'path' => $model_path,
                    'table' => $theModel->tableName(),
                    'exists' => $theModel->tableExists(),
                    'model' => $theModel,
                );
            }
        }
        return $tables;
    }
}
