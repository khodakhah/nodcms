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
class General_members extends NodCMS_Controller{
    public $avatar_file_key = "avatar-user-";
    function __construct(){
        parent::__construct("membership");
        $this->avatar_file_key .= $this->userdata['user_id'];
        $this->data['title'] = _l("Account", $this);
        $this->display_page_title = false;
    }

    /**
     * General members dashboard
     *  - will read all exists members dashboard and display in this page.
     *  URL: /user/dashboard
     */
    function dashboard()
    {
        if(!$this->userdata['has_dashboard']) {
            $this->showError();
            return;
        }
        $packages = $this->load->packageList();
        $this->data['dashboards'] = array();
        foreach ($packages as $item){
            if(!file_exists(APPPATH."controllers/".$item."_members.php")){
                continue;
            }
            $item = strtolower($item);
            array_push($this->data['dashboards'], base_url()."user-$item/dashboard");
        }

        $this->data['title'] = _l("Dashboard", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Dashboard", $this))
        );
        $this->data['keyword'] = "";
        $this->data['page'] = "dashboard";
        $this->data['content'] = $this->load->view($this->mainTemplate."/dashboards",$this->data, true);
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Change the account details
     *  URL: /user/account
     *
     */
    function account()
    {
        $user = $this->userdata;
        $user['language'] = $this->Public_model->getLanguage($user['language_id']);
        $this->data['data'] = $user;
        $this->data['tabs'] = array(
            array('label'=>_l("Personal Info", $this), 'url'=>base_url()."user/account/personal-info", 'active'=>"active"),
            array('label'=>_l("Change Avatar", $this), 'url'=>base_url()."user/account/change-avatar", 'active'=>""),
            array('label'=>_l("Change Password", $this), 'url'=>base_url()."user/account/change-password", 'active'=>""),
        );
        $this->data['content']=$this->load->view($this->mainTemplate.'/account',$this->data,true);
        $this->data['page'] = "account";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Change personal info page
     *  URL: user/account/personal-info
     */
    function accountPersonalInfo()
    {
        $self_url = base_url()."user/account/personal-info";
        $myform = new Form();
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
                'field'=>"language_id",
                'label'=>_l('Language',$this),
                'rules'=>"required|in_list[".join(',',array_column($languages,"language_id"))."]",
                'type'=>"select",
                'options'=>$languages,
                'option_name'=>"language_title",
                'option_value'=>"language_id",
                'default'=>$this->userdata['language_id'],
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
        );
        $myform->config($config, $self_url, 'post', 'ajax');
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

            $this->load->model("Nodcms_admin_model");
            if (!$this->Nodcms_admin_model->userManipulate($data,$this->session->userdata['user_id'])){
                $this->systemError("There was a problem to update the user data.", $self_url);
                return;
            }
            $row = $this->Nodcms_admin_model->getUserDetail($this->session->userdata['user_id']);
            $this->session->set_userdata('firstname', $row['firstname']);
            $this->session->set_userdata('lastname', $row['lastname']);
            $this->session->set_userdata('fullname', $row['fullname']);
            $this->session->set_userdata('username', $row['username']);
            $this->session->set_userdata('email', $row['email']);
            $this->userdata = $row;

            $this->systemSuccess("Your profile has been updated successfully.", $self_url);
            return;
        }

        if($this->input->is_ajax_request()){
            echo $myform->fetch();
            return;
        }
        $this->data['data'] = $this->userdata;
        $this->data['content'] = $myform->fetch();
        $this->data['page'] = "account";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Change the password
     *  URL: user/account/change-password
     */
    function accountChangePassword()
    {
        $self_url = base_url()."user/account/change-password";
        $myform = new Form();
        $languages = $this->Public_model->getAllLanguages();
        $config = array(
            array(
                'field'=>"password",
                'label'=>_l('Current Password',$this),
                'rules'=>"required",
                'type'=>"password",
            ),
            array(
                'field'=>"new_password",
                'label'=>_l('New Password',$this),
                'rules'=>"required|matches[confirm_password]",
                'type'=>"password",
            ),
            array(
                'field'=>"confirm_password",
                'label'=>_l('Confirm new Password',$this),
                'rules'=>"required",
                'type'=>"password",
            ),
        );
        $myform->config($config, $self_url, 'post', 'ajax');
        // * Submit form
        if($myform->ispost()){
            if($this->userdata['group_id']==100){
                $this->systemError("As demo account you aren't able to change any thing.", $self_url);
                return;
            }
            $post_data = $myform->getPost();
            // Stop Page
            if(!is_array($post_data) || count($post_data)==0 || $post_data == null){
                return;
            }

            if(md5($post_data['password']) != $this->userdata["password"]){
                $this->systemError("Your current password is wrong.", $self_url);
                return;
            }

            $data = array('password'=>$post_data['new_password'],);

            $this->load->model("Nodcms_admin_model");
            $this->Nodcms_admin_model->userManipulate($data,$this->session->userdata['user_id']);

            $this->systemSuccess("Your password has been updated successfully.", $self_url);
            return;
        }

        if($this->input->is_ajax_request()){
            echo $myform->fetch('', array('data-reset'=>1));
            return;
        }
        $this->data['data'] = $this->userdata;
        $this->data['content'] = $myform->fetch('', array('data-reset'=>1));
        $this->data['page'] = "account";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Change user avatar page
     *  URL: user/account/change-avatar
     */
    function accountChangeAvatar()
    {
        $self_url = base_url()."user/account/change-avatar";
        $myform = new Form();
        $uploaded_files = $myform->getLastUploadedFiles($this->avatar_file_key);
        $config = array(
            array(
                'field'=>"avatar",
                'label'=>_l('New Avatar',$this),
                'help'=>_l('Best size: 200x200px',$this),
                'rules'=>"required|in_list[".(join(',', array_column($uploaded_files, "file_id")))."]",
                'type'=>"files",
                //'max_file_size'=>2,//MB
                'max_files'=>1,
                'upload_url'=>base_url()."user/account-avatar-upload",
                'upload_key'=>$this->avatar_file_key,
                'accept_types'=>'jpg-png-gif',
                'errors'=>array(
                    'in_list'=>_l("There is an unavailable file.", $this)
                ),
                'default'=>"from-cookies",
            ),
            array(
                'type'=>"div",
                'label'=>'<button type="button" class="btn red-soft btn-ask" onclick="$.loadConfirmModal(\''.base_url().'user/account/remove-avatar\')">'
                    ._l("Remove my Avatar", $this).'</button>',
                'class'=>"margin-bottom-20"
            ),
        );
        $myform->config($config, $self_url, 'post', 'ajax');
        $myform->setStyle("bootstrap-vertical");
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

            $avatar = $this->Public_model->getFile($post_data['avatar']);
            // Remove current avatar file
            $data = array('avatar'=>$avatar['file_thumbnail'],);

            $this->load->model("Nodcms_admin_model");
            $this->userAvatarRemove();
            if (!$this->Nodcms_admin_model->userManipulate($data,$this->userdata['user_id'])){
                $this->systemError("There was a problem to update the user data.", $self_url);
                return;
            }
            $this->Public_model->setFileUsing($post_data['avatar']);

            $row = $this->Nodcms_admin_model->getUserDetail($this->userdata['user_id']);

            $this->userdata = $row;
            $avatar_url = get_user_avatar_url($row);

            $data = array(
                'success'=>"$('.user-avatar-img').attr('src','$avatar_url')"
            );
            $this->systemSuccess("Your profile has been updated successfully.", $self_url, $data);
            return;
        }

        if($this->input->is_ajax_request()){
            echo $myform->fetch('', array('data-reset'=>1));
            return;
        }
        $this->data['data'] = $this->userdata;
        $this->data['content'] = $myform->fetch();
        $this->data['title'] = _l("Account Settings",$this);
        $this->data['page'] = "account";
        $this->load->view($this->frameTemplate,$this->data);
    }

    /**
     * Delete the user avatar confirmation message and action
     *  URL: user/account/remove-avatar
     *
     * @param int $confirm
     */
    function accountRemoveAvatar($confirm = 0)
    {
        if(!$this->checkAccessGroup(1,20))
            return;

        if($this->userdata['avatar']==""){
            $this->systemError("There isn't set any Avatar image for you.", base_url()."user/account/change-avatar");
            return;
        }

        if($confirm!=1){
            echo json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center font-lg bold">'._l("Are you sure to delete your Avatar Image?", $this).'</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, remove it.", $this),
                'confirmUrl'=>base_url()."user/account/remove-avatar-confirmed",
            ));
            return;
        }

        $data = array('avatar'=>"",);
        $this->load->model("Nodcms_admin_model");
        $this->userAvatarRemove();
        $this->Nodcms_admin_model->userManipulate($data,$this->userdata['user_id']);

        $row = $this->Nodcms_admin_model->getUserDetail($this->userdata['user_id']);
        $success_data = array(
            'success'=>'$(".user-avatar-img").attr("src", "'.get_user_avatar_url($row).'"); myModal.modal("hide");',
        );
        $this->systemSuccess("Your Avatar has been deleted successfully.", base_url()."user/account/change-avatar", $success_data);
    }

    /**
     * Delete the user avatar file
     */
    private function userAvatarRemove()
    {
        $user = $this->Public_model->getUserDetails($this->userdata['user_id']);
        if($user['avatar']=='')
            return;
        $file = $this->Public_model->getFileDetails(array('file_thumbnail'=>$user['avatar']));
        if(!is_array($file) || count($file)==0)
            return;
        $theForm = new Form();
        $theForm->removeFiles($file['file_id']);
    }

    /**
     * Upload the user avatar image
     */
    function accountAvatarUpload()
    {
        $myform = new Form();
        $uploaded_files = $myform->getLastUploadedFiles($this->avatar_file_key);
        if(count($uploaded_files)>1){
            $this->systemError("You can't upload file any more.", base_url());
            return;
        }
        $file_types = "jpg|png|gif";
        // To keep storage clean
        $myform->removeUselessFiles(strtotime("last week"));
        echo $myform->uploadFile("upload_file/users/user-".$this->userdata['user_id']."/".date("Y-m"), $this->avatar_file_key, $file_types, true,1);
    }


}
