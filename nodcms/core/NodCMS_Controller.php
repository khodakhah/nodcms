<?php
/**
 * Created by PhpStorm.
 * User: Mojtaba
 * Date: 2/15/2016
 * Time: 6:27 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

class NodCMS_Controller extends CI_Controller{
    // Include NodCMS utilities
    use NodCMSUtilities;
    // Include NodCMS validators
    use NodCMSValidators;

    // !Important: undefined $lang keys put in this array to add to language files
    public $langArray = array();
    // Class type: backend or frontend
    public $controllerType;
    // Admin sidebar menus
    public $admin_panel_items = array();

    public $captcha_session_name = 'nodcms_captcha';
    // Keep the form data for form helpers
    public $html_form_data = array('form_group'=>"inline_form_group", 'label_col'=>"col-md-2", 'input_col'=>"col-md-10");

    // Controller type
    public $controller_type;

    // Settings default values
    public $settings_default;

    public $system_urls;

    /**
     * Add on configuration for page navigation
     *
     * @param $config
     */
    function mkPagination($config)
    {
        $this->load->library('pagination');
        $config["full_tag_open"] = "<nav class='text-center'><ul class='pagination'>";
        $config["full_tag_close"] = "</ul></nav>";
        $config["first_tag_open"] = $config["last_tag_open"] = $config["next_tag_open"] = $config["prev_tag_open"] = $config["num_tag_open"] = "<li class='page-item'>";
        $config["first_tag_close"] = $config["last_tag_close"] = $config["next_tag_close"] = $config["prev_tag_close"] = $config["num_tag_close"] = "</li>";
        $config["first_link"] = _l("First",$this);
        $config["last_link"] = _l("Last",$this);
        $config["prev_link"] = "<span aria-hidden='TRUE'>&laquo;</span>";
        $config["next_link"] = "<span aria-hidden='TRUE'>&raquo;</span>";
        $config["cur_tag_open"] = "<li class='page-item active'><a href='javascript:;' class='page-link'>";
        $config["cur_tag_close"] = "<span class='sr-only'>(current)</span></a></li>";
        $config['use_page_numbers']  = TRUE;
        $config['attributes']  = array('class'=>'page-link');
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();
    }

    /**
     * NodCMS_Controller constructor.
     *
     *  - This method will find out the controller type and set the options and run the codes that they required for the
     *  - type of the controller.
     *
     *  - Defined controller type:
     *  -   "backend", "frontend", "membership"
     *
     * @param string $controllerType
     */
    function __construct($controllerType='backend')
    {
        parent::__construct();

        $this->display_title = TRUE;
        $this->display_page_title = TRUE;

        if(!in_array($controllerType, array('backend', 'frontend', 'membership', 'api'))){
            die('The controller type is not acceptable!');
        }

        $this->controller_type = $controllerType;

        $this->cleanFrame = $this->config->item('clean_template_frame');
        if($this->router->method == "index"){
            $this->display_title = FALSE;
        }
        define('USER_UNDEFINED_AVATAR',base_url().'upload_file/images/user.png');
        define('ADMIN_URL',base_url().'admin/');
        define('PROVIDER_ADMIN_URL',base_url().'admin-provider/');
        $curl = str_replace("/index.php","",current_url());
        define('CURRENT_PAGE_URL',$_SERVER['QUERY_STRING'] ? $curl .'?'.$_SERVER['QUERY_STRING'] : $curl);
        define('CURRENT_PAGE_URI',str_replace(base_url(), "", CURRENT_PAGE_URL));

        // * Load Models and Helpers
        $models = $this->config->item($controllerType.'_models');
        $general_models = $this->config->item('general_models');
        if(is_array($general_models) && count($general_models)!=null){
            $models = array_merge($general_models, $models);
        }
        $this->load->model($models);
        $helpers = $this->config->item($controllerType.'_helpers');
        $this->load->helper($helpers);

        $this->settings_default = $this->config->item('settings_default');
        // * Get settings from Database
        $this->settings = array_merge($this->settings_default, $this->Public_model->getSettings());
        $this->settings['datepicker_date_format'] = str_replace(array('d', 'm', 'y', 'Y'), array('dd', 'mm', 'y', 'yy'), $this->settings['date_format']);
//        if($this->settings["dynamic_timezone"]==1) {
//            $picked_timezone = get_cookie('my_timezone');
//            $this->data['timezone_list'] = DateTimeZone::listIdentifiers();
//            $this->data['my_timezone'] = $picked_timezone;
//            if($picked_timezone!=null && in_array($picked_timezone, $this->data['timezone_list'])){
//                date_default_timezone_set($picked_timezone);
//            }else{
//                date_default_timezone_set($this->settings["timezone"]);
//            }
//        } else
        if($this->settings["timezone"]!=""){
            date_default_timezone_set($this->settings["timezone"]);
        }
        // Decode properties
        if(isset($this->settings["properties"]) && $this->settings["properties"]!=""){
            $this->settings["properties"] = json_decode($this->settings["properties"]);
        }else{
            $this->settings["properties"] = array();
        }
        $this->controllerType = $controllerType;
        $this->$controllerType();
        // Set currency options (pricing feature)
        if(isset($this->settings["currency_sign"]) && $this->settings["currency_sign"]!=""){
            $currencyConfig = array(
                "code"=>$this->settings["currency_code"],
                "sign"=>$this->settings["currency_sign"],
                "add_before"=>"$",
                "add_after"=>"USD",
            );
            if($this->settings["currency_sign_before"]==1) {
                $currencyConfig["add_before"] = $this->settings["currency_sign"];
                $currencyConfig["add_after"] = "";
            }else {
                $currencyConfig["add_before"] = "";
                $currencyConfig["add_after"] = $this->settings["currency_sign"];
            }
            $this->currency->setOptions($currencyConfig);
        }
        if($this->settings["currency_format"]!=""){
            $this->currency->setFormat($this->settings["currency_format"]);
        }

        $this->data["social_links"] = $this->Social_links_model->getAll();
        $this->data["production_copyright"] = $this->config->item('production_copyright');
        $this->data["page"] = "";
    }

    /**
     * To make a admin controller
     */
    private function backend()
    {
        $this->page_sidebar = "admin_sidebar";
        $this->mainTemplate = $this->config->item('backend_template');
        $this->frameTemplate = $this->config->item('backend_template_frame');

        if(!isset($this->session->userdata['user_id'])){
            $this->systemError("Please login to access to access.", base_url()."admin-sign");
            exit;
        }
        $this->data['base_url'] = base_url()."admin/";
        if($this->session->has_userdata("user_id")) {
            $this->userdata = $this->Nodcms_admin_model->getUserDetail($this->session->userdata("user_id"));
            if($this->userdata["active"]!=1){
                $this->accountLock();
                return;
            }
            if(!in_array($this->userdata['group_id'],array(1,100))){
                $this->systemError("Access denied!", base_url());
                exit;
            }
            $this->userdata['has_dashboard'] = $this->session->has_userdata("has_dashboard")?$this->session->userdata("has_dashboard"):false;
        }else
            $this->userdata = NULL;

        $this->loadLanguageAndSettings($this->userdata['language_id']);

        // Admin sidebar add control panel
        if(in_array($this->session->userdata('group'), array(1, 100))){
            $sidebar = array(
                'dashboard'=>array(
                    'url'=>ADMIN_URL.'',
                    'title'=>_l("Control Panel",$this),
                    'icon'=>'fas fa-th-large'
                ),
            );
            $this->addToAdminSidebar($sidebar);
        }

        /* System URLs
            General pages */
        $this->system_urls = array(
            array('title'=>_l("Home", $this), 'url'=>""),
            array('title'=>_l("Contact Us", $this), 'url'=>"contact"),
            array('title'=>_l("Terms & Conditions", $this), 'url'=>"terms-and-conditions"),
            array('title'=>_l("Privacy-Policy", $this), 'url'=>"privacy-policy"),
        );

        $this->load->packageHooks("backend");

        // Admin sidebar all general items
        if(in_array($this->session->userdata('group'), array(1, 100))){
            $sidebar = array(
                'users'=>array(
                    'url'=>ADMIN_URL.'user',
                    'title'=>_l("Members",$this),
                    'icon'=>'fas fa-users'
                ),
                'language'=>array(
                    'url'=>ADMIN_URL.'language',
                    'title'=>_l("Languages",$this),
                    'icon'=>'fas fa-language'
                ),
                'menu'=>array(
                    'url'=>ADMIN_URL.'menu',
                    'title'=>_l("Menus",$this),
                    'icon'=>'fas fa-link'
                ),
                'uploaded_images'=>array(
                    'url'=>ADMIN_URL.'imagesLibrary',
                    'title'=>_l("Images",$this),
                    'icon'=>'far fa-images'
                ),
                'tcpp_setting'=>array(
                    'url'=>ADMIN_URL.'settings/tcpp',
                    'title'=>_l("T&C, Privacy Policy", $this),
                    'icon'=>'fas fa-gavel'
                ),
                'settings'=>array(
                    'url'=>'javascript:;',
                    'title'=>_l("Settings",$this),
                    'icon'=>'fas fa-cogs',
                    'sub_menu'=>array(
                        'setting'=>array(
                            'url'=>ADMIN_URL.'settings',
                            'title'=>_l("General", $this)
                        ),
                        'advance_setting'=>array(
                            'url'=>ADMIN_URL.'settings/advance',
                            'title'=>_l("Advance", $this)
                        ),
                        'homepage_setting'=>array(
                            'url'=>ADMIN_URL.'settingsHomepage',
                            'title'=>_l("Homepage", $this)
                        ),
                        'date_setting'=>array(
                            'url'=>ADMIN_URL.'settings/date-and-time',
                            'title'=>_l("Date & Time", $this)
                        ),
                        'contact_setting'=>array(
                            'url'=>ADMIN_URL.'settings/contact',
                            'title'=>_l("Contact info", $this)
                        ),
                        'mail_setting'=>array(
                            'url'=>ADMIN_URL.'settings/mail',
                            'title'=>_l("Send mail", $this)
                        ),
                        'emails_texts'=>array(
                            'url'=>ADMIN_URL.'automaticEmailTexts',
                            'title'=>_l("Auto emails messages", $this)
                        ),
                        'social_links'=>array(
                            'url'=>ADMIN_URL.'socialLinks',
                            'title'=>_l("Social Links", $this)
                        ),
                    )
                ),
            );
            $this->addToAdminSidebar($sidebar);
            $this->data['top_menu'] = array(
                array('url'=>base_url().$this->language['code'], 'name'=>"View Site")
            );
        }
    }

    /**
     * To make a frontend controller
     */
    private function frontend()
    {
        $this->page_sidebar = "frontend_sidebar";
        $this->theme = "reservation";
        $this->mainTemplate = $this->config->item("frontend_template");
        $this->frameTemplate = $this->config->item('frontend_template_frame');
        if($this->frameTemplate==null)
            $this->frameTemplate = $this->mainTemplate;
//        $this->data['lang_url'] = $_SERVER["REQUEST_URI"];
        $this->data['lang_url'] = base_url().uri_string();
        if($this->session->has_userdata("user_id")) {
            $this->userdata = $this->Public_model->getUserDetails($this->session->userdata("user_id"));
            if($this->userdata["active"]!=1 && $this->router->method != "logout"){
                $this->accountLock();
                return;
            }
            $this->userdata['has_dashboard'] = $this->session->has_userdata("has_dashboard")?$this->session->userdata("has_dashboard"):false;
        }else
            $this->userdata = NULL;
    }

    /**
     * Set options of the Membership controllers type
     */
    private function membership()
    {
        $this->page_sidebar = "membership_sidebar";
        $this->mainTemplate = $this->config->item('membership_template');
        $this->frameTemplate = $this->config->item('membership_template_frame');

        $this->userLoggedIn();

        $this->userdata = $this->Public_model->getUserDetails($this->session->userdata("user_id"));
        if(count($this->userdata)==0){
            $this->loadLanguageAndSettings();
            $buttons = array(
                array('url'=>base_url(), 'label'=>_l("Home", $this)),
                array('url'=>base_url().$this->language['code']."/login", 'label'=>_l("Sign In", $this)),
                array('url'=>base_url().$this->language['code']."/user-register", 'label'=>_l("Sign Up", $this)),
            );
            $this->removeUserSessions();
            $this->showError(_l("Your account is not exists any more.", $this), 500, _l("Not exists account", $this), $buttons);
        }
        if($this->userdata["active"]!=1){
            $this->accountLock();
            return;
        }
        $this->userdata['has_dashboard'] = $this->session->has_userdata("has_dashboard")?$this->session->userdata("has_dashboard"):false;

        $this->loadLanguageAndSettings($this->userdata['language_id']);

        // Set header and footer menus
        $this->setMenus();

        // Execute Hooks
        $this->load->packageHooks("membership");


        // Add dashboard link in member sidebar if exists any other links
        if(count($this->page_sidebar_items)>1 && !key_exists("dashboard", $this->page_sidebar_items)){
            $sidebar = array(
                'dashboard'=>array(
                    'url'=>base_url().'user/dashboard',
                    'title'=>_l("Dashboard",$this),
                    'icon'=>'icon-speedometer'
                )
            );
            // Set Membership Sidebar
            $this->addToSidebar($sidebar, "prepend");
        }

        $sidebar = array(
            'account'=>array(
                'url'=>base_url().'user/account',
                'title'=>_l("Account", $this),
                'icon'=>'icon-user'
            )
        );

        $this->addToSidebar($sidebar);
    }

    /**
     * Set options of the Api controllers type
     */
    private function api()
    {
        $this->loadLanguageAndSettings();

        $this->loadLanguageAndSettings($this->userdata['language_id']);
        // Execute Hooks
        $this->load->packageHooks("api");
    }

    /**
     * Set language and site settings for api classes
     *
     * @param null $lang
     * @return int
     */
    function setLanguage($lang = null)
    {
        // Available just for api
        if($this->controllerType != 'api'){
            echo json_encode(array(
                'status'=>"error",
                'error'=>_l("System internal error. Please check the log files.", $this)
            ));
            log_message("error", "setLanguage() method just can use in the api type classes.");
            return 0;
        }

        if($lang != null){
            $language = $this->Languages_model->getOne(null, array('default'=>1));
        }
        else{
            $language = $this->Languages_model->getOne(null, array('code'=>$lang));
        }
        if($language == null || count($language)==0){
            echo json_encode(array(
                'status'=>"error",
                'error'=>_l("System internal error. Please check the log files.", $this)
            ));
            log_message("error", "There isn't any language to run the system on it.");
            return 0;
        }

        $this->language = $language;

        // Make nodcms lang file for version 3.6
        $my_lang_file = APPPATH.'language/'.$language['language_name'].'/nodcms_lang.php';
        if(!file_exists($my_lang_file)){
            resetLanguageTempFile();
        }
        $this->lang->load("nodcms", $language["language_name"]);
        $this->settings = array_merge($this->settings, $this->Public_model->getSettings($this->language['language_id']));

        return 1;
    }

    /**
     * Check referer request equal to current domain
     *
     * @return int
     */
    function privateRequest()
    {
        if(!isset($_SESSION['my_session_id']) || $_SESSION['my_session_id'] != session_id()){
            echo json_encode(array(
                'status'=>"error",
                'error'=>_l("API Access denied.", $this)
            ));
            return 0;
        }

        return 1;
    }

    /**
     * Remove/Reset all logged in sesions
     */
    function removeUserSessions()
    {
        $this->session->unset_userdata('fullname');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('group');
        $this->session->unset_userdata('avatar');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('logged_in_status');
        $this->session->unset_userdata('has_dashboard');
    }

    /**
     * Set the variables of language and settongs
     *
     * @param $language_id
     */
    private function loadLanguageAndSettings($language_id = null)
    {
        if($language_id!=null)
            $language = $this->Public_model->getLanguage($language_id);
        else
            $language = $this->Public_model->getDefaultLanguage();
        $_SESSION['language'] = $language;
        $this->language = $language;

        // Make nodcms lang file for version 3.6
        $my_lang_file = APPPATH.'language/'.$language['language_name'].'/nodcms_lang.php';
        if(!file_exists($my_lang_file)){
            resetLanguageTempFile();
        }
        $this->lang->load("nodcms", $language["language_name"]);
        $this->settings = array_merge($this->settings, $this->Public_model->getSettings($this->language['language_id']));
        $this->data['settings'] = $this->settings;
        $_SESSION['settings'] = $this->settings;
    }

    /**
     * Check a user to be log in
     */
    function userLoggedIn()
    {
        if(!$this->session->has_userdata("user_id")){
            // Return an ajax error
            if($this->input->is_ajax_request()){
                $data = array(
                    "status"=>"error",
                    "url"=>base_url(),
                    "error"=> _l("The user login data are lost. Please login to do this action.", $this)
                );
                echo  json_encode($data);
                exit;
            }
            // Set the language variable if it's not exists
            if($this->language == null){
                $language = $this->Public_model->getDefaultLanguage();
                $this->language = $language;
            }
            // Display the membership required page
            $this->memberRequiredPage();
        };
    }

    /*
     * Set new items in admin_panel_items
     * It will set admin panel sidebar in the view files
     */
    function addToAdminSidebar()
    {
        $i = func_num_args();
        // Merge an array with panel items
        if($i == 1){
            $array = func_get_arg(0);
            if(!is_array($array))
                show_error("error", "The method parameter doesn't have right type!");
            $this->admin_panel_items = array_merge($this->admin_panel_items, $array);
        }
        // Merge an array with panel items & set position of new item
        elseif($i == 2){
            $array = func_get_arg(0);
            $type = func_get_arg(1);
            if(!is_array($array))
                show_error("error", "The method parameter doesn't have right type!");
            // * Put the menu at the end position
            if($type == 'append')
                $this->admin_panel_items = array_merge($this->admin_panel_items, $array);
            // * Put the menu at the first position
            elseif($type == 'prepend')
                $this->admin_panel_items = array_merge($array, $this->admin_panel_items);
            // * Put the menu in a position
            elseif(is_numeric($type)){
                $the_count = count($this->admin_panel_items);
                $this->admin_panel_items = array_merge(array_slice($this->admin_panel_items, 0, $type), $array, array_slice($this->admin_panel_items, $type, $the_count-$type));
            }
            else
                show_error("error", "The method parameter doesn't have right type!");
        }
        // Add an item at the panel items
        elseif($i == 4){
            $key = func_get_arg(0);
            $title = func_get_arg(1);
            $url = func_get_arg(2);
            $icon = func_get_arg(3);
            $this->admin_panel_items[$key] = array(
                'url'=>$url,
                'title'=>$title,
                'icon'=>$icon
            );
        }
        // Add a sub item at the panel items
        elseif($i == 5){
            $key = func_get_arg(0);
            $title = func_get_arg(1);
            $url = func_get_arg(2);
            $icon = func_get_arg(3);
            $sub_key = func_get_arg(4);

            if(!isset($this->admin_panel_items[$key]))
                return;
            if(!isset($this->admin_panel_items[$key]['sub_menu']))
                $this->admin_panel_items[$key]['sub_menu'] = array();

            $this->admin_panel_items[$key]['sub_menu'][$sub_key] = array(
                'url'=>$url,
                'title'=>$title,
                'icon'=>$icon
            );
        }
    }

    /**
     * Set new items in $page_sidebar_items
     * It will set frontend sidebar in the view files
     */
    function addToSidebar()
    {
        $i = func_num_args();
        // Merge an array with panel items
        if($i == 1){
            $array = func_get_arg(0);
            if(!is_array($array))
                show_error("error", "The method parameter doesn't have right type!");
            $this->page_sidebar_items = array_merge($this->page_sidebar_items, $array);
        }
        // Merge an array with panel items & set position of new item
        elseif($i == 2){
            $array = func_get_arg(0);
            $type = func_get_arg(1);
            if(!is_array($array))
                show_error("error", "The method parameter doesn't have right type!");
            // * Put the menu at the end position
            if($type == 'append')
                $this->page_sidebar_items = array_merge($this->page_sidebar_items, $array);
            // * Put the menu at the first position
            elseif($type == 'prepend')
                $this->page_sidebar_items = array_merge($array, $this->page_sidebar_items);
            // * Put the menu in a position
            elseif(is_numeric($type)){
                $the_count = count($this->page_sidebar_items);
                $this->page_sidebar_items = array_merge(array_slice($this->page_sidebar_items, 0, $type), $array, array_slice($this->page_sidebar_items, $type, $the_count-$type));
            }
            else
                show_error("error", "The method parameter doesn't have right type!");
        }
        // Add an item at the panel items
        elseif($i == 4){
            $key = func_get_arg(0);
            $title = func_get_arg(1);
            $url = func_get_arg(2);
            $icon = func_get_arg(3);
            $this->page_sidebar_items[$key] = array(
                'url'=>$url,
                'title'=>$title,
                'icon'=>$icon
            );
        }
        // Add a sub item at the panel items
        elseif($i == 5){
            $key = func_get_arg(0);
            $title = func_get_arg(1);
            $url = func_get_arg(2);
            $icon = func_get_arg(3);
            $sub_key = func_get_arg(4);

            if(!isset($this->page_sidebar_items[$key]))
                return;
            if(!isset($this->page_sidebar_items[$key]['sub_menu']))
                $this->page_sidebar_items[$key]['sub_menu'] = array();

            $this->page_sidebar_items[$key]['sub_menu'][$sub_key] = array(
                'url'=>$url,
                'title'=>$title,
                'icon'=>$icon
            );
        }
    }

    /**
     * Show an error in page content
     *
     * @param null $message
     * @param int $status_code
     * @param null $heading
     * @param array $buttons
     */
    function showError($message = null, $status_code = 404, $heading = null, $buttons = array())
    {
        http_response_code($status_code);
        $this->data["title"] = _l("Error", $this)." $status_code";
        $this->data["status_code"] = $status_code;
        $this->data["heading"] = $heading!=null?$heading:_l("Page not found!", $this);
        $this->data["message"] = $message!=null?$message:_l("Your requested page not found.", $this);
        $this->data["buttons"] = $buttons;
        $this->data["content"] = $this->load->view("common/show_error", $this->data, true);
        echo $this->load->view($this->cleanFrame, $this->data, true);
        exit;
    }

    /**
     * Display an error page if membership is required
     *  - This method will use in controllers(pages) where a membership is requred and there is no sign in.
     */
    function memberRequiredPage()
    {
        if(!isset($this->data['title']))
            $this->data['title'] = _l("Members Protection", $this);
        if(!isset($this->data['message']))
            $this->data['message'] = _l("To access this part of system, you need to sign in as one of our members.", $this);
        $this->data['content'] = $this->load->view("common/member_required_page", $this->data, true);
        echo $this->load->view($this->frameTemplate, $this->data, true);
        exit;
    }

    /**
     * Check logged in user group access
     *
     * @return bool
     */
    function checkAccessGroup()
    {
        // Get parameters as an array
        $groups = func_get_args();
        if(!in_array($this->session->userdata('group'), $groups)){
            if(!$this->input->is_ajax_request()){
                $message = _l("Unfortunately you do not have permission to this part of system.", $this);
                $this->showError($message, 100, _l("Denied access", $this));
            }else{
                $this->systemError("Unfortunately you do not have permission to this part of system.", base_url());
            }
            return false;
        }
        return true;
    }

    /**
     * Check logged in and user group access
     *
     * @return bool
     */
    function requestAccessGroup()
    {
        if(!$this->session->has_userdata('group')){
            echo json_encode(array(
                'status'=>"error",
                'error'=>"Unfortunately you do not have permission to this part of system.",
            ));
            return false;
        }
        // Get parameters as an array
        $groups = func_get_args();
        if(!in_array($this->session->userdata('group'), $groups)){
            echo json_encode(array(
                'status'=>"error",
                'error'=>"Unfortunately you do not have permission to this part of system.",
            ));
            return false;
        }
        return true;
    }

    /*
     * This method useful for stop your system with an error
     */
    function systemError($error, $redirect = "", $translate = true)
    {
        $this->errorMessage($translate?_l($error, $this):$error, $redirect);
    }

    /*
     * This method useful for return successful messages
     */
    function systemSuccess($message, $redirect = "" , $add_on_data = null, $translate = true)
    {
        $this->successMessage($translate?_l($message, $this):$message, $redirect, $add_on_data);
    }

    /**
     * Load sorted packages from DB
     *
     * @param $method
     */
    private function packageHooks($method)
    {
        $packages = $this->Packages_dashboard_model->getAll(null, null, 1, array('package_sort', 'ASC'));
        foreach ($packages as $item){
            if(!$this->load->packageExists($item['package_name']))
                continue;
            $this->load->packageLoad($item['package_name'], $method);
        }
    }

    /**
     * @param $config
     * @param $URL
     * @return mixed
     */
    function formValidation($config, $URL)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules($config);
        // Form Error
        if ($this->form_validation->run() != TRUE) {
            $this->formError($URL);
        }
        return $this->form_validation->run();
    }

    // Return a default language URL if didn't set any lang in URL
    public function setLanguagePrefix()
    {
        // Available just for frontend
        if($this->controllerType != 'frontend') show_404();

        $language = $this->Nodcms_general_model->get_language_default();
        if($language!=0){
            // Redirect to new URL with default language
            redirect(base_url().$language['code']);
        }else{
            $language = $this->Nodcms_general_model->get_languages();
            if(count($language)!=0){
                // Redirect to new URL with default language
                redirect(base_url().$language[0]['code']);
            }else{
                // Not exists any language in database
                echo "System cannot find any language to load.";
                exit;
            }
        }
    }

    // Set system language from URL
    function preset($lang)
    {
        // Available just for frontend
        if($this->controllerType != 'frontend'){
            show_error("preset() method just can use in the frontend type classes.");
            return;
        }
        // Set system language from URL language code (Language prefix)
        $language = $this->Nodcms_general_model->get_language_by_code($lang);
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
        $this->load->packageHooks("preset", $lang);
    }

    /**
     * Set the top and bottom menus
     */
    private function setMenus()
    {
        $all_menus = array('top_menu', 'footer_menu');
        foreach($all_menus as $menu_type){
            $data_menu = array();
            $menu = $this->Public_model->getMenu($menu_type, 0);
            foreach($menu as $item)
            {
                $menu_item = array(
                    'name' =>$item['title_caption'],
                    'title' =>$item['title_caption'],
                    'icon' =>$item['menu_icon'],
                    'url' =>substr($item['menu_url'],0,4)=="http"?$item['menu_url']:base_url().$this->language['code']."/".$item['menu_url'],
                );
                $sub_menu = $this->Public_model->getMenu("top_menu", $item['menu_id']);

                if(count($sub_menu)!=0){
                    $sub_menu_data = array();
                    foreach ($sub_menu as $sub_item){
                        $sub_menu_item = array(
                            'name' =>$sub_item['title_caption'],
                            'title' =>$sub_item['title_caption'],
                            'icon' =>$sub_item['menu_icon'],
                            'url' =>substr($sub_item['menu_url'],0,4)=="http"?$sub_item['menu_url']:base_url().$this->language['code']."/".$sub_item['menu_url'],
                        );
                        array_push($sub_menu_data, $sub_menu_item);
                    }
                    $menu_item['sub_menu'] = $sub_menu_data;
                }

                array_push($data_menu, $menu_item);
            }
            $this->data[$menu_type] = $data_menu;
        }
    }

    // Method to send notification emails to users
    public function sendNotificationEmail($auto_message_key, $language_id, $to_email, $data = array())
    {
        $autoEmailMsgConfig = $this->config->item('Appointment_autoEmailMessages');
        $autoEmailMSG = $this->Public_model->getAutoMessages($auto_message_key, $language_id);
        if(!isset($autoEmailMSG['content'])){
            log_message("error", "Send e-mail to '$to_email' stopped, because there isn't any content for this email in the database.");
            return;
        }
        $email_content = $autoEmailMsgConfig[$auto_message_key]['replace_keys']($autoEmailMSG['content'], $data);
        $this->sendEmailAutomatic($to_email,$autoEmailMSG['subject'],$email_content);
    }

    // Send email with protocol that in settings set
    public function sendEmailAutomatic($emails, $subject, $content, $from = null)
    {
        if ($_SERVER['SERVER_NAME'] != 'localhost') {
            $content = $this->load->view('emails/general-frame', array('body'=>$content), true);
            $content = str_replace(
                array('&nbsp;'),
                array(' '),
                $content
            );
            $setting =  $this->settings;
            if($from == null)
                $from = array($setting['send_email'], $setting['company']);

            if(isset($setting['use_smtp']) && $setting['use_smtp']==1){
                $config = array(
                    'protocol' => 'smtp',
                    'smtp_host' => $setting['smtp_host'],
                    'smtp_port' => $setting['smtp_port'],
                    'smtp_user' => $setting['smtp_username'],
                    'smtp_pass' => $setting['smtp_password'],
                    'mailtype'  => 'html',
                    'charset'   => 'iso-8859-1',
                    'starttls'  => true,
                    'newline'   => "\r\n"
                );
            }else{
                $config = array(
                    'protocol' => 'mail',
                    'mailtype'  => 'html',
                    'charset'   => 'utf8',
                    'starttls'  => true,
                    'newline'   => "\r\n"
                );
            }
            $this->load->library('email',$config);
            try {
                $this->email->clear();
                $this->email->to($emails);
                $this->email->from($from[0],$from[1]);
                $this->email->set_header('Subject', $subject);
                $this->email->message($content);
                $this->email->send();
            }catch (Exception $e){
                //Do nothing
                $SendMailError = '<p>'.implode('</p><p>',$e).'</p>';
                log_message('error', $SendMailError);
            }
            return;
        }
        log_message('error', "The email to $emails did't send!");
    }

    /*
     * This method useful for stop your system with an error
     */
    function formError($redirect)
    {
        if($this->input->is_ajax_request()){
            $data = array(
                "status"=>"static-error",
                "url"=>$redirect,
                "error"=> validation_errors(),
            );
            echo  json_encode($data);
            exit;
        }else{
            $this->session->set_flashdata('static_error', validation_errors());
            redirect($redirect);
        }
    }

    /**
     * Redirect to the account locked
     */
    private function accountLock()
    {
        if(in_array($this->router->method, array("logout", "accountLocked"))) {
            return;
        }
        redirect(base_url()."account-locked");
        die();
    }
}