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

namespace NodCMS\Installer\Controllers;

use Config\Autoload;
use Config\Database;
use Config\Services;
use Exception;
use NodCMS\Core\Controllers\Base;
use NodCMS\Core\Libraries\DatabaseEnvConfig;
use NodCMS\Core\Libraries\Form;
use NodCMS\Core\Models\Settings;
use NodCMS\Core\Models\Users;
use NodCMS\Installer\Config\View;

class Installer extends Base
{
    public $product_name = "NodCMS";
    public $product_version = "3.4.0";
    private $required_php_version = '7.4.0';
    private $required_extensions = array('mysqli');
    public $php_version_valid;
    public $extensions_valid;
    public $back_url;
    public $next_url;
    public $self_url;

    private $_response;

    /**
     * @var \CodeIgniter\Database\BaseConnection
     */
    private $db;

    public function __construct()
    {
        parent::__construct();

        $this->view->setConfig(new View());

        $this->language = array(
            'code' => "en",
            'language_title' => "English",
            'language_name' => "english",
            'rtl' => "0",
        );
        $this->data['page'] = '';
        $this->data['title'] = "{$this->product_name} Installer";

        $this->php_version_valid = version_compare(PHP_VERSION, $this->required_php_version, '>=');
        $this->extensions_valid = true;
        foreach ($this->required_extensions as $item) {
            if (!extension_loaded($item)) {
                $this->extensions_valid = false;
                break;
            }
        }

        $steps = array(
            'start',
            'license',
            'authorization',
            'database',
            'settings',
            'complete',
        );

        $step_key = array_search($this->router->methodName(), $steps);

        if ($step_key > 0 && $step_key < count($steps)) {
            $this->back_url = base_url("installer/".$steps[$step_key-1]);
            $this->self_url = base_url("installer/".$steps[$step_key]);
            $this->next_url = base_url((isset($steps[$step_key+1]) ? "installer/".$steps[$step_key+1] : "en/login"));
        }

        $this->data['steps'] = array();
        foreach ($steps as $key=>$val) {
            if ($key<$step_key) {
                $url = base_url("installer/".$steps[$key]);
                $this->data['steps'][] = "<div class='col bg-green-jungle'>" .
                    "<a class='text-center d-block pt-3 pb-3 font-white' href='$url'>".($key+1).". ".strtoupper($val)."</a>" .
                    "</div>";
                continue;
            }
            if ($key == $step_key) {
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
        if (!$this->php_version_valid) {
            $requirements_error[] = "Unfortunately, your hosting's <strong>PHP version ".PHP_VERSION."</strong> is not compatible with {$this->product_name} v{$this->product_version} required <strong>PHP version {$this->required_php_version}</strong>.";
        }
        if (!$this->required_extensions) {
            foreach ($this->required_extensions as $item) {
                if (!extension_loaded($item)) {
                    $requirements_error[] = "Unfortunately, extension <strong>$item</strong> is not installed, activated, or loaded. It's required to run {$this->product_name} v{$this->product_version}.";
                }
            }
        }
        $this->data['requirements_error'] = $requirements_error;
        echo $this->viewRender("overview");
    }

    /**
     * End User License Agreement page
     */
    public function license()
    {
        if (!$this->hasAccess()) {
            return $this->_response;
        }
        $this->data['sub_title'] = "License Agreement";
        $config = array(
            array(
                'field'=>"license",
                'type'=>"textarea",
                'rules'=>"required",
                'label'=>"License Agreement",
                'default'=> include ROOTPATH."nodcms-installer/Views/license.php",
                'attr'=>array('readonly'=>"readonly", 'rows'=>10),
            ),
        );
        $myform = new Form($this);
        $myform->config($config, $this->self_url, 'post', 'ajax');
        $myform->setFormTheme("form_only");

        $myform->data['submit_label'] = "Accept and continue <i class=\"far fa-arrow-alt-circle-right\"></i>";
        $myform->data['submit_class']="btn-success";
        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }

            return $this->successMessage(null, $this->next_url);
        }
        $content = "<p>Please read the following license agreement before go to the next step.</p>";
        $content .= $myform->fetch('login-form', array('data-redirect'=>1,));
        return $this->viewRenderString($content);
    }

