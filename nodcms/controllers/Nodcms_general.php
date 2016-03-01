<?php
/**
 * Created by PhpStorm.
 * User: Mojtaba
 * Date: 9/15/2015
 * Time: 8:30 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Nodcms_general extends NodCMS_Controller {
    function __construct(){
        parent::__construct('frontend');
    }
    public function preset($lang){
        $language = $this->NodCMS_general_model->get_language_by_code($lang);
        if($language!=0){
            $_SESSION["language"] = $language;
            $this->data["lang"] = $lang;
        }else{
            $language = $this->NodCMS_general_model->get_language_default();
            if($language!=0){
                redirect(base_url().$language["code"]);
            }else{
                exit("Didn't found eny language!");
            }
        }
        $this->lang->load($lang, $language["language_name"]);
        $_SERVER['DOCUMENT_ROOT'] = dirname(dirname(dirname(__FILE__)));
        $this->data['lang_url'] = $_SERVER["REQUEST_URI"];
        $this->data['action'] = $_SERVER["REQUEST_URI"];
        $this->data['redirect'] = $_SERVER["REQUEST_URI"];

        $this->data['settings'] =  $this->NodCMS_general_model->get_website_info();
        $this->data['settings']["options"] =  $this->NodCMS_general_model->get_website_info_options($language["language_id"]);
        $this->data['settings']["company"] =  isset($this->data['settings']["options"]["company"])?$this->data['settings']["options"]["company"]:$this->data['settings']["company"];
        $_SESSION['settings']=$this->data['settings'];

        frontendStatisticCalc($this,$language);

        $this->data['data_menu'] = $this->NodCMS_general_model->get_menu(array('sub_menu'=>0));
        $i=0;
        foreach($this->data['data_menu'] as &$menu)
        {
            $menu['sub_menu_data'] = $this->NodCMS_general_model->get_menu(array('sub_menu'=>$menu['menu_id']));
            $menu['id']=$menu['page_id'];
            $menu['name']=$menu['title_caption'];
            $menu['url']=$menu['page_id']!=0?base_url().$lang."/page/".$menu['page_id']:((substr($menu['menu_url'],0,7)!='http://' && substr($menu['menu_url'],0,8)!='https://')?base_url().$menu['menu_url']:$menu['menu_url']);
            foreach($menu['sub_menu_data'] as &$subMenu){
                $subMenu['id']=$subMenu['page_id'];
                $subMenu['name']=$subMenu['title_caption'];
                $subMenu['url']=$subMenu['page_id']!=0?base_url().$lang."/page/".$subMenu['page_id']:((substr($subMenu['menu_url'],0,7)!='http://' && substr($subMenu['menu_url'],0,8)!='https://')?base_url().$subMenu['menu_url']:$subMenu['menu_url']);
            }
            $i++;
        }
        $this->data['languages'] = $this->NodCMS_general_model->get_languages();
        foreach ($this->data['languages'] as &$value) {
            $url_array = explode("/",$this->data["lang_url"]);
            $url_array[array_search($lang,$url_array)]=$value["code"];
            $value["lang_url"] = implode("/",$url_array);
        }

        $this->data['link_contact'] = base_url()."contact";
    }
    function ajax_submit_email(){
        if(isset($_POST["email"]) && preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/", $_POST['email'])){
            if($this->NodCMS_general_model->exists_email_in_emails($_POST["email"])){
                $_SESSION["download_email"] = $_POST["email"];
                echo "success";
            }elseif(@$this->NodCMS_general_model->insert_emails(array("email"=>$_POST["email"],"created_date"=>time()))){
                $_SESSION["download_email"] = $_POST["email"];
                echo "success";
            }else{
                echo _l("System error, please notify the Administrator",$this);
            }
        }else{
            echo _l("Error: ",$this)._l("Email is not valid",$this);
        }
    }
    function ajax_rate($extension_id,$rate){
//        if(isset($_POST["rate"]) && is_numeric($_POST["rate"]) && isset($_POST["ex_id"]) && is_numeric($_POST["ex_id"])){
        if(isset($rate) && isset($extension_id)){
            if($rate>5) $rate=5;
            if($rate<1) $rate=1;
            $extensions = $this->NodCMS_general_model->get_extension_by_id($extension_id);
            if(!$this->NodCMS_general_model->check_user_rate($_SESSION['user']['user_id'],$extension_id) && isset($extensions[0]["extension_id"])){
                $data = array(
                    "user_id"=>$_SESSION['user']['user_id'],
                    "extension_id"=>$extension_id,
                    "amount"=>$rate,
                    "created_date"=>time()
                );
                $this->NodCMS_general_model->insert_rate($data);
                $this->NodCMS_general_model->extension_count_rate($extension_id,$extensions[0]["count_rate"]+1);
                $this->NodCMS_general_model->extension_sum_rate($extension_id,$extensions[0]["count_rate"]+$rate);
                echo json_encode(array('status'=>1,'errors'=>_l("System error, please notify the Administrator",$this)));
            }else{
                echo json_encode(array('status'=>0,'errors'=>_l("You cat not send more than one for a extension.",$this)));
            }
        }else{
            echo json_encode(array('status'=>0,'errors'=>_l("System error, please notify the Administrator",$this)));
        }
    }
    function index($ln=null){
        $this->preset($ln);
        $this->load->library('spyc');
        $page_type = spyc_load_file(getcwd()."/page_type.yml") ;
        $pages_render = array();
        $pages = $this->NodCMS_general_model->get_preview_pages();
        foreach ($pages as $item) {
            $extension_data["page_data"] = $item;
            $extension_data["lang"] = $ln;
            $extension_data["settings"] = $this->data["settings"];
            $extension_data["preview_limit"] = $limit = get_extension_limit_preview($item["page_type"],$page_type);
            if(check_extension_order_preview($item["page_type"],$page_type)){
                $extension_data["data"] = $this->NodCMS_general_model->get_extensions_by_page_id($item["page_id"],"extension_order","ASC",$limit!=0?$limit:null);
            }else{
                $extension_data["data"] = $this->NodCMS_general_model->get_extensions_by_page_id($item["page_id"],"created_date","DESC",$limit!=0?$limit:null);
            }
            foreach ($extension_data["data"] as &$val) {
                if(isset($val['extension_more'])) { $val['extension_more'] = spyc_load($val['extension_more']); }
            }

            if(check_is_gallery($item["page_type"],$page_type)){
                $extension_data["gallery"] = $this->NodCMS_general_model->get_gallery_by_page_id($item["page_id"]);
                $extension_data["gallery_image"] = $this->NodCMS_general_model->get_gallery_image_by_page_id($item["page_id"],"created_date","DESC",$limit!=0?$limit:null);
            }
            $page_header = $item['title_caption'];
            $page_body = $this->load->view($page_type[$item["page_type"]]["theme_preview"],$extension_data,true);


            array_push($pages_render,array(
                "title"=>$page_header,
                "body"=>$page_body
            ));
        }

        $this->data['pages']= $pages_render;
        $this->data['title']= isset($this->data['settings']["options"]["site_title"])?$this->data['settings']["options"]["site_title"]:"";
        $this->data['keyword']= isset($this->data['settings']["options"]["site_keyword"])?$this->data['settings']["options"]["site_keyword"]:"";
        $this->data['description']= isset($this->data['settings']["options"]["site_description"])?$this->data['settings']["options"]["site_description"]:"";
        $this->data['content']=$this->load->view($this->mainTemplate.'/home',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data,'');

    }
    function login($lang,$logout=null){
        $this->preset($lang);
        if($logout!=null){
            unset($_SESSION["user"]);
            redirect(base_url().$lang."/login");
        }
        if(isset($_POST["data"]) && isset($_POST["data"]["email"]) && isset($_POST["data"]["password"])){
            if($_POST["data"]["email"]!="" && $_POST["data"]["password"]!=""){
                $user = $this->NodCMS_general_model->get_user_by_email_and_password($_POST["data"]["email"],md5($_POST["data"]["password"]));
                if($user!=0){
                    $_SESSION["user"] = $user;
                    if(isset($_POST["data"]["keep_login"]) && $_POST["data"]["keep_login"]){
                        $time = time();
                        setcookie("keep_login[0]",md5($user["email"]),0,"~",base_url());
                        setcookie("keep_login[1]",md5($time),0,"~",base_url());
                        $this->NodCMS_general_model->update_user_login($user["user_id"],md5($time),$_SESSION["HTTP_USER_AGENT"]);
                    }
                    redirect(base_url().$lang);
                }else{
                    $error = _l("Username or password not correct",$this);
                }
            }else{
                $error = _l("Username and password cannot be empty",$this);
            }
        }
        $this->data['login_error']=isset($error)?$error:null;
        $this->data['title']=_l('Login',$this);
        $this->data['content']=$this->load->view($this->mainTemplate.'/login',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data,'');
    }
    function register($lang){
        $this->preset($lang);
        if(isset($_SESSION["user"]["user_id"])) redirect(base_url());
        if(isset($_POST["email"])){
            if($_POST["email"]!="" && preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/", $_POST['email'])){
                if($this->NodCMS_general_model->check_email_exists($_POST["email"])){
                    $this->session->set_flashdata('message_error', _l("This email already exists, choose another email address or click on forget password.",$this));
                }else{
                    $make_username = explode("@",$_POST["email"]);
                    $new_username = $make_username[0];
                    while($this->NodCMS_general_model->check_username_exists($new_username)){
                        $new_username = $make_username[0].rand(1000,8989);
                    }
                    $active_code = md5(substr(md5(time()),4,6));
                    $email_hash = md5($_POST["email"]);
                    if($this->NodCMS_general_model->check_email_exists($_POST["email"],false)){
                        $user = array("email_hash"=>$email_hash,"active_code"=>$active_code,"reset_pass_exp"=>time()+18000);
                        $this->NodCMS_general_model->update_user_by_email($user,$_POST["email"]);
                    }else{
                        $user = array(
                            "email"=>$_POST["email"],
                            "username"=>$new_username,
                            "active_code"=>$active_code,
                            "email_hash"=>$email_hash,
                            "created_date"=>time(),
                            "reset_pass_exp"=>time()+(18000),
                            "group_id"=>3,
                            "active_register"=>0,
                            "active"=>1
                        );
                        $this->NodCMS_general_model->insert_user($user);
                    }
                    $search = array('[--$company--]','[--$date--]','[--$username--]','[--$email--]','[--$cdate--]','[--$refurl--]');
                    $replace = array($this->data['settings']["company"],my_int_date(time()),$user['username'],$user['email'],$user['created_date'],base_url().$lang.'/active_account/'.$user['email_hash'].'/'.$user['active_code']);
                    $body_data = str_replace($search,$replace,$this->data['settings']['options']['msg_reset_pass']);
                    $email_body = $this->load->view('nodcms_general/email-template-public',array('body'=>$body_data),true);
                    $this->sendEmailAutomatic($_POST["email"],_l("Confirm your account",$this),$email_body);
                    $this->session->set_flashdata('message_success', _l("We send you a link to your email, please check your email inbox and spam, and flow that.",$this));
                    redirect(base_url().$lang."/register?success");
                }
            }else{
                $this->session->set_flashdata('message_error', _l("Please insert the correct email address",$this));
            }
        }
        $this->data['title']='Register';
        $this->data['content']=$this->load->view($this->mainTemplate.'/register',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data,'');
    }
    function forget_password($lang){
        $this->preset($lang);
        if(isset($_SESSION["user"]["user_id"])) redirect(base_url());
        if(isset($_POST["email"])){
            if($_POST["email"]!="" && preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/", $_POST['email'])){
                if($this->NodCMS_general_model->check_email_exists($_POST["email"])){
                    $active_code = md5(substr(md5(time()),4,6));
                    $user = array("active_code"=>$active_code,"active"=>0,"reset_pass_exp"=>time()+18000);
                    $this->NodCMS_general_model->update_user_by_email($user,$_POST["email"]);
//                    Send email
                    $search = array('[--$company--]','[--$date--]','[--$username--]','[--$email--]','[--$cdate--]','[--$refurl--]');
                    $replace = array($this->data['settings']["company"],my_int_date(time()),$user['username'],$user['email'],$user['created_date'],base_url().$lang.'/reset-password/'.md5($_POST["email"])."/".$active_code);
                    $body_data = str_replace($search,$replace,$this->data['settings']['options']['msg_reset_pass']);
                    $email_body = $this->load->view('nodcms_general/email-template-public',array('body'=>$body_data),true);
                    $this->sendEmailAutomatic($_POST["email"],_l("Password Reset",$this),$email_body);
                    $this->session->set_flashdata('message_success', _l("We send you a link to your email, please check your email inbox and spam, and flow that.",$this));
                    redirect(base_url().$lang."/login");
                }else{
                    $this->session->set_flashdata('message_error', _l("This email already exists, choose another email address or click on forget password.",$this));
                }
            }else{
                $this->session->set_flashdata('message_error', _l("Please insert the correct email address",$this));
            }
            redirect(base_url().$lang."/forget-password");
        }
        $this->data['title']=_l('Forget password',$this);
        $this->data['content']=$this->load->view($this->mainTemplate.'/forget_password',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data,'');
    }
    function reset_password($lang,$email_hash,$active_code){
        $this->preset($lang);
        if($email_hash!=null && $active_code!=null){
            $user = $this->NodCMS_general_model->get_user_by_email_hash_and_active_code($email_hash,$active_code);
            if(isset($user) && $user["reset_pass_exp"]>time() && ($user["active"]==0 || $user["active_register"]==0)){
                $this->data['data'] = $user;
                if(isset($_POST["password"])){
                    $redirect = $user["active_register"]==0?"active_account":"reset-password";
                    if(strlen($_POST["password"])>5 && strlen($_POST["password"])<13){
                        if(isset($_POST["confirm_password"]) && $_POST["confirm_password"]==$_POST["password"]){
                            $this->NodCMS_general_model->user_set_new_password($user["user_id"],md5($_POST["password"]));
                            // Send email
                            $search = array('[--$company--]','[--$date--]','[--$username--]','[--$email--]','[--$cdate--]');
                            $replace = array($this->data['settings']["company"],my_int_date(time()),$user['username'],$user['email'],$user['created_date']);
                            $body_data = str_replace($search,$replace,$this->data['settings']['options']['msg_active']);
                            $email_body = $this->load->view('nodcms_general/email-template-public',array('body'=>$body_data),true);
                            $this->sendEmailAutomatic($user["email"],_l("Change password confirmation!",$this),$email_body);

                            $this->session->set_flashdata('message_success', _l("Your account is active now.",$this));
                            redirect(base_url().$lang."/login");
                        }else{
                            $this->session->set_flashdata('message_error', _l("Password not match",$this));
                        }
                    }else{
                        $this->session->set_flashdata('message_error', _l("Password not match",$this));
                    }
                    redirect(base_url().$lang."/".$redirect."/".$email_hash."/".$active_code);
                }
            }else{
                show_404();
            }
        }
        $this->data['title']=_l("Set password",$this);
        $this->data['content']=$this->load->view($this->mainTemplate.'/reset_password',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data,'');
    }
    function profile_password($lang){
        $this->preset($lang);
        if(isset($_SESSION['user']))
        {
            if(isset($_POST['old_password']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
                if($_POST['old_password']!="" && $_POST['password']!="" && strlen($_POST['password'])>5 && strlen($_POST['password'])<13 && $_POST['password'] = $_POST['confirm_password']){
                    if($_SESSION['user']['password']==md5($_POST['old_password'])){
                        $this->NodCMS_general_model->user_edit_password($_SESSION['user']['user_id'],md5($_POST['password']));
                        $this->session->set_flashdata('message_success', _l("Password changed",$this));
                    }else{
                        $this->session->set_flashdata('old_password_error', _l("Last password not correct",$this));
                    }
                }else{
                    $this->session->set_flashdata('error', _l("Inserted data not correct",$this));
                }
                redirect(base_url().$lang."/profile-password", 'refresh');
            }
            $this->data['title']=_l('Change password',$this);
            $this->data['content']=$this->load->view($this->mainTemplate.'/profilepassword',$this->data,true);
            $this->load->view($this->mainTemplate,$this->data,'');
        }
        else
        {	$_SESSION['redirect_page'] = "profile-password";
            redirect('/login', 'refresh');
        }
    }

    function extension_detail($lang,$id){
        $this->preset($lang);
        $extension = $this->NodCMS_general_model->get_extension_by_extension_id($id);
        $this->data['data']=$extension;
        $comments = $this->NodCMS_general_model->get_comments_by_extension_id($id);
        foreach ($comments as $key=>$value) {
            $sub_comments = $this->NodCMS_general_model->get_replay_comments($value["comment_id"]);
            if(count($sub_comments)!=0){ $comments[$key]["sub_comments"] = $sub_comments; }
        }
        if(isset($_GET["filter"]) && $_GET["filter"]!="") $this->data['search_result'] = $_GET["filter"];
        $this->data['comments'] = $comments;
        $this->load->library('spyc');
        $page_type = spyc_load_file(getcwd()."/page_type.yml") ;
        if(!allowed_theme_extension($extension["page_type"],$page_type)){
            show_404();
        }
        $this->data['relations'] = $this->NodCMS_general_model->get_extension_related($extension["page_id"],$id,"created_date","DESC",get_in_extension_related_limit($extension["page_type"],$page_type));
        $this->data['title']=$extension['name'];
        $this->data['keyword']=$extension['tag'];
        $this->data['description']=$extension['description'];
        $this->data['view']=get_theme_extension($extension["page_type"],$page_type);
        $this->data['content']=$this->load->view($this->mainTemplate.'/'.$this->data['view'],$this->data,true);
        $this->load->view($this->mainTemplate,$this->data,'');
    }

    function extension_addcomment($lang){
        $this->preset($lang);
        if(isset($_POST["ext_id"]) && isset($_SESSION['user'])){
            $comment = $this->input->post('comment');
            $ext_id = $this->input->post('ext_id');
            if(!$this->NodCMS_general_model->check_comment_by_session_id(session_id(),$comment,$ext_id) && $this->NodCMS_general_model->check_extension_exists($ext_id)){
                $data = array(
                    'user_id' 	=> $_SESSION['user']['user_id'],
                    'content' 	=> $comment,
                    'created_date' 	=> time(),
                    'extension_id' 	=> $ext_id,
                    'session_id' 	=> session_id(),
                    'lang' 	=> $lang
                );
                $new_comment_id = $this->NodCMS_general_model->insert_comment($data);
                if($_SESSION['user']['avatar']!="")
                    $avatar = $_SESSION['user']['avatar'];
                else
                    $avatar = "assets/frontend/img/noimage.jpg";

                echo json_encode(array('status'=>1,'comment'=>$_POST["comment"],'success'=>_l("Success: post comment ! After confirm your comment displayed in site",$this)));
            }else{
                echo json_encode(array('status'=>0,'errors'=>_l("Error: You cannot enter duplicate comment",$this)));
            }
        }else{
            echo json_encode(array('status'=>0,'errors'=>_l("Your request is not valid.",$this)));
        }
    }

    function page($lang,$id=null){
        $this->preset($lang);
        $this->load->library('spyc');
        $page_type = spyc_load_file(getcwd()."/page_type.yml");
        $page = $this->NodCMS_general_model->get_page_detail($id);
        if(!allowed_theme_page($page["page_type"],$page_type)){
            show_404();
        }
        $order_by="created_date"; $sort="DESC";
        if(check_extension_order_preview($page["page_type"],$page_type)){ $order_by="extension_order"; $sort="ASC"; }
        if(isset($page_type[$page["page_type"]]["dynamic"])){
            $page["body"] = $this->NodCMS_general_model->get_extensions_by_page_id($id,$order_by,$sort,10,(isset($_GET["offset"]) && is_numeric($_GET["offset"]))?$_GET["offset"]:0);
        }else{
            $page["body"] = $this->NodCMS_general_model->get_extensions_by_page_id($id,$order_by,$sort);
        }
        $this->data['data'] = $page;
        $this->data['title']=$page['title_caption'];
        if(isset($_GET["ajax"]) && allowed_theme_page_ajax($page["page_type"],$page_type)){
            echo $this->load->view('flatlab/'.get_theme_page_ajax($page["page_type"],$page_type),$this->data,true);
        }else{
            $this->data['view']=get_theme_page($page["page_type"],$page_type);
            $this->data['content']=$this->load->view($this->mainTemplate.'/'.$this->data['view'],$this->data,true);
            $this->load->view($this->mainTemplate,$this->data,'');
        }
    }

    function search($lang){
        $this->preset($lang);
        $search_text = isset($_GET["filter"])?str_replace("'","",$this->input->get("filter")):"";
        if($search_text!=""){
            $search = explode("_",$search_text);
            if(count($search)!=""){
                $limit = 20;
                if(isset($_GET["offset"]) && is_numeric($_GET["offset"])){
                    $offset = $_GET["offset"];
                }else{
                    $offset = 0;
                }
                $this->data['data'] = $this->NodCMS_general_model->search_extension($search,$limit,$offset);
            }
            $this->data['search_word']=str_replace("_"," ",$search_text);
            $this->data['text_search']=$search;
            $this->data['text_replace']=array_map(function($value){ return "<strong>".$value."</strong>"; },$search);
        }else{
            $this->data['data'] = array();
        }
        $this->data['title']=str_replace("_"," ",$search_text);
        if(isset($_GET["ajax"])){
            echo $this->load->view($this->mainTemplate.'/search_ajax',$this->data,true);
        }else{
            $this->data['content']=$this->load->view($this->mainTemplate.'/search',$this->data,true);
            $this->load->view($this->mainTemplate,$this->data,'');
        }
    }

    function contact($lang){
        $this->preset($lang);
        if(isset($_POST["data"])){
            if(isset($_POST["data"]["email"]) && $_POST["data"]["email"]!="" && preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/", $_POST["data"]['email'])){
                if(!isset($_POST["data"]["subject"]) || $_POST["data"]["subject"]==""){
                    $this->session->set_flashdata('message_error', "Subject should not be empty");
                    exit;
                }
                if(!isset($_POST["data"]["name"]) || $_POST["data"]["name"]==""){
                    $this->session->set_flashdata('message_error', "Subject should not be empty");
                    exit;
                }
                $body_data = array(
                    "title"=>$_POST["data"]["name"],
                    "body"=>$_POST["data"]["text"]
                );
                $email_body = $this->load->view('nodcms_general/email-template-public',$body_data,true);
                $this->sendEmailAutomatic($this->data["settings"]["email"],_l("Contact Form",$this).": ".$_POST["data"]["subject"],$email_body,$_POST["data"]["email"]);
            }else{
                $this->session->set_flashdata('message_error', "Email is not correct");
            }
            redirect(base_url().$lang."/contact");
        }
        $this->data['title']='Contact to admin';
        $this->data['content']=$this->load->view($this->mainTemplate.'/contact',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data,'');
    }
    function sendEmailAutomatic($emails, $subject,$content, $from = null, $mailType = 'html')
    {
        if ($_SERVER['SERVER_NAME'] != 'localhost') {
            $setting =  $this->data['settings'];
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
            //$this->email->initialize($config);
            try {
                $this->email->clear();
                $this->email->to($emails);

                if($from == NULL )
                {
                    $this->email->from($setting['email']);
                }
                else
                {
                    $this->email->from($from);
                }
                $this->email->subject($subject);
                $this->email->message($content);
                $this->email->send();
            }catch (Exception $e){
                //Do nothing
                print_r($e);die;
            }
        }
    }
    function admin(){
        redirect(base_url(). 'backend.php');
    }
    /*-------------------------------------------------------------------------------------*/
    function sitemap_xml($lang){
        $this->preset($lang);
        $this->data['extensions'] = $this->NodCMS_general_model->search_extension(array());
        $this->load->view('sitemap',$this->data,'');
    }
    function rss($lang){
        $this->preset($lang);
        $this->data['extensions'] = $this->NodCMS_general_model->search_extension(array());
        $this->load->view('rss',$this->data,'');
    }
}
