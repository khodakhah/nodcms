<?php

/**
 * Created by Mojtaba Khodakhah.
 * Date: 2/15/2017
 * Time: 11:54 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
class Form
{
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

    function __construct()
    {
        $this->CI =& get_instance();
        $this->upload_cookie_name = md5($this->upload_cookie_name);
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
        $this->CI =& get_instance();
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
        $post = $this->CI->input->input_stream();
        if($post != null && count($post)!=0)
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
        $get = $this->CI->input->get();
        if($get != null && count($get)!=0)
            return true;
        return false;
    }

    /**
     *
     *
     * @param null $url
     * @return false|array
     */
    function getPost($url = null)
    {
        $method = strtolower($this->data['method']);
        $config = $this->getRules();
        // Check rules empty
        if($config != null){
            if($url != null)
                $this->data['back_url'] = $url;
            // Load form_validation library
            $this->CI->load->library('form_validation');

            if($method == 'get'){
                $this->CI->form_validation->set_data($this->CI->input->get());
            }
            // Check form validation
            $this->CI->form_validation->set_rules($config);

            // Form Error
            if ($this->CI->form_validation->run() != TRUE) {
                if($this->CI->input->is_ajax_request()){
                    $data = array(
                        "status"=>"form-error",
                        "url"=>$this->data['back_url'],
                        "error"=> $this->CI->form_validation->error_array(),
                    );
                    echo  json_encode($data);
                }else{
                    $this->CI->session->set_flashdata('static_error', validation_errors());
                    redirect($this->data['back_url']);
                }
                return false;
            }
        }

        $method = strtolower($this->data['method']);
        $result = $this->CI->input->$method(NULL, TRUE);

        // Remove unset data
        if($config!=null){
            $config_fields = array_column($config, 'field');
            foreach ($result as $key=>$val){
                if(!is_array($val) && !in_array($key, $config_fields))
                    unset($result[$key]);
            }
        }
        return (!is_array($result) || count($result) == 0) ? false : $result;
    }

    function getRules()
    {
        if(count($this->data['inputs'])==0){
            return null;
        }
        $remove_types = array_merge($this->headers,$this->statics);
        // Remove headers tag from rules
        foreach ($this->data['inputs'] as $key=>&$item) {
            if (!isset($item['type']) || in_array($item['type'], $remove_types)){
                unset($this->data['inputs'][$key]);
                continue;
            }
            if(in_array($item['type'], $this->sub_items)){
                if(isset($item['sub_items'])){
                    $post_key = explode('[',$item['field']);
                    $post_key = count($post_key)!=0?$post_key[0]:$item['field'];
                    if(isset($_POST[$post_key]) && is_array($_POST[$post_key])){
                        $index = 0;
                        foreach ($_POST[$post_key] as $theKey=>$post_item){
                            foreach ($item['sub_items'] as $sun_item){
                                $sun_item['field'] = str_replace('[]',"[$theKey]",$item['field'].$sun_item['field']);
                                array_push($this->data['inputs'], $sun_item);
                            }
                            $index++;
                        }
                        unset($this->data['inputs'][$key]);
                    }
                }
            }
        }
        $sub_items = array_column($this->data['inputs'], 'sub_items');
        if(count($sub_items)!=0)
            $inputs = array_merge($this->data['inputs'], $sub_items[0]);
        else
            $inputs = $this->data['inputs'];
        // Check empty rules
        $rules = implode('', array_column($inputs, 'rules'));
        if($rules=='')
            return null;

        return $inputs;
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
            $this->data['notes'] = $this->CI->load->view($this->theme_patch.'notes', array('notes'=>$this->data['notes']), true);
        else
            $this->data['notes'] = '';

        if($this->custom_file != ''){
            $this->is_custom = true;
            $form_content = $this->CI->load->view($this->custom_file, array_merge(array('inputs'=>$this->inputs),$this->CI->data), true);
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
            'modal_format'=>($this->CI->input->is_ajax_request() && $ajax_modal_format),
        );
        if($this->submit_label!="") $data_output['submit_label'] = $this->submit_label;
        $data_output = array_merge($data_output, $this->data);

        $this->htmlIncludeFiles($this->data['inputs']);

        $form_content = $this->CI->load->view($this->theme_patch.'form', $data_output, true);

        if($this->CI->input->is_ajax_request() && $ajax_modal_format){
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
            $this->data['notes'] = $this->CI->load->view($this->theme_patch.'notes', array('notes'=>$this->data['notes']), true);
        else
            $this->data['notes'] = '';

        $form_content = $this->CI->load->view($custom_file, array_merge(array('inputs'=>$this->inputs),$this->CI->data), true);

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
            'modal_format'=>($this->CI->input->is_ajax_request() && $ajax_modal_format),
        );
        if($this->submit_label!="") $data_output['submit_label'] = $this->submit_label;
        $data_output = array_merge($data_output, $this->data);

        $this->htmlIncludeFiles($this->data['inputs']);

        $form_content = $this->CI->load->view($this->theme_patch.'form', $data_output, true);

        if($this->CI->input->is_ajax_request()){
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

        if(in_array('datepicker', $all_types)){
            $this->CI->load->addCssFile("assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min");
            $this->CI->load->addJsFile("assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min");
        }
        if(in_array('date', $all_types)){
            $this->CI->load->addCssFile("assets/plugins/jquery-ui-1.12.1/jquery-ui.min");
            $this->CI->load->addJsFile("assets/plugins/jquery-ui-1.12.1/jquery-ui.min");
        }
        if(in_array('image', $all_types)){
            $this->CI->load->addCssFile("assets/plugins/bootstrap-fileinput/bootstrap-fileinput");
            $this->CI->load->addJsFile("assets/plugins/bootstrap-fileinput/bootstrap-fileinput");
        }
        if(in_array('image-library', $all_types)){
            $this->CI->load->addCssFile("assets/mini-upload-image/css/style");
            $this->CI->load->addJsFile("assets/mini-upload-image/js/jquery.knob");
            $this->CI->load->addJsFile("assets/mini-upload-image/js/jquery.ui.widget");
            $this->CI->load->addJsFile("assets/mini-upload-image/js/jquery.iframe-transport");
            $this->CI->load->addJsFile("assets/mini-upload-image/js/jquery.fileupload");
            $this->CI->load->addJsFile("assets/mini-upload-image/js/script");
            $this->CI->load->addJsFile("assets/nodcms/form-handler/input-image-library.min");
        }

        if(in_array('icons', $all_types)){
            $this->CI->load->addJsFile("assets/nodcms/form-handler/select-icons");
        }
        if(in_array('texteditor', $all_types)){
            $this->CI->load->addJsFile("assets/plugins/ckeditor/ckeditor");
        }
        if(in_array('texteditor-quick', $all_types)){
            $this->CI->load->addJsFile("assets/plugins/ckeditor/ckeditor");
        }
        if(in_array('codeeditor', $all_types)){
            $this->CI->load->addCssFile("assets/plugins/codemirror/lib/codemirror");
            $this->CI->load->addCssFile("assets/plugins/codemirror/theme/neat");
            $this->CI->load->addCssFile("assets/plugins/codemirror/theme/ambiance");
            $this->CI->load->addCssFile("assets/plugins/codemirror/theme/material");
            $this->CI->load->addCssFile("assets/plugins/codemirror/theme/neo");

            $this->CI->load->addJsFile("assets/plugins/codemirror/lib/codemirror");
            $this->CI->load->addJsFile("assets/plugins/codemirror/mode/javascript/javascript");
            $this->CI->load->addJsFile("assets/plugins/codemirror/mode/htmlmixed/htmlmixed");
//            $this->CI->load->addJsFile("assets/plugins/codemirror/mode/htmlembedded/htmlembedded");
            $this->CI->load->addJsFile("assets/plugins/codemirror/mode/css/css");
        }
        // Multi upload files
        if(in_array('files', $all_types)){
            $this->CI->load->addCssFile("assets/plugins/dropzone/dropzone");
            $this->CI->load->addCssFile("assets/plugins/dropzone/basic");
            $this->CI->load->addJsFile("assets/plugins/dropzone/dropzone");
            $this->CI->load->addJsFile("assets/nodcms/form-handler/files");
        }

        if(in_array('jquery-repeater', $all_types)){
            $this->CI->load->addJsFile("assets/plugins/jquery-repeater/jquery.repeater");
        }
        if(in_array('currency', $all_types)){
            $this->CI->load->addJsFile("assets/nodcms/form-handler/input-currency");
        }
        if(in_array('attachment', $all_types)){
            $this->CI->load->addJsFile("assets/nodcms/form-handler/input-attachment");
        }
        if(in_array('number', $all_types)){
            $this->CI->load->addJsFile("assets/nodcms/form-handler/input-number");
        }

        if(in_array('range-select', $all_types) || in_array('range', $all_types)){
            $this->CI->load->addJsFile("assets/ion.rangeSlider-2.1.7/js/ion-rangeSlider/ion.rangeSlider");
            $this->CI->load->addCssFile("assets/ion.rangeSlider-2.1.7/css/ion.rangeSlider");
            $this->CI->load->addCssFile("assets/ion.rangeSlider-2.1.7/css/ion.rangeSlider.skinHTML5");
            $this->CI->load->addJsFile("assets/nodcms/form-handler/input-range");
        }

        $this->CI->load->addCssFile("assets/plugins/icheck/skins/all");
        $this->CI->load->addJsFile("assets/plugins/icheck/icheck.min");
        $this->CI->load->addCssFile("assets/plugins/bootstrap-touchspin/bootstrap.touchspin");
        $this->CI->load->addJsFile("assets/plugins/bootstrap-touchspin/bootstrap.touchspin");
        // Switch
        $this->CI->load->addCssFile("assets/plugins/bootstrap-switch/css/bootstrap-switch.min", "assets/plugins/bootstrap-switch/css/bootstrap-switch-rtl.min");
        $this->CI->load->addJsFile("assets/plugins/bootstrap-switch/js/bootstrap-switch.min");
        // Global js
        $this->CI->load->addJsFile("assets/nodcms/form-handler/form-handler");

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
            $data['prefix'] .= $this->CI->load->view($this->theme_patch.'postfix_language', $data["prefix_language"], true);
        }
        // Set label postfix
        $data['postfix'] = isset($data['postfix'])?$data['postfix']:'';
        if(isset($data['postfix_language'])){
            $data['postfix'] .= $this->CI->load->view($this->theme_patch.'postfix_language', $data["postfix_language"], true);
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
        $output = $this->CI->load->view($this->theme_patch.$this->styles[$this->style]['form_header'], $data_output, true);
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
            );
            $data = array_merge($data_default,$data);
            $input = $this->CI->load->view($this->theme_patch.'input', $data, true);
            if($data['type']=="hidden"){
                return $input;
            }
        }
        if($this->is_custom){
            return $input;
        }
        if(isset($data['help']) && $data['help']!='')
            $help = $this->CI->load->view($this->theme_patch.'help', array('message'=>$data['help']), true);
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
        $output = $this->CI->load->view($this->theme_patch.$this->styles[$this->style]['form_group'], $data_output, true);
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
            $input = $this->CI->load->view($data['custom_file'], $data, true);
        }else{
            $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
            $input = $this->CI->load->view($data['custom_file'], $data, true);
        }else{
            $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
            'not_set_preview'=>base_url()."noimage-200-50-Not_Set",
            'library_type'=>"images-library",
        );
        $data = array_merge($data_default, $data);
        $data['image_library_url'] = ADMIN_URL."getImagesLibrary/$data[name]/$data[library_type]";
        $data['img_src'] = base_url().($data['default']!=""?$data['default']:"noimage-200-50-Not_Set");
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
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
        $input = $this->CI->load->view($this->theme_patch.$data['type'], $data, true);
        return $this->addInput($data, $input);
    }

    /**
     * #Upload file: Upload a file with saving in database table "upload_files"
     *
     * @param $path
     * @param $save_key
     * @param $allowed_types
     * @param bool $encrypt_name
     * @param int $file_public
     * @param string $validation
     * @return string
     */
    function uploadFile($path,$save_key,$allowed_types,$encrypt_name = true, $file_public = 0, $validation = "")
    {
        /* TODO: set size limit from settings */
        /* TODO: set external upload file from settings */
        /* TODO: set file deadline from settings */
        // * Make directory if doesn't exists
        if(!file_exists(FCPATH.$path)){
            $dirs = explode("/",$path);
            $dir = FCPATH;
            foreach ($dirs as $item){
                $dir .= "$item/";
                if(!file_exists($dir))
                    mkdir($dir);
            }
        }
        $config['upload_path'] = $path;

        $unique_cookie = get_cookie($this->upload_cookie_name);
        if($unique_cookie == null){
            $file_count = 1;
            while ($file_count != 0){
                $new_unique_cookie = substr(md5(rand(1000,9999)+time()-rand(1000,9999)),rand(0,10),10);
                $this->CI->db->select("count(*)")->from("upload_files")->where(array('unique_cookie'=>$new_unique_cookie));
                $query = $this->CI->db->get();
                $row = $query->row_array();
                $file_count = count($row)!=0?$row['count(*)']:0;
            }
            $unique_cookie = $new_unique_cookie;
        }

        set_cookie($this->upload_cookie_name, $unique_cookie);

        $config['allowed_types'] = $allowed_types;
        $config['encrypt_name'] = $encrypt_name;
        $this->CI->load->library('upload');
        $this->CI->upload->initialize($config);
        if (!$this->CI->upload->do_upload("file")){
            return json_encode(array("status"=>"error","error"=>$this->CI->upload->display_errors()));
        }
        $data = $this->CI->upload->data();
        $explode_type = explode('/',$_FILES["file"]['type']);
        $file_type = count($explode_type)!=0?$explode_type[0]:"";
        if($file_type == "image"){
            $file_thumbnail = $config['upload_path'].'/'.$data["file_name"];
        }
        else{
            $file_thumbnail = "upload_file/images/file.png";
        }
        $data_insert = array(
            "user_id"=>$this->CI->userdata!=NULL?$this->CI->userdata['user_id']:0,
            "unique_cookie"=>$unique_cookie,
            "host"=>"localhost",
            "file_type"=>$_FILES["file"]['type'],
            "file_path"=>$config['upload_path'].'/'.$data["file_name"],
            "file_thumbnail"=>$file_thumbnail,
            "file_key"=>$file_public!=0?md5(rand(1000,9999)):"public",
            "upload_key"=>$save_key,
            "remove_key"=>md5($save_key),
            "name"=>$_FILES["file"]['name'],
            "size"=>$data["file_size"],
            "created_date"=>time(),
            "deadline"=>0,
            "download_validation"=>$validation,
        );
        $this->CI->db->insert("upload_files", $data_insert);
        $inserted_id = $this->CI->db->insert_id();
        if($inserted_id==0){
            return json_encode(array("status"=>"error","errors"=>_l("Unfortunately, the file could not be successfully saved.",$this->CI)));
        }
        $file_path = "file-$inserted_id-$data_insert[file_key]-$data_insert[name]";
//        $file_url = base_url().$file_path;
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
        $query = $this->CI->db->select("file_id,file_type,name,size,file_key,remove_key,file_thumbnail")->from("upload_files")
            ->where($conditions)->get();
        $result = $query->result_array();
        foreach($result as &$item){
            $url_prefix = substr($item['file_type'],0,5)=="image"?"image":"file";
            $item['file_url'] = base_url()."$url_prefix-$item[file_id]-$item[file_key]";
            $item['name'] = $upload_key." - $item[name]";
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
        $query = $this->CI->db->select("file_id,file_type,name,size,file_key,remove_key,file_thumbnail")->from("upload_files")
            ->where($conditions)->get();
        $result = $query->result_array();
        foreach($result as &$item){
            $url_prefix = substr($item['file_type'],0,5)=="image"?"image":"file";
            $item['file_url'] = base_url()."$url_prefix-$item[file_id]-$item[file_key]";
            $item['name'] = "$item[name]";
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
        $query = $this->CI->db->select("*")->from("upload_files")
            ->where($conditions)->get();
        $result = $query->result_array();
        foreach($result as $item){
            $file_path = FCPATH.$item['file_path'];
            if(file_exists($file_path))
                unlink($file_path);
            $this->CI->db->delete("upload_files", array('file_id'=>$item['file_id']));
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
        $query = $this->CI->db->select("*")->from("upload_files")
            ->where($conditions)->get();
        $result = $query->result_array();
        foreach($result as $item){
            $file_path = FCPATH.$item['file_path'];
            if(file_exists($file_path))
                unlink($file_path);
        }
        $this->CI->db->delete("upload_files", $conditions);

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

}