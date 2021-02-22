<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Controllers;

use Config\Autoload;
use Config\Modules;
use Config\Services;
use NodCMS\Core\View\View;
use NodCMS\Core\Libraries;

class General extends Frontend
{
    /**
     * Homepage
     *
     */
    public function index()
    {
        // Redirect to a URL
        if($this->settings['homepage_type'] == "redirect"){
            redirect($this->settings['homepage_redirect']);
            return;
        }

        // Open and return a file content
        if($this->settings['homepage_type'] == "display_file"){
            $myfile = fopen(SELF_PATH.$this->settings['homepage_display_file'], "r") or die("Unable to open file!");
            echo fread($myfile,filesize($this->settings['homepage_display_file']));
            fclose($myfile);
            return;
        }

        // Display/curl a web page
        if($this->settings['homepage_type'] == "display_page"){
            echo $this->curlWebPage($this->settings['homepage_display_page']);
            return;
        }

        $packages_home_contents = array();
        $_packages = $this->model->packages()->getAll(array('active'=>1),null,1,array('package_sort','ASC'));
        if($_packages!=null && count($_packages)>0){
            $packages = array_column($_packages, "package_name");
        }
        else{
            $packages = array();
        }

        $packagePaths = Autoload::modulesPaths();

        foreach ($packages as $item){
            if(!key_exists($item, $packagePaths)) {
                continue;
            }

            // Load controller in system
            if ( ! class_exists($item, FALSE) || !method_exists($item, 'home')) {
                continue;
            }
            $packages_home_contents[$item] = $item::home($this);
        }

        $this->data['packages'] = $packages_home_contents;
        // Custom home view file
        if($this->settings['homepage_type'] == "custom_view" && file_exists(SELF_PATH . "custom_views/{$this->settings['custom_view_path_home']}.php")){
            $index_content = $this->load->externalView(SELF_PATH, "custom_views/".$this->settings['custom_view_path_home'], $this->data, true);
        }
        // * System homepage default
        else {
            // Static home contents
            $index_content = $this->viewCommon("home", $this->data);
        }

        // Contact page
//        $data = $this->curlWebPage(base_url("$lang/contact-home"));
//        if(!preg_match("/\<title\>[\s]?Error 404[\s]?\<\/title>/",$data))
//            $index_content .= $data;

        $this->display_page_title = $this->settings['home_page_title_box'];
        if(!empty($this->settings['index_logo'])) {
            $this->data['title_logo'] = base_url($this->settings['index_logo']);
        }
        if(isset($this->settings['home_page_title_bg']) && !empty($this->settings['home_page_title_bg'])) {
            $this->data['title_bg'] = base_url($this->settings['home_page_title_bg']);
        }
        $this->data['title_bg_blur'] = $this->settings['home_page_title_bg_blur'];
        $this->data['title'] = $this->settings['company'];
        $this->data['sub_title'] = isset($this->settings["site_title"])?$this->settings["site_title"]:$this->settings['company'];
        $this->data['keyword'] = isset($this->settings["site_keyword"])?$this->settings["site_keyword"]:"";
        $this->data['description'] = isset($this->settings["site_description"])?$this->settings["site_description"]:"";
        $this->data['author'] = isset($this->settings["site_author"])?$this->settings["site_author"]:"";
        echo $this->viewRenderString($index_content);
    }

    /**
     * Remove a file
     *
     * @param $id
     * @param $key
     */
    function removeMyFile($id, $key)
    {
        $conditions = array(
            'file_id'=>$id,
            'remove_key'=>$key,
        );
        $this->db->select("*")->from("upload_files")->where($conditions);
        $query = $this->db->get();
        $row = $query->row_array();
        if(count($row)==0){
            $this->errorMessage("The file couldn't find.", base_url());
            return;
        }
        $file = $row['file_path'];
        if(preg_match('/^[ftp|http|https]\:\/\/(.*\.[\a])$/',$row['file_path'])!=1) {
            $file = SELF_PATH . $file;
        }
        $myForm = new Libraries\Form($this);
        $unique_cookie = $myForm->getFileUniqueCookie();
        if($row['unique_cookie']!=$unique_cookie){
            $this->errorMessage("You don't have access to remove this file.", base_url());
            return;
        }
        if (file_exists($file)) {
            unlink($file);
        }
        $this->db->delete("upload_files" ,array('file_id'=>$id));
        $this->successMessage("The file has been removed successfully.", base_url());
    }

    /**
     * Download a file
     *
     * @param $id
     * @param $key
     * @throws \Exception
     */
    public function file($id, $key)
    {
        $conditions = array(
            'file_id'=>$id,
            'file_key'=>$key,
        );
        $row = $this->model->uploadFiles()->getOne(null, $conditions);
        if(empty($row)){
            return $this->viewRenderString("The file couldn't find.");
        }
        $file = $row['file_path'];
        if(preg_match('/^[ftp|http|https]\:\/\/(.*\.[\a])$/',$row['file_path'])!=1) {
            $file = ROOTPATH . $file;
            if (!file_exists($file)) {
                throw new \Exception("The file doesn't exists.");
            }
        }
        return $this->response->download($file)->setFileName($row['name'])->setContentType($row['file_type']);
    }

