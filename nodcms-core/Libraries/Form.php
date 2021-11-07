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

namespace NodCMS\Core\Libraries;

use Config\Models;
use \Config\Services;

/**
 * Class Form
 * Handle a NodCMS html form
 */
class Form
{
    /**
     * @var \CodeIgniter\HTTP\RedirectResponse|string
     */
    private $errorResponse;

    private $lang;

    private $acceptedFields;

    public $CI;
    public $template_form = '';
    public $template_inputs = '';
    public $template_submit = '';
    public $data = array(
        'google-invisible-reCaptcha'=>0,
    );
    public $inputs = array();
    public $type = '';
    public $theme_patch = '';
    public $style = 'bootstrap-horizontal';
    public $has_error = false;
    public $error_message = "";
    public $form_theme = "form_portlet";
    public $styles = array(
        'bootstrap4-horizontal'=>array(
            'theme' => 'bootstrap',
            'form_group' => 'form_group_horizontal',
            'form_header' => 'header_horizontal',
            'form_class' => '',
            'form_content' => 'form_content',
            'class_input_large'=>"form-control-lg",
            'class_sr_only'=>"",
        ),
        'bootstrap-horizontal'=>array(
            'theme' => 'bootstrap',
            'form_group' => 'form_group_horizontal',
            'form_header' => 'header_horizontal',
            'form_class' => 'form-horizontal',
            'form_content' => 'form_content',
            'class_input_large'=>"input-large",
            'class_sr_only'=>"",
        ),
        'bootstrap-vertical'=>array(
            'theme' => 'bootstrap',
            'form_group' => 'form_group',
            'form_header' => 'header',
            'form_class' => 'form-vertical',
            'form_content' => 'form_content_vertical',
            'class_input_large'=>"input-large",
            'class_sr_only'=>"",
        ),
        'bootstrap-inline'=>array(
            'theme' => 'bootstrap',
            'form_group' => 'form_group',
            'form_header' => 'header',
            'form_class' => 'form-inline',
            'form_content' => 'form_content_inline',
            'class_input_large'=>"",
            'class_sr_only'=>"sr-only",
        ),
    );

    private $submit_label = "";
    private $submit_class = "";

    private $headers = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div');
    private $statics = array('static');
    private $normal_inputs = array('text', 'email', 'url', 'password', 'hidden');
    private $special_inputs = array('captcha');
    private $text_area = array('textarea', 'texteditor', 'texteditor-quick', 'codeeditor');
    private $select_inputs = array('select','select-array', 'select-radio', 'select-radio-inline', 'select-radio-md',
        'select-check', 'select-check-md', 'select-radio-image-md', 'select-radio-image', 'multiselect', 'select-text');
    private $images_inputs = array('image', 'image-library', 'image-provider', 'image-user');
    private $single_checkbox = array('switch', 'switch-check', 'accept-terms');
    private $upload_files = array('files');
    private $sub_items = array('sortable-list', 'jquery-repeater');
    private $icons = array('icons');
    private $dates = array('date', 'multi-date', 'range-date','datepicker', 'datemask');
    private $currency = array('currency');
    private $range = array('range','range-select','range-slider');
    private $attachment = array('attachment');
    private $ratings = array('rating');
    private $times = array('mask-time');
    private $numbers = array('number');

    private $upload_cookie_name = "nodcms-form-upload";

    private $custom_file = "";
    private $is_custom = false;

    function __construct($CI)
    {
        $this->CI = $CI;
        $this->upload_cookie_name = md5($this->upload_cookie_name);
        $this->lang = Services::language()->getLocale();
    }

    function config($inputs, $action = '', $method = 'post', $type = 'ajax', $notes = array())
    {
        $this->data['inputs'] = $inputs;
        $this->data['action'] = $action;
        $this->data['back_url'] = $action;
        $this->data['method'] = $method;
        $this->data['notes'] = $notes;
        $this->data['tabs'] = array();
        $this->type = $type;
        $this->theme_patch = 'common/form/'.$this->styles[$this->style]['theme'].'/';
    }

    function staticConfig($style)
    {
        $this->data['inputs'] = "";
        $this->data['action'] = "";
        $this->data['back_url'] = "";
        $this->data['method'] = "";
        $this->data['notes'] = array();
        $this->data['tabs'] = array();
        $this->type = "";
        $this->style = $style;
        $this->theme_patch = 'common/form/'.$this->styles[$this->style]['theme'].'/';
    }

    /**
     * Insert into notes array
     *
     * @param $value
     */
    function addNote($value)
    {
        array_push($this->data['notes'], $value);
    }

    /**
     * Set form theme
     *
     * @param $value
     */
    function setFormTheme($value)
    {
        $this->form_theme = $value;
    }

    /**
     * Set submit button label
     *
     * @param $value
     */
    function setSubmitLabel($value)
    {
        $this->submit_label = $value;
    }

    /**
     * Set submit button class
     *
     * @param $value
     */
    function setSubmitClass($value)
    {
        $this->submit_class = $value;
    }

    /**
     * Insert into notes array
     *
     * @param $value
     */
    function setNotes($value)
    {
        $this->data['notes'] = $value;
    }

    /**
     * Set form style
     *
     * @param $value
     */
    function setStyle($value)
    {
        $this->style = key_exists($value, $this->styles)?$value:$this->style;
    }

    /**
     * Set form tabs
     *
     * @param $value
     */
    function setTabs($value)
    {
        $this->data['tabs'] = $value;
    }

    /**
     * Set google invisible reCaptcha
     */
    function setGoogleCaptcha()
    {
        $this->setCaptcha("google-invisible");
    }

