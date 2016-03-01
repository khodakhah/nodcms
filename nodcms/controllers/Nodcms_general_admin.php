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
class Nodcms_general_admin extends NodCMS_Controller {
    function __construct()
    {
        parent::__construct('backend');
    }

    function index()
    {
        $extension_count = $this->NodCMS_general_admin_model->count_extensions();
        $comment_count = $this->NodCMS_general_admin_model->count_comment();
        $this->data['languages'] = $this->NodCMS_general_admin_model->get_all_language();
        foreach($this->data['languages'] as &$item){
            $item["content_percent"] = $this->NodCMS_general_admin_model->count_extensions(array("language_id"=>$item["language_id"]))!=0?(($this->NodCMS_general_admin_model->count_extensions(array("language_id"=>$item["language_id"])) * 100) / $extension_count):0;
            $item["comment_percent"] = $this->NodCMS_general_admin_model->count_comment(array("lang"=>$item["code"]))!=0?(($this->NodCMS_general_admin_model->count_comment(array("lang"=>$item["code"])) * 100) / $comment_count):0;
        }
        $this->data['extension_count']=$extension_count;
//        The new statistic
        $this->data['statistic_max_visitors']=$this->NodCMS_general_admin_model->get_statistic_max_visitors();
        $this->data['statistic']=$this->NodCMS_general_admin_model->get_all_statistic();
        krsort($this->data['statistic']);
        $this->data['statistic_total_visits']=$this->NodCMS_general_admin_model->get_statistic_total_visits();
        $this->data['statistic_total_visitors']=$this->NodCMS_general_admin_model->get_statistic_total_visitors();

        $this->data['page_count']=$this->NodCMS_general_admin_model->count_page();
        $this->data['gallery_count']=$this->NodCMS_general_admin_model->count_gallery();
        $this->data['gallery_image_count']=$this->NodCMS_general_admin_model->count_gallery_image();
        $this->data['image_count']=$this->NodCMS_general_admin_model->count_uploaded_image();
        $this->data['users_count']=$this->NodCMS_general_admin_model->count_users();
        $this->data['content']=$this->load->view($this->mainTemplate.'/main',$this->data,true);
        $this->data['title'] = "home";
        $this->data['page'] = "home";
        $this->load->view($this->mainTemplate,$this->data);
    }
    function settings($sub_page='general'){
        if(isset($_POST['data'])){
            if ($this->session->userdata['group']==1) {
                $data = $this->input->post('data');
                if(isset($data["options"])){
                    foreach($data["options"] as $key=>$value){
                        if($this->NodCMS_general_admin_model->check_setting_options($key)){
                            $this->NodCMS_general_admin_model->edit_setting_options($key,$value);
                        }else{
                            $this->NodCMS_general_admin_model->insert_setting_options($key,$value);
                        }
                    }
                    unset($data["options"]);
                }
                $this->NodCMS_general_admin_model->edit_setting($data);
                $this->session->set_flashdata('success', _l("Your Setting has been updated successfully!",$this));
            }else{
                $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
            }
            redirect(base_url().'admin/settings/'.$sub_page, 'refresh');
        }
        $data_options = array();
        $setting_options = $this->NodCMS_general_admin_model->get_all_setting_options();
        foreach($setting_options as $value){
            $data_options[$value["language_id"]] = $value;
        }
        $this->data['options'] = $data_options;
        $this->data['languages'] = $this->NodCMS_general_admin_model->get_all_language();
        if($sub_page=='general'){
            $this->data['title'] = _l('General settings',$this);
            $this->data['content']=$this->load->view($this->mainTemplate.'/setting',$this->data,true);
        }elseif($sub_page=='seo'){
            $this->data['title'] = _l('SEO optimise',$this);
            $this->data['content']=$this->load->view($this->mainTemplate.'/setting_seo',$this->data,true);
        }elseif($sub_page=='contact'){
            $this->data['title'] = _l('Contact settings',$this);
            $this->data['content']=$this->load->view($this->mainTemplate.'/setting_contact',$this->data,true);
        }elseif($sub_page=='mail'){
            $this->data['public_keys'] = array(
                array('label'=>_l('Company name',$this),'value'=>'[--$company--]'),
                array('label'=>_l('Site Email',$this),'value'=>'[--$smail--]'),
                array('label'=>_l('Date',$this),'value'=>'[--$date--]'),
            );
            $this->data['register_keys'] = array(
                array('label'=>_l('Company name',$this),'value'=>'[--$company--]'),
                array('label'=>_l('Username',$this),'value'=>'[--$username--]'),
                array('label'=>_l('Email address',$this),'value'=>'[--$email--]'),
                array('label'=>_l('User activate URL',$this),'value'=>'[--$refurl--]'),
                array('label'=>_l('Date',$this),'value'=>'[--$date--]'),
                array('label'=>_l('User created date',$this),'value'=>'[--$cdate--]'),
            );
            $this->data['activate_keys'] = array(
                array('label'=>_l('Company name',$this),'value'=>'[--$company--]'),
                array('label'=>_l('Username',$this),'value'=>'[--$username--]'),
                array('label'=>_l('Email address',$this),'value'=>'[--$email--]'),
                array('label'=>_l('Date',$this),'value'=>'[--$date--]'),
            );
            $this->data['reset_pass_keys'] = array(
                array('label'=>_l('Company name',$this),'value'=>'[--$company--]'),
                array('label'=>_l('Username',$this),'value'=>'[--$username--]'),
                array('label'=>_l('Email address',$this),'value'=>'[--$email--]'),
                array('label'=>_l('Make new password URL',$this),'value'=>'[--$refurl--]'),
            );
            $this->data['title'] = _l('Send mail settings',$this);
            $this->data['content']=$this->load->view($this->mainTemplate.'/setting_mail',$this->data,true);
        }
        $this->data['page'] = "setting";
        $this->load->view($this->mainTemplate,$this->data);
    }

