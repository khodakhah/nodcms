<?php

/**
 * Created by PhpStorm.
 * User: Mojtaba
 * Date: 9/6/2016
 * Time: 1:03 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class General extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct("frontend");
    }

    /**
     * Homepage
     *
     * @param $lang
     */
    function index($lang)
    {
        $this->preset($lang);

        // Redirect to a URL
        if($this->settings['homepage_type'] == "redirect"){
            redirect($this->settings['homepage_redirect']);
            return;
        }

        // Open and return a file content
        if($this->settings['homepage_type'] == "display_file"){
            $myfile = fopen(getcwd()."/".$this->settings['homepage_display_file'], "r") or die("Unable to open file!");
            echo fread($myfile,filesize($this->settings['homepage_display_file']));
            fclose($myfile);
            return;
        }

        // Display/curl a web page
        if($this->settings['homepage_type'] == "display_page"){
            echo $this->curlWebPage($this->settings['homepage_display_page']);
            return;
        }

        // * System homepage default

        $index_content = $this->load->view($this->mainTemplate."/home",$this->data, true);
        $_packages = $this->Packages_model->getAll(array('active'=>1),null,1,array('package_sort','ASC'));
        if($_packages!=null && count($_packages)>0){
            $packages = array_column($_packages, "package_name");
        }else{
            $packages = $this->load->packageList();
        }

        foreach ($packages as $item){
            if(!file_exists(APPPATH."controllers/$item.php")){
                continue;
            }
            $url = strtolower($item)."-index";
            $data = $this->curlWebPage(base_url()."$lang/$url");
            if(!preg_match("/\<title\>[\s]?Error 404[\s]?\<\/title>/",$data))
                $index_content .= $data;
            else
                $index_content .= "<p style='background: #ff1f00;border:1px solid #b51600;text-align:center;color:#ffffff;font-weight: bold;padding:10px;'>There isn't any index() method in controller/$item.php</p>";
        }

        // Contact page
//        $data = $this->curlWebPage(base_url()."$lang/contact-home");
//        if(!preg_match("/\<title\>[\s]?Error 404[\s]?\<\/title>/",$data))
//            $index_content .= $data;

//        $this->display_page_title = false;
        $this->data['title_logo'] = base_url().$this->settings['index_logo'];
        $this->data['title_bg'] = isset($this->settings['home_page_title_bg'])?base_url().$this->settings['home_page_title_bg']:"";
        $this->data['title'] = $this->settings['company'];
        $this->data['sub_title'] = isset($this->settings["site_title"])?$this->settings["site_title"]:$this->settings['company'];
        $this->data['keyword'] = isset($this->settings["site_keyword"])?$this->settings["site_keyword"]:"";
        $this->data['description'] = isset($this->settings["site_description"])?$this->settings["site_description"]:"";
        $this->data['author'] = isset($this->settings["site_author"])?$this->settings["site_author"]:"";
        $this->data['content'] = $index_content;
        $this->load->view($this->frameTemplate,$this->data);
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
            $this->systemError("The file couldn't find.", base_url());
            return;
        }
        $file = $row['file_path'];
        if(preg_match('/^[ftp|http|https]\:\/\/(.*\.[\a])$/',$row['file_path'])!=1) {
            $file = FCPATH . $file;
        }
        $myForm = new Form();
        $unique_cookie = $myForm->getFileUniqueCookie();
        if($row['unique_cookie']!=$unique_cookie){
            $this->systemError("You don't have access to remove this file.", base_url());
            return;
        }
        if (file_exists($file)) {
            unlink($file);
        }
        $this->db->delete("upload_files" ,array('file_id'=>$id));
        $this->systemSuccess("The file has been removed successfully.", base_url());
    }

    /**
     * Download a file
     *
     * @param $id
     * @param $key
     */
    function file($id, $key)
    {
        $conditions = array(
            'file_id'=>$id,
            'file_key'=>$key,
        );
        $this->db->select("*")->from("upload_files")->where($conditions);
        $query = $this->db->get();
        $row = $query->row_array();
        if(count($row)==0){
            show_error("The file couldn't find.");
            return;
        }
        $file = $row['file_path'];
        if(preg_match('/^[ftp|http|https]\:\/\/(.*\.[\a])$/',$row['file_path'])!=1) {
            $file = FCPATH . $file;
            if (!file_exists($file)) {
                show_error("The file doesn't exists.");
                return;
            }
        }
        header('Content-Description: File Transfer');
        header('Content-Type: '.$row['file_type']);
        header('Content-Disposition: attachment; filename="'.$row['name'].'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush(); // Flush system output buffer
        readfile($file);
    }

    /**
     * Display an undefined image
     *
     * @param $width
     * @param $height
     * @param string $text
     */
    function noimage($width, $height, $text = "No Image")
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
        $this->db->select("*")->from("upload_files")->where($conditions);
        $query = $this->db->get();
        $row = $query->row_array();
        if(count($row)==0){
            $this->noimage(400,400,"The file couldn't find");
            return;
        }
        $file = $row['file_path'];
        if(preg_match('/^[ftp|http|https]\:\/\/(.*\.[\a])$/',$row['file_path'])!=1) {
            $file = FCPATH . $file;
            if (!file_exists($file)) {
                $this->noimage(400,400,"The file doesn't exists");
                return;
            }
        }
        header('Content-Type: '.$row['file_type']);
        header('Content-Length: ' . filesize($file));
        echo file_get_contents(base_url().$row['file_path']);
    }

    /**
     * Change the account details
     *
     * @param $lang
     */
    function accountSetting($lang)
    {
        $this->preset($lang);
        if($this->userdata==null){
            $this->error404();
            return;
        }
        $languages = $this->Public_model->getAllLanguages();
        $config = array(
            array(
                'field'=>"username",
                'label'=>_l('Username',$this),
                'rules'=>"required|callback_isUnique[users,username,user_id,".$this->userdata['user_id']."]",
//                'type'=>"text",
//                'default'=>$this->userdata['username'],
                'type'=>"static",
                'value'=>$this->userdata['username'],
            ),
            array(
                'field'=>"email",
                'label'=>_l('Email Address',$this),
                'rules'=>"required|valid_email|callback_isUnique[users,email,user_id,".$this->userdata['user_id']."]",
//                'type'=>"text",
//                'default'=>$this->userdata['email'],
                'type'=>"static",
                'value'=>$this->userdata['email'],
            ),
            array(
                'field'=>"firstname",
                'label'=>_l('First Name',$this),
                'rules'=>"required|callback_formRulesName",
                'type'=>"text",
                'default'=>$this->userdata['firstname'],
            ),
            array(
                'field'=>"lastname",
                'label'=>_l('Last Name',$this),
                'rules'=>"required|callback_formRulesName",
                'type'=>"text",
                'default'=>$this->userdata['lastname'],
            ),
            array(
                'field'=>"mobile",
                'label'=>_l('Mobile',$this),
                'rules'=>"callback_validPhone",
                'type'=>"text",
                'default'=>$this->userdata['mobile'],
            ),
            array(
                'field'=>"website",
                'label'=>_l('Website',$this),
                'rules'=>"xss_clean|valid_url",
                'type'=>"url",
                'default'=>$this->userdata['website'],
            ),
            array(
                'field'=>"password",
                'label'=>_l('Password',$this),
                'rules'=>"callback_formRulesPassword",
                'type'=>"password",
            ),
            array(
                'field'=>"language_id",
                'label'=>_l('Language',$this),
                'rules'=>"required|in_list[".join(',',array_column($languages,"language_id"))."]",
                'type'=>"select",
                'options'=>$languages,
                'option_name'=>"language_title",
                'option_value'=>"language_id",
                'default'=>$this->userdata['language_id'],
            ),
        );
        $myform = new Form();
        $myform->config($config, base_url()."$lang/account-setting/", 'post', 'ajax');
        // * Submit form
        if($myform->ispost()){
            if($this->userdata['group_id']==100){
                $this->systemError("As demo account you aren't able to change any thing.", base_url());
                return;
            }
            $post_data = $myform->getPost();
            // Stop Page
            if(!is_array($post_data) || count($post_data)==0 || $post_data == null){
                return;
            }

            $data = array(
                'firstname'=>$post_data['firstname'],
                'lastname'=>$post_data['lastname'],
                'fullname'=>$post_data['firstname'].' '.$post_data['lastname'],
//                'username'=>$post_data['username'],
//                'email'=>$post_data['email'],
                'website'=>$post_data['website'],
                'mobile'=>$post_data['mobile'],
                'language_id'=>$post_data['language_id']
            );
            if($post_data['password']!='') $data['password'] = md5($post_data['password']);
            $this->load->model("Nodcms_admin_model");
            if (!$this->Nodcms_admin_model->userManipulate($data,$this->session->userdata['user_id'])){
                $this->systemError("There was a problem to update the user data.", base_url()."$lang/account-setting");
                return;
            }
            $row = $this->Nodcms_admin_model->getUserDetail($this->session->userdata['user_id']);
            $this->session->set_userdata('firstname', $row['firstname']);
            $this->session->set_userdata('lastname', $row['lastname']);
            $this->session->set_userdata('fullname', $row['fullname']);
            $this->session->set_userdata('username', $row['username']);
            $this->session->set_userdata('email', $row['email']);

            $this->systemSuccess("Your profile has been updated successfully.", base_url()."$lang/account-setting");
            return;
        }
        // * Upload an avatar
        $this->data['data'] = $this->userdata;
        $this->data['submit_form'] = $myform->fetch();
        $this->data['content']=$this->load->view('membership/account_setting',$this->data,true);
        $this->data['title'] = _l("Account setting",$this);
        $this->data['page'] = "account";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Upload and remove the user avatar
     *
     * @param $lang
     */
    function accountAvatarChange($lang)
    {
        $this->preset($lang);
        if($this->userdata==null){
            $this->error404();
            return;
        }
        if($this->userdata['group_id']==100){
            $this->systemError("As demo account you aren't able to change any thing.", base_url().$lang."/account-setting");
            return;
        }
        $this->load->model("Nodcms_admin_model");
        if(is_uploaded_file($_FILES["file"]["tmp_name"])){
            $config['upload_path'] ='upload_file/users';
            $dir = getcwd().'/'.$config['upload_path'];
            if( ! file_exists($dir))
                mkdir($dir);
            $config['upload_path'] .= "/user-".$this->userdata["user_id"];
            $dir .= "/user-".$this->userdata["user_id"];
            if( ! file_exists($dir))
                mkdir($dir);
            $config['allowed_types'] = 'gif|jpg|png';
            $config['encrypt_name'] = true;
            $dir = getcwd().'/'.$config['upload_path'];
            if( ! file_exists($dir))
                mkdir($dir);
            $this->load->library('upload', $config);
            if(! $this->upload->do_upload("file")){
                $this->systemError($this->upload->display_errors('<p>', '</p>'), base_url()."$lang/account-setting");
                return;
            }

            $file = $this->upload->data();
            $setData = array(
                "avatar"=>$config['upload_path']."/".$file['file_name'],
            );
            $this->Nodcms_admin_model->userManipulate($setData, $this->userdata['user_id']);
            if(isset($this->userdata["avatar"])&&$this->userdata["avatar"]!=null&&$this->userdata["avatar"]!=""&&file_exists(getcwd()."/".$this->userdata["avatar"]))
                unlink(getcwd()."/".$this->userdata["avatar"]);
            $this->systemSuccess("Your avatar has updated successfully.", base_url()."$lang/account-setting");
        }
        else{
            $setData = array(
                "avatar"=>"",
            );
            $this->Nodcms_admin_model->userManipulate($setData, $this->userdata['user_id']);
            if(isset($this->userdata["avatar"])&&$this->userdata["avatar"]!=null&&$this->userdata["avatar"]!=""&&file_exists(getcwd()."/".$this->userdata["avatar"]))
                unlink(getcwd()."/".$this->userdata["avatar"]);
            $this->systemSuccess("Your avatar has removed successfully.!", base_url()."$lang/account-setting");
        }
    }

    /**
     * Log out user (remove all user sessions)
     *
     * @param string $lang
     */
    function logout($lang = "")
    {
        $this->session->unset_userdata('fullname');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('group');
        $this->session->unset_userdata('avatar');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('logged_in_status');
        $this->session->unset_userdata('provider_id');
        $this->session->unset_userdata('provider_name');
        redirect(base_url().$lang);
    }

    /**
     * Display the fixed pages on the software
     *  - There is some fixed page on the software:
     *      * Terms & Conditions (Route: /[LanguagePrefix]/terms-and-conditions)
     *      * Privacy Policy (Route: /[LanguagePrefix]/privacy-policy)
     *
     * @param $lang
     * @param $page_name
     */
    function staticSettingsPages($lang, $page_name)
    {
        $this->preset($lang);
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
        $this->data['content'] = $this->load->view($this->mainTemplate."/static_settings_pages", $this->data, true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Contact form page and google map
     *
     * @param $lang
     * @param $home
     */
    function contact($lang, $home = null)
    {
        $this->preset($lang);

        if($this->settings['contact_form']==1){
            $self_url = base_url()."$lang/contact";
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
            $myForm = new Form();
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

                $this->systemSuccess("The message has been successfully sent.", $self_url);
                return;
            }
            $myForm->setFormTheme('form_only');
            $myForm->setStyle('bootstrap-vertical');

            $form_attr = array('data-reset'=>1,'data-message'=>1);
            if($this->input->is_ajax_request()){
                echo $myForm->fetch('',$form_attr, false);
                return;
            }
            $this->data['contact_form'] = $myForm->fetch('',$form_attr, false);
        }

        $this->data['title'] = _l("Contact us", $this);
        if($home!=null){
            echo $this->load->view($this->mainTemplate."/contact_home",$this->data, true);
            return;
        }

        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title']),
        );
        $this->data['description'] = isset($this->settings["site_description"])?$this->settings["site_description"]:"";
        $this->data['keyword'] = isset($this->settings["site_keyword"])?$this->settings["site_keyword"]:"";
        $this->data['content'] = $this->load->view($this->mainTemplate."/contact",$this->data, true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    function resetCaptcha()
    {
        print_captcha();
    }
}