    /**
     * Authorization page
     */
    public function authorization()
    {
        if (!$this->hasAccess()) {
            return $this->_response;
        }
        $this->data['title'] = "{$this->product_name} Installer";
        $this->data['sub_title'] = "Authorization";

        $config = array(
            array(
                'field'=>"host",
                'label'=>"Host Name",
                'rules'=>"required|validHostName",
                'type'=>"text",
                'default'=>"localhost",
            ),
            array(
                'field'=>"username",
                'label'=>"Username",
                'rules'=>"required|validateUsernameType",
                'type'=>"text",
                'default'=>"root",
            ),
            array(
                'field'=>"password",
                'label'=>"Password",
                'rules'=>"formRulesPassword",
                'type'=>"password",
            ),
            array(
                'field'=>"database",
                'label'=>"Database name",
                'rules'=>"required|validDatabaseName",
                'type'=>"text",
            ),
        );
        $myform = new Form($this);
        $myform->config($config, $this->self_url, 'post', 'ajax');
        $myform->setFormTheme("form_only");

        $myform->data['submit_label'] = "Let's go";
        $myform->data['submit_class']="btn-success";
        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }

            if (!$this->hasDatabase($data)) {
                return $this->_response;
            }
            $_SESSION['database_connect'] = $data;


            return $this->successMessage("Your entered data was correct.", $this->next_url);
        }
        $this->data['the_form'] = $myform->fetch('login-form', array('data-redirect'=>1));
        return $this->viewRender("authorization");
    }

    /**
     * Choose a database
     */
    public function database()
    {
        if (!$this->hasAccess() || !$this->hasDatabase()) {
            return $this->_response;
        }

        $coreTables = $this->getTables(get_all_php_files(COREPATH . 'Models'.DIRECTORY_SEPARATOR));
        $modulesTables = array();

        $modulesDirs = Autoload::modulesPaths();
        foreach ($modulesDirs as $dir) {
            if (!$moduleModels = get_all_php_files($dir . DIRECTORY_SEPARATOR . 'models'.DIRECTORY_SEPARATOR)) {
                continue;
            }

            $tables = $this->getTables($moduleModels);
            if (!empty($tables)) {
                $modulesTables[basename($dir)] = $tables;
            }
        }

        $this->data['title'] = "{$this->product_name} Installer";
        $this->data['sub_title'] = "Database installation";
        $this->data['tables'] = array($coreTables, $modulesTables);

        $paths = array_column($coreTables, 'path');
        if (isset($modulesTables) && !empty($modulesTables)) {
            foreach ($modulesTables as $path) {
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

        $myform = new Form($this);
        $myform->config($config, $this->self_url, 'post', 'ajax');
        $myform->setFormTheme("form_only");

        $myform->data['submit_label'] = "Create database";
        $myform->data['submit_class']="btn-success";
        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }

            $model_name = basename($data['path'], ".php");
            if (in_array($model_name, ["Model", "CoreModel"])) {
                return $this->errorMessage("Wrong table name!", $this->self_url);
            }

            $model_name = "\NodCMS\Core\Models\\".$model_name;
            $theModel = new $model_name($this->db);

            if ($theModel->tableExists()) {
                $theModel->dropTable();
            }
            if (!$theModel->installTable()) {
                return $this->errorMessage("Table couldn't install.", $this->self_url);
            }

            // Insert first default data
            if (method_exists($theModel, 'defaultData')) {
                $theModel->defaultData();
            }

            return $this->successMessage("Database has been created successfully.", $this->next_url);
        }

        return $this->viewRender("database");
    }

    /**
     * Prepare settings
     */
    public function settings()
    {
        if (!$this->hasAccess() || !$this->hasDatabase()) {
            return $this->_response;
        }

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
                'rules'=>"required|formRulesName",
                'type'=>"text",
            ),
            array(
                'field'=>"lastname",
                'label'=>'Last Name',
                'rules'=>"required|formRulesName",
                'type'=>"text",
            ),
            array(
                'field'=>"company",
                'label'=>'Company Name',
                'rules'=>"required|formRulesName",
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
                'rules'=>"required|formRulesPassword",
                'type'=>"password",
            ),
            array(
                'field'=>"timezone",
                'label'=>'Timezone',
                'rules'=>"required|in_list[".join(',', \DateTimeZone::listIdentifiers())."]",
                'type'=>"select-array",
                'options'=>\DateTimeZone::listIdentifiers(),
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
                'class' => 'select2me'
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
        $myform = new Form($this);
        $myform->config($config, $this->self_url, 'post', 'ajax');
        $myform->setFormTheme("form_only");

        $myform->data['submit_label'] = "Create database";
        $myform->data['submit_class']="btn-success";
        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }

            $Settings = new Settings($this->db);
            $Users = new Users($this->db);

            $update_data = $data;
            unset($update_data['password']);
            $update_data['sent_email'] = $data['email'];
            $Settings->updateSettings($update_data);

            $user = $Users->getOne(1);
            if (!is_array($user) || count($user) == 0) {
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
                $Users->add($_data);
            } else {
                $_data = array(
                    "firstname"=>$data['firstname'],
                    "lastname"=>$data['lastname'],
                    "email"=>$data['email'],
                    "password"=>$data['password'],
                );
                $Users->edit($user['user_id'], $_data);
            }
            return $this->successMessage("Settings has been saved successfully.", $this->next_url);
        }
        return $this->viewRenderString($myform->fetch('', array('data-redirect'=>1)));
    }

    /**
     * Last step. Create database config file
     *
     * @throws Exception
     */
    public function complete()
    {
        if (!$this->hasAccess() || !$this->hasDatabase()) {
            return $this->_response;
        }

        $this->data['sub_title'] = "Complete";

        $Settings = new Settings($this->db);
        $Users = new Users($this->db);

        $admin_user = $Users->getOne(1);
        $prepare_settings = $Settings->getCount() > 0;
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
        if (!empty($admin_user)) {
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
        $myform = new Form($this);
        $myform->config($config, $this->self_url, 'post', 'ajax');
        $myform->setFormTheme("form_only");

        $myform->data['submit_label'] = "Confirm & Done";
        $myform->data['submit_class']="btn-success";

        if ($myform->ispost()) {
            $data = $myform->getPost();
            // Stop Page
            if ($data === false) {
                return $myform->getResponse();
            }

            if (!$prepare_settings) {
                return $this->errorMessage("Prepared settings is not set.", $this->back_url);
            }

            if (!is_array($admin_user) || count($admin_user) == 0) {
                return $this->errorMessage("Administration account not found.", $this->back_url);
            }

            foreach ($_SESSION['database_connect'] as $key => $value) {
                switch ($key) {
                    case 'host':
                        Services::databaseEnvConfig()->setHost($value);
                        break;
                    case 'username':
                        Services::databaseEnvConfig()->setUsername($value);
                        break;
                    case 'password':
                        Services::databaseEnvConfig()->setPassword($value);
                        break;
                    case 'database':
                        Services::databaseEnvConfig()->setDatabase($value);
                        break;
                }
            }

            Services::databaseEnvConfig()->writeToEnv();

            session_destroy();

            return $this->successMessage("Installation has been done.", $this->next_url);
        }

        $this->data['the_form'] = $myform->fetch('', array('data-redirect'=>1));
        return $this->viewRender("complete");
    }

    /**
     * @param array $models_paths
     * @return array
     */
    private function getTables(array $models_paths): array
    {
        $base_tables = array();
        foreach ($models_paths as $model_path) {
            $model_name = basename($model_path, ".php");

            if (in_array($model_name, ["Model", "CoreModel"])) {
                continue;
            }

            $model_name = "\NodCMS\Core\Models\\".$model_name;
            $theModel = new $model_name($this->db);
            if (!is_subclass_of($theModel, "\NodCMS\Core\Models\Model")) {
                continue;
            }

            if (method_exists($theModel, 'tableName')) {
                $base_tables[] = array(
                    'path' => $model_path,
                    'table' => $theModel->tableName(),
                    'exists' => $theModel->tableExists(),
                );
            }
        }

        return $base_tables;
    }

    /**
     * Check the required services has been reached
     *
     * @return bool
     */
    private function hasAccess(): bool
    {
        if (!$this->required_extensions || !$this->required_php_version) {
            $this->_response = $this->errorMessage("Required services not found.", $this->back_url);
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
    public function hasDatabase($data = null): bool
    {
        if ($data == null) {
            if (!isset($_SESSION['database_connect'])) {
                $this->_response = $this->errorMessage("Database connection data not found.", $this->back_url);
                return false;
            }
            $data = $_SESSION['database_connect'];
        }
        if (!isset($data['database'])) {
            $this->_response = $this->errorMessage("Database not found.", $this->back_url);
            return false;
        }

        $custom = [
            'DSN'      => '',
            'hostname' => $data['host'],
            'username' => $data['username'],
            'password' => $data['password'],
            'database' => $data['database'],
            'DBDriver' => 'MySQLi',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug'  => false,
            'cacheOn'  => false,
            'cacheDir' => '',
            'charset'  => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre'  => '',
            'encrypt'  => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port'     => 3306,
        ];
        $db = Database::connect($custom);
        try {
            $db->connID = $db->connect();
        } catch (\Throwable $e) {
            $this->_response = $this->errorMessage($e->getMessage(), $this->self_url);
            return false;
        }

        $this->db = $db;

        return true;
    }
}
