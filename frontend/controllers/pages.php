<?php
class Pages extends CI_Controller {
	private $data;
	function preset($lang){
        session_start();

        $language = $this->general_model->get_language_by_code($lang);
        if($language!=0){
            $_SESSION["language"] = $language;
            $this->data["lang"] = $lang;
        }else{
            $language = $this->general_model->get_language_default();
            if($language!=0){
                redirect(base_url().$language["code"]);
            }else{
                die("System error 1");
            }
        }
        $this->lang->load($lang, $language["language_name"]);

        $_SERVER['DOCUMENT_ROOT'] = dirname(dirname(dirname(__FILE__)));
//        die($_SERVER["REQUEST_URI"]);
        $this->data['lang_url'] = $_SERVER["REQUEST_URI"];
		$this->data['action'] = $_SERVER["REQUEST_URI"];
		$this->data['redirect'] = $_SERVER["REQUEST_URI"];
		
        $this->data['settings'] =  $this->general_model->get_website_info();
        $this->data['settings']["options"] =  $this->general_model->get_website_info_options($language["language_id"]);

        $visitor = $this->general_model->get_duplicate_visitor(session_id(),$_SERVER["REQUEST_URI"]);
        if(count($visitor) == 0){
            // Get IP address
            if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
            }

            $ip = filter_var($ip, FILTER_VALIDATE_IP);
            $ip = ($ip === false) ? '0.0.0.0' : $ip;
            $this->load->library('spyc');
            $visitor_data = array(
                "session_id"=>session_id(),
                "user_id"=>isset($_SESSION["user"]["user_id"])?$_SESSION["user"]["user_id"]:0,
                "created_date"=>time(),
                "updated_date"=>time(),
                "user_agent"=>Spyc::YAMLDump($_SERVER["HTTP_USER_AGENT"]),
                "user_ip"=>$ip,
                "language_id"=>$language["language_id"],
                "language_code"=>$language["code"],
                "referrer"=>isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"",
                "request_url"=>$_SERVER['REQUEST_URI'],
                "count_view"=>1
            );
            $this->general_model->insert_visitors($visitor_data);
        }else{
            $visitor = @reset($visitor);
            $visitor_data = array(
                "user_id"=>isset($_SESSION["user"]["user_id"])?$_SESSION["user"]["user_id"]:0,
                "updated_date"=>time(),
                "count_view"=>$visitor["count_view"]+1
            );
            $this->general_model->update_duplicate_visitor(session_id(),$_SERVER["REQUEST_URI"],$visitor_data);
        }

        $data_menu = array();
        $menu = $this->general_model->get_menu();
        $i=0;
		foreach($menu as $menu)
		{
			$data_menu[$i] = array(
					'id'	=> $menu['page_id'],
					'name' =>$menu['title_caption'],
					'url' =>$menu['page_id']!=0?base_url().$lang."/page/".$menu['page_id']:$menu['menu_url'],
			);
			$i++;
		}
		$this->data['languages'] = $this->general_model->get_languages();
        foreach ($this->data['languages'] as &$value) {
            $url_array = explode("/",$this->data["lang_url"]);
            $url_array[array_search($lang,$url_array)]=$value["code"];
            $value["lang_url"] = implode("/",$url_array);
        }