    /**
     * Set a captcha on form
     *
     * @param string $type
     */
    function setCaptcha($type = "default")
    {
        // Codeigniter captcha helper
        if($type == "default"){
            $this->data['captcha'] = 1;
            array_push($this->data['inputs'],array(
                'field'=>"captcha",
                'label'=>_l('Captcha',$this->CI),
                'rules'=>"required|callback_validCaptcha",
                'type'=>"captcha",
            ));
        }
        // Google invisible reCaptcha
        elseif($type == "google-invisible"){
            $this->data['google-invisible-reCaptcha'] = 1;
            if(isset($this->CI->settings["google_captcha_site_key"]) && $this->CI->settings["google_captcha_site_key"]!=""){
                array_push($this->data['inputs'],array(
                    'field'=>"google-invisible-reCaptcha-token",
                    'label'=>_l("Google invisible reCaptcha", $this->CI),
                    'rules'=>"required|callback_validGoogleInvisibleReCaptcha",
                    'type'=>"hidden",
                ));
            }
        }
    }

    /**
     * Check sending data
     *
     * @return bool
     */
    function ispost()
    {
        if(Services::request()->getMethod() == 'post' && count(Services::request()->getPost()) != 0)
            return true;
        return false;
    }

    /**
     * Check sending data
     *
     * @return bool
     */
    function isget()
    {
        if($this->CI->request->getMethod() == 'get' && count($this->CI->request->getGet()) !=0 )
            return true;
        return false;
    }

    /**
     * Return an array of inputs
     *
     * @param null $url
     * @return array|false
     * @throws \Exception
     */
    function getPost($url = null)
    {
        $validation = Services::validation();
        $method = strtolower($this->data['method']);
        $input_data = $method == 'get' ? Services::request()->getGet() : Services::request()->getPost();
        $config = $this->getRules();
        // Check rules empty
        if($config != null){
            if($url != null)
                $this->data['back_url'] = $url;

            if(boolval(SSL_PROTOCOL) != Services::request()->isSecure()) {
                $response = Services::quickResponse();
                $this->errorResponse = $response->getError("Data post was not secure!", $this->data['back_url']);
                return false;
            }

            // Check form validation
            $validation->setRules(array_combine(array_column($config, 'field'), $config));

            // Form Error
            if ($validation->run($input_data) != TRUE) {
                $response = Services::quickResponse();
                $this->errorResponse = $response->getFormError($this->convertKeysToFields($validation->getErrors()), $validation->listErrors(), $this->data['back_url']);
                return false;
            }
        }

        // Remove unset data
        if($config!=null){
            $config_fields = $this->acceptedFields;
            foreach ($input_data as $key=>$val){
                if(!is_array($val) && !in_array($key, $config_fields)) {
                    unset($input_data[$key]);
                }
            }
        }
        return $input_data;
    }

    function getRules(): ?array
    {
        if(empty($this->data['inputs'])){
            return null;
        }
        $remove_types = array_merge($this->headers,$this->statics);
        // Remove headers tag from rules
        $inputs = [];
        $_inputs = [];
        foreach ((array) $this->data['inputs'] as $key=>$item) {
            if (!isset($item['type']) || in_array($item['type'], $remove_types)){
                continue;
            }
            if(in_array($item['type'], $this->sub_items)){
                if(isset($item['sub_items'])){
                    $post_key = explode('[',$item['field']);
                    $post_key = count($post_key)!=0?$post_key[0]:$item['field'];
                    if(isset($_POST[$post_key]) && is_array($_POST[$post_key])){
                        foreach ($_POST[$post_key] as $theKey=>$post_item){
                            foreach ($item['sub_items'] as $sun_item){
                                $_inputs[] = $sun_item['field'];
                                if(empty($sun_item['rules']))
                                    continue;
                                $sun_item['field'] = str_replace('[]',".$theKey",$item['field'].$sun_item['field']);
                                array_push($inputs, $sun_item);
                            }
                        }
                        continue;
                    }
                }
            }

            $_inputs[] = $item['field'];

            if(empty($item['rules']))
                continue;

            $inputs[$key] = $item;
        }

        $this->acceptedFields = $_inputs;

        $sub_items = array_column($inputs, 'sub_items');
        if(count($sub_items)!=0)
            $inputs = array_merge($inputs, $sub_items[0]);

        // Check empty rules
        if(empty($inputs))
            return null;

        foreach($inputs as $key=>$item) {
            $inputs[$key]['field'] = preg_replace('/\[([\w\d\-_]+)]/', ".$1", $item['field']);
        }

        return $this->convertFieldsToKeys($inputs);
    }

