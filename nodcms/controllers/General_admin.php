<?php
/**
 * Created by PhpStorm.
 * User: Mojtaba
 * Date: 9/16/2015
 * Time: 1:03 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class General_admin extends NodCMS_Controller{

    private $image_library_types = array(
        'logo'=>array(
            'dir'=>"logo",
            'encrypt_name'=>false,
        ),
        'language'=>array(
            'dir'=>"lang",
            'encrypt_name'=>false,
        ),
        'images-library'=>array(
            'dir'=>"images-library",
            'encrypt_name'=>true,
        ),
    );

    function __construct(){
//        Load NodCMS_Controller construct
        parent::__construct("backend");
    }

    function dashboard()
    {
        // * Update Sort
        $post_data = $this->input->post("data");
        if($post_data != null){
            if(!$this->checkAccessGroup(1))
                return;
            foreach($post_data as $i=>$item){
                $update_data = array(
                    'package_sort'=>$i,
                );
                $this->Packages_dashboard_model->edit($item, $update_data);
            }
            $this->systemSuccess("The packages has been successfully sorted.", ADMIN_URL);
            return;
        }
        $this->dashboardPackageSortReset();
        $packages = $this->Packages_dashboard_model->getAll(null, null, 1, array('package_sort', 'ASC'));
        $this->data['dashboards'] = array();
        foreach ($packages as $item){
            $name = strtolower($item['package_name']);
            $curl =  $this->curlJSON(base_url()."admin-$name/dashboard", null, 0, SSL_PROTOCOL, true);
            if($curl['status']!="success" || $curl['content'] == ""){
                continue;
            }
            $item['content'] = $curl['content'];
            $this->data['dashboards'][] = $item;
        }

        $auto_emails = $this->config->item('autoEmailMessages');
        // Load auto messages from packages directories
        $packages = $this->load->packageList();
        foreach ($packages as $item){
            $package_auto_emails = $this->config->item($item.'_autoEmailMessages');
            if(is_array($package_auto_emails))
                $auto_emails = array_merge($auto_emails, $package_auto_emails);
        }
        $missed = 0;
        $languages = $this->Languages_model->getAll();
        foreach ($auto_emails as $key=>$item){
            foreach($languages as $language){
                $filled = $this->Email_messages_model->getCount(array('code_key'=>$key, 'language_id'=>$language['language_id']));
                if($filled==0){
                    $missed++;
                }
            }
            if($missed>0){
                $this->data['auto_email_messages_badge'] = $missed;
            }
        }

        $this->data['title'] = _l("Dashboard", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Dashboard", $this))
        );
        $this->data['keyword'] = "";
        $this->data['page'] = "dashboard";
        $this->data['content'] = $this->load->view($this->mainTemplate."/dashboards",$this->data, true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * System basic settings
     *
     * @param string $sub_page
     */
    function settings($sub_page='general')
    {
        $forms = array(
            'general'=>array(
                'notes'=>array(),
                'page'=>"setting",
                'sub_title'=>_l('General settings',$this),
                'config'=>array(
                    array(
                        'type'=>"h3",
                        'label'=>_l("Basic info", $this),
                    ),
                    array(
                        'field'=>"company",
                        'label'=>_l('Company Name',$this),
                        'rules'=>"required",
                        'type'=>"text",
                        'default'=>$this->settings['company'],
                    ),
                    array(
                        'field'=>"logo",
                        'label'=>_l('Logo',$this),
                        'rules'=>"",
                        'type'=>"image-library",
                        'default'=>$this->settings['logo'],
                    ),
                    array(
                        'field'=>"logo_light",
                        'label'=>_l("Logo light",$this),
                        'rules'=>"",
                        'type'=>"image-library",
                        'default'=>$this->settings['logo_light'],
                        'help'=>_l("Logo on dark background like admin side.", $this),
                    ),
                    array(
                        'field'=>"fav_icon",
                        'label'=>_l('Fav Icon',$this),
                        'rules'=>"",
                        'type'=>"image-library",
                        'default'=>$this->settings['fav_icon'],
                    ),
                )
            ),
            'contact'=>array(
                'notes'=>array(),
                'page'=>"contact_setting",
                'sub_title'=>_l('Contact Info',$this),
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
                        'rules'=>"callback_validPhone",
                        'type'=>"text",
                        'default'=>$this->settings['phone'],
                    ),
                    array(
                        'field'=>"fax",
                        'label'=>_l("Fax number", $this),
                        'rules'=>"callback_validPhone",
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
                        'rules'=>"callback_validGoogleMapIframe",
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
                'sub_title'=>_l('Date and time settings',$this),
                'config'=>array(
                    array(
                        'field'=>"timezone",
                        'label'=>_l('Timezone',$this),
                        'rules'=>"",
                        'type'=>"select-array",
                        'default'=>$this->settings['timezone'],
                        'options'=>DateTimeZone::listIdentifiers(),
                        'class'=>"select2me",
                    ),
                    array(
                        'field'=>"date_format",
                        'label'=>_l('Date Format',$this),
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
                        'label'=>_l('Time Format',$this),
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
                        'label'=>_l('Email',$this),
                        'rules'=>"",
                        'type'=>"email",
                        'default'=>$this->settings['send_email'],
                        'help'=>_l("All system message will send from this email address.", $this),
                    ),
                    array(
                        'field'=>"use_smtp",
                        'label'=>_l('Use SMTP',$this),
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
                        'label'=>_l('SMTP host name',$this),
                        'rules'=>"",
                        'type'=>"text",
                        'default'=>$this->settings['smtp_host'],
                        'group_class'=>"smtp_options ".($this->settings['use_smtp']!=1?'hidden':''),
                    ),
                    array(
                        'field'=>"smtp_port",
                        'label'=>_l('SMTP port',$this),
                        'rules'=>"",
                        'type'=>"number",
                        'default'=>$this->settings['smtp_port'],
                        'group_class'=>"smtp_options ".($this->settings['use_smtp']!=1?'hidden':''),
                    ),
                    array(
                        'field'=>"smtp_username",
                        'label'=>_l('SMTP username',$this),
                        'rules'=>"",
                        'type'=>"text",
                        'default'=>$this->settings['smtp_username'],
                        'group_class'=>"smtp_options ".($this->settings['use_smtp']!=1?'hidden':''),
                    ),
                    array(
                        'field'=>"smtp_password",
                        'label'=>_l('SMTP password',$this),
                        'rules'=>"",
                        'type'=>"text",
                        'default'=>$this->settings['smtp_password'],
                        'group_class'=>"smtp_options ".($this->settings['use_smtp']!=1?'hidden':''),
                    ),
                )
            ),
            'advance'=>array(
                'notes'=>array(),
                'page'=>"advance_setting",
                'sub_title'=>_l('Advance settings',$this),
                'config'=>array(
                    array(
                        'field'=>"google_captcha_site_key",
                        'label'=>_l('Google invisible reCaptcha API site key',$this),
                        'rules'=>"",
                        'type'=>"password",
                        'attr'=>array(
                            'placeholder'=>isset($this->settings['google_captcha_site_key'])?substr($this->settings['google_captcha_site_key'],0,3).'***'.substr($this->settings['google_captcha_site_key'],-3,3):'',
                        ),
                    ),
                    array(
                        'field'=>"google_captcha_secret_key",
                        'label'=>_l('Google invisible reCaptcha API secret key',$this),
                        'rules'=>"",
                        'type'=>"password",
                        'attr'=>array(
                            'placeholder'=>isset($this->settings['google_captcha_secret_key'])?substr($this->settings['google_captcha_secret_key'],0,3).'***'.substr($this->settings['google_captcha_secret_key'],-3,3):'',
                        ),
                    ),
                    array(
                        'field'=>"google_captcha_active",
                        'label'=>_l('Google captcha',$this),
                        'rules'=>"required|in_list[0,1]",
                        'type'=>"switch",
                        'default'=>isset($this->settings['google_captcha_active'])?$this->settings['google_captcha_active']:'',
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
                        'label' => _l('Registration',$this),
                        'rules' => 'required|in_list[0,1]',
                        'type' => "switch",
                        'default'=>$this->settings["registration"],
                        'help'=>_l("This option lets any one to make an account in your system.", $this)
                    ),
                    array(
                        'field' => 'add_on_header',
                        'label' => _l('Add on scripts at header',$this),
                        'rules' => '',
                        'type' => "textarea",
                        'default'=>isset($this->settings["add_on_header"])?$this->settings["add_on_header"]:"",
                        'help'=>_l("If you would like to use some script such as statistics or analytics tool on your website, put it here.", $this).
                            '<br>'._l("This code would be in head tag in all pages even backend pages.", $this)
                    ),
                    array(
                        'field' => 'add_on_script',
                        'label' => _l('Add on scripts at footer',$this),
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
                            "<a target='_blank' href='".base_url().$this->language['code']."/terms-and-conditions'>Accept T&C</a> and " .
                            "<a target='_blank' href='".base_url().$this->language['code']."/privacy-policy'>Privacy Policy</a> required</strong> " .
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
                'sub_title'=>_l('T&C, Privacy Policy',$this),
                'config'=>array(
                    array(
                        'field'=>"terms_accept_required",
                        'label'=>_l('Accept T&C and Privacy Policy required',$this),
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

        $languages = $this->Languages_model->getAll();
        foreach($languages as $language){
            $setting = $this->Public_model->getSettings($language['language_id']);
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
                    'class'=>"tcpp_options".($this->settings['terms_accept_required']!=1?" hidden":""),
                ),
                array(
                    'label'=> _l('Terms & Conditions Page',$this),
                    'type'=>"h3",
                    'class' => "tcpp_options".($this->settings['terms_accept_required']!=1?" hidden":""),
                ),
                array(
                    'field'=>$prefix."[terms_and_conditions_title]",
                    'label' => _l("Page title", $this),
                    'rules' => "",
                    'type' => "text",
                    'group_class' => "tcpp_options".($this->settings['terms_accept_required']!=1?" hidden":""),
                    'default'=>isset($setting['terms_and_conditions_title'])?$setting['terms_and_conditions_title']:'',
                ),
                array(
                    'field'=>$prefix."[terms_and_conditions_content]",
                    'label' => _l("Page content",$this),
                    'rules' => "",
                    'type' => "texteditor",
                    'group_class' => "tcpp_options".($this->settings['terms_accept_required']!=1?" hidden":""),
                    'default'=>isset($setting['terms_and_conditions_content'])?$setting['terms_and_conditions_content']:'',
                ),
                array(
                    'label'=>_l("Privacy Policy Page", $this),
                    'type'=>"h3",
                    'class' => "tcpp_options".($this->settings['terms_accept_required']!=1?" hidden":""),
                ),
                array(
                    'field'=>$prefix."[privacy_policy_title]",
                    'label' => _l("Page title", $this),
                    'rules' => "",
                    'type' => "text",
                    'group_class' => "tcpp_options".($this->settings['terms_accept_required']!=1?" hidden":""),
                    'default'=>isset($setting['privacy_policy_title'])?$setting['privacy_policy_title']:'',
                ),
                array(
                    'field'=>$prefix."[privacy_policy_content]",
                    'label' => _l("Page content",$this),
                    'rules' => "",
                    'type' => "texteditor",
                    'group_class' => "tcpp_options".($this->settings['terms_accept_required']!=1?" hidden":""),
                    'default'=>isset($setting['privacy_policy_content'])?$setting['privacy_policy_content']:'',
                )
            ));
        }

        if(!key_exists($sub_page, $forms)){
            $this->systemError("Page not found.", ADMIN_URL.'settings');
            return;
        }

        $myform = new Form();
        $myform->config($forms[$sub_page]['config'], ADMIN_URL."settings/$sub_page", 'post', 'ajax', $forms[$sub_page]['notes']);
        // * Submit form
        if($myform->ispost()){
            $this->checkAccessGroup(1);
            $data = $myform->getPost();
            // Stop Page
            if(!is_array($data) || count($data)==0 || $data == null){
                return;
            }
            // Email templates and messages save
            if(isset($data["auto_messages"])){
                $auto_emails = $this->config->item('autoEmailMessages');
                // Load auto messages from packages directories
                $packages = $this->load->packageList();
                foreach ($packages as $item){
                    $package_auto_emails = $this->config->item($item.'_autoEmailMessages');
                    if(is_array($package_auto_emails))
                        $auto_emails = array_merge($auto_emails, $package_auto_emails);
                }
                foreach($data["auto_messages"] as $language_id=>$value){
                    foreach($auto_emails as $code_key=>$msg_val){
                        $item = $value[$code_key];
                        $message = $this->Email_messages_model->getOne(null, array('code_key'=>$code_key, 'language_id'=>$language_id));
                        if($message!=null){
                            $this->Email_messages_model->edit($message['msg_id'], $item);
                        }
                        // Add new message
                        else{
                            $item['language_id'] = $language_id;
                            $item['code_key'] = $code_key;
                            $this->Email_messages_model->add($item);
                        }
                    }
                }
                unset($data["auto_messages"]);
            }
            // Options in all languages save
            if(isset($data["options"])){
                foreach($data["options"] as $language_id=>$item){
                    if(!$this->Nodcms_admin_model->updateSettings($item, $language_id)){
                        $this->systemError("A settings options could not be saved.", $this);
                        return;
                    }
                }
                unset($data["options"]);
            }
            if(isset($data['google_captcha_site_key']) && $data['google_captcha_site_key']==""){
                unset($data['google_captcha_site_key']);
            }
            if(isset($data['google_captcha_secret_key']) && $data['google_captcha_secret_key']==""){
                unset($data['google_captcha_secret_key']);
            }
            if(isset($data['google_map'])){
                $matches = array();
                preg_match('/(.+)src="([^\s]+)"(.+)/i', $data['google_map'], $matches);
                $data['google_map_url'] = $matches[2];
            }else{
                $data['google_map_url'] = "";
            }
            // The settings without language_id
            $this->Nodcms_admin_model->updateSettings($data);
            $this->systemSuccess("Your Setting has been updated successfully!", ADMIN_URL."settings/$sub_page");
            return;
        }

        $this->data['page'] = $forms[$sub_page]['page'];
        $this->data['sub_title'] = $forms[$sub_page]['sub_title'];

        //Set auto messages properties
        if($sub_page=='mail'){
            $this->data['auto_emails'] = $this->config->item('autoEmailMessages');
            // Load auto messages from packages directories
            $packages = $this->load->packageList();
            foreach ($packages as $item){
                $package_auto_emails = $this->config->item($item.'_autoEmailMessages');
                if(is_array($package_auto_emails))
                    $this->data['auto_emails'] = array_merge($this->data['auto_emails'], $package_auto_emails);
            }

            $auto_messages_data = array();
            foreach($this->data['auto_emails'] as $language_id=>$val){
                $autoMsgData = $this->Email_messages_model->getAll(array('language_id'=>$language_id));
                foreach($autoMsgData as $value){
                    $auto_messages_data[$value["language_id"]][$language_id] = $value;
                }
            }
            $this->data['auto_messages_data'] = $auto_messages_data;
        }

        $this->data['title'] = _l('Settings',$this);
        $this->data['breadcrumb'] = array(array('title'=>$this->data['title']),
            array('title'=>$this->data['sub_title']));
        $this->data['content'] = $myform->fetch();
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Automatic email text management
     */
    function automaticEmailTexts()
    {
        $this->data['page'] = "emails_texts";
        $auto_emails = $this->config->item('autoEmailMessages');
        // Load auto messages from packages directories
        $packages = $this->load->packageList();
        foreach ($packages as $item){
            $package_auto_emails = $this->config->item($item.'_autoEmailMessages');
            if(is_array($package_auto_emails))
                $auto_emails = array_merge($auto_emails, $package_auto_emails);
        }
        $keys = array_keys($auto_emails);
        $languages = $this->Languages_model->getCount();
        foreach ($auto_emails as $key=>&$item){
            $_missed = $this->Email_messages_model->getCount(array('code_key'=>$key));
            $item['form_url'] = ADMIN_URL."automaticEmailTextForm/$key";
            if($_missed<$languages){
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
        $this->data['content'] = $this->load->view($this->mainTemplate.'/auto_emails',$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Automatic email text submit form
     *
     * @param $email_key
     */
    function automaticEmailTextForm($email_key)
    {
        $self_url = ADMIN_URL."automaticEmailTextForm/$email_key";
        $back_url = ADMIN_URL."automaticEmailTexts";
        // Get auto messages keys from config
        $auto_emails = $this->config->item('autoEmailMessages');
        // Load auto messages from packages directories
        $packages = $this->load->packageList();
        foreach ($packages as $item){
            $package_auto_emails = $this->config->item($item.'_autoEmailMessages');
            if(is_array($package_auto_emails))
                $auto_emails = array_merge($auto_emails, $package_auto_emails);
        }
        if(!key_exists($email_key,$auto_emails)){
            $this->systemError("The email message isn't exists.", $back_url);
            return;
        }
        $languages = $this->Languages_model->getAll();
        // Form configuration
        $config = array(
            array(
                'type'=>'h2',
                'label'=>$auto_emails[$email_key]['label']
            ),
        );
        foreach ($languages as $item){
            // Get contents from database
            $autoMsgData = $this->Email_messages_model->getOne(null, array('code_key'=>$email_key, 'language_id'=>$item['language_id']));
            $config = array_merge($config, array(
                array(
                    'field'=>$email_key."[$item[language_id]][subject]",
                    'type'=>"text",
                    'rules'=>"",
                    'label'=>_l("Subject", $this),
                    'prefix_language'=>$item,
                    'default'=>$autoMsgData!=null?$autoMsgData['subject']:'',
                ),
                array(
                    'field'=>$email_key."[$item[language_id]][content]",
                    'type'=>"texteditor-quick",
                    'rules'=>"",
                    'label'=>_l("Content", $this),
                    'prefix_language'=>$item,
                    'default'=>$autoMsgData!=null?$autoMsgData['content']:'',
                    'shortkeys'=>$auto_emails[$email_key]['keys'],
                ),
            ));
        }
        $myform = new Form();
        $myform->config($config, $self_url, 'post', 'ajax');
        if($myform->ispost()) {
            $this->checkAccessGroup(1);
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return;
            }
            $language_ids = array_column($languages, 'language_id');
            foreach($data[$email_key] as $key=>$item){
                if(!in_array($key, $language_ids))
                    continue;
                // Edit message
                $message = $this->Email_messages_model->getOne(null, array('code_key'=>$email_key, 'language_id'=>$key));
                if($message!=null){
                    $this->Email_messages_model->edit($message['msg_id'], $item);
                }
                // Add new message
                else{
                    $item['language_id'] = $key;
                    $item['code_key'] = $email_key;
                    $this->Email_messages_model->add($item);
                }
            }
            // Options in all languages save
            $this->systemSuccess("The message has been edited successfully.", $back_url);
            return;
        }
        echo $myform->fetch('', array(), true);
    }

    /**
     * Menu manager page
     */
    function menu()
    {
        $this->data['title'] = _l("Menu Manager", $this);
        $this->data['sub_title'] = _l("Menu List", $this);

        $this->data['menu_types']=array(
            array('title'=>_l('Top menu',$this), 'key'=>"top_menu"),
            array('title'=>_l('Footer menu',$this), 'description'=>_l("This menu will display in the pre footer.", $this), 'key'=>"footer_menu"),
        );
        foreach($this->data['menu_types'] as &$val){
            $val['data_list'] = $this->Nodcms_admin_model->get_all_menu(array('menu_key'=>$val['key'], 'sub_menu'=>0));
            foreach($val['data_list'] as &$item){
                $item['sub_menu_data'] = $this->Nodcms_admin_model->get_all_menu(array('menu_key'=>$val['key'], 'sub_menu'=>$item['menu_id']));
            }
        }
        $this->data['breadcrumb']=array(
            array('title'=>_l('Menu',$this)),
        );
        $this->data['page'] = "menu";
        $this->data['content']=$this->load->view($this->mainTemplate.'/menu_manager',$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Menu add and edit form
     *
     * @param int $id
     */
    function menuForm($id=0, $menu_type)
    {
        if($id!=0)
        {
            $current_data = $this->Nodcms_admin_model->getMenuDetail($id);
            if(count($current_data)==0)
                $this->showError("The menu couldn't find.",ADMIN_URL."menuForm");
            $this->data["form_title"] = _l("Edit", $this);
        }else{
            $this->data["form_title"] = _l("Add", $this);
        }

        $languages = $this->Public_model->getAllLanguages();
        $config = array(
            array(
                'field'=>"menu_name",
                'label'=>_l("Title", $this),
                'rules'=>"required",
                'type'=>"text",
                'default'=>isset($current_data)?$current_data['menu_name']:'',
            ),
        );
        foreach($languages as $item){
            if(isset($current_data))
                $title = $this->Public_model->getTitle("menu",$current_data['menu_id'], $item['language_id']);
            $config[] = array(
                'field'=>"titles[$item[language_id]]",
                'label'=>_l("Title", $this),
                'rules'=>"required",
                'type'=>"text",
                'prefix_language'=>$item,
                'default'=>isset($title)?$title['title_caption']:'',
            );
        }

        $config[] = array(
            'field'=>"menu_url",
            'label'=>_l("System URL", $this),
            'rules'=>"",
            'type'=>"select-text",
            'options'=>$this->system_urls,
            'option_name'=>"title",
            'option_value'=>"url",
            'default'=>isset($current_data)?$current_data['menu_url']:'',
        );
        $config[] = array(
            'field'=>"menu_key",
            'label'=>_l("Menu type", $this),
            'rules'=>"required|in_list[top_menu,footer_menu]",
            'type'=>"hidden",
            'default'=>$menu_type,
        );
        $myform = new Form();
        $myform->config($config, ADMIN_URL."menuForm/$id/$menu_type", 'post', 'ajax');
        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }
            if(!$this->checkAccessGroup(1))
                return;
            $this->Nodcms_admin_model->menu_manipulate($data,$id);
            $this->systemSuccess("Menu has been successfully updated.", ADMIN_URL."menu");
            return;
        }

        if($this->input->is_ajax_request()){
            echo $myform->fetch('',array('data-redirect'=>1));
            return;
        }

        $this->data['title'] = _l("Menu Manager", $this);
        $this->data['breadcrumb']=array(
            array('title'=>_l('Menu',$this),'url'=>ADMIN_URL.'menu'),
            array('title'=>$this->data['sub_title'])
        );
        $this->data['page'] = "menu_edit";
        $this->data['content'] = $myform->fetch('',array('data-redirect'=>1));;
        $this->load->view($this->frameTemplate,$this->data);
    }

    function menuVisibility($id)
    {
        $data = $this->Nodcms_admin_model->getMenuDetail($id);
        if(count($data)==0){
            $this->systemError("Couldn't find the menu item.", ADMIN_URL."menu");
            return;
        }
        $public = $this->input->post('data');
        if($public == 1){
            $public = 0;
        }elseif($public == 0){
            $public = 1;
        }else{
            $this->systemError("Visibility value isn't correct. Please reload the page to solve this problem.", ADMIN_URL."menu");
            return;
        }
        $update_data = array(
            'public'=>$public
        );
        $this->Nodcms_admin_model->menu_manipulate($update_data, $id);
        $this->systemSuccess("Success", ADMIN_URL."menu");
    }

    function menuSort($menu_key='')
    {
        $this->checkAccessGroup(1);
        $i = 0;
        $index = 0;
        $sub_menu = array(0);
        $children = array($this->input->post('data',TRUE));
        $children[$index] = json_decode($children[$index]);
        do{
            $data = $children[$index];
            foreach($data as $key=>$item){
                $i++;
                $update_data = array(
                    'menu_order'=>$i,
                    'sub_menu'=>$sub_menu[$index],
                    'menu_key'=>$menu_key,
                );
                $this->Nodcms_admin_model->menu_manipulate($update_data, $item->id);
                if(isset($item->children)){
                    $sub_menu[$index+1] = $item->id;
                    $children[$index+1] = $item->children;
                }
            }
            $index++;
        }while(isset($children[$index]));
        $this->systemSuccess("Your menu items has been successfully sorted.", ADMIN_URL."menu");
    }

    /**
     * Menu add and edit post action
     *
     * @param null $id
     */
    function menu_manipulate($id=null)
    {
        $this->checkAccessGroup(1);
        if ($this->Nodcms_admin_model->menu_manipulate($this->input->post('data',TRUE),$id))
        {
            $this->session->set_flashdata('success', _l('Updated menu',$this));
        }
        else
        {
            $this->session->set_flashdata('error', _l('Updated menu error. Please try later',$this));
        }
        redirect(ADMIN_URL."menu");
    }

    /**
     * Remove a menu item
     *
     * @param int $id
     */
    function menuDelete($id=0)
    {
        $this->checkAccessGroup(1);
        $this->db->trans_start();
        $this->db->delete('menu', array('menu_id' => $id));
        $this->db->trans_complete();
        $this->systemSuccess("Menu item has been successfully removed.", ADMIN_URL."menu");
    }

    /**
     * Languages management page
     */
    function language()
    {
        $this->data['title'] = _l("Languages",$this);
        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title'])
        );
        $this->data['data_list']=$this->Languages_model->getAll(null,null,1,array('sort_order','asc'));
        $this->data['key_changes'] = findNewLangKeys($this);
        $this->data['page'] = "language";
        $this->data['content'] = $this->load->view($this->mainTemplate.'/language_sort',$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Language add and edit form
     *
     * @param string $id
     */
    function languageSubmit($id=null)
    {
        $this->data['title'] = _l("Languages",$this);
        if($id!=null)
        {
            $current_data = $this->Languages_model->getOne($id);
            if($current_data==null || count($current_data)==0){
                $this->systemError("Language not found.", ADMIN_URL."language");
                return;
            }
            $form_attr = array();
            $this->data['sub_title'] = _l("Edit",$this);
        }
        else{
            $form_attr = array('data-redirect'=>1);
            $this->data['sub_title'] = _l("Add",$this);
        }

        $language_codes = array(
            'en' => array('code'=>'en', 'rtl'=>0, 'name'=>'english', 'title' => 'English'),
//            'aa' => array('code'=>'aa', 'rtl'=>0, 'name'=>'Afar', 'title' => 'Afar'),
//            'ab' => array('code'=>'ab', 'rtl'=>0, 'name'=>'Abkhazian', 'title' => 'Abkhazian'),
//            'af' => array('code'=>'af', 'rtl'=>0, 'name'=>'Afrikaans', 'title' => 'Afrikaans'),
//            'am' => array('code'=>'am', 'rtl'=>0, 'name'=>'Amharic', 'title' => 'Amharic'),
            'ar' => array('code'=>'ar', 'rtl'=>1, 'name'=>'arabic', 'title' => 'Arabic'),
            'hy' => array('code'=>'hy', 'rtl'=>0, 'name'=>'armenian', 'title' => 'Armenian'),
//            'as' => array('code'=>'as', 'rtl'=>0, 'name'=>'Assamese', 'title' => 'Assamese'),
//            'ay' => array('code'=>'ay', 'rtl'=>0, 'name'=>'Aymara', 'title' => 'Aymara'),
            'az' => array('code'=>'az', 'rtl'=>0, 'name'=>'azerbaijani', 'title' => 'Azerbaijani'),
//            'ba' => array('code'=>'ba', 'rtl'=>0, 'name'=>'Bashkir', 'title' => 'Bashkir'),
//            'be' => array('code'=>'be', 'rtl'=>0, 'name'=>'Byelorussian', 'title' => 'Byelorussian'),
            'bg' => array('code'=>'bg', 'rtl'=>0, 'name'=>'bulgarian', 'title' => 'Bulgarian'),
//            'bh' => array('code'=>'bh', 'rtl'=>0, 'name'=>'Bihari', 'title' => 'Bihari'),
//            'bi' => array('code'=>'bi', 'rtl'=>0, 'name'=>'Bislama', 'title' => 'Bislama'),
            'bn' => array('code'=>'bn', 'rtl'=>0, 'name'=>'bengali', 'title' => 'Bengali/Bangla'),
//            'br' => array('code'=>'br', 'rtl'=>0, 'name'=>'Breton', 'title' => 'Breton'),
            'ca' => array('code'=>'ca', 'rtl'=>0, 'name'=>'catalan', 'title' => 'Catalan'),
//            'co' => array('code'=>'co', 'rtl'=>0, 'name'=>'Corsican', 'title' => 'Corsican'),
            'cs' => array('code'=>'cs', 'rtl'=>0, 'name'=>'czech', 'title' => 'Czech'),
            'nl' => array('code'=>'nl', 'rtl'=>0, 'name'=>'dutch', 'title' => 'Dutch'),
//            'bo' => array('code'=>'bo', 'rtl'=>0, 'name'=>'Tibetan', 'title' => 'Tibetan'),
//            'cy' => array('code'=>'cy', 'rtl'=>0, 'name'=>'Welsh', 'title' => 'Welsh'),
//            'da' => array('code'=>'da', 'rtl'=>0, 'name'=>'Danish', 'title' => 'Danish'),
            'de' => array('code'=>'de', 'rtl'=>0, 'name'=>'german', 'title' => 'German'),
//            'dz' => array('code'=>'dz', 'rtl'=>0, 'name'=>'Bhutani', 'title' => 'Bhutani'),
            'el' => array('code'=>'el', 'rtl'=>0, 'name'=>'greek', 'title' => 'Greek'),
//            'eo' => array('code'=>'eo', 'rtl'=>0, 'name'=>'Esperanto', 'title' => 'Esperanto'),
            'es' => array('code'=>'es', 'rtl'=>0, 'name'=>'spanish', 'title' => 'Spanish'),
//            'et' => array('code'=>'et', 'rtl'=>0, 'name'=>'Estonian', 'title' => 'Estonian'),
//            'eu' => array('code'=>'eu', 'rtl'=>0, 'name'=>'Basque', 'title' => 'Basque'),
            'fa' => array('code'=>'fa', 'rtl'=>1, 'name'=>'persian', 'title' => 'Persian'),
//            'fi' => array('code'=>'fi', 'rtl'=>0, 'name'=>'Finnish', 'title' => 'Finnish'),
//            'fj' => array('code'=>'fj', 'rtl'=>0, 'name'=>'Fiji', 'title' => 'Fiji'),
//            'fo' => array('code'=>'fo', 'rtl'=>0, 'name'=>'Faeroese', 'title' => 'Faeroese'),
            'fr' => array('code'=>'fr', 'rtl'=>0, 'name'=>'french', 'title' => 'French'),
//            'fy' => array('code'=>'fy', 'rtl'=>0, 'name'=>'Frisian', 'title' => 'Frisian'),
//            'ga' => array('code'=>'ga', 'rtl'=>0, 'name'=>'Irish', 'title' => 'Irish'),
//            'gd' => array('code'=>'gd', 'rtl'=>0, 'name'=>'Scots/Gaelic', 'title' => 'Scots/Gaelic'),
//            'gl' => array('code'=>'gl', 'rtl'=>0, 'name'=>'Galician', 'title' => 'Galician'),
//            'gn' => array('code'=>'gn', 'rtl'=>0, 'name'=>'Guarani', 'title' => 'Guarani'),
            'gu' => array('code'=>'gu', 'rtl'=>0, 'name'=>'gujarati', 'title' => 'Gujarati'),
//            'ha' => array('code'=>'ha', 'rtl'=>0, 'name'=>'Hausa', 'title' => 'Hausa'),
            'hi' => array('code'=>'hi', 'rtl'=>0, 'name'=>'hindi', 'title' => 'Hindi'),
//            'hr' => array('code'=>'hr', 'rtl'=>0, 'name'=>'Croatian', 'title' => 'Croatian'),
            'hu' => array('code'=>'hu', 'rtl'=>0, 'name'=>'hungarian', 'title' => 'Hungarian'),
//            'ia' => array('code'=>'ia', 'rtl'=>0, 'name'=>'Interlingua', 'title' => 'Interlingua'),
//            'ie' => array('code'=>'ie', 'rtl'=>0, 'name'=>'Interlingue', 'title' => 'Interlingue'),
//            'ik' => array('code'=>'ik', 'rtl'=>0, 'name'=>'Inupiak', 'title' => 'Inupiak'),
            'in' => array('code'=>'in', 'rtl'=>0, 'name'=>'indonesian', 'title' => 'Indonesian'),
//            'is' => array('code'=>'is', 'rtl'=>0, 'name'=>'Icelandic', 'title' => 'Icelandic'),
            'it' => array('code'=>'it', 'rtl'=>0, 'name'=>'italian', 'title' => 'Italian'),
//            'iw' => array('code'=>'iw', 'rtl'=>0, 'name'=>'Hebrew', 'title' => 'Hebrew'),
            'ja' => array('code'=>'ja', 'rtl'=>0, 'name'=>'japanese', 'title' => 'Japanese'),
//            'ji' => array('code'=>'ji', 'rtl'=>0, 'name'=>'Yiddish', 'title' => 'Yiddish'),
//            'jw' => array('code'=>'jw', 'rtl'=>0, 'name'=>'Javanese', 'title' => 'Javanese'),
//            'ka' => array('code'=>'ka', 'rtl'=>0, 'name'=>'Georgian', 'title' => 'Georgian'),
//            'kk' => array('code'=>'kk', 'rtl'=>0, 'name'=>'Kazakh', 'title' => 'Kazakh'),
//            'kl' => array('code'=>'kl', 'rtl'=>0, 'name'=>'Greenlandic', 'title' => 'Greenlandic'),
//            'km' => array('code'=>'km', 'rtl'=>0, 'name'=>'Cambodian', 'title' => 'Cambodian'),
//            'kn' => array('code'=>'kn', 'rtl'=>0, 'name'=>'Kannada', 'title' => 'Kannada'),
            'ko' => array('code'=>'ko', 'rtl'=>0, 'name'=>'korean', 'title' => 'Korean'),
//            'ks' => array('code'=>'ks', 'rtl'=>0, 'name'=>'Kashmiri', 'title' => 'Kashmiri'),
//            'ku' => array('code'=>'ku', 'rtl'=>0, 'name'=>'Kurdish', 'title' => 'Kurdish'),
//            'ky' => array('code'=>'ky', 'rtl'=>0, 'name'=>'Kirghiz', 'title' => 'Kirghiz'),
//            'la' => array('code'=>'la', 'rtl'=>0, 'name'=>'Latin', 'title' => 'Latin'),
//            'ln' => array('code'=>'ln', 'rtl'=>0, 'name'=>'Lingala', 'title' => 'Lingala'),
//            'lo' => array('code'=>'lo', 'rtl'=>0, 'name'=>'Laothian', 'title' => 'Laothian'),
            'lt' => array('code'=>'lt', 'rtl'=>0, 'name'=>'lithuanian', 'title' => 'Lithuanian'),
            'lv' => array('code'=>'lv', 'rtl'=>0, 'name'=>'latvian', 'title' => 'Latvian/Lettish'),
//            'mg' => array('code'=>'mg', 'rtl'=>0, 'name'=>'Malagasy', 'title' => 'Malagasy'),
//            'mi' => array('code'=>'mi', 'rtl'=>0, 'name'=>'Maori', 'title' => 'Maori'),
//            'mk' => array('code'=>'mk', 'rtl'=>0, 'name'=>'Macedonian', 'title' => 'Macedonian'),
//            'ml' => array('code'=>'ml', 'rtl'=>0, 'name'=>'Malayalam', 'title' => 'Malayalam'),
//            'mn' => array('code'=>'mn', 'rtl'=>0, 'name'=>'Mongolian', 'title' => 'Mongolian'),
//            'mo' => array('code'=>'mo', 'rtl'=>0, 'name'=>'Moldavian', 'title' => 'Moldavian'),
//            'mr' => array('code'=>'mr', 'rtl'=>0, 'name'=>'Marathi', 'title' => 'Marathi'),
//            'ms' => array('code'=>'ms', 'rtl'=>0, 'name'=>'Malay', 'title' => 'Malay'),
//            'mt' => array('code'=>'mt', 'rtl'=>0, 'name'=>'Maltese', 'title' => 'Maltese'),
//            'my' => array('code'=>'my', 'rtl'=>0, 'name'=>'Burmese', 'title' => 'Burmese'),
//            'na' => array('code'=>'na', 'rtl'=>0, 'name'=>'Nauru', 'title' => 'Nauru'),
//            'ne' => array('code'=>'ne', 'rtl'=>0, 'name'=>'Nepali', 'title' => 'Nepali'),
            'no' => array('code'=>'no', 'rtl'=>0, 'name'=>'norwegian', 'title' => 'Norwegian'),
//            'oc' => array('code'=>'oc', 'rtl'=>0, 'name'=>'Occitan', 'title' => 'Occitan'),
//            'om' => array('code'=>'om', 'rtl'=>0, 'name'=>'(Afan)/Oromoor/Oriya', 'title' => '(Afan)/Oromoor/Oriya'),
//            'pa' => array('code'=>'pa', 'rtl'=>0, 'name'=>'Punjabi', 'title' => 'Punjabi'),
            'pl' => array('code'=>'pl', 'rtl'=>0, 'name'=>'polish', 'title' => 'Polish'),
//            'ps' => array('code'=>'ps', 'rtl'=>0, 'name'=>'Pashto/Pushto', 'title' => 'Pashto/Pushto'),
            'pt' => array('code'=>'pt', 'rtl'=>0, 'name'=>'portuguese', 'title' => 'Portuguese'),
//            'qu' => array('code'=>'qu', 'rtl'=>0, 'name'=>'Quechua', 'title' => 'Quechua'),
//            'rm' => array('code'=>'rm', 'rtl'=>0, 'name'=>'Rhaeto-Romance', 'title' => 'Rhaeto-Romance'),
//            'rn' => array('code'=>'rn', 'rtl'=>0, 'name'=>'Kirundi', 'title' => 'Kirundi'),
            'ro' => array('code'=>'ro', 'rtl'=>0, 'name'=>'romanian', 'title' => 'Romanian'),
            'ru' => array('code'=>'ru', 'rtl'=>0, 'name'=>'russian', 'title' => 'Russian'),
//            'rw' => array('code'=>'rw', 'rtl'=>0, 'name'=>'Kinyarwanda', 'title' => 'Kinyarwanda'),
            'sa' => array('code'=>'sa', 'rtl'=>0, 'name'=>'Sanskrit', 'title' => 'Sanskrit'),
//            'sd' => array('code'=>'sd', 'rtl'=>0, 'name'=>'Sindhi', 'title' => 'Sindhi'),
//            'sg' => array('code'=>'sg', 'rtl'=>0, 'name'=>'Sangro', 'title' => 'Sangro'),
//            'sh' => array('code'=>'sh', 'rtl'=>0, 'name'=>'Serbo-Croatian', 'title' => 'Serbo-Croatian'),
//            'si' => array('code'=>'si', 'rtl'=>0, 'name'=>'Singhalese', 'title' => 'Singhalese'),
            'sk' => array('code'=>'sk', 'rtl'=>0, 'name'=>'slovak', 'title' => 'Slovak'),
            'sl' => array('code'=>'sl', 'rtl'=>0, 'name'=>'slovenian', 'title' => 'Slovenian'),
//            'sm' => array('code'=>'sm', 'rtl'=>0, 'name'=>'Samoan', 'title' => 'Samoan'),
//            'sn' => array('code'=>'sn', 'rtl'=>0, 'name'=>'Shona', 'title' => 'Shona'),
//            'so' => array('code'=>'so', 'rtl'=>0, 'name'=>'Somali', 'title' => 'Somali'),
//            'sq' => array('code'=>'sq', 'rtl'=>0, 'name'=>'Albanian', 'title' => 'Albanian'),
            'sr' => array('code'=>'sr', 'rtl'=>0, 'name'=>'serbian', 'title' => 'Serbian'),
//            'ss' => array('code'=>'ss', 'rtl'=>0, 'name'=>'Siswati', 'title' => 'Siswati'),
//            'st' => array('code'=>'st', 'rtl'=>0, 'name'=>'Sesotho', 'title' => 'Sesotho'),
//            'su' => array('code'=>'su', 'rtl'=>0, 'name'=>'Sundanese', 'title' => 'Sundanese'),
            'sv' => array('code'=>'sv', 'rtl'=>0, 'name'=>'swedish', 'title' => 'Swedish'),
//            'sw' => array('code'=>'sw', 'rtl'=>0, 'name'=>'Swahili', 'title' => 'Swahili'),
            'ta' => array('code'=>'ta', 'rtl'=>0, 'name'=>'tamil', 'title' => 'Tamil'),
//            'te' => array('code'=>'te', 'rtl'=>0, 'name'=>'Tegulu', 'title' => 'Tegulu'),
//            'tg' => array('code'=>'tg', 'rtl'=>0, 'name'=>'Tajik', 'title' => 'Tajik'),
//            'th' => array('code'=>'th', 'rtl'=>0, 'name'=>'Thai', 'title' => 'Thai'),
//            'ti' => array('code'=>'ti', 'rtl'=>0, 'name'=>'Tigrinya', 'title' => 'Tigrinya'),
//            'tk' => array('code'=>'tk', 'rtl'=>0, 'name'=>'Turkmen', 'title' => 'Turkmen'),
//            'tl' => array('code'=>'tl', 'rtl'=>0, 'name'=>'Tagalog', 'title' => 'Tagalog'),
//            'tn' => array('code'=>'tn', 'rtl'=>0, 'name'=>'Setswana', 'title' => 'Setswana'),
//            'to' => array('code'=>'to', 'rtl'=>0, 'name'=>'Tonga', 'title' => 'Tonga'),
            'tr' => array('code'=>'tr', 'rtl'=>0, 'name'=>'turkish', 'title' => 'Turkish'),
//            'ts' => array('code'=>'ts', 'rtl'=>0, 'name'=>'Tsonga', 'title' => 'Tsonga'),
//            'tt' => array('code'=>'tt', 'rtl'=>0, 'name'=>'Tatar', 'title' => 'Tatar'),
//            'tw' => array('code'=>'tw', 'rtl'=>0, 'name'=>'Twi', 'title' => 'Twi'),
            'uk' => array('code'=>'uk', 'rtl'=>0, 'name'=>'ukrainian', 'title' => 'Ukrainian'),
            'ur' => array('code'=>'ur', 'rtl'=>1, 'name'=>'urdu', 'title' => 'Urdu'),
//            'uz' => array('code'=>'uz', 'rtl'=>0, 'name'=>'Uzbek', 'title' => 'Uzbek'),
            'vi' => array('code'=>'vi', 'rtl'=>0, 'name'=>'vietnamese', 'title' => 'Vietnamese'),
//            'vo' => array('code'=>'vo', 'rtl'=>0, 'name'=>'Volapuk', 'title' => 'Volapuk'),
//            'wo' => array('code'=>'wo', 'rtl'=>0, 'name'=>'Wolof', 'title' => 'Wolof'),
//            'xh' => array('code'=>'xh', 'rtl'=>0, 'name'=>'Xhosa', 'title' => 'Xhosa'),
//            'yo' => array('code'=>'yo', 'rtl'=>0, 'name'=>'Yoruba', 'title' => 'Yoruba'),
            'zh' => array('code'=>'zh', 'rtl'=>0, 'name'=>'simpleified-chinese', 'title' => 'Chinese'),
//            'zu' => array('code'=>'zu', 'rtl'=>0, 'name'=>'Zulu', 'title' => 'Zulu'),
            '?' => array('code'=>'?', 'rtl'=>0, 'name'=>'', 'title' => 'Other'),
        );
        $config = array(
            array(
                'field'=>"image",
                'label'=>_l("Flag", $this),
                'type'=>"image-library",
                'rules'=>"",
                'library_type'=>"lang",
                'default'=>isset($current_data['image'])?$current_data['image']:"",
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
                'default'=>isset($current_data['code'])?$current_data['code']:"",
            ),
            array(
                'field'=>"language_title",
                'label'=>_l("Title", $this),
                'type'=>"text",
                'rules'=>"required",
                'default'=>isset($current_data['language_title'])?$current_data['language_title']:"",
            ),
            array(
                'field'=>"language_name",
                'label'=>_l("Directory Name", $this),
                'type'=>"text",
                'rules'=>"required|callback_validateUsernameType",
                'default'=>isset($current_data['language_name'])?$current_data['language_name']:"",
            ),
            array(
                'field'=>"code",
                'label'=>_l("Language Code", $this),
                'type'=>"text",
                'rules'=>"required|exact_length[2]|alpha",
                'default'=>isset($current_data['code'])?$current_data['code']:"",
            ),
            array(
                'field'=>"rtl",
                'label'=>_l("Direction RTL", $this),
                'type'=>"switch",
                'rules'=>"required|in_list[0,1]",
                'default'=>isset($current_data['rtl'])?$current_data['rtl']:"",
            ),
            array(
                'field'=>"public",
                'label'=>_l("Public", $this),
                'type'=>"switch",
                'rules'=>"required|in_list[0,1]",
                'default'=>isset($current_data['public'])?$current_data['public']:"",
            ),
            array(
                'field'=>"default",
                'label'=>_l("Default", $this),
                'type'=>"switch",
                'rules'=>"required|in_list[0,1]",
                'default'=>isset($current_data['default'])?$current_data['default']:"",
            ),
        );

        $myform = new Form();
        $myform->config($config, ADMIN_URL."languageSubmit/$id", 'post', 'ajax');
        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false || !is_array($data) || count($data)==0 || $data == null){
                return;
            }
            if(!$this->checkAccessGroup(1))
                return;

            unset($data['languages']);
            if($id != null){
                $this->Languages_model->edit($id, $data);
            }else{
                $inserted_id = $this->Languages_model->add($data);
            }

            $dir = APPPATH."language/$data[language_name]/";
            // Make directory
            if(!file_exists($dir)){
                mkdir($dir);
            }
            // Create index file
            $file = $dir.'index.php';
            if(!file_exists($file)){
                $myfile = fopen($file, "w") or die("Unable to open file!");
                $txt = "<?php\n http_response_code(404)";
                fwrite($myfile, $txt);
                fclose($myfile);
            }

            if($id!=null){
                $this->systemSuccess("Your language has been successfully updated.", ADMIN_URL."language");
            }else{
                // Fix language translations
                resetLanguageTempFile($data['language_name']);

                $this->systemSuccess("A new language was successfully added.", ADMIN_URL."language");
            }
        }

        $this->data['language_codes'] = $language_codes;
        $this->data['submit_form'] = $myform->fetch("", $form_attr);

        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title'], 'url'=>ADMIN_URL."language"),
            array('title'=>$this->data['sub_title'])
        );
        $this->data['page'] = "language";
        $this->data['content'] = $this->load->view($this->mainTemplate."/language_submit", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    /**
     * Language sort submit action
     */
    function languageSortSubmit()
    {
        if(!$this->checkAccessGroup(1))
            return;
        $post_data = $this->input->post("data");
        if($post_data == null) {
            $this->systemError("Sort data shouldn't be empty.", ADMIN_URL."language");
            return;
        }
        $post_data = json_decode($post_data);
        foreach($post_data as $i=>$item){
            $update_data = array(
                'sort_order'=>$i,
            );
            $this->Languages_model->edit($item->id, $update_data);
        }
        $this->systemSuccess("Languages have been successfully sorted.", ADMIN_URL."language");
    }

    /**
     * Language remove action
     *
     * @param int $id
     * @param int $confirm
     */
    function languageDelete($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $current_data = $this->Languages_model->getOne($id);
        if(count($current_data)==0){
            $this->systemError("Language not found!", ADMIN_URL."language");
            return;
        }

        $back_url = ADMIN_URL."language";
        $self_url = ADMIN_URL."languageDelete/$id";

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">' .
                    str_replace("{data}", "<strong>$current_data[language_title]</strong>",_l("This action will delete the language '{data}' from database.", $this)) .' '.
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
            return;
        }

        $this->Languages_model->remove($id);
        $this->systemSuccess("Language has been deleted successfully.", $back_url);
    }

    /**
     * Edit languages translation files
     *
     * @param $id
     * @param $file_name
     */
    function languageEditFile($id,$file_name)
    {
        $this->data['data']=$this->Nodcms_admin_model->get_language_detail($id);
        if($this->data['data']==null || !file_exists(getcwd().'/nodcms/language/'.$this->data['data']['language_name'].'/'.$file_name.'_lang.php')){
            $this->session->set_flashdata('error', _l('URL-Request was not exists!',$this));
            redirect(base_url()."admin/language");
        }
        $this->load->library('Get_lang_in_array');
        $CI = new Get_lang_in_array();
        $this->data['lang_list'] = $CI->load($file_name,$this->data['data']['language_name']);
        if(count($this->data['lang_list'])==0){
            $defaultLangFileName = strlen($file_name)==2?$_SESSION['language']['code']:$file_name;
            $this->data['lang_list'] = $CI->load($defaultLangFileName,$_SESSION['language']['language_name']);
        }
        if($this->input->input_stream('data')){
            if ($this->session->userdata['group']==1) {
                $post_data = $this->input->post('data');
                $i=0;
                $fileContent = "<?php\n";
                foreach ($this->data['lang_list'] as $key=>&$val) {
                    $label = str_replace('"', '&quot;', $post_data[$i]);
                    $fileContent .= '$lang["'.$key.'"] = "'.$label.'";'."\n";
                    $val = $post_data[$i];
                    $i++;
                }
                $file = getcwd().'/nodcms/language/'.$this->data['data']['language_name'].'/'.$file_name.'_lang.php';
                if(file_exists($file)){
                    file_put_contents($file, $fileContent);
                }
                $this->session->set_flashdata('success', _l('Edit language file successfully!',$this));
                redirect(base_url()."admin/edit_lang_file/".$id.'/'.$file_name);
            }else{
                $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
                redirect(ADMIN_URL."language");
            }
        }
        $this->data['file_name'] = $file_name;
        $this->data['languages']=$this->Nodcms_admin_model->get_all_language();
        $this->data['title'] = _l("Edit language file",$this);
        $this->data['page'] = "edit lang file";
        $this->data['content']=$this->load->view($this->mainTemplate.'/language_edit_file',$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Edit languages translation files
     *
     * @param $id
     */
    function languageTranslation($id)
    {
        include APPPATH."language/lang_temp.php";
        if(!isset($lang_temp)){
            $this->systemError("lang_temp.php file not found.", ADMIN_URL."language");
            return;
        }
        $this->data['data'] = $this->Nodcms_admin_model->get_language_detail($id);
        if($this->data['data']==null){
            $this->systemError("The language was not found.", ADMIN_URL."language");
            return;
        }

        $this->data['title'] = _l("Edit Translation File",$this);
        $this->data['sub_title'] = $this->data['data']['language_name'];
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Languages", $this), 'url'=>ADMIN_URL."language"),
            array('title'=>$this->data['sub_title'])
        );

        $this->load->library('Get_lang_in_array');
        $CI = new Get_lang_in_array();
        $this->data['lang_list'] = $CI->load("nodcms", $this->data['data']['language_name']);
        if($this->input->input_stream('key')){
            if(!$this->checkAccessGroup(1))
                return;
            $key = $this->input->post('key');
            $value = $this->input->post('value');

            $file = getcwd().'/nodcms/language/'.$this->data['data']['language_name'].'/nodcms_lang.php';
            if(!file_exists($file)){
                $this->systemError("System couldn't find the language file.", ADMIN_URL."language");
            }
            $key_pattern = str_replace(array(
                '?',
                '!',
                '%',
                '$',
                '/',
                '(',
                ')',
                '-',
                '_',
                '[',
                ']',
                '{',
                '}',
                '@',
                '|',
                ':',
                ';',
                '#',
                "'",
                '.',
                ',',
            ),array(
                '\?',
                '\!',
                '\%',
                '\$',
                '\/',
                '\(',
                '\)',
                '\-',
                '\_',
                '\[',
                '\]',
                '\{',
                '\}',
                '\@',
                '\|',
                '\:',
                '\;',
                '\#',
                "\'",
                '\.',
                '\,',
            ), $key);
            $fileContent = file_get_contents($file);
            $pattern = '/\$lang\[\"'.$key_pattern.'\"\][\s]?\=[\s]\"(.*)\"\;/';
            $replace = '$lang["'.$key.'"] = "'.str_replace('"',"''", $value).'";';
            $fileContent = preg_replace($pattern, $replace, $fileContent);
            file_put_contents($file, $fileContent);
            $this->systemSuccess("Edit language file successfully!", ADMIN_URL."languageTranslation/$id");
            return;
        }
        $this->data['languages'] = $this->Nodcms_admin_model->get_all_language();
        $this->data['title'] = _l("Edit Translation File",$this);
        $this->data['sub_title'] = $this->data['data']['language_name'];
        $this->data['page'] = "language_translation";
        $this->data['content']=$this->load->view($this->mainTemplate.'/language_edit_file',$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    function languageUpdateTranslation()
    {
        if(!$this->checkAccessGroup(1))
            return;
        findNewLangKeys($this, 1, 1);
        $this->systemSuccess("The translation file successfully updated.", ADMIN_URL."language");
    }

    /**
     * Social links manager page
     *
     * @param int $page
     */
    function socialLinks($page = 1)
    {
        $this->load->library("Ajaxlist");
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
                    'function'=>function($_data) {
                        return "<i class='fab fa-{$_data['class']}'></i>";
                    }
                ),
                array(
                    'label'=>_l("URL", $this),
                    'function'=>function($_data) {
                        return "<a target='_blank' href='{$_data['url']}'>{$_data['url']}</a>";
                    }
                ),
                array(
                    'label'=>"",
                    'theme'=>"open_btn",
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
        $config['total_rows'] = $this->Social_links_model->getCount($conditions);
        $theList->setOptions($config);
        if($this->input->is_ajax_request()){
            $data = $this->Social_links_model->getAll($conditions, $config['per_page'], $config['page']);
            echo $theList->ajaxData($data);
            return;
        }
        $this->data['title'] = _l("Social Links", $this);
        $this->data['sub_title'] = _l("List", $this);
        $this->data['data_list'] = $this->Nodcms_admin_model->getSocialLinks();
        $this->data['breadcrumb']=array(
            array('title'=>$this->data['title']),
        );
        $this->data['actions_buttons'] = array(
            'add' => ADMIN_URL."socialLinksForm",
        );
        $this->data['page'] = "social_links";
        $this->data['the_list'] = $theList->getPage();
        $this->data['content'] = $this->load->view($this->mainTemplate."/data_list",$this->data, true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Social links add and edit form
     *
     * @param int $id
     */
    function socialLinksForm($id = 0)
    {
        $back_url = ADMIN_URL."socialLinks";
        $self_url = ADMIN_URL."socialLinksForm";
        if($id > 0){
            $current_data = $this->Social_links_model->getOne($id);
            if(!is_array($current_data) || count($current_data) == 0){
                $this->systemError("Social link not found.", $back_url);
                return;
            }
            $self_url .= "/$id";
            $this->data["sub_title"] = _l("Edit Item", $this);
            $isUniqueValidationRules = "|callback_isUnique[social_links,class,id,$id]";
        }else{
            $this->data["sub_title"] = _l("Insert New Item", $this);
            $isUniqueValidationRules = '|callback_isUnique[social_links,class]';
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
                'label' => _l('Type',$this),
                'field' => 'class',
                'type'=>'select',
                'options'=> $social_types,
                'option_name'=> "title",
                'option_value'=> "class",
                'rules' => 'required|in_list['.join(',', array_column($social_types, 'class')).']'.$isUniqueValidationRules,
                'default'=>isset($current_data)?$current_data['class']:''
            ),
            array(
                'label' => _l('URL',$this),
                'field' => 'url',
                'type' => 'text',
                'rules' => 'required|valid_url',
                'default'=>isset($current_data)?$current_data['url']:''
            ),
        );

        $myform = new Form();
        $myform->config($config, $self_url, 'post', 'ajax');
        if($myform->ispost()){
            if(!$this->checkAccessGroup(1))
                return;
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }

            $titles = array_combine(array_column($social_types, 'class'), array_column($social_types, 'title'));
            $data['title'] = $titles[$data['class']];
            if ($id > 0) {
                $this->Social_links_model->edit($id, $data);
                $this->systemSuccess("Social link has been updated.", $back_url);
                return;
            }
            $this->Social_links_model->add($data);
            $this->systemSuccess("Social link has been inserted.", $back_url);
            return;
        }

        $this->data['title'] = _l("Social Links", $this);
        $this->data['breadcrumb']=array(
            array('title'=>_l('Social Links',$this),'url'=>$back_url),
            array('title'=>$this->data['sub_title'])
        );
        $this->data['page'] = "social_links_edit";

        $this->data['content'] = $myform->fetch('', array('data-redirect'=>1));
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Remove a menu item
     *
     * @param int $id
     * @param int $confirm
     */
    function socialLinksDelete($id, $confirm = 0)
    {if(!$this->checkAccessGroup(1))
        return;

        $current_data = $this->Social_links_model->getOne($id);
        if(count($current_data)==0){
            $this->systemError("Link not found!", ADMIN_URL."user");
            return;
        }

        $back_url = ADMIN_URL."socialLinks";
        $self_url = ADMIN_URL."socialLinksDelete/$id";

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">' .
                    str_replace("{data}", "<strong>$current_data[url]</strong>",_l("This action will delete the social link '{data}' from database.", $this)) .' '.
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
            return;
        }

        $this->Social_links_model->remove($id);
        $this->systemSuccess("The social link has been deleted successfully.", $back_url);
    }

    /**
     * Site members list
     *
     * @param int $page
     */
    function user($page = 1)
    {
        $this->data['title'] = _l("Users",$this);
        $this->data['sub_title'] = _l("User's list",$this);
        $this->data['page'] = "users";
        if(!$this->checkAccessGroup(1))
            return;
        $this->load->helper('date');

        $config = array();
        $config['base_url'] = ADMIN_URL.'user';
        $config['query_string_segment'] = '';
        $config['reuse_query_string'] = TRUE;
        $config['total_rows'] = $this->Nodcms_admin_model->countAllUser();
        $config['uri_segment'] = 3;
        $config['per_page'] = 10;
        $this->mkPagination($config);
        $page--;

        $this->data['data_list'] = $this->Nodcms_admin_model->getAllUser($page, $config['per_page']);
        $this->data['content']=$this->load->view($this->mainTemplate.'/user',$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * A user profile
     *
     * @param $id
     */
    function userProfile($id)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $user = $this->Users_model->getOne($id);
        if(!is_array($user) || count($user)==0){
            $this->systemError("The user is not exists.", ADMIN_URL."user");
            return;
        }
        $user_group = $this->Groups_model->getOne($user['group_id']);
        if(!is_array($user) || count($user)==0){
            $user['group_name'] = "undefined";
        }else{
            $user['group_name'] = $user_group['group_name'];
        }

        if($user['avatar']=='')
            $user['avatar'] = 'upload_file/images/user.png';

        if($user['language_id']!=-0){
            $user_language = $this->Public_model->getLanguage($user['language_id']);
            if(count($user_language)!=0){
                $user['language'] = $user_language;
            }
        }

        $this->data['data'] = $user;

        $this->data['uploaded_files_size'] = human_file_size($this->Nodcms_admin_model->getUploadedSize(array('user_id'=>$user['user_id'])));

        $this->data['title'] = _l("Members",$this);
        $this->data['sub_title'] = _l("Profile",$this);
        $this->data['page'] = "user_profile";
        if($this->input->is_ajax_request()){
            echo json_encode(array(
                'status'=>'success',
                'content'=>$this->load->view($this->mainTemplate.'/user_profile_ajax',$this->data, true),
                'title'=>$user['username'],
                'closeBtnLable'=>_l("Close", $this),
                'footerLinks'=>array(
                    array('color'=>"blue", 'url'=>ADMIN_URL."userProfile/$id", 'caption'=>_l("More info", $this)),
                    array('color'=>"blue", 'url'=>ADMIN_URL."userEdit/$id", 'caption'=>_l("Edit", $this)),
                ),
            ));
            return;
        }

        // Data from add-ons
        $this->load->packageHooks("userProfile", $user);

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Members", $this), 'url'=>ADMIN_URL."user/$id"),
            array('title'=>$this->data['sub_title'])
        );
        $this->data['breadcrumb_options'] = array(
            array('title'=>_l("Profile", $this),'url'=>ADMIN_URL."userProfile/$id",'active'=>1),
            array('title'=>_l("Edit", $this),'url'=>ADMIN_URL."userEdit/$id"),
        );
        $this->data['content'] = $this->load->view($this->mainTemplate.'/user_profile',$this->data, true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * List of uploaded files of a user
     *
     * @param $id
     * @param int $page
     */
    function userUploadedFiles($id, $page = 1)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $user = $this->Nodcms_admin_model->getUserDetail($id);
        if(count($user)==0){
            $this->systemError("User not found!", ADMIN_URL."user");
            return;
        }

        $this->load->library("Ajaxlist");
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
                    'callback_function'=>"human_file_size"
                ),
                array(
                    'label'=>_l("Upload", $this),
                    'content'=>"created_date",
                    'callback_function'=>"my_int_date"
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
        $config['total_rows'] = $this->Nodcms_admin_model->getFilesCount($conditions);
        $theList->setOptions($config);
        if($this->input->post('record_result')==1){
            $data = $this->Nodcms_admin_model->getFiles($conditions, $config['per_page'], $config['page']);
            echo $theList->ajaxData($data);
            return;
        }

        if($this->input->is_ajax_request()){
            $this->data['the_list'] = $theList->getPage();
            echo json_encode(array(
                'status'=>"success",
                'content'=> $this->load->view($this->mainTemplate."/data_list",$this->data, true),
            ));
            return;
        }

        $this->data['title'] = _l('Members',$this);
        $this->data['sub_title'] = _l('Uploaded files',$this);
        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title'], 'url'=>ADMIN_URL."user"),
            array('title'=>$user['username'], 'url'=>ADMIN_URL."userProfile/$user[user_id]"),
            array('title'=>$this->data['sub_title']));
        $this->data['the_list'] = $theList->getPage();
        $this->data['content'] = $this->load->view($this->mainTemplate."/data_list",$this->data, true);
        $this->data['page'] = "user_uploaded_files";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Display details of an uploaded file
     *
     * @param $id
     */
    function uploadedFile($id)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $current_data = $this->Public_model->getFile($id);
        if(count($current_data)==0){
            $this->systemError("File not found!", ADMIN_URL."user");
            return;
        }
        $this->data['title'] = _l('An Uploaded file details ',$this);
        $this->data['data'] = $current_data;
        if($current_data['user_id']!=0){
            $this->data['user'] = $this->Public_model->getUserDetails($current_data['user_id']);
        }
        if($this->input->is_ajax_request()){
            echo json_encode(array(
                'status'=>"success",
                'title'=>$this->data['title'],
                'content'=> $this->load->view($this->mainTemplate."/uploaded_file",$this->data, true),
            ));
            return;
        }

        $this->data['content'] = $this->load->view($this->mainTemplate."/uploaded_file",$this->data, true);
        $this->data['page'] = "uploaded_file";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Delete an uploaded file
     *
     * @param $id
     * @param int $confirm
     */
    function uploadedFileDelete($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $current_data = $this->Public_model->getFile($id);
        if(count($current_data)==0){
            $this->systemError("File not found!", ADMIN_URL."user");
            return;
        }

        $back_url = ADMIN_URL."user";
        $self_url = ADMIN_URL."uploadedFileDelete/$id";

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">' .
                    str_replace("{data}", "<strong>$current_data[name]</strong>",_l("This action will delete the file '{data}' from your host.", $this)) .' '.
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
            return;
        }

        $myform = new Form();
        $myform->removeFiles($id);
        $this->systemSuccess("The file has been deleted successfully.", $back_url);
    }

    /**
     * Delete a user account
     *
     * @param $id
     * @param int $confirm
     */
    function userDelete($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $user = $this->Nodcms_admin_model->getUserDetail($id);
        if(count($user)==0){
            $this->systemError("User not found!", ADMIN_URL."user");
            return;
        }

        if($user['avatar']=='')
            $user['avatar'] = 'upload_file/images/user.png';

        if($user['user_id']==$this->userdata['user_id']){
            $this->systemError("You can't delete your account with yourself.", ADMIN_URL."user");
            return;
        }

        $back_url = ADMIN_URL."user";
        $self_url = ADMIN_URL."userDelete/$id";

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>$this->load->view($this->mainTemplate.'/user_delete_confirm',$user, true),
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>str_replace("{data}", $user['username'], _l("Yes, delete {data}'s account.", $this)),
                'confirmUrl'=>$self_url."/1",
                'redirect'=>1,
            ));
            return;
        }

        $this->Nodcms_admin_model->userDelete($id);

        $this->systemSuccess("The user account has been deleted successfully.", $back_url);
    }

    /**
     * Add or edit a user
     *
     * @param string $id
     */
    function userEdit($id='')
    {
        if(!$this->checkAccessGroup(1))
            return;
        if($id!=''){
            $data = $this->Nodcms_admin_model->getUserDetail($id);
            if($data==null)
                $this->systemError("The user couldn't find.", ADMIN_URL."user");
            $this->data['sub_title'] = _l("Edit a user",$this);
            $form_attr = array();
            $this->data['breadcrumb_options'] = array(
                array('title'=>_l("Profile", $this),'url'=>ADMIN_URL."userProfile/$id"),
                array('title'=>_l("Edit", $this),'url'=>ADMIN_URL."userEdit/$id",'active'=>1),
            );
        }else{
            $this->data['sub_title'] = _l("Add new user",$this);
            $form_attr = array('data-reset'=>1);
        }
        $config = array(
            array(
                'field' => 'language_id',
                'label' => _l("Language", $this),
                'rules' => 'required',
                'type' => "select",
                'options' => $this->Nodcms_admin_model->get_all_language(),
                'option_value'=>"language_id",
                'option_name'=>"language_name",
                'default'=>isset($data)?$data["language_id"]:''
            ),
            array(
                'field' => 'group_id',
                'label' => _l("Group", $this),
                'rules' => 'required',
                'type' => "select",
                'options' => $this->Nodcms_admin_model->get_all_groups(),
                'option_value'=>"group_id",
                'option_name'=>"group_name",
                'default'=>isset($data)?$data["group_id"]:''
            ),
            array(
                'field' => 'email',
                'label' => _l("Email", $this),
                'rules' => 'required|valid_email|callback_emailUnique'.(isset($data)?'['.$data['user_id'].']':''),
                'type' => "email",
                'default'=>isset($data)?$data["email"]:''
            ),
            array(
                'field' => 'username',
                'label' => _l("Username", $this),
                'rules' => 'required|callback_validateUsername'.(isset($data)?'['.$data['user_id'].']':''),
                'type' => "text",
                'default'=>isset($data)?$data["username"]:''
            ),
            array(
                'field' => 'password',
                'label' => _l("Password", $this),
                'rules' => (isset($data)?'':'required|').'callback_formRulesPassword',
                'type' => "password",
                'default'=>''
            ),
            array(
                'field' => 'firstname',
                'label' => _l("First Name", $this),
                'rules' => 'callback_formRulesName',
                'type' => "text",
                'default'=>isset($data)?$data["firstname"]:''
            ),
            array(
                'field' => 'lastname',
                'label' => _l("Last Name", $this),
                'rules' => 'callback_formRulesName',
                'type' => "text",
                'default'=>isset($data)?$data["lastname"]:''
            ),
            array(
                'field' => 'mobile',
                'label' => _l("Phone Number", $this),
                'rules' => 'callback_validPhone',
                'type' => "text",
                'default'=>isset($data)?$data["mobile"]:''
            ),
            array(
                'field' => 'website',
                'label' => _l("Website", $this),
                'rules' => 'valid_url',
                'type' => "url",
                'default'=>isset($data)?$data["website"]:''
            ),
            array(
                'field' => 'status',
                'label' => _l("Email confirmation", $this),
                'rules' => 'required|in_list[0,1]',
                'type' => "switch-check",
                'default'=>isset($data)?$data["status"]:'',
                'help'=>_l("This option display user email confirmation status.", $this).
                    ' '._l("Also you can change the email confirmation for this account manually.", $this)
            ),
        );

        $myform = new Form();
        $myform->config($config, ADMIN_URL."userEdit/$id", 'post', 'ajax');
        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false || !is_array($data) || count($data)==0 || $data == null){
                return;
            }
            $data["fullname"] = $data["firstname"]." ".$data["lastname"];
            if ($this->Nodcms_admin_model->userManipulate($data, $id)) {
                $this->systemSuccess("The users has successfully updated", ADMIN_URL."userEdit/$id");
            }else{
                $this->systemError("The user couldn't successfully saved.", ADMIN_URL."userEdit/$id");
            }
            return;
        }

        $this->data['breadcrumb']=array(
            array('title'=>_l('Members',$this),'url'=>ADMIN_URL.'user'),
            array('title'=>$this->data['sub_title'])
        );
        $this->data['title'] = _l("Members",$this);
        $this->data['page'] = "user";
        $this->data['content'] = $myform->fetch('',$form_attr);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Make a user active or de-active
     *
     * @param $id
     */
    function userDeactive($id)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $user = $this->Public_model->getUserDetails($id);
        if(count($user)==0){
            $this->systemError("User not found!", ADMIN_URL."user");
            return;
        }

        if($user["user_id"] == $this->userdata["user_id"]){
            $this->systemError("You can't ban yourself.", ADMIN_URL."user");
            return;
        }

        // * Deactive the user
        if($user["active"]==1){
            $this->Nodcms_admin_model->userManipulate(array('active'=>0), $user["user_id"]);
            $this->systemSuccess("The user has been successfully banned!", ADMIN_URL."user");
        }
        // * Active the user
        else{
            $this->Nodcms_admin_model->userManipulate(array('active'=>1), $user["user_id"]);
            $this->systemSuccess("The user has been successfully activated!", ADMIN_URL."user");
        }
    }

    /**
     * Load images library for ajax requests
     *
     * @param $input_id
     * @param null $type
     */
    function getImagesLibrary($input_id, $type = null)
    {
        if($type==null)
            $type = "images-library";
        if(!key_exists($type, $this->image_library_types)){
            echo json_encode(array(
                'status'=>"error",
                'error'=>_l("Library type is undefined.", $this)
            ));
            return;
        }
        $this->data["upload_url"] = ADMIN_URL."uploadImage/$type";
        $this->data['input_id'] = $input_id;
        $this->data['images'] = $this->Images_model->getAll(array('folder'=>$this->image_library_types[$type]['dir']), null, 1, array('image_id','DESC'));
        $data = array(
            'status'=>"success",
            'content'=>$this->load->view($this->mainTemplate.'/images_library',$this->data, true),
            'title'=>_l("Image Library", $this),
            'closeBtnLable'=>_l("Close", $this),
        );
        echo json_encode($data);
    }

    function uploaded_images()
    {
        $this->data["data_list"] = $this->Nodcms_admin_model->get_all_images();
        echo $this->load->view($this->mainTemplate.'/uploaded_images',$this->data, true);
    }
    function imagesLibrary()
    {
        $this->data["data_list"] = $this->Images_model->getAll(null, null, 1, array('image_id','DESC'));
        $this->data['upload_url'] = ADMIN_URL."uploadImage/images-library";
        $this->data['page'] = "uploaded_images";
        $this->data['title'] = _l("Images Library",$this);
        $this->data['content']= $this->load->view($this->mainTemplate.'/uploaded_images_manager',$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    function imageDelete($id=0, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $current_data = $this->Images_model->getOne($id);
        if(!is_array($current_data) || count($current_data)==0){
            $this->systemError("Image not found.", ADMIN_URL."imagesLibrary");
            return;
        }

        $back_url = ADMIN_URL."imagesLibrary";
        $self_url = ADMIN_URL."imageDelete/$id";

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">' .
                    str_replace("{data}", "<strong>$current_data[name]</strong>",_l("This action will delete the image file '{data}' and record from database.", $this)) .' '.
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
            return;
        }

        if(file_exists(FCPATH.$current_data["image"])){
            unlink(FCPATH.$current_data["image"]);
        }
        $this->Images_model->remove($id);
        $this->systemSuccess("Language has been deleted successfully.", $back_url, array('removed'=>$id));
    }

    /**
     * Upload images on images library
     *
     * @param null|string $type
     */
    function uploadImage($type=null)
    {
        if (!$this->checkAccessGroup(1))
            return;
        $types = $this->image_library_types;
        if(!key_exists($type, $types)){
            echo json_encode(array("status"=>"error","errors"=>str_replace("{data}", "'<strong>$type</strong>'",  _l("The {data} upload type is undefined.", $this))));
            return;
        }

        $type_dir = $types[$type]['dir'];
        $dir = FCPATH."upload_file/$type_dir/";
        // Make directory
        if(!file_exists($dir)){
            mkdir($dir);
        }
        // Create index file
        $file = $dir.'index.php';
        if(!file_exists($file)){
            $myfile = fopen($file, "w") or die("Unable to open file!");
            $txt = "<?php\n http_response_code(404); ";
            fwrite($myfile, $txt);
            fclose($myfile);
        }

        $current_file_name = basename($_FILES["file"]["name"]);
        $config = array(
            'upload_path'=>"upload_file/$type_dir/",
            'allowed_types'=>"gif|jpg|png",
            'encrypt_name'=> $types[$type]['encrypt_name'],
        );
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload("file"))
        {
            echo json_encode(array("status"=>"error","errors"=>$this->upload->display_errors('<p>', '</p>')));
            return;
        }

        $data = $this->upload->data();
        $data_image = array(
            "image"=>$config['upload_path'].$data["file_name"],
            "width"=>$data["image_width"],
            "height"=>$data["image_height"],
            "name"=>$current_file_name,
            "root"=>$config["upload_path"],
            "folder"=>$type_dir,
            "size"=>$data["file_size"],
            'user_id'=>$this->userdata['user_id']
        );
        $image_id = $this->Images_model->add($data_image);
        if($image_id!=0) {
            echo json_encode(array(
                "status" => "success",
                "file_patch" => $config['upload_path'] . $data["file_name"],
                "file_url" => base_url() . $config['upload_path'] . $data["file_name"],
                "width" => $data["image_width"],
                "height" => $data["image_height"],
                "image_id" => $image_id,
                "image_name" => $current_file_name,
                "size" => $data["file_size"]));
            return;
        }
        unlink(FCPATH.$data_image["image"]);
        echo json_encode(array("status"=>"error","errors"=>_l("Could not save images data in database.",$this)));
    }

    /**
     * Homepage settings
     */
    function settingsHomepage()
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
        $unable_urls = array(base_url(),substr(base_url(),0,-1));
        $languages = $this->Public_model->getAllLanguages();
        foreach($languages as $language){
            $unable_urls[] = base_url().$language['code'];
            $unable_urls[] = base_url().$language['code']."/";
        }

        $config = array(
            array(
                'field'=>"homepage_type",
                'label'=>_l('One homepage',$this),
                'rules'=>"required|in_list[".join(',',array_column($homepage_types, "key"))."]",
                'type'=>"select-radio",
                'options'=>$homepage_types,
                'option_name'=>"title",
                'option_value'=>"key",
                'default'=>$this->settings['homepage_type'],
            ),
            array(
                'field'=>"index_logo",
                'label'=>_l('Homepage logo',$this),
                'rules'=>"",
                'type'=>"image-library",
                'default'=>$this->settings['index_logo'],
                'group_class'=>"inputs_default ".($this->settings['homepage_type']!="default"?"hidden":""),
            ),
            array(
                'field'=>"home_page_title_bg",
                'label'=>_l('Homepage background',$this),
                'rules'=>"",
                'type'=>"image-library",
                'default'=>$this->settings['home_page_title_bg'],
                'group_class'=>"inputs_default ".($this->settings['homepage_type']!="default"?"hidden":""),
            ),
            array(
                'field'=>"home_page_title_bg_blur",
                'label'=>_l('Homepage background blur',$this),
                'rules'=>"required|in_list[0,1]",
                'type'=>"switch",
                'default'=>$this->settings['home_page_title_bg_blur'],
                'group_class'=>"inputs_default ".($this->settings['homepage_type']!="default"?"hidden":""),
            ),
            array(
                'field'=>"homepage_sort",
                'label'=>_l('Home preview sort',$this),
                'type'=>"static",
//                'value'=>'<button type="button" onclick="$.loadInModal(\''.ADMIN_URL.'settingsHomepageSort\');" class="btn default">'.
//                    _l("Make a system sort", $this).'</button>',
                'value'=>'Loading',
                'tag'=>'div',
                'class'=>"bg-grey-cararra padding-10",
                'attr'=>array(
                    'style'=>"width:100%;padding:10px;",
                    'data-role'=>"auto-load",
                    'data-url'=>ADMIN_URL."settingsHomepageSort",
//                    'data-load'=>ADMIN_URL."settingsHomepageSort",
                ),
                'group_class'=>"inputs_default ".($this->settings['homepage_type']!="default"?"hidden":""),
            ),
            array(
                'field'=>"homepage_redirect",
                'label'=>_l('Redirect URL',$this),
                'rules'=>"callback_validateRequiredIf[homepage_type,redirect]|callback_validateNotEqual[".join(',',$unable_urls)."]",
                'type'=>"text",
                'default'=>$this->settings['homepage_redirect'],
                'group_class'=>"inputs_redirect ".($this->settings['homepage_type']!="redirect"?"hidden":""),
            ),
            array(
                'field'=>"homepage_display_file",
                'label'=>_l('File patch',$this),
                'rules'=>"callback_validateRequiredIf[homepage_type,display_file]|callback_validateFileExists",
                'type'=>"text",
                'input_prefix'=>getcwd()."\\",
                'default'=>$this->settings['homepage_display_file'],
                'group_class'=>"inputs_display_file ".($this->settings['homepage_type']!="display_file"?"hidden":""),
            ),
            array(
                'field'=>"homepage_display_page",
                'label'=>_l('Page URL',$this),
                'rules'=>"callback_validateRequiredIf[homepage_type,display_page]",
                'type'=>"text",
                'default'=>$this->settings['homepage_display_page'],
                'group_class'=>"inputs_display_page ".($this->settings['homepage_type']!="display_page"?"hidden":""),
            ),
        );

        $seo_group_class = "inputs_default ".($this->settings['homepage_type']!="default"?"hidden":"");
        foreach($languages as $language){
            $setting = $this->Public_model->getSettings($language['language_id']);
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
                'label'=>_l("Page author",$this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($setting['site_author'])?$setting['site_author']:'',
                'group_class'=>$seo_group_class,
            ));
            array_push($config, array(
                'field'=>$prefix."[site_title]",
                'label'=>_l("Page title",$this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($setting['site_title'])?$setting['site_title']:'',
                'group_class'=>$seo_group_class,
            ));
            array_push($config, array(
                'field'=>$prefix."[site_description]",
                'label'=>_l("Page description", $this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($setting['site_description'])?$setting['site_description']:'',
                'group_class'=>$seo_group_class,
            ));
            array_push($config, array(
                'field'=>$prefix."[site_keyword]",
                'label'=>_l("Page keywords", $this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($setting['site_keyword'])?$setting['site_keyword']:'',
                'group_class'=>$seo_group_class,
            ));
        }

        $myform = new Form();
        $myform->config($config, ADMIN_URL."settingsHomepage", 'post', 'ajax');
        // * Submit form
        if($myform->ispost()){
            if(!$this->checkAccessGroup(1))
                return;
            $data = $myform->getPost();
            // Stop Page
            if(!is_array($data) || count($data)==0 || $data == null){
                return;
            }
            // Options in all languages save
            if(isset($data["options"])){
                foreach($data["options"] as $language_id=>$item){
                    if(!$this->Nodcms_admin_model->updateSettings($item, $language_id)){
                        $this->systemError("A settings options could not be saved.", $this);
                        return;
                    }
                }
                unset($data["options"]);
            }
            // The settings without language_id
            $this->Nodcms_admin_model->updateSettings($data);
            $this->systemSuccess("Your Setting has been updated successfully!", ADMIN_URL."settingsHomepage");
            return;
        }

        $this->data['page'] = "homepage_setting";
        $this->data['sub_title'] = _l("Homepage", $this);

        $this->data['title'] = _l('Settings',$this);
        $this->data['breadcrumb'] = array(array('title'=>$this->data['title']),
            array('title'=>$this->data['sub_title']));
        $this->data['content'] = $this->load->view($this->mainTemplate."/homepage_sort_includes", $this->data, true).
            $myform->fetch();
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Homepage sort
     */
    function settingsHomepageSort()
    {
        // * Update Sort
        $post_data = $this->input->post("data");
        if($post_data != null){
            if(!$this->checkAccessGroup(1))
                return;
            $post_data = json_decode($post_data);
            foreach($post_data as $i=>$item){
                $update_data = array(
                    'package_sort'=>$i,
                );
                $this->Packages_model->edit($item->id, $update_data);
            }
            $this->systemSuccess("The packages has been successfully sorted.", ADMIN_URL."settingsHomepageSort");
            return;
        }

        // * Update package in database
        // Get packages list
        $packages = $this->load->packageList();
        // Remove packages that doesn't have home preview
        foreach($packages as $key=>$item){
            if(!file_exists(APPPATH."controllers/$item.php")){
                unset($packages[$key]);
                continue;
            }
            require_once APPPATH."controllers/$item.php";
            if ( class_exists($item, FALSE) && method_exists($item, 'home')){
                continue;
            }
            unset($packages[$key]);
        }
        // Get packages from DB
        $db_packages = $this->Packages_model->getAll(null, null, 1, array('package_sort', 'ASC'));
        $max = 0;
        // Remove not exists packages
        foreach ($db_packages as $item){
            if(!in_array($item['package_name'], $packages)){
                $this->Packages_model->remove($item['package_id']);
                continue;
            }
            if($item['package_sort']>$max){
                $max = $item['package_sort'];
            }
        }
        // Add the packages that not exists in database
        foreach($packages as $item){
            $count = $this->Packages_model->getCount(array('package_name'=>$item));
            if($count == 0){
                $this->Packages_model->add(array('package_name'=>$item, 'package_sort'=>$max));
            }
        }

        $this->data['data_list'] = $this->Packages_model->getAll(null, null, 1, array('package_sort', 'ASC'));

        $this->data['page'] = "settings_homepage_sort";
        $this->data['sub_title'] = _l("Sort homepage", $this);
        $this->data['title'] = _l('Settings',$this);

        if($this->input->is_ajax_request()){
            echo json_encode(array(
                'status'=>"success",
                'content'=>$this->load->view($this->mainTemplate."/homepage_sort", $this->data, true),
                'title'=>$this->data['sub_title'],
            ));
            return;
        }

        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title']),
            array('title'=>_l("Homepage", $this), 'url'=>ADMIN_URL."settingsHomepage"),
            array('title'=>$this->data['sub_title']));
        $this->data['content'] = $this->load->view($this->mainTemplate."/homepage_sort", $this->data, true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Toggle active status of a package
     *
     * @param $id
     */

    /**
     * Reset available packages
     */
    private function dashboardPackageSortReset()
    {
        // * Update package in database
        // Get packages list
        $packages = $this->load->packageList();
        // Remove packages that doesn't have home preview
//        foreach($packages as $key=>$item){
//            if(!file_exists(APPPATH."controllers/".$item."_admin.php")){
//                unset($packages[$key]);
//                continue;
//            }
//            $url = base_url()."admin-".strtolower($item)."/dashboard";
//            $data = $this->curlWebPage($url, true);
//            if($data == "")
//                continue;
//            $array_data = json_decode($data, true);
//            if(json_last_error()!=JSON_ERROR_NONE || $array_data['content']=="")
//                continue;
//            unset($packages[$key]);
//        }
        // Get packages from DB
        $db_packages = $this->Packages_dashboard_model->getAll();
        $max = 0;
        // Remove not exists packages
        foreach ($db_packages as $item){
            if(!in_array($item['package_name'], $packages)){
                $this->Packages_dashboard_model->remove($item['package_id']);
                continue;
            }
            if($item['package_sort']>$max){
                $max = $item['package_sort'];
            }
        }
        // Add the packages that not exists in database
        foreach($packages as $item){
            $count = $this->Packages_dashboard_model->getCount(array('package_name'=>$item));
            if($count == 0){
                $this->Packages_dashboard_model->add(array('package_name'=>$item, 'package_sort'=>$max));
            }
        }
    }

    /**
     * Toggle active status of a package for dashboard
     *
     * @param $id
     */
    function packageToggleActive($id)
    {
        if(!$this->checkAccessGroup(1))
            return;
        $current_data = $this->Packages_dashboard_model->getOne($id);
        if($current_data==null || count($current_data)==0){
            $this->systemError("The package not found.", ADMIN_URL);
            return;
        }

        if($current_data['active']==1){
            $this->Packages_dashboard_model->edit($id, array('active'=>0));
            $this->systemSuccess("The package has been successfully deactivated.", ADMIN_URL);
            return;
        }
        $this->Packages_dashboard_model->edit($id, array('active'=>1));
        $this->systemSuccess("The package has been successfully activated.", ADMIN_URL);
    }

    /**
     * Toggle active status of a package for dashboard
     *
     * @param $id
     */
    function settingsHomepageToggleActive($id)
    {
        if(!$this->checkAccessGroup(1))
            return;
        $current_data = $this->Packages_model->getOne($id);
        if($current_data==null || count($current_data)==0){
            $this->systemError("The package not found.", ADMIN_URL);
            return;
        }

        if($current_data['active']==1){
            $this->Packages_model->edit($id, array('active'=>0));
            $this->systemSuccess("The package has been successfully deactivated.", ADMIN_URL);
            return;
        }
        $this->Packages_model->edit($id, array('active'=>1));
        $this->systemSuccess("The package has been successfully activated.", ADMIN_URL);
    }
}