        $this->data['data_menu'] = $data_menu;
		$this->data['link_contact'] = base_url()."contact";

    }

    function ajax_submit_email(){
        if(isset($_POST["email"]) && preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/", $_POST['email'])){
            if($this->general_model->exists_email_in_emails($_POST["email"])){
                $_SESSION["download_email"] = $_POST["email"];
                echo "success";
            }elseif(@$this->general_model->insert_emails(array("email"=>$_POST["email"],"created_date"=>time()))){
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
            $extensions = $this->general_model->get_extension_by_id($extension_id);
            if(!$this->general_model->check_user_rate($_SESSION['user']['user_id'],$extension_id) && isset($extensions[0]["extension_id"])){
                $data = array(
                    "user_id"=>$_SESSION['user']['user_id'],
                    "extension_id"=>$extension_id,
                    "amount"=>$rate,
                    "created_date"=>time()
                );
                $this->general_model->insert_rate($data);
                $this->general_model->extension_count_rate($extension_id,$extensions[0]["count_rate"]+1);
                $this->general_model->extension_sum_rate($extension_id,$extensions[0]["count_rate"]+$rate);
                echo json_encode(array('status'=>1,'errors'=>_l("System error, please notify the Administrator",$this)));
            }else{
                echo json_encode(array('status'=>0,'errors'=>_l("You cat not send more than one for a extension.",$this)));
            }
        }else{
            echo json_encode(array('status'=>0,'errors'=>_l("System error, please notify the Administrator",$this)));
       }
    }

    function ajax_newpassword(){
       $result = $this->general_model->get_user_by_email($_POST['email']);
       if(count($result) > 0){
           $newpassword = substr(md5(session_id().time()),4,6);
           if(@$this->general_model->edit_pass_by_email($_POST["email"],$newpassword)){
               @$this->general_model->send_email_newpassword($_POST["email"],_l("Your password is reset, please login with your new password in http://NodCMS.com.",$this)."<br>".$newpassword);
               echo "success";
           }else{
               echo _l("System error, please notify the Administrator",$this);
           }
       }else{
           echo _l("Error: ",$this)._l("Email is not exists",$this);
       }
    }

    function ajax_login(){
       $result = $this->general_model->check_login_fb($_POST['username'],$_POST['username']);
       $user = @reset($result);
       if($user['password'] == md5($_POST['password'])){
            $this->general_model->update_last_login($user['id']);
           if($_POST['remember']){
                $cookie = array(
                    'name'   => 'remember_me',
                    'value'  => serialize($user),
                    'expire' => 86400*365,
                );
                set_cookie($cookie);
            }
            //$this->session->set_userdata(array('user'=>$user));
            $_SESSION['user'] = $user;

            echo 'success';

       }
    }

	function index($ln=null){
        $this->preset($ln);
        $this->load->library('spyc');
        $page_type = spyc_load_file(getcwd()."/page_type.yml") ;
        $pages_render = array();
        $pages = $this->general_model->get_preview_pages();
        foreach ($pages as $item) {
            $extension_data["page_data"] = $item;
            $extension_data["lang"] = $ln;
            $extension_data["settings"] = $this->data["settings"];
            $extension_data["preview_limit"] = $limit = get_extension_limit_preview($item["page_type"],$page_type);
            if(check_extension_order_preview($item["page_type"],$page_type)){
                $extension_data["data"] = $this->general_model->get_extensions_by_page_id($item["page_id"],"extension_order","ASC",$limit!=0?$limit:null);
            }else{
                $extension_data["data"] = $this->general_model->get_extensions_by_page_id($item["page_id"],"created_date","DESC",$limit!=0?$limit:null);
            }
            foreach ($extension_data["data"] as &$val) {
                if(isset($val['extension_more'])) { $val['extension_more'] = spyc_load($val['extension_more']); }
            }

            if(check_is_gallery($item["page_type"],$page_type)){
                $extension_data["gallery"] = $this->general_model->get_gallery_by_page_id($item["page_id"]);
                $extension_data["gallery_image"] = $this->general_model->get_gallery_image_by_page_id($item["page_id"],"created_date","DESC",$limit!=0?$limit:null);
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
        $this->data['content']='home';
        $this->data['keyword']= isset($this->data['settings']["options"]["site_keyword"])?$this->data['settings']["options"]["site_keyword"]:"";
        $this->data['description']= isset($this->data['settings']["options"]["site_description"])?$this->data['settings']["options"]["site_description"]:"";
        $this->load->view('flatlab',$this->data,'');

	}
    function login($lang,$logout=null){
        $this->preset($lang);
        if($logout!=null){
            unset($_SESSION["user"]);
            redirect(base_url().$lang."/login");
        }
        if(isset($_POST["data"]) && isset($_POST["data"]["email"]) && isset($_POST["data"]["password"])){
            if($_POST["data"]["email"]!="" && $_POST["data"]["password"]!=""){
                $user = $this->general_model->get_user_by_email_and_password($_POST["data"]["email"],md5($_POST["data"]["password"]));
                if($user!=0){
                    $_SESSION["user"] = $user;
                    if(isset($_POST["data"]["keep_login"]) && $_POST["data"]["keep_login"]){
                        $time = time();
                        setcookie("keep_login[0]",md5($user["email"]),0,"~",base_url());
                        setcookie("keep_login[1]",md5($time),0,"~",base_url());
                        $this->general_model->update_user_login($user["user_id"],md5($time),$_SESSION["HTTP_USER_AGENT"]);
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
        $this->data['content']='login';
        $this->load->view('flatlab',$this->data,'');
    }
	function register($lang){
        $this->preset($lang);
        if(isset($_SESSION["user"]["user_id"])) redirect(base_url());
        if(isset($_GET["success"])){
            $this->data['submit_success']=_l("We send you a link to your email, please check your email inbox and spam, and flow that.",$this);
        }elseif(isset($_POST["email"])){
            if($_POST["email"]!="" && preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/", $_POST['email'])){
                if($this->general_model->check_email_exists($_POST["email"])){
                    $error = _l("This email already exists, choose another email address or click on forget password.",$this);
                }else{
                    $make_username = explode("@",$_POST["email"]);
                    $new_username = $make_username[0];
                    while($this->general_model->check_username_exists($new_username)){
                        $new_username = $make_username.rand(1000,8989);
                    }
                    $active_code = md5(substr(md5(time()),4,6));
                    $email_hash = md5($_POST["email"]);
                    if($this->general_model->check_email_exists($_POST["email"],false)){
                        $user = array("active_code"=>$active_code,"reset_pass_exp"=>time()+18000);
                        $this->general_model->update_user_by_email($user,$_POST["email"]);
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
                        $this->general_model->insert_user($user);
                    }
                    $body_data = array(
                        "title"=>_l("Welcome to our website members",$this),
                        "body"=>_l("We make a new account for you, for active your it and choose your password click on this link",$this).
                            "<a href='".base_url().$lang."/active_account/".$email_hash."/".$active_code."'>"._l("Active account & Choose your password",$this)."</a>"
                    );
                    $email_body = $this->load->view('flatlab/email-template-public',$body_data,true);
                    $this->sendEmailAutomatic($_POST["email"],_l("Welcome to",$this),$email_body);
                    redirect(base_url().$lang."/register?success");
                }
            }else{
                $error = _l("Please insert the correct email address",$this);
            }
        }
        if(isset($error)){
            $this->data['submit_error']=$error;
        }
        $this->data['title']='Register';
        $this->data['content']='register';
        $this->load->view('flatlab',$this->data,'');
	}
	function forget_password($lang){
        $this->preset($lang);
        if(isset($_SESSION["user"]["user_id"])) redirect(base_url());
        if(isset($_POST["email"])){
            if($_POST["email"]!="" && preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/", $_POST['email'])){
                if($this->general_model->check_email_exists($_POST["email"])){
                    $active_code = md5(substr(md5(time()),4,6));
                    $user = array("active_code"=>$active_code,"active"=>0,"reset_pass_exp"=>time()+18000);
                    $this->general_model->update_user_by_email($user,$_POST["email"]);
                    $body_data = array(
                        "title"=>_l("Reset your password",$this),
                        "body"=>_l("You want to change your password, for active your it and choose your new password click on this link",$this).
                            "<a href='".base_url().$lang."/reset-password/".md5($_POST["email"])."/".$active_code."'>"._l("Active account & Choose your password",$this)."</a>"
                    );
                    $email_body = $this->load->view('flatlab/email-template-public',$body_data,true);
                    $this->sendEmailAutomatic($_POST["email"],_l("Welcome to",$this),$email_body);
                    $this->session->set_flashdata('message_success', _l("We send you a link to your email, please check your email inbox and spam, and flow that.",$this));
                }else{
                    $this->session->set_flashdata('message_error', _l("This email already exists, choose another email address or click on forget password.",$this));
                }
            }else{
                $this->session->set_flashdata('message_error', _l("Please insert the correct email address",$this));
            }
            redirect(base_url().$lang."/forget-password");
        }
        $this->data['title']=_l('Forget password',$this);
        $this->data['content']='forget_password';
        $this->load->view('flatlab',$this->data,'');
	}
    function reset_password($lang,$email_hash,$active_code){
        $this->preset($lang);
        if($email_hash!=null && $active_code!=null){
            $user = $this->general_model->get_user_by_email_hash_and_active_code($email_hash,$active_code);
            if($user && $user["reset_pass_exp"]>time() && ($user["active"]==0 || $user["active_register"]==0)){
                $this->data['data'] = $user;
                if(isset($_POST["password"])){
                    $redirect = $user["active_register"]==0?"active_account":"reset-password";
                    if(strlen($_POST["password"])>5 && strlen($_POST["password"])<13){
                        if(isset($_POST["confirm_password"]) && $_POST["confirm_password"]==$_POST["password"]){
                            $this->general_model->user_set_new_password($user["user_id"],md5($_POST["password"]));
                            $this->session->set_flashdata('message_success', _l("Your account is active now.",$this));
                        }else{
                            $this->session->set_flashdata('message_error', _l("Password not match",$this));
                        }
                    }else{
                        $this->session->set_flashdata('message_error', _l("Password not match",$this));
                    }
                    redirect(base_url().$lang."/".$redirect."/".$email_hash."/".$active_code);
                }
            }else{
                $this->session->set_flashdata('message_error', _l("Your request not valid.",$this));
            }
        }
        $this->data['title']=_l("Set password",$this);
        $this->data['content']='reset_password';
        $this->load->view('flatlab',$this->data,'');
    }
    function profile(){
        $this->data["userdata"] = $_SESSION["user"];
        $detail = $this->general_model->get_user_by_user_id($_SESSION['user']['user_id']);
        if(count($detail) > 0)
            $this->data['banners'] = $detail[0];
        else
            $this->data['banners'] = null;
        $this->data['title']='Profile';
        $this->data['content']='profile';
        $this->load->view('flatlab',$this->data,'');
	}
 	function profile_detail(){
         $this->data["userdata"] = $_SESSION["user"];
         $url=getcwd()."/upload_file/avatars/";
         $this->data["avatars"]= scandir($url);
         unset($this->data["avatars"][0]);unset($this->data["avatars"][1]);
         if(isset($_SESSION['user']))
         {
             $this->data['property_data_default'] = $this->general_model->get_user_property_default_by_category_id();
             foreach($this->data['property_data_default'] as &$da)
             {
                 $p_data = $this->general_model->get_user_property_by_value_id($da['value_id'],$_SESSION['user']['user_id']);
                 if(count($p_data)>0)
                 {
                     $da['property_value'] = $p_data[0]['property_value'];
                     $da['user_id'] = $_SESSION['user']['user_id'];
                 }
             }
             if(isset($_POST) && $_POST != null )
             {
                 if(isset($_POST['data'])){
                     $data = $_POST['data'];
                     $error_username = $this->general_model->get_user_by_username($data['username']);
                     if(count($error_username)!=0 && $error_username[0]['user_id'] != $_SESSION['user']['user_id']){
                         $this->data['error_username'] = true;
                         $error_username = true;
                     }
                     $error_email = $this->general_model->get_user_by_email($data['email']);
                     if(count($error_email)!=0 && $error_email[0]['user_id'] != $_SESSION['user']['user_id']){
                         $this->data['error_email'] = true;
                         $error_email = true;
                     }
                     unset($data["country"]);
                     unset($data["region"]);
                     if(isset($data['custom']))
                     {
                         $custom = $data["custom"];
                         unset($data["custom"]);
                     }
                     else
                     {
                         $custom = null;
                     }
                     if(in_array($_POST["image"], $this->data["avatars"])){
                         $data["image"]='upload_file/avatars/'.$_POST["image"];
                     }else{
                         $data["image"]="";
                     }
                     if(isset($error_username) && $error_username ==true && isset($error_email) && $error_email ==true){


                         $this->session->set_flashdata('message', "account details");
                         $this->general_model->edit_user($_SESSION['user']['user_id'],$data,$custom);



                     } else {
                         $this->data['data'] = $data;
                         if(isset($data['custom']))
                         {
                             foreach($this->data['property_data_default'] as &$da)
                             {
                                 $da['property_value'] = $data['custom'][$da['value_id']];
                             }
                         }
                     }
                 }

                 if(isset($_POST['notify'])){
                     $notify = $_POST['notify'];
                     $this->general_model->edit_notify($_SESSION['user']['user_id'],$notify);
                 }
                 redirect('/profile-detail', 'refresh');
             }

             $detail = $this->general_model->get_user_by_user_id($_SESSION['user']['user_id']);
             if(count($detail) > 0)
                 $this->data['banners'] = $detail[0];
             else
                 $this->data['banners'] = null;
             $this->data['payment_method'] = $this->general_model->get_all_payment();

             $this->data['title']='Edit Detail';
             $this->data['content']='profiledetail';
             $this->load->view('flatlab',$this->data,'');
         }
         else
         {	$_SESSION['redirect_page'] = "profile-detail";
             redirect('/login', 'refresh');
         }
	}
	
	function profile_password($lang){
        $this->preset($lang);
        if(isset($_SESSION['user']))
        {
            if(isset($_POST['old_password']) && isset($_POST['password']) && isset($_POST['confirm_password'])){
                if($_POST['old_password']!="" && $_POST['password']!="" && strlen($_POST['password'])>5 && strlen($_POST['password'])<13 && $_POST['password'] = $_POST['confirm_password']){
                    if($_SESSION['user']['password']==md5($_POST['old_password'])){
                        $this->general_model->user_edit_password($_SESSION['user']['user_id'],md5($_POST['password']));
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
            $this->data['content']='profilepassword';
            $this->load->view('flatlab',$this->data,'');
        }
        else
        {	$_SESSION['redirect_page'] = "profile-password";
            redirect('/login', 'refresh');
        }
	}

    function profile_forget_password(){
        if(!isset($_SESSION['user']))
        {
            if(isset($_POST['data'])){
                $data = $_POST['data'];
                $data['password'] = md5($data['password']);
                $this->session->set_flashdata('message', "password");
                unset($data['confirm']);
                $this->general_model->edit_pass($_SESSION['user']['user_id'],$data);
                redirect('/profile', 'refresh');
            }
            $this->data['title']=_l('Reset password',$this);;
            $this->data['content']='profileforgetpassword';
            $this->load->view('templates',$this->data,'');
        }
        else
            redirect('/', 'refresh');
    }

	function extension_detail($lang,$id){
        $this->preset($lang);
        $extension = $this->general_model->get_extension_by_extension_id($id);
        $this->data['data']=$extension;
        $comments = $this->general_model->get_comments_by_extension_id($id);
        foreach ($comments as $key=>$value) {
            $sub_comments = $this->general_model->get_replay_comments($value["comment_id"]);
            if(count($sub_comments)!=0){ $comments[$key]["sub_comments"] = $sub_comments; }
        }
        if(isset($_GET["filter"]) && $_GET["filter"]!="") $this->data['search_result'] = $_GET["filter"];
        $this->data['comments'] = $comments;
        $this->load->library('spyc');
        $page_type = spyc_load_file(getcwd()."/page_type.yml") ;
        if(!allowed_theme_extension($extension["page_type"],$page_type)){
            show_404();
        }
        $this->data['relations'] = $this->general_model->get_extension_related($extension["page_id"],$id,"created_date","DESC",get_in_extension_related_limit($extension["page_type"],$page_type));
        $this->data['content']=get_theme_extension($extension["page_type"],$page_type);
        $this->data['title']=$extension['name'];
        $this->data['keyword']=$extension['tag'];
        $this->data['description']=$extension['description'];
        $this->load->view('flatlab',$this->data,'');
	}

	function extension_addcomment($lang){
        $this->preset($lang);
        if(isset($_POST["ext_id"]) && isset($_SESSION['user'])){
            if(!$this->general_model->check_comment_by_session_id(session_id(),$_POST['comment'],$_POST['ext_id']) && $this->general_model->check_extension_exists($_POST['ext_id'])){
                $data = array(
                    'user_id' 	=> $_SESSION['user']['user_id'],
                    'content' 	=> $_POST['comment'],
                    'created_date' 	=> time(),
                    'extension_id' 	=> $_POST['ext_id'],
                    'session_id' 	=> session_id(),
                    'lang' 	=> $lang
                );
                $new_comment_id = $this->general_model->insert_comment($data);
                if($_SESSION['user']['avatar']!="")
                    $avatar = $_SESSION['user']['avatar'];
                else
                    $avatar = "assets/frontend/img/noimage.jpg";

                echo json_encode(array('status'=>1,'comment'=>$_POST["comment"],'success'=>_l("Success: post comment ! After confirm your comment displayed in site",$this)));
            }else{
                echo json_encode(array('status'=>0,'errors'=>_l("Error: You cannot enter duplicate comment",$this)));
            }
        }else{
            echo json_encode(array('status'=>0,'errors'=>_l("Your request not valid.",$this)));
        }
    }

    function page($lang,$id=null){
        $this->preset($lang);
        $this->load->library('spyc');
        $page_type = spyc_load_file(getcwd()."/page_type.yml");
        $page = $this->general_model->get_page_detail($id);
        if(!allowed_theme_page($page["page_type"],$page_type)){
            show_404();
        }
        $order_by="created_date"; $sort="DESC";
        if(check_extension_order_preview($page["page_type"],$page_type)){ $order_by="extension_order"; $sort="ASC"; }
        if(isset($page_type[$page["page_type"]]["dynamic"])){
            $page["body"] = $this->general_model->get_extensions_by_page_id($id,$order_by,$sort,10,(isset($_GET["offset"]) && is_numeric($_GET["offset"]))?$_GET["offset"]:0);
        }else{
            $page["body"] = $this->general_model->get_extensions_by_page_id($id,$order_by,$sort);
        }
        $this->data['data'] = $page;
        $this->data['title']=$page['title_caption'];
        if(isset($_GET["ajax"]) && allowed_theme_page_ajax($page["page_type"],$page_type)){
            echo $this->load->view('flatlab/'.get_theme_page_ajax($page["page_type"],$page_type),$this->data,true);
        }else{
            $this->data['content']=get_theme_page($page["page_type"],$page_type);
            $this->load->view('flatlab',$this->data,'');
        }
    }

    function search($lang){
        $this->preset($lang);
        $search_text = isset($_GET["filter"])?$_GET["filter"]:"";
        $search = explode("_",$search_text);
        if(count($search)!=""){
            $limit = 20;
            if(isset($_GET["offset"]) && is_numeric($_GET["offset"])){
                $offset = $_GET["offset"];
            }else{
                $offset = 0;
            }
            $this->data['data'] = $this->general_model->search_extension($search,$limit,$offset);
        }
        $this->data['search_word']=str_replace("_"," ",$search_text);
        $this->data['text_search']=$search;
        $this->data['text_replace']=array_map(function($value){ return "<strong>".$value."</strong>"; },$search);
        $this->data['title']=str_replace("_"," ",$search_text);
        if(isset($_GET["ajax"])){
            echo $this->load->view('flatlab/search_ajax',$this->data,true);
        }else{
            $this->data['content']="search";
            $this->load->view('flatlab',$this->data,'');
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
                $email_body = $this->load->view('flatlab/email-template-public',$body_data,true);
                $this->sendEmailAutomatic($this->data["settings"]["email"],_l("Contact Form",$this).": ".$_POST["data"]["subject"],$email_body,$_POST["data"]["email"]);
            }else{
                $this->session->set_flashdata('message_error', "Email is not correct");
            }
            redirect(base_url().$lang."/contact");
        }
        $this->data['title']='Contact to admin';
        $this->data['content']='contact';
        $this->load->view('flatlab',$this->data,'');
    }
	function sendEmailAutomatic($emails, $subject,$content, $from = null, $mailType = 'html')
	{
		/*
	     * Send mail
	     */
		$setting =  @reset($this->general_model->get_email_from_setting());
	
//	   	$config = array(
//			  	'protocol' => 'smtp',
//			    'smtp_host' => $setting['smtp_host'],
//			    'smtp_port' => $setting['smtp_port'],
//			    'smtp_user' => $setting['smtp_username'],
//			    'smtp_pass' => $setting['smtp_password'],
//			    'mailtype'  => 'html',
//			    'charset'   => 'iso-8859-1',
//	   			'starttls'  => true,
//            	'newline'   => "\r\n"
//		);
	   	$config = array(
			  	'protocol' => 'mail',
			    'mailtype'  => $mailType,
			    'charset'   => 'utf8',
	   			'starttls'  => true,
            	'newline'   => "\r\n"
		);
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
	function admin(){
        redirect(base_url(). 'backend.php');
    }
    /*-------------------------------------------------------------------------------------*/
    function sitemap_xml($lang){
        $this->preset($lang);
        $this->data['extensions'] = $this->general_model->search_extension(array());
        $this->load->view('sitemap',$this->data,'');
    }
    function rss($lang){
        $this->preset($lang);
        $this->data['extensions'] = $this->general_model->search_extension(array());
        $this->load->view('rss',$this->data,'');
    }

}