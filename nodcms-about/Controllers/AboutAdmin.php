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

/**
 * Created by Mojtaba Khodakhah.
 * Date: 15-Apr-19
 * Time: 3:25 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

namespace NodCMS\About\Controllers;

use NodCMS\About\Config\Models;
use NodCMS\Core\Controllers\Backend;
use Config\Services;
use NodCMS\About\Config\ViewBackend;
use NodCMS\Core\Libraries\Form;

class AboutAdmin extends Backend
{
    function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new ViewBackend());
    }

    /**
     * List of all profiles
     *
     * @return string
     */
    function profiles()
    {
        $this->data['title'] = _l("Profiles",$this);
        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title']),
        );
        $this->data['data_list']=Models::about()->getAll(null, null, 1, ['order', 'asc']);
        $this->data['page'] = "about_profiles";
        return $this->viewRender("about_sort");
    }

    /**
     * profile edit/add form
     *
     * @param null|int $id
     */
    function profileForm($id = 0)
    {
        $this->data['title'] = _l("Profile",$this);
        $back_url = ABOUT_ADMIN_URL."profiles";
        if(!empty($id)){
            $current_data = Models::about()->getOne($id);
            if($current_data==null || count($current_data)==0){
                return $this->errorMessage("The profile couldn't find.",$back_url);
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
                'rules' => 'validURI|is_unique[about_profiles.profile_uri'.(isset($current_data)?",profile_id,$current_data[profile_id]":"").']',
                'type' => "text",
                'default'=>isset($current_data)?$current_data["profile_uri"]:'',
                'input_prefix'=>base_url($this->language['code']."/about-"),
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
        $languages = Models::languages()->getAll();
        foreach($languages as $language){
            if(!empty($id))
                $translate = Models::about()->getTranslations($id, $language['language_id']);
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

        $myform = new Form($this);
        $myform->config($config, ABOUT_ADMIN_URL."profileForm/$id", 'post', 'ajax');

        if($myform->ispost()){
            if(!Services::identity()->isAdmin()){
                return Services::identity()->getResponse();
            }
            $post_data = $myform->getPost();
            // Stop Page
            if($post_data === false){
                return $myform->getResponse();
            }

            if(key_exists('translate',$post_data)){
                $translates = $post_data['translate'];
                unset($post_data['translate']);
            }

            if(!empty($id)){
                Models::about()->edit($id, $post_data);
                if(isset($translates)){
                    Models::about()->updateTranslations($id,$translates,$languages);
                }
//                log_message('alert', "EDIT".json_encode($post_data));
//                log_message('alert', "\$_POST".json_encode($_POST));
                return $this->successMessage("Profile has been edited successfully.", $back_url);
            }
            else{
                log_message('alert', json_encode($post_data));
                $new_id = Models::about()->add($post_data);
                if(isset($translates)){
                    Models::about()->updateTranslations($new_id,$translates,$languages);
                }
                return $this->successMessage("Profile has been sent successfully.", $back_url);
            }
        }

        $this->data['breadcrumb'] = array(
            array('title'=>_l("Profiles", $this),'url'=>$back_url),
            array('title'=>$this->data['sub_title']),
        );

        $this->data['page'] = "profile_form";
        return $this->viewRenderString($myform->fetch("", $form_attr));
    }

    /**
     * Remove an profile
     *
     * @param $id
     * @param int $confirm
     */
    function profileRemove($id, $confirm = 0)
    {
        if(!Services::identity()->isAdmin())
            return Services::identity()->getResponse();

        $back_url = ABOUT_ADMIN_URL."profiles";
        $self_url = ABOUT_ADMIN_URL."profileRemove/$id";
        $data = Models::about()->getOne($id);
        if(count($data)==0){
            return $this->errorMessage("The profile couldn't find.", $back_url);
        }

        if($confirm!=1){
            return json_encode(array(
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
        }

        Models::about()->remove($id);
        return $this->successMessage("The profile has been deleted successfully.", $back_url);
    }

    /**
     * @param $id
     */
    function profileVisibility($id)
    {
        if(!Services::identity()->isAdmin(true)){
            return Services::identity()->getResponse();
        }
        $back_url = ABOUT_ADMIN_URL."profiles";
        $data= Models::about()->getOne($id);
        if($data == null || count($data)==0){
            return $this->errorMessage("Couldn't find the profile.", $back_url);
        }
        $public = Services::request()->getPost('data');
        if($public == 1){
            $public = 0;
        }elseif($public == 0){
            $public = 1;
        }else{
            return $this->errorMessage("Visibility value isn't correct. Please reload the page to solve this problem.", $back_url);
        }
        $update_data = array(
            'public'=>$public
        );
        Models::about()->edit($id, $update_data);
        return $this->successMessage("Success", $back_url);
    }

    /**
     * Handle sort action
     */
    function profileSort()
    {
        if(!Services::identity()->isAdmin()){
            return Services::identity()->getResponse();
        }
        $i = 0;
        // TODO: input validate
        $data = json_decode(Services::request()->getPost('data'));
        if(empty($data))
            return $this->errorMessage("There is no data to save.", ABOUT_ADMIN_URL."profiles");

        foreach($data as $key=>$item){
            $i++;
            $update_data = array(
                'order'=>$i,
            );
            Models::about()->edit($item->id, $update_data);
        }
        return $this->successMessage("Your profiles has been successfully sorted.", ABOUT_ADMIN_URL."profiles");
    }
}