    function editmenu($id=0)
    {
        if(isset($_POST['data'])){
            if ($this->session->userdata['group']==1) {
                $data = $_POST['data'];
                $this->NodCMS_general_admin_model->edit_setting($data);
                $this->session->set_flashdata('success', _l("Your Setting has been updated successfully!",$this));
            }else{
                $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
            }
            redirect(base_url().'admin/admin_setting', 'refresh');
        }
        if($id!='')
        {
            $this->data['data']=$this->NodCMS_general_admin_model->get_menu_detail($id);
            if($this->data['data']==null)
                redirect(base_url()."admin/editmenu");
        }
        $titles = array();
        $data_titles = $this->NodCMS_general_admin_model->get_all_titles("menu",$id);
        if(count($data_titles)!=0){
            foreach ($data_titles as $value) {
                $titles[$value["language_id"]] = $value;
            }
        }

        $this->load->library('spyc');
        $icons = spyc_load_file(getcwd()."/icons.yml");
        $this->data['faicons'] = $icons["fa"];

        $this->data['titles'] = $titles;
        $this->data['data_list'] = $this->NodCMS_general_admin_model->get_all_menu(array('sub_menu'=>0));
        foreach($this->data['data_list'] as &$item){
            $item['sub_menu_data'] = $this->NodCMS_general_admin_model->get_all_menu(array('sub_menu'=>$item['menu_id']));
        }
        $this->data['parents'] = $this->NodCMS_general_admin_model->get_all_menu(array('sub_menu'=>0));
        $this->data['pages'] = $this->NodCMS_general_admin_model->get_all_page();
        $this->data['languages'] = $this->NodCMS_general_admin_model->get_all_language();
        $this->data['title'] = _l("menu manager",$this);
        $this->data['page'] = "menu";
        $this->data['content']=$this->load->view($this->mainTemplate.'/menumanager',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function menu_manipulate($id=null)
    {
        if ($this->session->userdata['group']==1) {
            if ($this->NodCMS_general_admin_model->menu_manipulate($_POST["data"],$id))
            {
                $this->session->set_flashdata('success', _l('Updated menu',$this));
            }
            else
            {
                $this->session->set_flashdata('error', _l('Updated menu error. Please try later',$this));
            }
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/editmenu/");
    }
    function deletemenu($id=0)
    {
        if ($this->session->userdata['group']==1) {
            $this->db->trans_start();
            $this->db->delete('menu', array('menu_id' => $id));
            $this->db->trans_complete();
            $this->session->set_flashdata('success', _l('Deleted Menu',$this));
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/editmenu/");
    }

    function language()
    {
        $this->data['data_list']=$this->NodCMS_general_admin_model->get_all_language();
        $this->data['title'] = _l("language",$this);
        $this->data['page'] = "language";
        $this->data['content']=$this->load->view($this->mainTemplate.'/language',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function editlanguage($id='')
    {
        if($id!='')
        {
            $this->data['data']=$this->NodCMS_general_admin_model->get_language_detail($id);
            if($this->data['data']==null)
                redirect(base_url()."admin/language");
        }
        $this->data['title'] = _l("language",$this);
        $this->data['page'] = "language";
        $this->data['content']=$this->load->view($this->mainTemplate.'/language_edit',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function language_manipulate($id=null)
    {
        if ($this->session->userdata['group']==1) {
            if ($this->NodCMS_general_admin_model->language_manipulate($_POST["data"],$id))
            {
                $dir = getcwd().'/nodcms/language/'.$_POST["data"]['language_name'].'/';
                $file = $dir.$_POST["data"]['code'].'_lang.php';
                if(!file_exists($dir)){
                    mkdir($dir);
                }
                if(!file_exists($file)){
                    $myfile = fopen($file, "w") or die("Unable to open file!");
                    $txt = "<?php\n";
                    fwrite($myfile, $txt);
                    fclose($myfile);
                }
                $file = $dir.'backend_lang.php';
                if(!file_exists($file)){
                    $myfile = fopen($file, "w") or die("Unable to open file!");
                    $txt = "<?php\n";
                    fwrite($myfile, $txt);
                    fclose($myfile);
                }
                $this->session->set_flashdata('success', _l('Updated Language',$this));
            }
            else
            {
                $this->session->set_flashdata('error', _l('Updated Language error. Please try later',$this));
            }
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/language/");
    }
    function deletelanguage($id=0)
    {
        if ($this->session->userdata['group']==1) {
            $this->db->trans_start();
            $this->db->delete('languages', array('language_id' => $id));
            $this->db->trans_complete();
            $this->session->set_flashdata('success', _l('Deleted Language',$this));
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/language/");
    }
    function edit_lang_file($id,$file_name)
    {
        $this->data['data']=$this->NodCMS_general_admin_model->get_language_detail($id);
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
        if(isset($_POST['data'])){
            if ($this->session->userdata['group']==1) {
                $post_data = $this->input->post('data');
                $i=0;
                $fileContent = "<?php\n";
                foreach ($this->data['lang_list'] as $key=>&$val) {
                    $fileContent .= '$lang["'.$key.'"] = "'.$post_data[$i].'";'."\n";
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
                redirect(base_url()."admin/language");
            }
        }
        $this->data['file_name'] = $file_name;
        $this->data['languages']=$this->NodCMS_general_admin_model->get_all_language();
        $this->data['title'] = _l("Edit language file",$this);
        $this->data['page'] = "edit lang file";
        $this->data['content']=$this->load->view($this->mainTemplate.'/language_edit_file',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }

    function comment()
    {
        $this->data['data_list']=$this->NodCMS_general_admin_model->get_all_comment();
        $this->data['title'] = _l("comment",$this);
        $this->data['page'] = "comment";
        $this->data['content']=$this->load->view($this->mainTemplate.'/comment',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function editcomment($id='')
    {
        if($id!='')
        {
            $this->data['data']=$this->NodCMS_general_admin_model->get_comment_detail($id);
            if($this->data['data']==null){
                $this->session->set_flashdata('error', _l('Updated replay comment error. Please try later',$this));
                redirect(base_url()."admin/comment");
            }else{
                $this->data['reply_data']=$this->NodCMS_general_admin_model->get_comment_detail($id,true);
            }
        }
        $this->data['title'] = _l("comment",$this);
        $this->data['page'] = "comment";
        $this->data['content']=$this->load->view($this->mainTemplate.'/comment_edit',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function comment_manipulate($id=null)
    {
        if ($this->session->userdata['group']==1) {
            if ($this->NodCMS_general_admin_model->comment_manipulate($_POST["data"],$id))
            {
                $this->session->set_flashdata('success', _l('Updated comment',$this));
                if(isset($_POST["replay"]) && $_POST["replay"]["content"]!="" && $id!=null){
                    if ($this->NodCMS_general_admin_model->comment_replay_manipulate($_POST["replay"],$id))
                    {
                        $this->session->set_flashdata('success', _l('Updated replay comment',$this));
                    }
                    else
                    {
                        $this->session->set_flashdata('error', _l('Updated replay comment error. Please try later',$this));
                    }
                }
            }
            else
            {
                $this->session->set_flashdata('error', _l('Updated comment error. Please try later',$this));
            }
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/comment/");
    }
    function deletecomment($id=0)
    {
        if ($this->session->userdata['group']==1) {
            $this->db->trans_start();
            $this->db->delete('comment', array('comment_id' => $id));
            $this->db->trans_complete();
            $this->session->set_flashdata('success', _l('Deleted comment',$this));
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/comment/");
    }

    function user()
    {
        if($this->session->userdata['group']!=1){
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
            redirect(base_url()."admin/");
        }
        $this->data['data_list']=$this->NodCMS_general_admin_model->get_all_user();
        $this->data['title'] = _l("User",$this);
        $this->data['page'] = "user";
        $this->data['content']=$this->load->view($this->mainTemplate.'/user',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function edituser($id='')
    {
        if($this->session->userdata['group']!=1){
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
            redirect(base_url()."admin/");
        }
        if($id!='')
        {
            $this->data['data']=$this->NodCMS_general_admin_model->get_user_detail($id);
            if($this->data['data']==null)
                redirect(base_url()."admin/user");
        }
        $this->data['title'] = _l("User",$this);
        $this->data['page'] = "user";
        $this->data['content']=$this->load->view($this->mainTemplate.'/user_edit',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function user_manipulate($id=null)
    {
        if ($this->session->userdata['group']==1) {
            if ($this->NodCMS_general_admin_model->user_manipulate($_POST["data"],$id))
            {
                $this->session->set_flashdata('success', _l('Updated user',$this));
            }
            else
            {
                $this->session->set_flashdata('error', _l('Updated user error. Please try later',$this));
            }
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
            redirect(base_url()."admin/");
        }
        redirect(base_url()."admin/user/");
    }
    function deleteuser($id=0,$status=0)
    {
        if ($this->session->userdata['group']==1) {
            $this->db->trans_start();
            $this->db->where('user_id',$id);
            $this->db->update('users', array('status' => $status));
            $this->db->trans_complete();
            $this->session->set_flashdata('success', _l('Deleted user',$this));
        }
        redirect(base_url()."admin/user/");
    }

    function extensions($data_type=null,$relation_id=null)
    {
        if($data_type!=null && $relation_id!=null && is_numeric($relation_id)){
            $accept_type = array("city","tours","page");
            if(!in_array($data_type,$accept_type)){
                $this->session->set_flashdata('error', _l('Your request is problem!',$this));
                redirect(base_url()."admin/gallery");
            }
            $this->data['data_list']=$this->NodCMS_general_admin_model->get_all_extension($data_type,$relation_id);
            if($data_type=="page"){
                $data = $this->NodCMS_general_admin_model->get_page_detail($relation_id);
                $add_on_title = " - ".$data["page_name"];
            }
        }else{
            $this->data['data_list']=$this->NodCMS_general_admin_model->get_all_extension();
            $add_on_title = "";
        }
        if($data_type!=null) $this->data['data_type'] = $data_type;
        if($relation_id!=null) $this->data['relation_id'] = $relation_id;
        $this->data['title'] = _l("extension",$this).$add_on_title;
        $this->data['page'] = "extension";
        $this->data['content']=$this->load->view($this->mainTemplate.'/extensions',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);

    }
    function editextension($id=0,$data_type=null,$relation_id=null)
    {
        if($id!=0)
        {
            $this->data['data']=$this->NodCMS_general_admin_model->get_extension_detail($id);
            if($this->data['data']==null)
                redirect(base_url()."admin/extension");
            if(isset($this->data['data']['extension_more'])) { $this->data['data']['extension_more'] = spyc_load($this->data['data']['extension_more']); }
            if(isset($this->data['data']["data_type"]) && $this->data['data']["data_type"]!="") $data_type = $this->data['data_type'] = $this->data['data']["data_type"];
            if(isset($this->data['data']["relation_id"]) && $this->data['data']["relation_id"]!=0) $relation_id = $this->data['relation_id'] = $this->data['data']["relation_id"];
        }elseif($data_type==null || $relation_id==null){
            $this->session->set_flashdata('error', _l('Your request is not valid.',$this));
            redirect(base_url()."admin/extensions/");
        }else{
            if($data_type!=null) $this->data['data_type'] = $data_type;
            if($relation_id!=null) $this->data['relation_id'] = $relation_id;
        }
        $this->load->library('spyc');
        if($data_type=="page" && $relation_id!=null){
            $page = $this->NodCMS_general_admin_model->get_page_detail($relation_id);
            $options = spyc_load_file(getcwd()."/page_type.yml");
            if(isset($options[$page["page_type"]])){
                $this->data['page_type'] = $options[$page["page_type"]];
            }
        }else{
            $this->data['fields'] = array("icon","image","description","full_description");
        }
        $icons = spyc_load_file(getcwd()."/icons.yml");
        $this->data['faicons'] = $icons["fa"];
        $this->data['languages'] = $this->NodCMS_general_admin_model->get_all_language();
        $this->data['title'] = _l("extension",$this);
        $this->data['page'] = "extension";
        $this->data['content']=$this->load->view($this->mainTemplate.'/extension_edit',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function extension_manipulate($id=null)
    {
        if ($this->session->userdata['group']==1) {
            if(isset($_POST["data"]["extension_more"])){ $_POST["data"]["extension_more"] = Spyc::YAMLDump($_POST["data"]["extension_more"]); }
            if ($this->NodCMS_general_admin_model->extension_manipulate($_POST["data"],$id))
            {
                $this->session->set_flashdata('success', _l('Updated extension',$this));
            }
            else
            {
                $this->session->set_flashdata('error', _l('Updated extension error. Please try later',$this));
            }
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/extensions/".(isset($_POST["data"]["data_type"])?$_POST["data"]["data_type"]."/":"").(isset($_POST["data"]["data_type"])?$_POST["data"]["relation_id"]."/":""));
    }
    function deleteextension($id=0,$data_type=null,$relation_id=null)
    {
        if ($this->session->userdata['group']==1) {
            $this->db->trans_start();
            $this->db->delete('extensions', array('extension_id' => $id));
            $this->db->trans_complete();
            $this->session->set_flashdata('success', _l('Deleted extension',$this));
        }
        redirect(base_url()."admin/extensions/".($data_type!=null?$data_type."/":"").($relation_id!=null?$relation_id."/":""));
    }

    function page()
    {
        $this->load->library('spyc');
        $this->data['page_type'] = spyc_load_file(getcwd()."/page_type.yml") ;
        $this->data['data_list']=$this->NodCMS_general_admin_model->get_all_page();
        $this->data['title'] = _l("page",$this);
        $this->data['page'] = "page";
        $this->data['content']=$this->load->view($this->mainTemplate.'/page',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function editpage($id='')
    {
        if($id!='')
        {
            $this->data['data']=$this->NodCMS_general_admin_model->get_page_detail($id);
            if($this->data['data']==null)
                redirect(base_url()."admin/page");
        }

        $this->load->library('spyc');
        $this->data['page_type'] = spyc_load_file(getcwd()."/page_type.yml") ;

        $titles = array();
        $data_titles = $this->NodCMS_general_admin_model->get_all_titles("page",$id);
        if(count($data_titles)!=0){
            foreach ($data_titles as $value) {
                $titles[$value["language_id"]] = $value;
            }
        }
        $this->data['titles'] = $titles;
        $this->data['languages'] = $this->NodCMS_general_admin_model->get_all_language();
        $this->data['title'] = _l("page",$this);
        $this->data['page'] = "page";
        $this->data['content']=$this->load->view($this->mainTemplate.'/page_edit',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function editpage_options($id='')
    {
        if($id!='')
        {
            $this->data['data']=$this->NodCMS_general_admin_model->get_page_detail($id);
            if($this->data['data']==null){
                $this->session->set_flashdata('error', _l('Your request not valid.',$this));
                redirect(base_url()."admin/page");
            }
            $this->load->library('spyc');
            $this->data['page_type'] = spyc_load_file(getcwd()."/page_type.yml") ;

            $this->data['title'] = _l("page",$this);
            $this->data['page'] = "page";
            $this->data['content']=$this->load->view($this->mainTemplate.'/page_options',$this->data,true);
            $this->load->view($this->mainTemplate,$this->data);
        }else{
            $this->session->set_flashdata('error', _l('Your request not valid.',$this));
            redirect(base_url()."admin/page");
        }
    }
    function page_manipulate($id=null)
    {
        if ($this->session->userdata['group']==1) {
            $this->load->library('spyc');
            $this->data['page_type'] = spyc_load_file(getcwd()."/page_type.yml");
            if(isset($_POST["data"]["page_type"])){ $_POST["data"]["page_dynamic"]=get_page_dynamic($_POST["data"]["page_type"],$this->data['page_type']); }
            if ($this->NodCMS_general_admin_model->page_manipulate($_POST["data"],$id))
            {
                $this->session->set_flashdata('success', _l('Updated page',$this));
            }
            else
            {
                $this->session->set_flashdata('error', _l('Updated page error. Please try later',$this));
            }
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/page/");
    }
    function deletepage($id=0)
    {
        if ($this->session->userdata['group']==1) {
            if($this->NodCMS_general_admin_model->count_gallery_image(array("data_type"=>"page","relation_id"=>$id))==0){
                $this->db->trans_start();
                $this->db->delete('gallery', array('relation_id' => $id,"data_type"=>"page"));
                $this->db->delete('extensions', array('relation_id' => $id,"data_type"=>"page"));
                $this->db->delete('page', array('page_id' => $id));
                $this->db->trans_complete();
                $this->session->set_flashdata('success', _l('Deleted page',$this));
            }else{
                $this->session->set_flashdata('error', _l('You should first delete galleries.',$this));
            }
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/page/");
    }

    function gallery($data_type=null,$relation_id=null){
        if($data_type!=null && $relation_id!=null && is_numeric($relation_id)){
            $accept_type = array("city","tours","page");
            if(!in_array($data_type,$accept_type)){
                $this->session->set_flashdata('error', _l('Your request is problem!',$this));
                redirect(base_url()."admin/gallery");
            }
            if($data_type=="page"){
                $data = $this->NodCMS_general_admin_model->get_page_detail($relation_id);
                $add_on_title = " - ".$data["page_name"];
            }else{
                $add_on_title = "";
            }
            $this->data['data_list']=$this->NodCMS_general_admin_model->get_gallery($data_type,$relation_id);
            $this->data['data_type'] = $data_type;
            $this->data['relation_id'] = $relation_id;
            $this->data['title'] = _l("gallery",$this).$add_on_title;
            $this->data['page'] = "gallery";
            $this->data['content']=$this->load->view($this->mainTemplate.'/gallery',$this->data,true);
            $this->load->view($this->mainTemplate,$this->data);
        }else{
            $this->session->set_flashdata('error', _l('Your request not valid.',$this));
            redirect(base_url()."admin/gallery");
        }
    }
    function editgallery($id='',$data_type=null,$relation_id=null)
    {
        if($id!=0)
        {
            $this->data['data']=$this->NodCMS_general_admin_model->get_gallery_detail($id);
            if($this->data['data']==null)
                redirect(base_url()."admin/gallery");
            if(isset($this->data['data']["data_type"]) && $this->data['data']["data_type"]!="") $this->data['data_type'] = $this->data['data']["data_type"];
            if(isset($this->data['data']["relation_id"]) && $this->data['data']["relation_id"]!=0) $this->data['relation_id'] = $this->data['data']["relation_id"];
        }else{
            if($data_type!=null) $this->data['data_type'] = $data_type;
            if($relation_id!=null) $this->data['relation_id'] = $relation_id;
        }
        $this->load->library('spyc');
        if($data_type=="page" && $relation_id!=null){
            $page = $this->NodCMS_general_admin_model->get_page_detail($relation_id);
            $options = spyc_load_file(getcwd()."/page_type.yml");
            if(isset($options[$page["page_type"]]["gallerys_fields"])){
                $this->data['fields'] = $options[$page["page_type"]]["gallerys_fields"];
            }
        }else{
            $this->data['fields'] = array("icon","image","description","full_description");
        }
        $icons = spyc_load_file(getcwd()."/icons.yml");
        $this->data['faicons'] = $icons["fa"];
        $this->data['languages'] = $this->NodCMS_general_admin_model->get_all_language();
        $this->data['title'] = _l("gallery",$this);
        $this->data['page'] = "gallery";
        $this->data['content']=$this->load->view($this->mainTemplate.'/gallery_edit',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function gallery_manipulate($id=null)
    {
        if ($this->session->userdata['group']==1) {
            if ($this->NodCMS_general_admin_model->gallery_manipulate($_POST["data"],$id))
            {
                $this->session->set_flashdata('success', _l('Updated gallery',$this));
            }
            else
            {
                $this->session->set_flashdata('error', _l('Updated gallery error. Please try later',$this));
            }
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/gallery/".(isset($_POST["data"]["data_type"])?$_POST["data"]["data_type"]."/":"").(isset($_POST["data"]["data_type"])?$_POST["data"]["relation_id"]."/":""));
    }

    function gallery_upload($gallery_id,$data_type=null,$relation_id=null){
        if($data_type!=null && $relation_id!=null && is_numeric($relation_id)){
            $accept_type = array("page");
            if(!in_array($data_type,$accept_type)){
                $this->session->set_flashdata('error', _l('Your request is problem!',$this));
                redirect(base_url()."admin/gallery");
            }
            $this->data['data_list']=$this->NodCMS_general_admin_model->get_gallery_image($gallery_id);
            $this->data['data_type'] = $data_type;
            $this->data['relation_id'] = $relation_id;
            $this->data['gallery_id'] = $gallery_id;
            $this->data['title'] = _l("gallery upload",$this);
            $this->data['page'] = "gallery_upload";
            $this->data['content']=$this->load->view($this->mainTemplate.'/gallery_upload',$this->data,true);
            $this->load->view($this->mainTemplate,$this->data);
        }else{
            redirect(base_url()."admin/");
        }
    }

    function deletegallery($id=0)
    {
        if ($this->session->userdata['group']==1) {
            $gallery = $this->NodCMS_general_admin_model->get_gallery_detail($id);
            if($this->NodCMS_general_admin_model->count_gallery_image(array("gallery_id"=>$id))==0){
                $this->db->trans_start();
                $this->db->delete('gallery', array('gallery_id' => $id));
                $this->db->trans_complete();
                $this->session->set_flashdata('success', _l('deleted gallery.',$this));
            }else{
                $this->session->set_flashdata('error', _l('You should first delete galleries.',$this));
            }
        }else{
            $this->session->set_flashdata('error', _l('This request is just fore real admin.',$this));
        }
        redirect(base_url()."admin/gallery/".$gallery["data_type"]."/".$gallery["relation_id"]);
    }
    function deletegallery_image($id=0)
    {
        if ($this->session->userdata['group']==1) {
            $gallery_image = $this->NodCMS_general_admin_model->get_gallery_image_detail($id);
            if($gallery_image!=0){
                if(file_exists($gallery_image["image"])){
                    unlink($gallery_image["image"]);
                }
                $this->db->trans_start();
                $this->db->delete('gallery_image', array('image_id' => $id));
                $this->db->trans_complete();
                echo json_encode(array("status"=>"success"));
            }else{
                echo json_encode(array("status"=>"error","errors"=>_l('Your request is problem!',$this)));
            }
        }else{
            echo json_encode(array("status"=>"error","errors"=>_l('Your request is problem!',$this)));
        }
    }

    function uploaded_images(){
        $this->data["data_list"] = $this->NodCMS_general_admin_model->get_all_images();
        echo $this->load->view($this->mainTemplate.'/uploaded_images',$this->data,true);
    }
    function uploaded_images_manager(){
        $this->data["data_list"] = $this->NodCMS_general_admin_model->get_all_images();
        $this->data['page'] = "uploaded_images";
        $this->data['title'] = _l("Management uploaded images",$this);
        $this->data['content']= $this->load->view($this->mainTemplate.'/uploaded_images_manager',$this->data,true);
        $this->load->view($this->mainTemplate,$this->data);
    }
    function deleteuploaded_image($id=0)
    {
        if ($this->session->userdata['group']==1) {
            $image = $this->NodCMS_general_admin_model->get_image_detail($id);
            if($image!=0){
                if(file_exists($image["image"])){
                    unlink($image["image"]);
                }
                $this->db->trans_start();
                $this->db->delete('images', array('image_id' => $id));
                $this->db->trans_complete();
                echo json_encode(array("status"=>"success"));
            }else{
                echo json_encode(array("status"=>"error","errors"=>_l('Your request is problem!',$this)));
            }
        }else{
            echo json_encode(array("status"=>"error","errors"=>_l('Your request is problem!',$this)));
        }
    }

    function upload_image($type=null,$data_type=null,$relation_id=null,$gallery_id=null){
        if ($this->session->userdata['group']==1) {
            if($type==null){
                echo json_encode(array("status"=>"error","errors"=>"empty"));
            }elseif($type==1){
                $folder = "logo/";
                $config['upload_path'] ='upload_file/'.$folder;
                $config['allowed_types'] = 'gif|jpg|png';
                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload("file"))
                {
                    echo json_encode(array("status"=>"error","errors"=>$this->upload->display_errors('<p>', '</p>')));
                }
                else
                {
                    $data = $this->upload->data();
                    $data_image = array(
                        "image"=>$config['upload_path'].$data["file_name"],
                        "width"=>$data["image_width"],
                        "height"=>$data["image_height"],
                        "name"=>$data["file_name"],
                        "root"=>$config["upload_path"],
                        "folder"=>$folder,
                        "size"=>$data["file_size"]
                    );
                    $getid = $this->NodCMS_general_admin_model->insert_image($data_image);
                    if($getid!=0){
                        echo json_encode(array("status"=>"success","file_patch"=>$config['upload_path'].$data["file_name"],"file_url"=>base_url().$config['upload_path'].$data["file_name"]));
                    }else{
                        unlink(getcwd()."/".$data_image["image"]);
                        echo json_encode(array("status"=>"error","errors"=>_l("Data Set error 1!",$this)));
                    }
                }
            }elseif($type==2){
                $folder = "lang/";
                $config['upload_path'] ='upload_file/'.$folder;
                $config['allowed_types'] = 'gif|jpg|png';

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload("file"))
                {
                    echo json_encode(array("status"=>"error","errors"=>$this->upload->display_errors('<p>', '</p>')));
                }
                else
                {
                    $data = $this->upload->data();
                    $data_image = array(
                        "image"=>$config['upload_path'].$data["file_name"],
                        "width"=>$data["image_width"],
                        "height"=>$data["image_height"],
                        "name"=>$data["file_name"],
                        "root"=>$config['upload_path'],
                        "folder"=>$folder,
                        "size"=>$data["file_size"]
                    );
                    $getid = $this->NodCMS_general_admin_model->insert_image($data_image);
                    if($getid!=0){
                        echo json_encode(array("status"=>"success","file_patch"=>$config['upload_path'].$data["file_name"],"file_url"=>base_url().$config['upload_path'].$data["file_name"]));
                    }else{
                        unlink(getcwd()."/".$data_image["image"]);
                        echo json_encode(array("status"=>"error","errors"=>_l("Data Set error 2!",$this)));
                    }
                }
            }elseif($type=="10" && $data_type!=null && $relation_id!=null && is_numeric($relation_id) && $gallery_id!=null && is_numeric($gallery_id)){
                $accept_type = array("city","tours","page");
                if(in_array($data_type,$accept_type)){
                    $folder = "images/";
                    $config['upload_path'] ='upload_file/'.$folder;
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['encrypt_name'] = true;

                    $this->load->library('upload', $config);

                    if ( ! $this->upload->do_upload("file"))
                    {
                        echo json_encode(array("status"=>"error","errors"=>$this->upload->display_errors('<p>', '</p>')));
                    }
                    else
                    {
                        $data = $this->upload->data();
                        $data_gallery = array(
                            "gallery_id"=>$gallery_id,
                            "relation_id"=>$relation_id,
                            "data_type"=>$data_type,
                            "image"=>$config['upload_path'].$data["file_name"],
                            "width"=>$data["image_width"],
                            "height"=>$data["image_height"],
                            "name"=>$data["file_name"],
                            "size"=>$data["file_size"]
                        );
                        $getid = $this->NodCMS_general_admin_model->get_insert_gallery_image($data_gallery);
                        if($getid!=0){
                            echo json_encode(array("status"=>"success","getid"=>$getid,"file_patch"=>$config['upload_path'].$data["file_name"],"file_url"=>base_url().$config['upload_path'].$data["file_name"]));
                        }else{
                            echo json_encode(array("status"=>"error","errors"=>_l("System problem!",$this)));
                        }
                    }
                }else{
                    echo json_encode(array("status"=>"error","errors"=>_l('Your request is problem!',$this)));
                }
            }elseif($type=="20"){
                $folder = "images20/";
                $config['upload_path'] ='upload_file/'.$folder;
                $config['allowed_types'] = 'gif|jpg|png';
                $config['encrypt_name'] = true;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload("file"))
                {
                    echo json_encode(array("status"=>"error","errors"=>$this->upload->display_errors('<p>', '</p>')));
                }
                else
                {
                    $data = $this->upload->data();
                    $data_image = array(
                        "image"=>$config['upload_path'].$data["file_name"],
                        "width"=>$data["image_width"],
                        "height"=>$data["image_height"],
                        "name"=>$data["file_name"],
                        "root"=>$config["upload_path"],
                        "folder"=>$folder,
                        "size"=>$data["file_size"]
                    );
                    $getid = $this->NodCMS_general_admin_model->insert_image($data_image);
                    if($getid!=0){
                        echo json_encode(array("status"=>"success","file_patch"=>$config['upload_path'].$data["file_name"],"file_url"=>base_url().$config['upload_path'].$data["file_name"]));
                    }else{
                        unlink(getcwd()."/".$data_image["image"]);
                        echo json_encode(array("status"=>"error","errors"=>_l("Data Set error 10!",$this)));
                    }
                }
            }else{
                echo json_encode(array("status"=>"error","errors"=>_l('Cannot find url!',$this)));
            }
        }else{
            echo json_encode(array("status"=>"error","errors"=>_l('This request just for real admin!',$this)));
        }
    }
}