    /**
     * Fetch inputs and return the form view
     *
     * @param null $class
     * @param array $attr
     * @param bool $ajax_modal_format
     * @return mixed
     */
    function fetch($class = null, $attr = array(), $ajax_modal_format = true)
    {
        $this->inputs($this->data['inputs']);

        if(!isset($attr['id'])){
            $form_id = 'form'.md5(rand(0,50));
        }else{
            $form_id = $attr['id'];
            unset($attr['id']);
        }

        if(count($this->data['notes'])!=0)
            $this->data['notes'] = Services::formLayout()->setData(array('notes'=>$this->data['notes']))->render($this->theme_patch.'notes');
        else
            $this->data['notes'] = '';

        if($this->custom_file != ''){
            $this->is_custom = true;
            $form_content = Services::formLayout()->setData(array_merge(array('inputs'=>$this->inputs),$this->CI->data))->render($this->custom_file);
        }

        elseif(isset($this->data['columns']) && count($this->data['columns'])!=0){
            $form_content = '<div class="row">';
            foreach ($this->data['columns'] as $item){
                $form_content .= '<div class="'.$item["class"].'">';
                if(is_array($item['items'])){
                    foreach ($item['items'] as $index){
                        if(isset($this->inputs[$index])){
                            $form_content .= $this->inputs[$index];
                        }
                    }
                }else{
                    if(isset($this->inputs[$item['items']])){
                        $form_content .= $this->inputs[$item['items']];
                    }
                }
                $form_content .= '</div>';
            }
            $form_content .= '</div>';
        }
        else{
            $form_content = implode(' ', $this->inputs);
        }

        $submit_attr = array();
        $submit_class = array();
        if(isset($this->data['google-invisible-reCaptcha']) && $this->data['google-invisible-reCaptcha']==1){
            if(isset($this->CI->settings["google_captcha_site_key"]) && $this->CI->settings["google_captcha_site_key"]!=""){
                array_push($submit_class, "g-recaptcha");
                $submit_attr['data-sitekey'] = $this->CI->settings["google_captcha_site_key"];
                $submit_attr['data-callback'] = "onSubmit";
                $submit_attr['onclick'] = "$(this).addClass('disabled').prepend($('<i class=\'fa fa-spinner fa-pulse loading-icon\'></i>'))";
                $attr['data-grecaptcha'] = 1;
            }
        }

        $data_output = array(
            'class'=>$this->styles[$this->style]['form_class'].' '.$class,
            'attr'=>$attr,
            'form_content'=>$form_content,
            'form_type'=>$this->type,
            'form_theme'=>$this->theme_patch.$this->form_theme,
            'form_id'=>$form_id,
            'tabs'=>$this->data['tabs'],
            'form_content_theme'=>$this->theme_patch.$this->styles[$this->style]['form_content'],
            'submit_attr'=>$submit_attr,
            'submit_class'=>$this->submit_class." ".join(" ", $submit_class),
            'modal_format'=>($this->CI->request->isAjax() && $ajax_modal_format),
        );
        if($this->submit_label!="") $data_output['submit_label'] = $this->submit_label;
        $data_output = array_merge($data_output, $this->data);

        $this->htmlIncludeFiles($this->data['inputs']);
        $form_content = Services::formLayout()->setData($data_output)->render($this->theme_patch.'form');

        if($this->CI->request->isAjax() && $ajax_modal_format){
            return json_encode(array(
                'status'=>'success',
                'content'=>$form_content,
                'title'=>isset($this->data["form_title"])?$this->data["form_title"]:_l("Form", $this->CI),
                'closeBtnLabel'=>_l("Cancel", $this->CI),
                'footerButtons'=>array(
                    array('color'=>"btn-primary", 'onclick'=>"$('#$form_id').submit();", 'caption'=>"<i class='fas fa-check'></i> ".(isset($data_output['submit_label'])?$data_output['submit_label']:_l("Submit", $this->CI))),
                ),
            ));
        }
        return $form_content;
    }

    /**
     * Fetch inputs and return the customized form view
     *
     * @param $custom_file
     * @param array $attr
     * @param bool $ajax_modal_format
     * @return string
     */
    function fetchCustom($custom_file, $attr = array(), $ajax_modal_format = true)
    {
        $this->is_custom = true;
        $this->inputs($this->data['inputs']);

        if(!isset($attr['id'])){
            $form_id = 'form'.md5(rand(0,50));
        }else{
            $form_id = $attr['id'];
            unset($attr['id']);
        }

        if(count($this->data['notes'])!=0)
            $this->data['notes'] = Services::formLayout()->setData(array('notes'=>$this->data['notes']))->render($this->theme_patch.'notes');
        else
            $this->data['notes'] = '';

        $form_content = Services::formLayout()->setData(array_merge(array('inputs'=>$this->inputs),$this->CI->data))->render($custom_file);

        $submit_attr = array();
        $submit_class = array();
        if(isset($this->data['google-invisible-reCaptcha']) && $this->data['google-invisible-reCaptcha']==1){
            if(isset($this->CI->settings["google_captcha_site_key"]) && $this->CI->settings["google_captcha_site_key"]!=""){
                array_push($submit_class, "g-recaptcha");
                $submit_attr['data-sitekey'] = $this->CI->settings["google_captcha_site_key"];
                $submit_attr['data-callback'] = "onSubmit";
                $attr['data-grecaptcha'] = 1;
            }
        }

        $data_output = array(
            'class'=>$this->styles[$this->style]['form_class'],
            'attr'=>$attr,
            'form_content'=>$form_content,
            'form_type'=>$this->type,
            'form_theme'=>$this->theme_patch.$this->form_theme,
            'form_id'=>$form_id,
            'tabs'=>$this->data['tabs'],
            'form_content_theme'=>$this->theme_patch.$this->styles[$this->style]['form_content'],
            'submit_attr'=>$submit_attr,
            'submit_class'=>join(" ", $submit_class),
            'modal_format'=>($this->CI->request->isAjax() && $ajax_modal_format),
        );
        if($this->submit_label!="") $data_output['submit_label'] = $this->submit_label;
        $data_output = array_merge($data_output, $this->data);

        $this->htmlIncludeFiles($this->data['inputs']);

        $form_content = Services::formLayout()->setData($data_output)->render($this->theme_patch.'form');

        if($this->CI->request->isAjax()){
            return json_encode(array(
                'status'=>'success',
                'content'=>$form_content,
                'title'=>isset($this->data["form_title"])?$this->data["form_title"]:_l("Form", $this->CI),
                'closeBtnLabel'=>_l("Cancel", $this->CI),
                'footerButtons'=>array(
                    array('color'=>"blue", 'onclick'=>"$('#$form_id').submit();", 'caption'=>_l("Submit", $this->CI)),
                ),
            ));
        }
        return $form_content;
    }

