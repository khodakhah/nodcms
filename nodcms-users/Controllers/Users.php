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

namespace NodCMS\Users\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;
use Exception as ExceptionAlias;
use NodCMS\Core\Libraries\Form;

class Users extends \NodCMS\Core\Controllers\Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->view->setConfig(new \NodCMS\Users\Config\View());
    }

    /**
     * Register a new user
     *
     * @return RedirectResponse|false|string
     * @throws ExceptionAlias
     */
    public function userRegistration()
    {
        if ($this->settings['registration']!=1) {
            return redirect()->to("/{$this->lang}");
        }
        $this->data['title']=_l("User registration", $this);
        $config = array(
            array(
                'type'=>"h3",
                'label'=>$this->data['title']
            ),
            array(
                'field' => 'fname',
                'label' => _l("First name", $this),
                'rules' => 'required|formRulesName',
                'type' => "text",
                'default'=>"",
            ),
            array(
                'field' => 'lname',
                'label' => _l("Last name", $this),
                'rules' => 'required|formRulesName',
                'type' => "text",
                'default'=>"",
            ),
            array(
                'field' => 'phone',
                'label' => _l("Phone number", $this),
                'rules' => 'validPhone',
                'type' => "text",
                'default'=>""
            ),
            array(
                'field' => 'email',
                'label' => _l("Email", $this),
                'rules' => 'required|valid_email|emailUnique',
                'type' => "email",
                'help'=>_l("The activate link would be send to this email address.", $this),
                'default'=>""
            ),
            array(
                'field' => 'username',
                'label' => _l("Username", $this),
                'rules' => 'required|validateUsernameType|is_unique[users.username]',
                'type' => "text",
                'default'=>""
            ),
            array(
                'field' => 'password',
                'label' => _l("Password", $this),
                'rules' => 'required|formRulesPassword',
                'type' => "password",
                'default'=>""
            ),
        );
        $myform = new Form($this);
        $myform->config($config, base_url("{$this->lang}/user-registration"), 'post', 'ajax');
        $myform->setStyle("bootstrap-vertical");
        $myform->setFormTheme("form_only");
        if (isset($this->settings['google_captcha_active']) && $this->settings['google_captcha_active']==1) {
            $myform->setGoogleCaptcha();
        }

        $myform->data['title']=_l("Registration form", $this);
        $myform->data['submit_label']=_l("Register", $this);
        $myform->data['submit_class']="green-soft";
        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }
            $active_code = md5(substr(md5(time()), 4, 6));
            $unique_key = md5(time()+rand(100000, 999999));
            while (Services::model()->users()->getCount(['user_unique_key'=>$unique_key])!=0) {
                $unique_key = md5(time()+rand(100000, 999999));
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
            Services::model()->users()->add($user);
            $refurl = base_url("{$this->lang}/user-registration/active/{$unique_key}/{$active_code}");

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
            send_notification_email("registration_confirm", $data['email'], $email_data);
            // Make confirm message
            return $this->successMessage("Your registration was successful. We sent the confirmation link to your email.", base_url("{$this->lang}/user-registration/message"));
        }
        $this->data['the_form'] = $myform->fetch(null, array('data-message'=>1,'data-reset'=>1));
        return $this->viewRender('signup_form');
    }

    /**
     * Return password
     *
     * @return RedirectResponse|false|string
     * @throws ExceptionAlias
     */
    public function returnPassword()
    {
        $this->data['title'] = _l("Return Password", $this);
        $config = array(
            array(
                'type'=>"h3",
                'label'=>$this->data['title']
            ),
            array(
                'field'=>"email",
                'label'=>_l('Email Address', $this),
                'rules'=>"required|valid_email|existsEmail",
                'type'=>"email",
            )
        );
        $myform = new Form($this);
        $myform->config($config, base_url("{$this->lang}/return-password"), 'post', 'ajax');
        $myform->setStyle("bootstrap-vertical");
        $myform->setFormTheme("form_only");
        if (isset($this->settings['google_captcha_active']) && $this->settings['google_captcha_active']==1) {
            $myform->setGoogleCaptcha();
        }

        $myform->data['title']=_l("Registration form", $this);
        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }

            $email = $data['email'];

            $user = Services::model()->users()->getOne(null, ['email'=>$email]);
            if (empty($user)) {
                return $this->errorMessage("User not found!", base_url("{$this->lang}/return-password"));
            }

            $tries = 0;
            $active_code_expired = strtotime("+24h");
            if ($user['active_code_expired'] > strtotime("now")) {
                $active_code_expired = $user['active_code_expired'];
                $tries = $user['reset_password_tries'];
                if ($tries >= Services::settings()->get()['reset_password_tries_limit']) {
                    return $this->errorMessage("For security reason you are not able to request the a new password any more. " .
                        "Please try after 24 hours again.", base_url("{$this->lang}/return-password"));
                }
                $tries++;
            }

            $rand_str = md5(rand(1000, 9999) + time() + rand(1000, 9999));

            $update_data = [
                'active_code'=>$rand_str,
                'active_code_expired'=>$active_code_expired,
                'reset_password_tries'=>$tries
            ];
            Services::model()->users()->edit($user['user_id'], $update_data);

            $data = array_merge($user, array(
                'company'=> $this->settings["company"],
                'first_name'=> $user["firstname"],
                'last_name'=> $user["lastname"],
                'username'=> $user["username"],
                'email'=> $user["email"],
                'date'=>my_int_date(time()),
                'active_code_expired'=>$active_code_expired,
                'reference_url'=>base_url("/{$this->lang}/set-new-password/$user[user_unique_key]/$active_code_expired"),
            ));
            send_notification_email('reset_password', $email, $data, $this->language['language_id']);
            return $this->successMessage("We sent you an email. Please check your inbox and spam box.", "/{$this->lang}/login");
        }

        $this->data['the_form'] = $myform->fetch('login_form', array('data-reset'=>1,'data-message'=>1, 'data-redirect'=>1));
        return $this->viewRender('return_password_form');
    }

    /**
     * Set a new password for who forgot the password
     *
     * @param $user_unique_key
     * @param $active_code
     * @return RedirectResponse|false|string
     * @throws ExceptionAlias
     */
    public function setNewPassword($user_unique_key, $active_code)
    {
        $this->data['title']=_l("Set a new password", $this);
        $user = Services::model()->users()->getOneWithSecretKeys($user_unique_key, $active_code);
        $errorButtons = array(
            array('url'=>base_url("{$this->lang}"),'label'=>_l("Home", $this)),
            array('url'=>base_url("{$this->lang}/login"),'label'=>_l("Login", $this)),
            array('url'=>base_url("{$this->lang}/user-registration"),'label'=>_l("Register", $this)),
        );
        if (count($user)==0) {
            $header = _l("Not available", $this);
            $message = _l("This page is not available any more.", $this);

            return $this->showError($message, 1, $header, $errorButtons);
        }
        if ($user['active_code_expired'] <= time()) {
            $header = _l("Active link is expired", $this);
            $message = _l("Thi link is not active any more. Please request to reset your password again.", $this);
            return $this->showError($message, 1, $header, $errorButtons);
        }

        $config = array(
            array(
                'type'=>"h3",
                'label'=>$this->data['title']
            ),
            array(
                'field'=>"password",
                'label'=>_l('Password', $this),
                'rules'=>"required|formRulesPassword",
                'type'=>"password",
            ),
            array(
                'field'=>"re_password",
                'label'=>_l('Password Confirm', $this),
                'rules'=>"required|formRulesPassword|matches[password]",
                'type'=>"password",
            ),
        );
        $myform = new Form($this);
        $myform->config($config, base_url("{$this->lang}/set-new-password/$user_unique_key/$active_code"), 'post', 'ajax');
        $myform->setStyle("bootstrap-vertical");
        $myform->setFormTheme("form_only");
        if (isset($this->settings['google_captcha_active']) && $this->settings['google_captcha_active']==1) {
            $myform->setGoogleCaptcha();
        }

        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }

            $update_data = array(
                'active_code'=>"",
                'active_code_expired'=>0,
                'password'=>md5($data['password']),
            );
            Services::model()->users()->edit($user['user_id'], $update_data);
            return $this->successMessage("Your password has been reset successfully.", "{$this->lang}/login");
        }
        $this->data['the_form'] = $myform->fetch('login_form', array('data-reset'=>1,'data-message'=>1, 'data-redirect'=>1, 'data-replace'=>1));
        return $this->viewRender('return_password_form');
    }

    /**
     * Login form
     */
    public function login()
    {
        $this->data['title'] = _l("Sign in", $this);

        $config = array(
            array(
                'type'=>"h3",
                'label'=>$this->data['title']
            ),
            array(
                'field'=>"username",
                'label'=>_l('Username', $this),
                'rules'=>"required|validateUsernameType",
                'type'=>"text",
            ),
            array(
                'field'=>"password",
                'label'=>_l('Password', $this),
                'rules'=>"required|formRulesPassword",
                'type'=>"password",
            )
        );
        $myform = new Form($this);
        $myform->config($config, base_url($this->lang."/login"), 'post', 'ajax');
        $myform->setStyle("bootstrap-vertical");
        $myform->setFormTheme("form_only");
        if (isset($this->settings['google_captcha_active']) && $this->settings['google_captcha_active']==1) {
            $myform->setGoogleCaptcha();
        }

        $myform->data['submit_label'] = $this->data['title'];
        $myform->data['submit_class']="blue-steel";
        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }

            $username = $data['username'];
            $password = $data['password'];

            $user = Services::model()->users()->loginMatch($username, $password);
            if (empty($user)) {
                return $this->errorMessage("Password or username is incorrect.", "/{$this->lang}/login");
            }

            $login_data = array(
                'fullname'  => $user['fullname'],
                'username'  => $user['username'],
                'user_id' => $user['user_id'],
                'group'   => $user['group_id'],
                'avatar'   => $user['avatar'],
                'email'   => $user['email'],
                'logged_in_status'   => true,

            );
            Services::session()->set($login_data);
            $continue_url = $user['group_id']==1 ? ADMIN_URL : base_url();
            return $this->successMessage(null, $continue_url);
        }

        $this->data['the_form'] = $myform->fetch('login-form', array('data-redirect'=>1));
        return $this->viewRender('signin_form');
    }

    /**
     * Reservation messages page after any active
     *
     * @return RedirectResponse|string
     */
    public function userRegistrationMessage()
    {
        if (!Services::session()->getFlashdata('message')) {
            return redirect()->to("/{$this->lang}");
        }

        $message = Services::session()->getFlashdata('message');
        $this->data['title'] = _l('User Registration', $this);
        $this->data['message_title'] = $message['title'];
        $this->data['message'] = $message['body'];
        $this->data['message_class'] = $message['class'];
        return $this->viewRender('user_registration_message');
    }

    // Set new password for users after restoring request
    public function activeAccount($user_unique_key, $active_code)
    {
        $user = Services::model()->users()->getOneWithSecretKeys($user_unique_key, $active_code);
        if (!empty($user) && $user["reset_pass_exp"] > time() && $user["active_register"]==0) {
            Services::model()->users()->setActive($user['user_id']);
            $message = array(
                'title' => _l('User Activate', $this),
                'body' => _l('Your account has been successfully activated.', $this),
                'class' => 'note note-success'
            );
            Services::session()->setFlashdata('message', $message);
            return redirect()->to("/{$this->lang}/user-registration/message");
        }

        $buttons = array(
            array('url'=>base_url(),'label'=>_l("Home", $this)),
            array('url'=>base_url("/{$this->lang}/login"),'label'=>_l("Login", $this)),
            array('url'=>base_url("/{$this->lang}/user-registration"),'label'=>_l("Registration", $this)),
        );
        return $this->showError(null, 404, null, $buttons);
    }

    /**
     * Display account lock page
     *
     * @return string
     */
    public function accountLocked(): string
    {
        $this->data['title'] = _l("Account locked", $this);
        return $this->viewRender('account_lock');
    }

    /**
     * Log out user (remove all user sessions)
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Services::session()->destroy();
        return redirect()->to("/{$this->language['code']}");
    }
}