    /**
     * Display an undefined image
     *
     * @param $width
     * @param $height
     * @param string $text
     */
    public function noimage($width, $height, $text = "No Image")
    {
        $font_size = 20;
        header("Content-Type: image/png");
        $im = @imagecreate($width, $height)
        or die("Cannot Initialize new GD image stream");
        imagecolorallocate($im, 240, 240, 240);
        $text_color = imagecolorallocate($im, 3, 3, 3);
        $black = imagecolorallocate($im, 0, 0, 0);
        $x = ($width/2)-(strlen($text)*($font_size/3));
        $y = ($height/2)+($font_size/2);
        $text = str_replace("_", " ", $text);
        $font = APPPATH.'OpenSans-Regular.ttf';
        imagettftext($im, $font_size, 0, $x, $y, $text_color, $font, $text);
//        imagestring($im, 3, $x, $y, $text, $text_color);
        imagepng($im);
//        echo file_get_contents($im);
        imagedestroy($im);
    }

    /**
     * Display an image
     *
     * @param $id
     * @param $key
     */
    function image($id, $key)
    {
        $conditions = array(
            'file_id'=>$id,
            'file_key'=>$key,
        );
        $row = $this->model->uploadFiles()->getOne(null, $conditions);
        if(empty($row)){
            $this->noimage(400,400,"The file couldn't find");
            return;
        }
        $file = $row['file_path'];
        if(preg_match('/^[ftp|http|https]\:\/\/(.*\.[\a])$/',$row['file_path'])!=1) {
            $file = SELF_PATH . $file;
            if (!file_exists($file)) {
                $this->noimage(400,400,"The file doesn't exists");
                return;
            }
        }
        header('Content-Type: '.$row['file_type']);
        header('Content-Length: ' . filesize($file));
        echo file_get_contents(base_url($row['file_path']));
    }

    /**
     * Display the fixed pages on the software
     *  - There is some fixed page on the software:
     *      * Terms & Conditions (Route: /[LanguagePrefix]/terms-and-conditions)
     *      * Privacy Policy (Route: /[LanguagePrefix]/privacy-policy)
     *
     * @param $page_name
     */
    function staticSettingsPages($page_name)
    {
        $page_contents = array(
            'terms-and-conditions'=>array(
                'title'=>$this->settings['terms_and_conditions_title'],
                'content'=>$this->settings['terms_and_conditions_content']
            ),
            'privacy-policy'=>array(
                'title'=>$this->settings['privacy_policy_title'],
                'content'=>$this->settings['privacy_policy_content']
            )
        );
        $this->data['title'] = $page_contents[$page_name]['title'];
        $this->data['keyword'] = "";
        $this->data['data'] = $page_contents[$page_name]['content'];
        echo $this->viewRender("static_settings_pages");
    }

    /**
     * Contact form page and google map
     *
     * @param $home
     */
    public function contact($home = null)
    {
        if($this->settings['contact_form']==1){
            $self_url = base_url("{$this->lang}/contact");
            $config = array(
                array(
                    'field'=>"email",
                    'rules'=>"required|valid_email",
                    'label'=>_l("Email", $this),
                    'type'=>"text",
                ),
                array(
                    'field'=>"name",
                    'rules'=>"required",
                    'label'=>_l("Your name", $this),
                    'type'=>"text",
                ),
                array(
                    'field'=>"message",
                    'rules'=>"required",
                    'label'=>_l("Message", $this),
                    'type'=>"textarea"
                ),
            );
            if($this->settings['terms_accept_required']==1){
                $config[] = array(
                    'field'=>"terms_and_conditions",
                    'type'=>"accept-terms",
                    'rules'=>"callback_acceptTermsAndConditions",
                    'label'=>_l("Terms & Conditions", $this),
                );
            }
            if($this->userdata!=null){
                $config[0]['type'] = "static";
                $config[0]['class'] = "bold";
                $config[0]['value'] = $this->userdata['email'];
                $config[1]['type'] = "static";
                $config[1]['class'] = "bold";
                $config[1]['value'] = $this->userdata['fullname'];
            }
            $myForm = new Libraries\Form($this);
            $myForm->config($config, $self_url, 'post', 'ajax');
            if($myForm->ispost()){
                $data = $myForm->getPost();
                if(!is_array($data) || count($data)==0){
                    return;
                }
                if($this->userdata!=null){
                    $data['username'] = $this->userdata['username'];
                    $data['email'] = $this->userdata['email'];
                    $data['name'] = $this->userdata['fullname'];
                }else{
                    $data['username'] = "";
                }
                $data['date'] = my_int_date(time());

                // Send Notification Emial
                send_notification_email("contact_form", $this->settings['email'], $data, $this->language['language_id'],$data['email']);

                $this->successMessage("The message has been successfully sent.", $self_url);
                return;
            }
            $myForm->setFormTheme('form_only');
            $myForm->setStyle('bootstrap-vertical');

            $form_attr = array('data-reset'=>1,'data-message'=>1);
            if($this->request->isAJAX()){
                echo $myForm->fetch('',$form_attr, false);
                return;
            }
            $this->data['contact_form'] = $myForm->fetch('',$form_attr, false);
        }

        $this->data['title'] = _l("Contact us", $this);
        if($home!=null){
            echo $this->viewCommon("contact_home", $this->data);
            return;
        }

        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title']),
        );
        $this->data['description'] = isset($this->settings["site_description"])?$this->settings["site_description"]:"";
        $this->data['keyword'] = isset($this->settings["site_keyword"])?$this->settings["site_keyword"]:"";
        echo $this->viewRender("contact");
    }

    function resetCaptcha()
    {
        print_captcha();
    }
}