    /**
     * Add the requires css and js files to the documents
     *
     * @param $inputs
     */
    private function htmlIncludeFiles($inputs)
    {
        $all_types = array_column($inputs, 'type');
        $sub_inputs = array_column($inputs, 'sub_items');
        foreach ($sub_inputs as $item){
            $sub_inputs_types = array_column($item, 'type');
            $all_types = array_merge($all_types, $sub_inputs_types);
        }

        Services::formLayout()->addJsFile("assets/nodcms/form-handler/form-handler.min");

        if(in_array('datepicker', $all_types)){
            Services::formLayout()->addCssFile("assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min");
            Services::formLayout()->addJsFile("assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min");
        }
        if(in_array('date', $all_types)){
            Services::formLayout()->addCssFile("assets/plugins/jquery-ui-1.12.1/jquery-ui.min");
            Services::formLayout()->addJsFile("assets/plugins/jquery-ui-1.12.1/jquery-ui.min");
        }
        if(in_array('image', $all_types)){
            Services::formLayout()->addCssFile("assets/plugins/bootstrap-fileinput/bootstrap-fileinput");
            Services::formLayout()->addJsFile("assets/plugins/bootstrap-fileinput/bootstrap-fileinput");
        }
        if(in_array('image-library', $all_types)){
            Services::formLayout()->addCssFile("assets/mini-upload-image/css/style");
            Services::formLayout()->addJsFile("assets/mini-upload-image/js/jquery.knob");
            Services::formLayout()->addJsFile("assets/mini-upload-image/js/jquery.ui.widget");
            Services::formLayout()->addJsFile("assets/mini-upload-image/js/jquery.iframe-transport");
            Services::formLayout()->addJsFile("assets/mini-upload-image/js/jquery.fileupload");
            Services::formLayout()->addJsFile("assets/mini-upload-image/js/script");
            Services::formLayout()->addJsFile("assets/nodcms/form-handler/input-image-library.min");
        }

        if(in_array('icons', $all_types)){
            Services::formLayout()->addJsFile("assets/nodcms/form-handler/select-icons");
        }
        if(in_array('texteditor', $all_types)){
            Services::formLayout()->addJsFile("assets/plugins/ckeditor/ckeditor");
        }
        if(in_array('texteditor-quick', $all_types)){
            Services::formLayout()->addJsFile("assets/plugins/ckeditor/ckeditor");
        }
        if(in_array('codeeditor', $all_types)){
            Services::formLayout()->addCssFile("assets/plugins/codemirror/lib/codemirror");
            Services::formLayout()->addCssFile("assets/plugins/codemirror/theme/neat");
            Services::formLayout()->addCssFile("assets/plugins/codemirror/theme/ambiance");
            Services::formLayout()->addCssFile("assets/plugins/codemirror/theme/material");
            Services::formLayout()->addCssFile("assets/plugins/codemirror/theme/neo");

            Services::formLayout()->addJsFile("assets/plugins/codemirror/lib/codemirror");
            Services::formLayout()->addJsFile("assets/plugins/codemirror/mode/javascript/javascript");
            Services::formLayout()->addJsFile("assets/plugins/codemirror/mode/htmlmixed/htmlmixed");
//            \Configself::layout()->addJsFile("assets/plugins/codemirror/mode/htmlembedded/htmlembedded");
            Services::formLayout()->addJsFile("assets/plugins/codemirror/mode/css/css");
        }
        // Multi upload files
        if(in_array('files', $all_types)){
            Services::formLayout()->addCssFile("assets/plugins/dropzone/dropzone");
            Services::formLayout()->addCssFile("assets/plugins/dropzone/basic");
            Services::formLayout()->addJsFile("assets/plugins/dropzone/dropzone");
            Services::formLayout()->addJsFile("assets/nodcms/form-handler/files");
        }

        if(in_array('jquery-repeater', $all_types)){
            Services::formLayout()->addJsFile("assets/plugins/jquery-repeater/jquery.repeater");
        }
        if(in_array('currency', $all_types)){
            Services::formLayout()->addJsFile("assets/nodcms/form-handler/input-currency");
        }
        if(in_array('attachment', $all_types)){
            Services::formLayout()->addJsFile("assets/nodcms/form-handler/input-attachment");
        }
        if(in_array('number', $all_types)){
            Services::formLayout()->addJsFile("assets/nodcms/form-handler/input-number");
        }

        if(in_array('range-select', $all_types) || in_array('range', $all_types)){
            Services::formLayout()->addJsFile("assets/plugins/ion.rangeSlider-2.1.7/js/ion-rangeSlider/ion.rangeSlider");
            Services::formLayout()->addCssFile("assets/plugins/ion.rangeSlider-2.1.7/css/ion.rangeSlider");
            Services::formLayout()->addCssFile("assets/plugins/ion.rangeSlider-2.1.7/css/ion.rangeSlider.skinHTML5");
            Services::formLayout()->addJsFile("assets/nodcms/form-handler/input-range");
        }

        Services::formLayout()->addCssFile("assets/plugins/icheck/skins/all");
        Services::formLayout()->addJsFile("assets/plugins/icheck/icheck.min");
        Services::formLayout()->addCssFile("assets/plugins/bootstrap-touchspin/bootstrap.touchspin");
        Services::formLayout()->addJsFile("assets/plugins/bootstrap-touchspin/bootstrap.touchspin");
        // Switch
        Services::formLayout()->addCssFile("assets/plugins/bootstrap-switch/css/bootstrap-switch.min", "assets/plugins/bootstrap-switch/css/bootstrap-switch-rtl.min");
        Services::formLayout()->addJsFile("assets/plugins/bootstrap-switch/js/bootstrap-switch.min");
        // Global js
        Services::formLayout()->addJsFile("assets/nodcms/form-handler/form-handler");

    }

