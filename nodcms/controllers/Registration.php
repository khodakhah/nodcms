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
class Registration extends NodCMS_Controller {
    function __construct()
    {
        parent::__construct('frontend');
        $this->load->model('Registration_model');
//        $this->frameTemplate = $this->mainTemplate;
        $this->mainTemplate = 'membership';
        $this->frameTemplate = 'membership/layout';
    }

    // Set system language from URL
    function preset($lang)
    {
        parent::preset($lang);
        // Set top menu
        $data_menu = array();
        $menu = $this->Nodcms_general_model->get_menu();
        $i=0;
        if(isset($menu) && count($menu)!=0){
            foreach($menu as $item)
            {
                $data_menu[$i] = array(
                    'name' =>$item['title_caption'],
                    'icon' =>$item['menu_icon'],
                    'url' =>$item['menu_url'],
                );
                $i++;
            }
        }
        $this->data['data_menu'] = $data_menu;
        $languages = $this->Nodcms_general_model->get_languages();
        foreach ($languages as &$value) {
            $url_array = explode("/",$this->data["lang_url"]);
            $url_array[array_search($lang,$url_array)]=$value["code"];
            $value["lang_url"] = implode("/",$url_array);
        }
        $this->data['languages'] = $languages;
        $this->data['link_contact'] = base_url().$lang."/contact";

        $this->data['description'] = isset($this->settings["options"]["site_description"])?$this->settings["options"]["site_description"]:"";
    }

    // Register a new user
    function userRegistration($lang)
    {
        if($this->settings['registration']!=1){
            redirect(base_url().$lang);
        }
        $this->preset($lang);
        $this->data['title']=_l("User registration",$this);
        $config = array(
            array(
                'type'=>"h3",
                'label'=>$this->data['title']
            ),
            array(
                'field' => 'fname',
                'label' => _l("First name", $this),
                'rules' => 'required|callback_formRulesName',
                'type' => "text",
                'default'=>"",
            ),
            array(
                'field' => 'lname',
                'label' => _l("Last name", $this),
                'rules' => 'required|callback_formRulesName',
                'type' => "text",
                'default'=>"",
            ),
            array(
                'field' => 'phone',
                'label' => _l("Phone number", $this),
                'rules' => 'callback_validPhone',
                'type' => "text",
                'default'=>""
            ),
            array(
                'field' => 'email',
                'label' => _l("Email", $this),
                'rules' => 'required|valid_email|callback_emailUnique',
                'type' => "email",
                'help'=>_l("The activate link would be send to this email address.", $this),
                'default'=>""
            ),
            array(
                'field' => 'username',
                'label' => _l("Username", $this),
                'rules' => 'required|callback_validateUsernameType|callback_isUnique[users,username]',
                'type' => "text",
                'default'=>""
            ),
            array(
                'field' => 'password',
                'label' => _l("Password", $this),
                'rules' => 'required|callback_formRulesPassword',
                'type' => "password",
                'default'=>""
            ),
        );
        $myform = new Form();
        $myform->config($config, base_url().$lang."/user-registration", 'post', 'ajax');
        $myform->setStyle("bootstrap-vertical");
        $myform->setFormTheme("form_only");
        if(isset($this->settings['google_captcha_active']) && $this->settings['google_captcha_active']==1)
            $myform->setGoogleCaptcha();

        $myform->data['title']=_l("Registration form",$this);
        $myform->data['submit_label']=_l("Register",$this);
        $myform->data['submit_class']="green-soft";
        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }

            $active_code = md5(substr(md5(time()),4,6));
            $unique_key = md5(time()+rand(100000,999999));
            while ($this->Public_model->isUnique($unique_key, "users", "user_unique_key")!=0){
                $unique_key = md5(time()+rand(100000,999999));
            }
            $user = array(
                "user_unique_key"=>$unique_key,
                "firstname"=>$data['fname'],
                "lastname"=>$data['lname'],
                "fullname"=>"$data[fname] $data[lname]",
                "mobile"=>$data['phone'],
                "email"=>$data['email'],
                "username"=>$data['username'],
                "password"=>md5($data['password']),
                "active_code"=>$active_code,
                "created_date"=>time(),
                "reset_pass_exp"=>time()+(18000),
                "group_id"=>20,
                "active_register"=>0,
                "active"=>1,
                "status"=>0
            );
            $this->Registration_model->insertUser($user);
            $refurl = base_url().$lang.'/user-registration/active/'.$unique_key.'/'.$active_code;

