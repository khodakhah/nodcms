<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 15-Apr-19
 * Time: 3:25 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class About_admin extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct("backend");
    }

    /**
     * List of all profiles
     */
    function profiles()
    {
        $this->data['title'] = _l("Profiles",$this);
        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title']),
        );
        $this->data['data_list']=$this->About_model->getAll();
        $this->data['page'] = "about_profiles";
        $this->data['content'] = $this->load->view($this->mainTemplate."/about_sort",$this->data,true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * profile edit/add form
     *
     * @param null|int $id
     */
    function profileForm($id = null)
    {
        $this->data['title'] = _l("Profile",$this);
        $back_url = ABOUT_ADMIN_URL."profiles";
        if($id!=null){
            $current_data = $this->About_model->getOne($id);
            if($current_data==null || count($current_data)==0){
                $this->systemError("The profile couldn't find.",$back_url);
                return;
            }
            $this->data['sub_title'] = _l("Edit",$this);
            $form_attr = array();
        }else{
            $this->data['sub_title'] = _l("Add",$this);
            $form_attr = array('data-redirect'=>1);
        }

        $themes = array(
            array('theme'=>"default", 'name'=>_l("Default", $this)),
            array('theme'=>"half_image", 'name'=>_l("Half image", $this)),
            array('theme'=>"big_image", 'name'=>_l("Big image", $this)),
            array('theme'=>"bg_image", 'name'=>_l("Background image", $this)),
            array('theme'=>"card", 'name'=>_l("Card", $this)),
        );
        $config = array(
            array(
                'field' => 'profile_uri',
                'label' => _l("Page URI", $this),
                'rules' => 'callback_validURI|callback_isUnique[about_profiles,profile_uri'.(isset($current_data)?",profile_id,$current_data[profile_id]":"").']',
                'type' => "text",
                'default'=>isset($current_data)?$current_data["profile_uri"]:'',
                'input_prefix'=>base_url().$this->language['code']."/about-",
                'help'=>_l("The unique text that will be in URI to open this profile.", $this),
            ),
            array(
                'field' => 'profile_name',
                'label' => _l("Name", $this),
                'rules' => 'required',
                'type' => "text",
                'default'=>isset($current_data)?$current_data["profile_name"]:''
            ),
            array(
                'field' => 'profile_image',
                'label' => _l("Image", $this),
                'rules' => '',
                'type' => "image-library",
                'default'=>isset($current_data)?$current_data["profile_image"]:''
            ),
            array(
                'field' => 'profile_theme',
                'label' => _l("Display Theme", $this),
                'rules' => 'required|in_list['.join(',', array_column($themes, 'theme')).']',
                'type' => "select",
                'options'=>$themes,
                'option_name'=>"name",
                'option_value'=>"theme",
                'default'=>isset($current_data)?$current_data["profile_theme"]:''
            ),
            array(
                'field' => 'public',
                'label' => _l("Public", $this),
                'rules' => 'in_list[0,1]',
                'type' => "switch",
                'default'=>isset($current_data)?$current_data["public"]:''
            ),
        );
        $languages = $this->Languages_model->getAll();
        foreach($languages as $language){
            $translate = $this->About_model->getTranslations($id, $language['language_id']);
            // Add language title
            array_push($config,array(
                'prefix_language'=>$language,
                'label'=>$language['language_title'],
                'type'=>"h4",
            ));
            $prefix = "translate[$language[language_id]]";
            array_push($config, array(
                'field'=>$prefix."[name]",
                'label'=>_l("Name", $this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($translate['name'])?$translate['name']:'',
            ));
            array_push($config, array(
                'field'=>$prefix."[name_title]",
                'label'=>_l("Title", $this),
                'rules'=>"",
                'type'=>"text",
                'default'=>isset($translate['name_title'])?$translate['name_title']:'',
            ));
            array_push($config, array(
                'field'=>$prefix."[preview_description]",
                'label'=>_l("Description on home", $this),
                'rules'=>"",
                'type'=>"texteditor",
                'default'=>isset($translate['preview_description'])?$translate['preview_description']:'',
            ));
            array_push($config, array(
                'field'=>$prefix."[description]",
                'label'=>_l("Page description",$this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($translate['description'])?$translate['description']:'',
            ));
            array_push($config, array(
                'field'=>$prefix."[keywords]",
                'label'=>_l("Keywords", $this),
                'rules'=>"",
                'type'=>"textarea",
                'default'=>isset($translate['keywords'])?$translate['keywords']:'',
            ));
            array_push($config, array(
                'field'=>$prefix."[content]",
                'label'=>_l("Page Content",$this),
                'rules'=>"",
                'type'=>"texteditor",
                'default'=>isset($translate['content'])?$translate['content']:'',
            ));
        }

        $myform = new Form();
        $myform->config($config, ABOUT_ADMIN_URL."profileForm/$id", 'post', 'ajax');

        if($myform->ispost()){
            if(!$this->checkAccessGroup(1)){
                return;
            }
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return;
            }

            if(key_exists('translate',$post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if(!$this->checkAccessGroup(1))
                return;
            if($id!=null){
                $this->About_model->edit($id, $post_data);
                if(isset($translates)){
                    $this->About_model->updateTranslations($id,$translates,$languages);
                }
                $this->systemSuccess("Profile has been edited successfully.", $back_url);
            }
            else{
                $new_id = $this->About_model->add($post_data);
                if(isset($translates)){
                    $this->About_model->updateTranslations($new_id,$translates,$languages);
                }
                $this->systemSuccess("Profile has been sent successfully.", $back_url);
            }
            return;
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Profiles", $this),'url'=>$back_url),
            array('title'=>$this->data['sub_title']),
        );

        $this->data['page'] = "profile_form";
        $this->data['content']=$myform->fetch("", $form_attr);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Remove an profile
     *
     * @param $id
     * @param int $confirm
     */
    function profileRemove($id, $confirm = 0)
    {
        if(!$this->checkAccessGroup(1))
            return;

        $back_url = ABOUT_ADMIN_URL."profiles";
        $self_url = ABOUT_ADMIN_URL."profileRemove/$id";
        $data = $this->About_model->getOne($id);
        if(count($data)==0){
            $this->systemError("The profile couldn't find.", $back_url);
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center">'._l("This action will delete the profile from database.", $this).
                    '<br>'._l("After this, you will not to able to restore it.", $this).'</p>'.
                    '<p class="text-center font-lg bold">'._l("Are you sure to delete this?", $this).'</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, delete it.", $this),
                'confirmUrl'=>"$self_url/1",
                'redirect'=>1,
            ));
            return;
        }

        $this->About_model->remove($id);
        $this->systemSuccess("The profile has been deleted successfully.", $back_url);
    }

    /**
     * @param $id
     */
    function profileVisibility($id)
    {
        if(!$this->checkAccessGroup(1)){
            return;
        }
        $back_url = ABOUT_ADMIN_URL."profiles";
        $data= $this->About_model->getOne($id);
        if($data == null || count($data)==0){
            $this->systemError("Couldn't find the profile.", $back_url);
            return;
        }
        $public = $this->input->post('data');
        if($public == 1){
            $public = 0;
        }elseif($public == 0){
            $public = 1;
        }else{
            $this->systemError("Visibility value isn't correct. Please reload the page to solve this problem.", $back_url);
            return;
        }
        $update_data = array(
            'public'=>$public
        );
        $this->About_model->edit($id, $update_data);
        $this->systemSuccess("Success", $back_url);
    }

    /**
     * Handle sort action
     */
    function profileSort()
    {
        if(!$this->checkAccessGroup(1)){
            return;
        }
        $i = 0;
        $data = json_decode($this->input->post('data',TRUE));
        foreach($data as $key=>$item){
            $i++;
            $update_data = array(
                'order'=>$i,
            );
            $this->About_model->edit($item->id, $update_data);
        }
        $this->systemSuccess("Your profiles has been successfully sorted.", ABOUT_ADMIN_URL."profiles");
    }
}