    /**
     * Read inputs from config array and make html inputs
     *  - This method put the html inputs in an array with input index
     *
     * @param null $data
     */
    private function inputs($data = null)
    {
        foreach ($data as $item){
            $input = $this->fetchItem($item);
            if($input!=false){
                if(!isset($item['field']))
                    $this->inputs[] = $input;
                else
                    $this->inputs[$item['field']] = $input;

            }
        }
    }

    static function makeInput($data, $style = "bootstrap-inline"){
        $theForm = new Form;
        $theForm->staticConfig($style);
        $default_data = array(
            'field'=>"",
            'label'=>"",
            'default'=>"",
            'type'=>"text",
        );
        $data = array_merge($default_data, $data);
        return $theForm->fetchItem($data);
    }

    static function makeInputs($data, $style = "bootstrap-inline"){
        $theForm = new Form;
        $theForm->staticConfig($style);
        $default_data = array(
            'field'=>"",
            'label'=>"",
            'default'=>"",
            'type'=>"text",
        );
        $inputs = array();
        foreach ($data as $item){
            $item = array_merge($default_data, $item);
            array_push($inputs, $theForm->fetchItem($item));
        }
        $theForm->htmlIncludeFiles($data);
        return join('',$inputs);
    }

    /**
     * Fetch items, find out which method should run for an item(input type)
     *
     * @param $data
     * @param bool $is_custom
     * @return bool|null|string
     */
    private function fetchItem($data, $is_custom = false)
    {
        if(!isset($data['type'])){
            return false;
        }

        $this->is_custom = $is_custom;

        // Set label prefix
        $data['prefix'] = isset($data['prefix'])?$data['prefix']:'';
        if(isset($data['prefix_language'])){
            $data['prefix'] .= Services::formLayout()->setData($data["prefix_language"])->render($this->theme_patch.'postfix_language');
        }
        // Set label postfix
        $data['postfix'] = isset($data['postfix'])?$data['postfix']:'';
        if(isset($data['postfix_language'])){
            $data['postfix'] .= Services::formLayout()->setData($data["postfix_language"])->render($this->theme_patch.'postfix_language');
        }

        if(in_array($data['type'], $this->headers)){
            return $this->addHeader($data);
        }

        // Replace the characters '[' and ']' with '_1' and '1_' in field_id that will been in inputs "id" attr
        if(isset($data['field']))
            $data['field_id'] = str_replace(array('[', ']'), array('_1', '1_'), $data['field']);

        // Check "self" value in default option in $_GET requests and replace them with the $_GET values
        if($this->data['method'] == 'get' && isset($data['default']) && $data['default']=="self")
            $data['default'] = $this->CI->input->get($data['field'])!=null?$this->CI->input->get($data['field']):'';

        /* In this conditions each method contains an special default variables
            to call the view to return the input html codes */
        // All normals inputs
        if(in_array($data['type'], $this->normal_inputs)){
            return $this->addInput($data);
        }
        // Special inputs
        if(in_array($data['type'], $this->special_inputs)){
            return $this->addSpecialInputs($data);
        }
        // Inputs to enter dates
        elseif(in_array($data['type'], $this->dates)){
            return $this->addDate($data);
        }
        // Inputs to upload and attach files
        elseif(in_array($data['type'], $this->upload_files)){
            return $this->addInputFiles($data);
        }
        // Inputs with sub items
        elseif(in_array($data['type'], $this->sub_items)){
            return $this->addInputWithSubItems($data);
        }
        // A textarea
        elseif(in_array($data['type'], $this->text_area)){
            return $this->addTextarea($data);
        }
        // Raining stars
        elseif(in_array($data['type'], $this->ratings)){
            return $this->addRating($data);
        }
        // Select boxes to enter some selective values
        elseif(in_array($data['type'], $this->select_inputs)){
            return $this->addSelect($data);
        }
        // Upload and attach images
        elseif(in_array($data['type'], $this->images_inputs)){
            return $this->addImage($data);
        }
        // A list of check box
        elseif($data['type']=='checkbox'){
            return $this->addCheckbox($data);
        }
        // A single checkbox
        elseif(in_array($data['type'], $this->single_checkbox)){
            return $this->addSwitch($data);
        }
        // Static text (display such as lable)
        elseif(in_array($data['type'],$this->statics)){
            return $this->addStatic($data);
        }
        // Input to search and enter font icons
        elseif(in_array($data['type'],$this->icons)){
            return $this->addSelectIcons($data);
        }
        // Input to enter numbers
        elseif(in_array($data['type'],$this->numbers)){
            return $this->addNumber($data);
        }
        // Input to enter a currency value
        elseif(in_array($data['type'],$this->currency)){
            return $this->addCurrency($data);
        }
        // Input to enter a range of numbers
        elseif(in_array($data['type'],$this->range)){
            return $this->addRange($data);
        }
        // Input to search and enter some defined values
        elseif(in_array($data['type'],$this->attachment)){
            return $this->addAttachment($data);
        }
        // Input to search and enter some defined values
        elseif(in_array($data['type'],$this->times)){
            return $this->addTimes($data);
        }
        return false;
    }

