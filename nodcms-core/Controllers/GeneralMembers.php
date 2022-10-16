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

namespace NodCMS\Core\Controllers;

use Config\Services;
use Config\Models;
use NodCMS\Core\Libraries\Form;

class GeneralMembers extends Membership
{
    public $avatar_file_key = "avatar-user-";
    public function __construct()
    {
        parent::__construct();
        $this->avatar_file_key .= $this->userdata['user_id'];
        $this->data['title'] = _l("Account", $this);
        $this->display_page_title = false;
    }

    /**
     * General members dashboard
     *  - will read all exists members dashboard and display in this page.
     *  URL: /user/dashboard
     */
    public function dashboard()
    {
        if (!Services::modules()->hasMemberDashboard()) {
            return $this->showError();
        }

        $this->data['dashboards'] = Services::modules()->getMemberDashboards();

        $this->data['title'] = _l("Dashboard", $this);
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Dashboard", $this))
        );
        $this->data['keyword'] = "";
        $this->data['page'] = "dashboard";
        return $this->viewRender("dashboards");
    }

    /**
     * Change the account details
     *  URL: /user/account
     *
     */
    public function account()
    {
        $user = $this->userdata;
        $user['language'] = Models::languages()->getOne($user['language_id']);
        $this->data['data'] = $user;
        $this->data['tabs'] = array(
            array('label'=>_l("Personal Info", $this), 'url'=>base_url("user/account/personal-info"), 'active'=>"active"),
            array('label'=>_l("Change Avatar", $this), 'url'=>base_url("user/account/change-avatar"), 'active'=>""),
            array('label'=>_l("Change Password", $this), 'url'=>base_url("user/account/change-password"), 'active'=>""),
        );
        $this->data['page'] = "account";
        return $this->viewRender("account");
    }

    /**
     * Change personal info page
     *  URL: user/account/personal-info
     */
    public function accountPersonalInfo()
    {
        $self_url = base_url("user/account/personal-info");
        $myform = new Form($this);
        $languages = Models::languages()->getAllActives();
        $config = array(
            array(
                'field'=>"username",
                'label'=>_l('Username', $this),
                'rules'=>"required|is_unique[users.username,user_id,".$this->userdata['user_id']."]",
//                'type'=>"text",
//                'default'=>$this->userdata['username'],
                'type'=>"static",
                'value'=>$this->userdata['username'],
            ),
            array(
                'field'=>"email",
                'label'=>_l('Email Address', $this),
                'rules'=>"required|valid_email|is_unique[users.email,user_id,".$this->userdata['user_id']."]",
//                'type'=>"text",
//                'default'=>$this->userdata['email'],
                'type'=>"static",
                'value'=>$this->userdata['email'],
            ),
            array(
                'field'=>"language_id",
                'label'=>_l('Language', $this),
                'rules'=>"required|in_list[".join(',', array_column($languages, "language_id"))."]",
                'type'=>"select",
                'options'=>$languages,
                'option_name'=>"language_title",
                'option_value'=>"language_id",
                'default'=>$this->userdata['language_id'],
            ),
            array(
                'field'=>"firstname",
                'label'=>_l('First Name', $this),
                'rules'=>"required|formRulesName",
                'type'=>"text",
                'default'=>$this->userdata['firstname'],
            ),
            array(
                'field'=>"lastname",
                'label'=>_l('Last Name', $this),
                'rules'=>"required|formRulesName",
                'type'=>"text",
                'default'=>$this->userdata['lastname'],
            ),
            array(
                'field'=>"mobile",
                'label'=>_l('Mobile', $this),
                'rules'=>"validPhone",
                'type'=>"text",
                'default'=>$this->userdata['mobile'],
            ),
            array(
                'field'=>"website",
                'label'=>_l('Website', $this),
                'rules'=>"valid_url",
                'type'=>"url",
                'default'=>$this->userdata['website'],
            ),
        );
        $myform->config($config, $self_url, 'post', 'ajax');
        // * Submit form
        if ($myform->ispost()) {
            if ($this->userdata['group_id']==100) {
                return $this->errorMessage("As demo account you aren't able to change any thing.", base_url());
            }
            $post_data = $myform->getPost();
            // Stop Page
            if ($post_data === false) {
                return $myform->getResponse();
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

            Models::users()->edit($this->userdata['user_id'], $data);
            $row = Models::users()->getOne($this->userdata['user_id']);
            Services::session()->set('firstname', $row['firstname']);
            Services::session()->set('lastname', $row['lastname']);
            Services::session()->set('fullname', $row['fullname']);
            Services::session()->set('username', $row['username']);
            Services::session()->set('email', $row['email']);
            $this->userdata = $row;

            return $this->successMessage("Your profile has been updated successfully.", $self_url);
        }

        if (Services::request()->isAJAX()) {
            return $myform->fetch();
        }
        $this->data['data'] = $this->userdata;
        $this->data['page'] = "account";
        return $this->viewRenderString($myform->fetch());
    }

    /**
     * Change the password
     *  URL: user/account/change-password
     */
    public function accountChangePassword()
    {
        $self_url = base_url("user/account/change-password");
        $myform = new Form($this);
        $config = array(
            array(
                'field'=>"password",
                'label'=>_l('Current Password', $this),
                'rules'=>"required",
                'type'=>"password",
            ),
            array(
                'field'=>"new_password",
                'label'=>_l('New Password', $this),
                'rules'=>"required|matches[confirm_password]",
                'type'=>"password",
            ),
            array(
                'field'=>"confirm_password",
                'label'=>_l('Confirm new Password', $this),
                'rules'=>"required",
                'type'=>"password",
            ),
        );
        $myform->config($config, $self_url, 'post', 'ajax');
        // * Submit form
        if ($myform->ispost()) {
            if ($this->userdata['group_id']==100) {
                return $this->errorMessage("As demo account you aren't able to change any thing.", $self_url);
            }
            $post_data = $myform->getPost();
            // Stop Page
            if ($post_data === false) {
                return $myform->getResponse();
            }

            if (md5($post_data['password']) != $this->userdata["password"]) {
                return $this->errorMessage("Your current password is wrong.", $self_url);
            }

            $data = array('password'=>$post_data['new_password'],);

            Models::users()->edit($this->userdata['user_id'], $data);

            return $this->successMessage("Your password has been updated successfully.", $self_url);
        }

        if (Services::request()->isAJAX()) {
            return $myform->fetch('', array('data-reset'=>1));
        }
        $this->data['data'] = $this->userdata;
        $this->data['page'] = "account";
        return $this->viewRenderString($myform->fetch('', array('data-reset'=>1)));
    }

    /**
     * Change user avatar page
     *  URL: user/account/change-avatar
     */
    public function accountChangeAvatar()
    {
        $self_url = base_url("user/account/change-avatar");
        $myform = new Form($this);
        $uploaded_files = $myform->getLastUploadedFiles($this->avatar_file_key);
        $config = [
            [
                'field'=>"avatar",
                'label'=>_l('New Avatar', $this),
                'help'=>_l('Best size: 200x200px', $this),
                'rules'=>"required|in_list[".(join(',', array_column($uploaded_files, "file_id")))."]",
                'type'=>"files",
                //'max_file_size'=>2,//MB
                'max_files'=>1,
                'upload_url'=>base_url("user/account-avatar-upload"),
                'upload_key'=>$this->avatar_file_key,
                'accept_types'=>'jpg-png-gif',
                'errors'=>array(
                    'in_list'=>_l("There is an unavailable file.", $this)
                ),
                'default'=>"from-cookies",
            ]
        ];

        if ($this->userdata['avatar'] != null) {
            $config[] = [
                'type'=>"div",
                'label'=>'<button type="button" class="btn red-soft btn-ask" onclick="$.loadConfirmModal(\''.base_url("user/account/remove-avatar").'\')">'
                    ._l("Remove my Avatar", $this).'</button>',
                'class'=>"margin-bottom-20"
            ];
        }
        $myform->config($config, $self_url, 'post', 'ajax');
        $myform->setStyle("bootstrap-vertical");
        // * Submit form
        if ($myform->ispost()) {
            if ($this->userdata['group_id']==100) {
                return $this->errorMessage("As demo account you aren't able to change any thing.", base_url());
            }
            $post_data = $myform->getPost();
            // Stop Page
            if ($post_data === false) {
                return $myform->getResponse();
            }

            $avatar = Models::uploadFiles()->getOne($post_data['avatar']);
            // Remove current avatar file
            $data = array('avatar'=>$avatar['file_thumbnail'],);

            $this->userAvatarRemove();
            Models::users()->edit($this->userdata['user_id'], $data);
            Models::uploadFiles()->updateFileUsing($post_data['avatar']);

            $row = Models::users()->getOne($this->userdata['user_id']);

            $this->userdata = $row;
            $avatar_url = get_user_avatar_url($row);

            $data = array(
                'success'=>"$('.user-avatar-img').attr('src','$avatar_url')"
            );
            return $this->successMessage("Your profile has been updated successfully.", $self_url, $data);
        }

        if (Services::request()->isAJAX()) {
            return $myform->fetch('', array('data-reset'=>1));
        }
        $this->data['data'] = $this->userdata;
        $this->data['title'] = _l("Account Settings", $this);
        $this->data['page'] = "account";
        return $this->viewRenderString($myform->fetch());
    }

    /**
     * Delete the user avatar confirmation message and action
     *  URL: user/account/remove-avatar
     *
     * @param int $confirm
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     * @throws \Exception
     */
    public function accountRemoveAvatar(int $confirm = 0)
    {
        if (!Services::identity()->isValidMember()) {
            return Services::identity()->getResponse();
        }

        if (Services::identity()->isValid()) {
            if ($this->userdata['avatar']=="") {
                return $this->errorMessage("There isn't set any Avatar image for you.", base_url("user/account/change-avatar"));
            }
        }

        if ($confirm!=1) {
            return json_encode(array(
                'status'=>'success',
                'content'=>'<p class="text-center font-lg bold">'._l("Are you sure to delete your Avatar Image?", $this).'</p>',
                'title'=>_l("Delete confirmation", $this),
                'noBtnLabel'=>_l("Cancel", $this),
                'yesBtnLabel'=>_l("Yes, remove it.", $this),
                'confirmUrl'=>base_url("user/account/remove-avatar-confirmed"),
            ));
        }

        $data = array('avatar'=>"",);
        $this->userAvatarRemove();
        Models::users()->edit($this->userdata['user_id'], $data);

        $row = Models::users()->getOne($this->userdata['user_id']);
        $success_data = array(
            'success'=>'$(".user-avatar-img").attr("src", "'.get_user_avatar_url($row).'"); myModal.modal("hide");',
        );
        return $this->successMessage("Your Avatar has been deleted successfully.", base_url("user/account/change-avatar"), $success_data);
    }

    /**
     * Delete the user avatar file
     */
    private function userAvatarRemove()
    {
        $user = Models::users()->getOne($this->userdata['user_id']);
        if ($user['avatar']=='') {
            return;
        }
        $file = Models::uploadFiles()->getOne(null, ['file_thumbnail'=>$user['avatar']]);
        if (!is_array($file) || count($file)==0) {
            return;
        }
        $theForm = new Form($this);
        $theForm->removeFiles($file['file_id']);
    }

    /**
     * Upload the user avatar image
     * @throws \Exception
     */
    public function accountAvatarUpload()
    {
        $myform = new Form($this);
        $uploaded_files = $myform->getLastUploadedFiles($this->avatar_file_key);
        if (count($uploaded_files)>1) {
            return $this->errorMessage("You can't upload file any more.", base_url());
        }
        // To keep storage clean
        $myform->removeUselessFiles(strtotime("last week"));
        return $myform->uploadFile("upload_file/users/user-".$this->userdata['user_id']."/".date("Y-m"), $this->avatar_file_key, 'image', true, 1);
    }


    /**
     * Change the account details
     *
     */
    public function accountSetting()
    {
        if ($this->userdata==null) {
            return $this->showError();
        }
        $languages = $this->model->languages()->getAll();
        $config = array(
            array(
                'field'=>"username",
                'label'=>_l('Username', $this),
                'rules'=>"required|is_unique[users.username,user_id,".$this->userdata['user_id']."]",
//                'type'=>"text",
//                'default'=>$this->userdata['username'],
                'type'=>"static",
                'value'=>$this->userdata['username'],
            ),
            array(
                'field'=>"email",
                'label'=>_l('Email Address', $this),
                'rules'=>"required|valid_email|is_unique[users.email,user_id,".$this->userdata['user_id']."]",
//                'type'=>"text",
//                'default'=>$this->userdata['email'],
                'type'=>"static",
                'value'=>$this->userdata['email'],
            ),
            array(
                'field'=>"firstname",
                'label'=>_l('First Name', $this),
                'rules'=>"required|formRulesName",
                'type'=>"text",
                'default'=>$this->userdata['firstname'],
            ),
            array(
                'field'=>"lastname",
                'label'=>_l('Last Name', $this),
                'rules'=>"required|formRulesName",
                'type'=>"text",
                'default'=>$this->userdata['lastname'],
            ),
            array(
                'field'=>"mobile",
                'label'=>_l('Mobile', $this),
                'rules'=>"validPhone",
                'type'=>"text",
                'default'=>$this->userdata['mobile'],
            ),
            array(
                'field'=>"website",
                'label'=>_l('Website', $this),
                'rules'=>"valid_url",
                'type'=>"url",
                'default'=>$this->userdata['website'],
            ),
            array(
                'field'=>"password",
                'label'=>_l('Password', $this),
                'rules'=>"formRulesPassword",
                'type'=>"password",
            ),
            array(
                'field'=>"language_id",
                'label'=>_l('Language', $this),
                'rules'=>"required|in_list[".join(',', array_column($languages, "language_id"))."]",
                'type'=>"select",
                'options'=>$languages,
                'option_name'=>"language_title",
                'option_value'=>"language_id",
                'default'=>$this->userdata['language_id'],
            ),
        );
        $myform = new Form($this);
        $myform->config($config, base_url("{$this->language['code']}/account-setting/"), 'post', 'ajax');
        // * Submit form
        if ($myform->ispost()) {
            if ($this->userdata['group_id']==100) {
                return $this->errorMessage("As demo account you aren't able to change any thing.", base_url());
            }
            $post_data = $myform->getPost();
            // Stop Page
            if ($post_data === false) {
                return $myform->getResponse();
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
            if ($post_data['password']!='') {
                $data['password'] = md5($post_data['password']);
            }
            Models::users()->edit($this->userdata['user_id'], $data);

            $row = Models::users()->getOne($this->userdata['user_id']);
            Services::session()->set('firstname', $row['firstname']);
            Services::session()->set('lastname', $row['lastname']);
            Services::session()->set('fullname', $row['fullname']);
            Services::session()->set('username', $row['username']);
            Services::session()->set('email', $row['email']);

            return $this->successMessage("Your profile has been updated successfully.", base_url("{$this->language['code']}/account-setting"));
        }
        // * Upload an avatar
        $this->data['data'] = $this->userdata;
        $this->data['submit_form'] = $myform->fetch();
        $this->data['title'] = _l("Account setting", $this);
        $this->data['page'] = "account";
        return $this->viewRender("membership/account_setting");
    }

    /**
     * Upload and remove the user avatar
     * TODO: Change this!
     */
    public function accountAvatarChange()
    {
        if ($this->userdata==null) {
            return $this->showError();
        }
        $back_url = base_url("{$this->language['code']}/account-setting");
        if (Services::identity()->isDemo()) {
            return $this->errorMessage("As demo account you aren't able to change any thing.", $back_url);
        }
        if (Services::request()->getFile("file")->isValid()) {
            $dir = "upload_file/users/user-".$this->userdata["user_id"];

            $upload = Services::upload()->filterTypes("images")->setBackUrl($back_url);
            if (!$upload->save("file", $dir)) {
                return $upload->getErrorResponse();
            }

            $setData = array(
                "avatar"=>$upload->getResult()->path,
            );
            Models::users()->edit($this->userdata['user_id'], $setData);
            if (isset($this->userdata["avatar"])&&$this->userdata["avatar"]!=null&&$this->userdata["avatar"]!=""&&file_exists(SELF_PATH.$this->userdata["avatar"])) {
                unlink($dir."/".$this->userdata["avatar"]);
            }
            return $this->successMessage("Your avatar has updated successfully.", base_url("{$this->language['code']}/account-setting"));
        }

        $setData = array(
            "avatar"=>"",
        );
        Models::users()->edit($this->userdata['user_id'], $setData);
        if (isset($this->userdata["avatar"])&&$this->userdata["avatar"]!=null&&$this->userdata["avatar"]!=""&&file_exists(SELF_PATH.$this->userdata["avatar"])) {
            unlink(SELF_PATH.$this->userdata["avatar"]);
        }

        return $this->successMessage("Your avatar has removed successfully.!", base_url("{$this->language['code']}/account-setting"));
    }
}