            // Send auto email for user confirm
            $email_data = array(
                'company'=>$this->settings['company'],
                'username'=>$data['username'],
                'email'=>$data['email'],
                'date'=>time(),
                'first_name'=>$data['fname'],
                'last_name'=>$data['lname'],
                'refurl'=>$refurl
            );
            send_notification_email("registration_confirm",$data['email'],$email_data);
            // Make confirm message
            $this->systemSuccess("Your registration was successful. We sent the confirmation link to your email.",base_url().$lang.'/user-registration/message');
            return;
        }
        $this->data['the_form'] = $myform->fetch(null,array('data-message'=>1,'data-reset'=>1));
        $this->data['content'] = $this->load->view($this->mainTemplate."/signup_form", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    // Return password
    function returnPassword($lang)
    {
        $this->preset($lang);
        $this->load->library('form_validation');
        $this->data['title'] = _l("Return Password",$this);
        $config = array(
            array(
                'type'=>"h3",
                'label'=>$this->data['title']
            ),
            array(
                'field'=>"email",
                'label'=>_l('Email Address',$this),
                'rules'=>"required|valid_email|callback_existsEmail",
                'type'=>"email",
            )
        );
        $myform = new Form();
        $myform->config($config, base_url().$lang."/return-password", 'post', 'ajax');
        $myform->setStyle("bootstrap-vertical");
        $myform->setFormTheme("form_only");
        if(isset($this->settings['google_captcha_active']) && $this->settings['google_captcha_active']==1)
            $myform->setGoogleCaptcha();

        $myform->data['title']=_l("Registration form",$this);
//        $myform->data['submit_label']=_l("Register",$this);
//        $myform->data['submit_class']="blue-steel";
        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }

            $email = $data['email'];

            $user = $this->Registration_model->userDetails($email);
            if(count($user)==0){
                $this->systemError("User not found!", base_url().$lang."return-password");
                return;
            }

            $rand_str = md5(rand(1000,9999) + time() + rand(1000, 9999) );

            $active_code_expired = strtotime("tomorrow");
            $update_data = array('active_code'=>$rand_str, 'active_code_expired'=>$active_code_expired);
            $this->db->update("users", $update_data, array('user_id'=>$user['user_id']));

            $data = array_merge($user, array(
                'company'=> $this->settings["company"],
                'first_name'=> $user["fname"],
                'last_name'=> $user["lname"],
                'username'=> $user["username"],
                'email'=> $user["email"],
                'date'=>my_int_date(time()),
                'active_code_expired'=>$active_code_expired,
                'reference_url'=>base_url().$this->language['code']."/set-new-password/$user[user_unique_key]/$active_code_expired",
            ));
            send_notification_email('reset_password', $email, $data, $this->language['language_id']);
            $this->systemSuccess("Your account has been activated successfully. Now you can sign in with your account.", base_url().$this->language['code']."/login");
            return;
        }

        $this->data['the_form'] = $myform->fetch('login_form', array('data-reset'=>1,'data-message'=>1, 'data-redirect'=>1));
        $this->data['content'] = $this->load->view($this->mainTemplate.'/return_password_form',$this->data,true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    /**
     * Set a new password for who forgot the password
     *
     * @param $lang
     * @param $user_unique_key
     * @param $active_code
     *
     */
    function setNewPassword($lang, $user_unique_key, $active_code)
    {
        $this->preset($lang);
        $this->data['title']=_l("Set a new password",$this);
        $user = $this->Registration_model->getUserByUniqueCode($user_unique_key, $active_code);
        $errorButtons = array(
            array('url'=>base_url()."$lang",'label'=>_l("Home", $this)),
            array('url'=>base_url()."$lang/login",'label'=>_l("Login", $this)),
            array('url'=>base_url()."$lang/user-registration",'label'=>_l("Register", $this)),
        );
        if(count($user)==0) {
            $header = _l("Not available", $this);
            $message = _l("This page is not available any more.", $this);
            $this->showError($message, 1, $header, $errorButtons);
            return;
        }
        if($user['active_code_expired'] <= time()){
            $header = _l("Active link is expired", $this);
            $message = _l("Thi link is not active any more. Please request to reset your password again.", $this);
            $this->showError($message, 1, $header, $errorButtons);
            return;
        }

        $config = array(
            array(
                'type'=>"h3",
                'label'=>$this->data['title']
            ),
            array(
                'field'=>"password",
                'label'=>_l('Password',$this),
                'rules'=>"required|callback_formRulesPassword",
                'type'=>"password",
            ),
            array(
                'field'=>"re_password",
                'label'=>_l('Password Confirm',$this),
                'rules'=>"required|callback_formRulesPassword|matches[password]",
                'type'=>"password",
            ),
        );
        $myform = new Form();
        $myform->config($config, base_url().$lang."/set-new-password/$user_unique_key/$active_code", 'post', 'ajax');
        $myform->setStyle("bootstrap-vertical");
        $myform->setFormTheme("form_only");
        if(isset($this->settings['google_captcha_active']) && $this->settings['google_captcha_active']==1)
            $myform->setGoogleCaptcha();

        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }

            $update_data = array(
                'active_code'=>"",
                'active_code_expired'=>0,
                'password'=>md5($data['password']),
            );
            $this->db->update("users", $update_data, array('user_id'=>$user['user_id']));
            $this->systemSuccess("Your password has been reset successfully.", base_url().$this->language['code']."/login");
            return;
        }
        $this->data['the_form'] = $myform->fetch('login_form', array('data-reset'=>1,'data-message'=>1, 'data-redirect'=>1, 'data-replace'=>1));
        $this->data['content'] = $this->load->view($this->mainTemplate.'/return_password_form',$this->data,true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    /**
     * Login form
     *
     * @param null $lang
     */
    function login($lang = NULL)
    {
        if($lang==NULL){
            $language = $this->Nodcms_general_model->get_language_default();
            if($language==0){
                show_error("Couldn't find default language.");
                return;
            }
            $lang = $language["code"];
        }

        $this->preset($lang);

        $this->data['title'] = _l("Sign in", $this);

        $config = array(
            array(
                'type'=>"h3",
                'label'=>$this->data['title']
            ),
            array(
                'field'=>"username",
                'label'=>_l('Username',$this),
                'rules'=>"required|callback_validateUsernameType",
                'type'=>"text",
            ),
            array(
                'field'=>"password",
                'label'=>_l('Password',$this),
                'rules'=>"required|callback_formRulesPassword",
                'type'=>"password",
            )
        );
        $myform = new Form();
        $myform->config($config, base_url().$lang."/login", 'post', 'ajax');
        $myform->setStyle("bootstrap-vertical");
        $myform->setFormTheme("form_only");
        if(isset($this->settings['google_captcha_active']) && $this->settings['google_captcha_active']==1)
            $myform->setGoogleCaptcha();

        $myform->data['submit_label'] = $this->data['title'];
        $myform->data['submit_class']="blue-steel";
        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }

            $username = $data['username'];
            $password = $data['password'];

            $user = $this->Registration_model->loginMatch($username, md5($password));
            if(count($user)==0){
                $this->systemError("Password or username is incorrect.", base_url().$lang."/login");
                return;
            }

            $packages = $this->load->packageList();
            $has_dashboard = false;
            foreach ($packages as $item){
                if(!file_exists(APPPATH."controllers/".$item."_members.php")){
                    continue;
                }
                $has_dashboard = true;
                break;
            }

            $login_data = array(
                'fullname'  => $user['fullname'],
                'username'  => $user['username'],
                'user_id' => $user['user_id'],
                'group'   => $user['group_id'],
                'avatar'   => $user['avatar'],
                'email'   => $user['email'],
                'logged_in_status'   => true,
                'has_dashboard'   => $has_dashboard,

            );
            $this->session->set_userdata($login_data);
            $continue_url = $user['group_id']==1?ADMIN_URL:base_url();
            $this->systemSuccess(NULL, $continue_url);
            return;
        }
        $this->data['the_form'] = $myform->fetch('login-form', array('data-redirect'=>1));
        $this->data['content'] = $this->load->view($this->mainTemplate.'/signin_form',$this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    // Reservation messages page after any active
    function userRegistrationMessage($lang)
    {
        $this->preset($lang);
        if($this->session->flashdata('message')){
            $message = $this->session->flashdata('message');
            $this->data['title'] = _l('User Registration', $this);
            $this->data['message_title'] = $message['title'];
            $this->data['message'] = $message['body'];
            $this->data['message_class'] = $message['class'];
            $this->data['content']=$this->load->view($this->mainTemplate.'/user_registration_message',$this->data,true);
            $this->load->view($this->frameTemplate, $this->data);
        }else{
            redirect(base_url().$lang);
        }
    }

    // Set new password for users after restoring request
    function activeAccount($lang, $user_unique_key, $active_code)
    {
        $this->preset($lang);
        $user = $this->Registration_model->getUserByUniqueCode($user_unique_key,$active_code);
        if(isset($user) && $user["reset_pass_exp"] > time() && $user["active_register"]==0){
            $this->Registration_model->activeUser($user['user_id']);
            $message = array(
                'title'=>_l('User Activate', $this),
                'body'=>_l('Your account has been successfully activated.', $this),
                'class'=>'note note-success'
            );
            $this->session->set_flashdata('message', $message);
            redirect(base_url().$lang.'/user-registration/message');
        }else{
            $buttons = array(
                array('url'=>base_url(),'label'=>_l("Home", $this)),
                array('url'=>base_url()."$lang/login",'label'=>_l("Login", $this)),
                array('url'=>base_url()."$lang/user-registration",'label'=>_l("Registration", $this)),
            );
            $this->showError(null,404,null,$buttons);
        }
    }

    // Validation exists email for user return password
    public function existsEmail($value)
    {
        if ($this->Registration_model->userUniqueEmail($value)) {
            $this->form_validation->set_message('existsEmail', _l("The {field} didn't find.", $this));
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * Display account lock page
     *
     * @param null|string $lang
     */
    function accountLocked($lang = NULL)
    {
        if($lang==NULL){
            $language = $this->Nodcms_general_model->get_language_default();
            if($language==0){
                show_error("Couldn't find default language.");
                return;
            }
            $lang = $language["code"];
        }
        $this->preset($lang);
        $this->data['title'] = _l("Account locked", $this);
        $this->data['content'] = $this->load->view($this->mainTemplate.'/account_lock',$this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }
}