    private function addHeader($data)
    {
        $default = array(
            'tag'=>$data['type'],
            'label'=>"",
            'description'=>'',
            'class'=>'',
            'group_class'=>'',
            'postfix'=>"",
            'prefix'=>"",
        );
        $data_output = array_merge($default, $data);
        $output = Services::formLayout()->setData($data_output)->render($this->theme_patch.$this->styles[$this->style]['form_header']);
        return $output;
    }

    private function addInput($data, $input = null)
    {
        if($input==null){
            $data_default = array(
                'class'=>"input-large",
                'attr'=>array(),
                'field_id'=>"",
                'name'=>$data['field'],
                'type'=>"",
                'default'=>"",
                'input_prefix'=>"",
                'input_postfix'=>"",
            );
            $data = array_merge($data_default,$data);
            $input = Services::formLayout()->setData($data)->render($this->theme_patch.'input');
            if($data['type']=="hidden"){
                return $input;
            }
        }
        if($this->is_custom){
            return $input;
        }
        if(isset($data['help']) && $data['help']!='')
            $help = Services::formLayout()->setData(array('message'=>$data['help']))->render($this->theme_patch.'help');
        else
            $help = '';
        if($this->style=="bootstrap-inline" && $data['label']!=""){
            $input = "<br>$input";
        }
        $data_output_default = array(
            'input'=>$input,
            'field_id'=>"",
            'label'=>"",
            'label_class'=>"",
            'help'=>$help,
            'group_class'=>"",
            'postfix'=>"",
            'prefix'=>"",
        );
        $data_output = array_merge($data_output_default, $data);
        $output = Services::formLayout()->setData($data_output)->render($this->theme_patch.$this->styles[$this->style]['form_group']);
        return $output;
    }

