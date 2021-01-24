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

abstract class Frontend extends App
{
    public function __construct()
    {
        parent::__construct();

        $this->page_sidebar = "frontend_sidebar";
        $this->view->config->frameFile = $this->config->frontend_template_frame;

        if($this->frameTemplate==null)
            $this->frameTemplate = $this->mainTemplate;

        $this->data['lang_url'] = base_url().uri_string();
        if($this->session->has("user_id")) {
            $userModel = new \NodCMS\Core\Models\Users_model();
            $this->userdata = $userModel->getOne($this->session->get("user_id"));
//            if($this->userdata["active"]!=1 && $this->router->methodName() != "logout"){
//                $this->accountLock();
//                return;
//            }
            if(empty($this->userdata)) {
                $this->userdata['has_dashboard'] = $this->session->has("has_dashboard") ? $this->session->get("has_dashboard") : false;
            }
            else{
                $this->userdata = NULL;
            }
        }
        else
            $this->userdata = NULL;
    }

    // Set system language from URL
    function preset($lang)
    {
        $languageModel = new \NodCMS\Core\Models\Languages_model();
        // Set system language from URL language code (Language prefix)
        $language = $languageModel->getByCode($lang);
        if($language!=0){
            $_SESSION["language"] = $language;
            $this->language = $language;
            $this->data["lang"] = $lang;
        }else{
            $this->setLanguagePrefix();
        }

        // Make nodcms lang file for version 3.6
        $my_lang_file = APPPATH.'language/'.$language['language_name'].'/nodcms_lang.php';
        if(!file_exists($my_lang_file)){
            resetLanguageTempFile();
        }
        $this->lang->load("nodcms", $language["language_name"]);

        $_SERVER['DOCUMENT_ROOT'] = dirname(dirname(dirname(__FILE__)));

        $this->settings = array_merge($this->settings, $this->Public_model->getSettings($this->language['language_id']));

        $this->data['settings'] =  $this->settings;
        $_SESSION['settings'] = $this->settings;

        // Set Languages menu
        $this->data['languages'] = $this->Nodcms_general_model->get_languages();
        foreach ($this->data['languages'] as &$value) {
            $url_array = explode("/",$this->data["lang_url"]);
            $url_array[array_search($lang,$url_array)]=$value["code"];
            $value["lang_url"] = implode("/",$url_array);
        }
        // Set header and footer menus
        $this->setMenus();

//        if($this->frameTemplate == "mt-layout4"){
//            if(!isset($this->settings['forms_theme']) || $this->settings['forms_theme'] != "form-clean"){
//                $this->page_sidebar_closed = false;
//                $this->addToSidebar($this->data["top_menu"]);
//            }
//        }
        // Run hooks classes
        $this->packageHooks("preset", $lang);
    }
}