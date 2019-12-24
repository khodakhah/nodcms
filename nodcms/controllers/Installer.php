<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 15-Dec-19
 * Time: 12:26 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Installer extends CI_Controller
{
    use NodCMSUtilities;
    use NodCMSValidators;

    public $product_name = "NodCMS";
    public $product_version = "2.0";
    private $required_php_version = '7.0.0';
    private $required_extensions = array('mysqli');
    public $php_version_valid;
    public $extensions_valid;
    public $back_url;
    public $next_url;
    public $self_url;

    public function __construct()
    {
        parent::__construct();
        $this->mainTemplate = 'installer';
        $this->frameTemplate = 'installer/layout';

        $this->language = array(
            'code' => "en",
            'language_title' => "English",
            'language_name' => "english",
            'rtl' => "0",
        );
        $this->data['page'] = '';
        $this->data['title'] = "{$this->product_name} Installer";
        $this->settings = $this->config->item('settings_default');

        $this->php_version_valid = version_compare(PHP_VERSION, $this->required_php_version, '>=');
        $this->extensions_valid = true;
        foreach($this->required_extensions as $item) {
            if(!extension_loaded($item)) {
                $this->extensions_valid = false;
                break;
            }
        }

        $steps = array(
            'start',
            'eula',
            'authorization',
            'database',
            'settings',
            'complete',
        );

        $step_key = array_search($this->router->method, $steps);

        if($step_key > 0 && $step_key < count($steps)) {
            $this->back_url = base_url()."installer/".$steps[$step_key-1];
            $this->self_url = base_url()."installer/".$steps[$step_key];
            $this->next_url = base_url().(isset($steps[$step_key+1])?"installer/".$steps[$step_key+1]:"login");
        }

        $this->data['steps'] = array();
        foreach($steps as $key=>$val) {
            if($key<$step_key) {
                $url = base_url()."installer/".$steps[$key];
                $this->data['steps'][] = "<div class='col bg-green-jungle'>" .
                    "<a class='text-center d-block pt-3 pb-3 font-white' href='$url'>".($key+1).". ".strtoupper($val)."</a>" .
                    "</div>";
                continue;
            }
            if($key == $step_key) {
                $this->data['steps'][] = "<div class='col bg-grey'>" .
                    "<span class='text-center d-block pt-3 pb-3'>".($key+1).". ".strtoupper($val)."</span>" .
                    "</div>";
                continue;
            }
            $this->data['steps'][] = "<div class='col bg-grey-cararra'>" .
                "<span class='text-center d-block pt-3 pb-3 font-grey-mint'>".($key+1).". ".strtoupper($val)."</span>" .
                "</div>";
        }
    }

    /**
     * First step, overview
     */
    public function start()
    {
        $this->data['sub_title'] = "Welcome";
        $requirements_error = array();
        if(!$this->php_version_valid) {
            $requirements_error[] = "Unfortunately, your hosting's <strong>PHP version ".PHP_VERSION."</strong> is not compatible with {$this->product_name} v{$this->product_version} required <strong>PHP version {$this->required_php_version}</strong>.";
        }
        if(!$this->required_extensions) {
            foreach ($this->required_extensions as $item) {
                if(!extension_loaded($item)) {
                    $requirements_error[] = "Unfortunately, extension <strong>$item</strong> is not installed, activated, or loaded. It's required to run {$this->product_name} v{$this->product_version}.";
                }
            }
        }
        $this->data['requirements_error'] = $requirements_error;
        $this->data['content'] = $this->load->view($this->mainTemplate."/overview", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    /**
     * End User License Agreement page
     */
    public function eula()
    {
        if(!$this->hasAccess())
            return;
        $this->data['sub_title'] = "EULA";
        $config = array(
            array(
                'field'=>"eula",
                'type'=>"textarea",
                'rules'=>"required",
                'label'=>"End User License Agreement",
                'default'=> include APPPATH."views/installer/eula.php",
                'attr'=>array('readonly'=>"readonly", 'rows'=>10),
            ),
        );
        $myform = new Form();
        $myform->config($config, $this->self_url, 'post', 'ajax');
        $myform->setFormTheme("form_only");

        $myform->data['submit_label'] = "Accept EULA and continue <i class=\"far fa-arrow-alt-circle-right\"></i>";
        $myform->data['submit_class']="btn-success";
        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }

            $this->successMessage(null, $this->next_url);
            return;
        }
        $this->data['content'] = "<p>Please read the following license agreement before go to the next step.</p>";
        $this->data['content'] .= $myform->fetch('login-form', array('data-redirect'=>1,));
        $this->load->view($this->frameTemplate, $this->data);
    }

    /**
     * Authorization page
     */
    public function authorization()
    {
        if(!$this->hasAccess())
            return;
        $this->data['title'] = "{$this->product_name} Installer";
        $this->data['sub_title'] = "Authorization";

        $config = array(
            array(
                'field'=>"host",
                'label'=>"Host Name",
                'rules'=>"required|callback_validHostName",
                'type'=>"text",
                'default'=>"localhost",
            ),
            array(
                'field'=>"username",
                'label'=>"Username",
                'rules'=>"required|callback_validateUsernameType",
                'type'=>"text",
                'default'=>"root",
            ),
            array(
                'field'=>"password",
                'label'=>"Password",
                'rules'=>"callback_formRulesPassword",
                'type'=>"password",
            ),
            array(
                'field'=>"database",
                'label'=>"Database name",
                'rules'=>"required|callback_validDatabaseName",
                'type'=>"text",
            ),
        );
        $myform = new Form();
        $myform->config($config, $this->self_url, 'post', 'ajax');
        $myform->setFormTheme("form_only");

        $myform->data['submit_label'] = "Let's go";
        $myform->data['submit_class']="btn-success";
        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }

            if (!$this->hasDatabase($data)) {
                return;
            }
            $_SESSION['database_connect'] = $data;


            $this->successMessage("Your entered data was correct.", $this->next_url);
            return;
        }
        $this->data['the_form'] = $myform->fetch('login-form', array('data-redirect'=>1));
        $this->data['content'] = $this->load->view($this->mainTemplate."/authorization", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    /**
     * Choose a database
     */
    public function database()
    {
        if(!$this->hasAccess() || !$this->hasDatabase())
            return;

        $tables = $this->getTables();

        $this->data['title'] = "{$this->product_name} Installer";
        $this->data['sub_title'] = "Database installation";
        $this->data['tables'] = $tables;

        $paths = array_column($tables[0], 'path');
        if(isset($tables[1]) && !empty($tables[1])) {
            foreach($tables[1] as $path) {
                $paths = array_merge($paths, array_column($path, 'path'));
            }
        }

        $config = array(
            array(
                'field'=>"path",
                'label'=>"Path",
                'rules'=>"required|in_list[".join(',', $paths)."]",
                'errors'=>array(
                    'in_list' => "Path not listed!",
                ),
                'type'=>"hidden",
            ),
        );

        $myform = new Form();
        $myform->config($config, $this->self_url, 'post', 'ajax');
        $myform->setFormTheme("form_only");

        $myform->data['submit_label'] = "Create database";
        $myform->data['submit_class']="btn-success";
        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }

            include_once BASEPATH."core/Model.php";
            include_once APPPATH."core/NodCMS_Model.php";
            include_once $data['path'];
            $model_name = basename($data['path'],".php");
            $theModel = new $model_name();

            if($theModel->tableExists()) {
                $theModel->dropTable();
            }
            if(!$theModel->installTable()) {
                $this->errorMessage("Table couldn't install.", $this->self_url);
                return;
            }

            // Insert first default data
            if(method_exists($theModel, 'defaultData')) {
                $theModel->defaultData();
            }

            $this->successMessage("Database has been created successfully.", $this->next_url);
            return;
        }

        $this->data['content'] = $this->load->view($this->mainTemplate."/database", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    /**
     * Prepare settings
     */
    public function settings()
    {
        if(!$this->hasAccess() || !$this->hasDatabase())
            return;

        $this->data['title'] = "{$this->product_name} Installer";
        $this->data['sub_title'] = "Prepare settings";

        $date_formats = array(
            array("format"=>"d.m.Y", "name"=>"dd.mm.yy"),
            array("format"=>"m/d/Y", "name"=>"mm/dd/yy"),
            array("format"=>"Y-m-d", "name"=>"yy-mm-dd"),
        );
        $time_formats = array(
            array("format"=>"H:i", "name"=>"24"),
            array("format"=>"h:i A", "name"=>"12"),
        );

        $config = array(
            array(
                'field'=>"firstname",
                'label'=>'First Name',
                'rules'=>"required|callback_formRulesName",
                'type'=>"text",
            ),
            array(
                'field'=>"lastname",
                'label'=>'Last Name',
                'rules'=>"required|callback_formRulesName",
                'type'=>"text",
            ),
            array(
                'field'=>"company",
                'label'=>'Company Name',
                'rules'=>"required|callback_formRulesName",
                'type'=>"text",
            ),
            array(
                'field'=>"email",
                'label'=>"Email",
                'rules'=>"required|valid_email",
                'type'=>"text",
            ),
            array(
                'field'=>"password",
                'label'=>"Password",
                'rules'=>"required|callback_formRulesPassword",
                'type'=>"password",
            ),
            array(
                'field'=>"timezone",
                'label'=>'Timezone',
                'rules'=>"required|in_list[".join(',', DateTimeZone::listIdentifiers())."]",
                'type'=>"select-array",
                'options'=>DateTimeZone::listIdentifiers(),
                'class'=>"select2me",
            ),
            array(
                'field'=>"date_format",
                'label'=>'Date Format',
                'rules'=>"required|in_list[".join(',', array_column($date_formats, 'format'))."]",
                'type'=>"select",
                'options'=>$date_formats,
                'option_name'=>'name',
                'option_value'=>'format',
            ),
            array(
                'field'=>"time_format",
                'label'=>"Time Format",
                'rules'=>"required|in_list[".join(',', array_column($time_formats, 'format'))."]",
                'type'=>"select",
                'default'=>$this->settings['time_format'],
                'options'=>$time_formats,
                'option_name'=>'name',
                'option_value'=>'format',
            ),
        );
        $myform = new Form();
        $myform->config($config, $this->self_url, 'post', 'ajax');
        $myform->setFormTheme("form_only");

        $myform->data['submit_label'] = "Create database";
        $myform->data['submit_class']="btn-success";
        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }

            $db = $_SESSION['database_connect'];
            $dsn = "mysqli://{$db['username']}:{$db['password']}@{$db['host']}/{$db['database']}";
            $this->load->database($dsn);
            $this->load->model('Settings_model');
            $this->load->model('Users_model');

            $update_data = $data;
            unset($update_data['password']);
            $update_data['sent_email'] = $data['email'];
            $this->Settings_model->updateSettings($update_data);

            $user = $this->Users_model->getOne(1);
            if(!is_array($user) || count($user) == 0) {
                $_data = array(
                    "firstname"=>$data['firstname'],
                    "lastname"=>$data['lastname'],
                    "email"=>$data['email'],
                    "username"=>"admin",
                    "password"=>$data['password'],
                    "group_id"=>1,
                    "active"=>1,
                    "status"=>1
                );
                $this->Users_model->add($_data);
            }
            else {
                $_data = array(
                    "firstname"=>$data['firstname'],
                    "lastname"=>$data['lastname'],
                    "email"=>$data['email'],
                    "password"=>$data['password'],
                );
                $this->Users_model->edit($user['user_id'], $_data);
            }
            $this->successMessage("Settings has been saved successfully.", $this->next_url);
            return;
        }
        $this->data['content'] = $myform->fetch('',array('data-redirect'=>1));
        $this->load->view($this->frameTemplate, $this->data);
    }

    /**
     * Last step. Create database config file
     */
    public function complete()
    {
        if(!$this->hasAccess() || !$this->hasDatabase())
            return;

        $db = $_SESSION['database_connect'];
        $dsn = "mysqli://{$db['username']}:{$db['password']}@{$db['host']}/{$db['database']}";
        $this->load->database($dsn);
        $this->load->model('Settings_model');
        $this->load->model('Users_model');

        $this->data['sub_title'] = "Complete";
        $admin_user = $this->Users_model->getOne(1);
        $prepare_settings = $this->Settings_model->getCount() > 0;
        $config = array(
            array(
                'type'=>"h4",
                'label'=>"Requires to create config file",
            ),
            array(
                'field'=>"data",
                'type'=>"hidden",
                'rules'=>"",
                'default'=>"1",
            ),
            array(
                'field'=>"Database",
                'label'=>'Database',
                'type'=>"static",
                'value'=>'<i class="fa fa-check text-success"></i> '.$_SESSION['database_connect']['database'],
            ),
            array(
                'field'=>"prepare_settings",
                'label'=>'Prepare settings',
                'type'=>"static",
                'value'=>'<i class="fa '.($prepare_settings ? "fa-check text-success" : "fa-times text-danger").'"></i>',
            ),
            array(
                'field'=>"admin_user",
                'label'=>'Administrator created',
                'type'=>"static",
                'value'=>empty($admin_user) ? "<i class=\"fa fa-times text-danger\"></i>" : "<i class='fa fa-check text-success'></i>",
            ),
        );
        if(!empty($admin_user)) {
            $config = array_merge($config, array(
                array(
                    'type'=>"h4",
                    'label'=>"Administrator account data",
                ),
                array(
                    'field'=>"username",
                    'label'=>'Username',
                    'type'=>"static",
                    'value'=>$admin_user['username'],
                    'class'=>"font-weight-bold",
                ),
                array(
                    'field'=>"password",
                    'label'=>'Password',
                    'type'=>"static",
                    'value'=>"<span class='text-grey'>******</span>",
                    'help'=>"This is the password that you entered in the last step \"Settings\"",
                    'class'=>"font-weight-bold",
                ),
            ));
        }
        $myform = new Form();
        $myform->config($config, $this->self_url, 'post', 'ajax');
        $myform->setFormTheme("form_only");

        $myform->data['submit_label'] = "Confirm & Done";
        $myform->data['submit_class']="btn-success";

        if($myform->ispost()){
            $data = $myform->getPost();
            // Stop Page
            if($data === false){
                return;
            }

            if(!$prepare_settings) {
                $this->errorMessage("Prepared settings is not set.", $this->back_url);
                return;
            }

            if(!is_array($admin_user) || count($admin_user) == 0) {
                $this->errorMessage("Administration account not found.", $this->back_url);
                return;
            }

            $myfile = fopen(DB_CONFIG_PATH, "w");
            if(!$myfile) {
                $errors = error_get_last();
                if(count($errors) > 1) {
                    $this->errorMessage($errors['message'], $this->self_url);
                    return;
                }
                $this->errorMessage("Unable to open db config file.", $this->self_url);
                return;
            }

            $sample_content = file_get_contents(APPPATH."config/database_manual.php");
            if(!$sample_content) {
                $this->errorMessage("Unable to open database_manual.php file.", $this->self_url);
                return;
            }

            $replace = array();
            foreach($_SESSION['database_connect'] as $key=>$item) {
                $replace["@$key@"] = $item;
            }
            $content = str_replace(array_keys($replace), array_values($replace), $sample_content);
            fwrite($myfile, $content);
            fclose($myfile);

            session_destroy();

            $this->successMessage("Installation has been done.", $this->next_url);
            return;
        }

        $this->data['the_form'] = $myform->fetch('',array('data-redirect'=>1));
        $this->data['content'] = $this->load->view($this->mainTemplate."/complete", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    /**
     * @return array
     */
    private function getTables()
    {
        $db = $_SESSION['database_connect'];
        $dsn = "mysqli://{$db['username']}:{$db['password']}@{$db['host']}/{$db['database']}";
        $this->load->database($dsn);

        include_once BASEPATH."core/Model.php";
        include_once APPPATH."core/NodCMS_Model.php";

        // Base models
        $models_paths = get_all_php_files(APPPATH . 'models'.DIRECTORY_SEPARATOR);
        $base_tables = array();
        foreach($models_paths as $model_path) {
            include_once $model_path;
            $model_name = basename($model_path,".php");
            $theModel = new $model_name();
            if(method_exists($theModel, 'tableName')) {
                $base_tables[] = array(
                    'path' => $model_path,
                    'table' => $theModel->tableName(),
                    'exists' => $theModel->tableExists(),
                );
            }
        }

        // Models from "third_party"s
        $tables = array();
        $dirs = glob(APPPATH."third_party/*", GLOB_BRACE);
        foreach ($dirs as $dir) {
            $models_paths = get_all_php_files($dir . DIRECTORY_SEPARATOR . 'models'.DIRECTORY_SEPARATOR);
            if(!$models_paths) {
                continue;
            }

            foreach($models_paths as $model_path) {
                include_once $model_path;
                $model_name = basename($model_path,".php");
                $theModel = new $model_name();
                if(!method_exists($theModel, 'tableName')) {
                    continue;
                }
                $base_path = basename($dir);
                if(!key_exists($base_path, $tables)) {
                    $tables[$base_path] = array();
                }

                $tables[$base_path][] = array(
                    'path' => $model_path,
                    'table' => $theModel->tableName(),
                    'exists' => $theModel->tableExists(),
                );
            }

        }

        return array($base_tables, $tables);
    }

    /**
     * Check the required services has been reached
     *
     * @return bool
     */
    private function hasAccess()
    {
        if(!$this->required_extensions || !$this->required_php_version) {
            $this->errorMessage("Required services not found.", $this->back_url);
            return false;
        }
        return true;
    }

    /**
     * Check database connection and database exists
     *
     * @param null|array $data
     * @return bool
     */
    public function hasDatabase($data = null)
    {
        if($data == null) {
            if(!isset($_SESSION['database_connect'])) {
                $this->errorMessage("Database connection data not found.", $this->back_url);
                return false;
            }
            $data = $_SESSION['database_connect'];
        }
        if(!isset($data['database'])) {
            $this->errorMessage("Database not found.", $this->back_url);
            return false;
        }

        $mysqli =@ new mysqli($data['host'], $data['username'], $data['password']);
        $mysqli->select_db($data['database']);

        if(!empty($mysqli->error_list)) {
            $this->errorMessage("MySQL error: ".join(',', array_column($mysqli->error_list, 'error')), $this->self_url);
            return false;
        }

        return true;
    }
}