    private function addInputWithSubItems($data)
    {
        $default_data = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>'',
            'name'=>$data['field'],
            'type'=>'select',
            'sub_items'=>array(),
            'default'=>array(),
            'custom_file'=>'',
        );
        $data = array_merge($default_data, $data);
        $default_item = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>'',
            'field'=>"",
            'type'=>'select',
            'sub_items'=>array(),
            'default'=>'',
        );
        $sub_items = $data['sub_items'];
        $data['sub_items'] = array();
        foreach ($sub_items as $key=>$item){
            $item = array_merge($default_item, $item);
            $item['default'] = "";
            $item['attr']['data-name'] = $data['field'].$item['field'];
            if($data['custom_file']!='') {
                $item['form'] = $this->fetchItem($item, true);
            }else{
                $item['form'] = $this->fetchItem($item);
            }
            $data['sub_items'][$item['field']] = $item;
        }

        $default_values = array();
        $default_values_array = array();
        if($data['default']!=null){
            $index = 0;
            // * The result form DB
            foreach ($data['default'] as &$default){
                $items = array();
                foreach ($sub_items as $sub_item){
                    $sub_item = array_merge($default_item, $sub_item);
                    $field = $sub_item['field'];
                    if($sub_item['type']!='static')
                        $sub_item['attr']['data-name'] = $data['field'].$sub_item['field'];
                    $sub_item['field'] = str_replace('[]',"[$index]",$data['field'].$sub_item['field']);
                    if($sub_item['default']!='' && isset($default[$sub_item['default']])){
                        if($sub_item['type']!='static')
                            $sub_item['default'] = $default[$sub_item['default']];
                        else
                            $sub_item['value'] = $default[$sub_item['default']];
                    }
                    else{
                        $sub_item['default'] = "";
                    }
                    if($data['custom_file']!='') {
                        $items[$field] = $this->fetchItem($sub_item, true);
                    }else{
                        $items[$field] =$this->fetchItem($sub_item);
                    }
                }
                if(count($items)!=0)
                    $default_values[] = join('', $items);
                    $default_values_array[] = $items;
                $index++;
            }
        }
        $data['default'] = json_encode($default_values);
        $data['default_array'] = $default_values_array;

        $data['items'] = str_replace(array('<','>'),array("&lt;","&gt;"),join('', array_column($data['sub_items'], 'form')));
        if($data['custom_file']!=''){
            $input = Services::formLayout()->setData($data)->render($data['custom_file']);
        }else{
            $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        }
        if($this->custom_file == '') $this->is_custom = false;
        return $this->addInput($data, $input);
    }

    private function addSelect($data)
    {
        $default_data = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>'',
            'name'=>$data['field'],
            'type'=>'select',
            'options'=>array(),
            'option_value'=>'',
            'option_name'=>'',
            'option_description'=>'',
            'option_theme'=>'',
            'option_image'=>'',
            'default'=>'',
            'custom_file'=>'',
        );
        $data = array_merge($default_data, $data);
        if($data['custom_file']!=''){
            $input = Services::formLayout()->setData($data)->render($data['custom_file']);
        }else{
            $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        }
        return $this->addInput($data, $input);
    }

    private function addSpecialInputs($data)
    {
        $default_data = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>'',
            'name'=>$data['field'],
            'type'=>'select',
            'default'=>'',
        );
        $data = array_merge($default_data, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addDate($data)
    {
        $default_data = array(
            'format'=>'', // * Not working any more
            'calendarfilter'=>'',
            'class'=>"",
            'default_microtime'=>'',
            'attr'=>array(),
            'datepicker'=>array(),
            'field_id'=>"",
            'name'=>$data['field'],
            'default'=>"",
        );
        $data = array_merge($default_data, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addImage($data)
    {
        $data_default = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>"",
            'name'=>$data['field'],
            'type'=>"",
            'default'=>"",
            'remove_url'=>"",
            'not_set_preview'=>base_url("{$this->lang}/noimage-200-50-Not_Set"),
            'library_type'=>"images-library",
        );
        $data = array_merge($data_default, $data);
        $data['image_library_url'] = ADMIN_URL."getImagesLibrary/$data[name]/$data[library_type]";
        $data['img_src'] = base_url($data['default']!=""?$data['default']:"{$this->lang}/noimage-200-50-Not_Set");
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addInputFiles($data)
    {
        $data_default = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>"",
            'name'=>$data['field'],
            'type'=>"",
            'accept_types'=>"pdf-txt-jpg-png",
            'default'=>"",
            'upload_url'=>"",
            'upload_key'=>"",
            'max_files'=>"null",
            'max_file_size'=>"null",
        );
        $data = array_merge($data_default, $data);
        if($data['accept_types']=="")
            $data['accept_types'] = "*";
        elseif($data['accept_types']!="*")
            $data['accept_types'] = ".".str_replace('-',',.',$data['accept_types']);
        if($data['default']=="from-cookies"){
            $data['default'] = $this->getLastUploadedFiles($data['upload_key']);
        }
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addTextarea($data)
    {
        $data_default = array(
            'class'=>'',
            'shortkeys'=>array(),
            'html_keys'=>array(),
            'attr'=>array(),
            'field_id'=>"",
            'name'=>$data['field'],
            'default'=>"",
        );
        $data = array_merge($data_default, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addRating($data)
    {
        $data_default = array(
            'class'=>'',
            'count'=>5,
            'item_icon_off'=>"",
            'item_icon_on'=>"",
            'attr'=>array(),
            'field_id'=>"",
            'name'=>$data['field'],
            'default'=>"",
        );
        $data = array_merge($data_default, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addNumber($data)
    {
        $data_default = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>"",
            'name'=>$data['field'],
            'default'=>"",
            'min'=>0,
            'max'=>0,
        );
        $data = array_merge($data_default, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addCurrency($data)
    {
        $data_default = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>"",
            'name'=>$data['field'],
            'default'=>"",
            'default_formatted'=>"",
            'before_sign' => "",
            'after_sign' => "",
            'divider' => ".",
        );
        $data = array_merge($data_default, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addRange($data)
    {
        $data_default = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>"",
            'name'=>$data['field'],
            'default'=>"",
            'min'=>"",
            'max' => "",
            'divider' => "-",
            'grid' => 1,
        );
        $data = array_merge($data_default, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addAttachment($data)
    {
        $data_default = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>"",
            'name'=>$data['field'],
            'default'=>"",
            'url' => "-",
        );
        $data = array_merge($data_default, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addSwitch($data)
    {
        $data_default = array(
            'class'=>'',
            'attr'=>array(),
            'field_id'=>'',
            'name'=>$data['field'],
            'default'=>'',
            'description'=>'',
        );
        $data = array_merge($data_default, $data);
        $data['default'] = $data['default']!=1?'':'checked';
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addCheckbox($data)
    {
        $data_default = array(
            'label'=>'',
            'name'=>$data['field'],
            'class'=>'',
            'attr'=>array(),
            'default'=>'',
            'description'=>'',
            'sub_items'=>array(),
        );
        $data = array_merge($data_default, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addSelectIcons($data)
    {
        $data_default = array(
            'label'=>'',
            'name'=>$data['field'],
            'class'=>'',
            'attr'=>array(),
            'default'=>'',
            'modal_title'=>_l("Select Icons", $this->CI),
        );
        $data = array_merge($data_default, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addStatic($data)
    {
        $data_default = array(
            'label'=>'',
            'value'=>'',
            'tag'=>'p',
            'class'=>'',
            'field_id'=>"",
            'name'=>$data['field'],
            'attr'=>array(),
        );
        $data = array_merge($data_default, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    private function addTimes($data)
    {
        $data_default = array(
            'label'=>'',
            'value'=>'',
            'mask'=>'99:99',
            'class'=>'',
            'field_id'=>"",
            'name'=>$data['field'],
            'attr'=>array(),
        );
        $data = array_merge($data_default, $data);
        $input = Services::formLayout()->setData($data)->render($this->theme_patch.$data['type']);
        return $this->addInput($data, $input);
    }

    /**
     * #Upload file: Upload a file with saving in database table "upload_files"
     *
     * @param string $path
     * @param string $save_key
     * @param string|array $allowed_types
     * @param bool $encrypt_name
     * @param int $file_public
     * @param string $validation
     * @return string
     * @throws \Exception
     */
    function uploadFile(string $path, string $save_key, $allowed_types, bool $encrypt_name = true, int $file_public = 0,string $validation = ""): string
    {
        /* TODO: set size limit from settings */
        /* TODO: set external upload file from settings */
        /* TODO: set file deadline from settings */
        // * Make directory if doesn't exists

        $unique_cookie = get_cookie($this->upload_cookie_name);
        if($unique_cookie == null){
            $file_count = 1;
            while ($file_count != 0){
                $new_unique_cookie = substr(md5(rand(1000,9999)+time()-rand(1000,9999)),rand(0,10),10);
                $file_count = Models::uploadFiles()->getCount(['unique_cookie'=>$new_unique_cookie]);
            }
            $unique_cookie = $new_unique_cookie;
        }

        setcookie($this->upload_cookie_name, $unique_cookie, time()+86400, "/");

        $back_url = base_url("user/account");
        $upload = Services::upload()->filterTypes($allowed_types)->setBackUrl($back_url);
        if(!$upload->save("file", $path)) {
            return $upload->getErrorResponse();
        }

        if($upload->getResult()->isImage()){
            $file_thumbnail = $upload->getResult()->fullPath;
            $file_type = "image";
        }
        else{
            $file_thumbnail = "upload_file/images/file.png";
            $file_type = $upload->getResult()->fileType;
        }
        $data_insert = array(
            "user_id"=>$this->CI->userdata!=NULL?$this->CI->userdata['user_id']:0,
            "unique_cookie"=>$unique_cookie,
            "host"=>"localhost",
            "file_type"=>$file_type,
            "file_path"=>$upload->getResult()->fullPath,
            "file_thumbnail"=>$file_thumbnail,
            "file_key"=>$file_public!=0?md5(rand(1000,9999)):"public",
            "upload_key"=>$save_key,
            "remove_key"=>md5($save_key),
            "name"=>$upload->getResult()->clientName,
            "size"=>$upload->getResult()->fileSize,
            "created_date"=>time(),
            "deadline"=>0,
            "download_validation"=>$validation,
        );
        $inserted_id = Models::uploadFiles()->add($data_insert);
        if($inserted_id==0){
            return json_encode(array("status"=>"error","errors"=>_l("Unfortunately, the file could not be successfully saved.",$this->CI)));
        }
        $file_path = "file-$inserted_id-$data_insert[file_key]-$data_insert[name]";
//        $file_url = base_url($file_path);
        return json_encode(array("status"=>"success",
            "file_id"=>$inserted_id,
            "file_key"=>$data_insert['file_key'],
            "remove_key"=>$data_insert['remove_key'],
            "file_path"=>$file_path,
//            "file_url"=>$file_url,
            "file_thumbnail"=>$file_thumbnail));
    }

    /**
     * #Upload file: Get last uploaded file from the unique cookie
     *
     * @param $upload_key
     * @return array
     */
    function getLastUploadedFiles($upload_key)
    {
        $unique_cookie = get_cookie($this->upload_cookie_name);
        if($unique_cookie == null){
            return array();
        }
        $conditions = array('unique_cookie'=>$unique_cookie, 'upload_key'=>$upload_key, 'file_using'=>0);
        $files = Models::uploadFiles()->getAll($conditions);
        $result = [];
        foreach($files as $item) {
            $_item = [
                'file_id' => $item['file_id'],
                'file_type' => $item['file_type'],
                'name' => $item['name'],
                'size' => $item['size'],
                'file_key' => $item['file_key'],
                'remove_key' => $item['remove_key'],
                'file_thumbnail' => $item['file_thumbnail']
            ];
            $url_prefix = substr($item['file_type'],0,5)=="image"?"image":"file";
            $_item['file_url'] = base_url("$url_prefix-$item[file_id]-$item[file_key]");
            $_item['name'] = $upload_key." - $item[name]";
            $result[] = $_item;
        }
        return $result;
    }

    /**
     * Select the uploaded files
     *
     * @param $file_ids
     * @return array
     */
    function getDefaultUploadedFiles($file_ids)
    {
        $unique_cookie = get_cookie($this->upload_cookie_name);
        if($unique_cookie == null){
            return array();
        }

        $conditions = "file_id IN ($file_ids)";
        $files = Models::uploadFiles()->getAll($conditions);
        $result = [];
        foreach($files as $item) {
            $_item = [
                'file_id' => $item['file_id'],
                'file_type' => $item['file_type'],
                'name' => $item['name'],
                'size' => $item['size'],
                'file_key' => $item['file_key'],
                'remove_key' => $item['remove_key'],
                'file_thumbnail' => $item['file_thumbnail']
            ];
            $url_prefix = substr($item['file_type'],0,5)=="image"?"image":"file";
            $_item['file_url'] = base_url("$url_prefix-$item[file_id]-$item[file_key]");
            $_item['name'] = "$item[name]";
            $result[] = $_item;
        }
        return $result;
    }

    /**
     * Remove the usless files
     *
     * @param $time
     */
    function removeUselessFiles($time)
    {
        $conditions = array('file_using'=>0, 'created_date <'=>$time);
        $files = Models::uploadFiles()->getAll($conditions);
        foreach($files as $item){
            $file_path = FCPATH.$item['file_path'];
            if(file_exists($file_path))
                unlink($file_path);
            Models::uploadFiles()->remove($item['file_id']);
        }
    }

    /**
     * Remove and delete files
     *
     * @param $files_id
     */
    function removeFiles($files_id)
    {
        $conditions = "file_id IN ($files_id)";
        $files = Models::uploadFiles()->getAll($conditions);
        foreach($files as $item){
            $file_path = FCPATH.$item['file_path'];
            if(file_exists($file_path))
                unlink($file_path);
        }
        Models::uploadFiles()->clean($conditions);
    }

    /**
     * Return the unique cookie codes of the uploaded files
     *
     * @return mixed
     */
    function getFileUniqueCookie()
    {
        return get_cookie($this->upload_cookie_name);
    }

    /**
     * @param array $inputs
     * @return array
     */
    private function convertFieldsToKeys(array $inputs): array
    {
        foreach($inputs as $key=>$item) {
            $inputs[$key]['field'] = preg_replace('/\[([\w\d\-_]+)]/', ".$1", $item['field']);
        }

        return $inputs;
    }

    /**
     * @param array $errors
     * @return array
     */
    private function convertKeysToFields(array $errors): array
    {
        $result = [];
        foreach($errors as $key=>$item) {
            $result[preg_replace('/\.([\w\d\-_]+)/', "[$1]", $key)] = $item;
        }

        return $result;
    }

    /**
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function getResponse()
    {
        if(!empty($this->errorResponse))
            return $this->errorResponse;

        return "";
    }
}
