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

use CodeIgniter\Controller;
use Config\Services;
use Config\Settings;
use NodCMS\Core\View\Layout;

abstract class Base extends Controller
{
    // use for system settings
    public $settings;
    // current language
    public $language;
    // use for view main folder
    public $mainTemplate;
    // use for view main file
    public $frameTemplate;
    // use form view clean frame file
    public $cleanFrame;
    // Outputs data (The parameters will use in view files)
    public $data;
    // Static sidebar close status
    public $page_sidebar = "frontend_sidebar";
    public $page_sidebar_closed = false;
    public $page_sidebar_items = array();
    public $page_sidebar_close = false;
    public $page_sidebar_menu_closed = false;
    // Title display status
    public $display_title = true;
    public $display_page_title = false;

    /**
     * @var \CodeIgniter\Router\Router
     */
    protected $router;

    /**
     * @var \NodCMS\Core\View\Layout
     */
    public $view;

    /**
     * @var \CodeIgniter\Session\Session
     */
    public $session;

    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    public $request;

    /**
     * @var \Config\Settings
     */
    protected $config;

    /**
     * @var \Config\Models
     */
    protected $model;

    public function __construct()
    {
        $this->config = new Settings();
        $this->settings = Services::settings()->get();
        $this->router = \Config\Services::router();
        $this->view = Services::layout();
        $this->request = Services::request();
        $this->session = Services::session();
        $this->model = Services::model();
        helper("NodCMS\Core\core");
        helper('cookie');
    }

    /*
     * This method useful for stop your system with an error
     */
    protected function errorMessage($error, $redirect = "")
    {
        if ($this->request->isAJAX()) {
            $data = array(
                "status"=>"error",
                "url"=>$redirect,
                "error"=> $error
            );
            return json_encode($data);
        } else {
            $this->session->setFlashdata('error', $error);
            return redirect()->to($redirect);
        }
    }

    /*
     * This method useful for return successful messages
     */
    protected function successMessage(string $message=null, string $redirect = "", $add_on_data = null, $translate = false)
    {
        if ($this->request->isAJAX()) {
            $data = array(
                "status"=>"success",
                "url"=>$redirect,
            );
            if ($message!=null) {
                $data['msg'] = $message;
            }

            if ($add_on_data!=null) {
                $data["data"] = $add_on_data;
            }

            return json_encode($data);
        } else {
            if ($message!=null) {
                $this->session->setFlashdata('success', $message);
            }
            return redirect()->to($redirect);
        }
    }

    /**
     * Curling a web page to return the content
     *
     * @param $url
     * @param $internal bool
     * @return mixed
     */
    protected function curlWebPage($url, $internal = false)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $header = array();
        $header[] = 'Accept: text/xml,application/xml,application/xhtml+xml, text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: html/text';

        if ($internal) {
            $_SESSION['my_session_id'] = session_id();
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if (isset($_COOKIE[session_name()])) {
                curl_setopt($ch, CURLOPT_COOKIE, session_name().'='.$_COOKIE[session_name()].'; path=/');
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, SSL_PROTOCOL);
            session_write_close();
            $data = curl_exec($ch);
            curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            session_start();
        } else {
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            $data = curl_exec($ch);
            curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        }

        return $data;
    }

    /**
     * Curl a page and return json content as array
     *
     * @param $url
     * @param null $data
     * @param int $data_post
     * @param int $ssl
     * @param bool $internal
     * @return mixed
     */
    protected function curlJSON($url, $data = null, $data_post = 0, $ssl = 0, $internal = false)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $header = array();
        $header[] = 'Content-type: application/json';

        if ($data!=null) {
            if ($data_post == 1) {
                curl_setopt($ch, CURLOPT_POST, true);
            }
            $data_string = json_encode($data);
            if (is_array($data)) {
                foreach ($data as &$item) {
                    if (is_array($item)) {
                        $item = json_encode($item);
                    }
                }
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $header[] = 'Content-Length: '. strlen($data_string);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl);
        if ($internal) {
            $_SESSION['my_session_id'] = session_id();
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if (isset($_COOKIE[session_name()])) {
                curl_setopt($ch, CURLOPT_COOKIE, session_name().'='.$_COOKIE[session_name()].'; path=/');
            }
            session_write_close();
            $return_data = curl_exec($ch);
            curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            session_start();
        } else {
            curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $return_data = curl_exec($ch);
            curl_close($ch);
        }

        $json_data = json_decode($return_data, true);
        if ($json_data==null) {
            return array(
                'status'=>"error",
                'error'=>"Data transfer result: $return_data",
            );
        }
        return $json_data;
    }

    /**
     * Render view with whole page structure
     *
     * @param string $view_file
     * @param bool|null $saveData
     * @return string
     */
    protected function viewRender(string $view_file, bool $saveData = null): string
    {
        $this->viewPrepare();
        Services::layout()->setVar("content", Services::layout()->render($view_file));
        return Services::layout()->renderFrame(null, $saveData);
    }

    /**
     * Render whole page structure with an string content
     *
     * @param string $string
     * @param bool|null $saveData
     * @return string
     */
    protected function viewRenderString(string $string, bool $saveData = null): string
    {
        $this->viewPrepare();
        $this->view->setVar("content", $string);
        return $this->view->renderFrame(null, $saveData);
    }

    /**
     *
     *
     * @param string $view_file
     * @param array $data
     * @param string|null $view_path
     * @return string
     */
    public function viewCommon(string $view_file, array $data): string
    {
        foreach ($data as $key=>$value) {
            $this->view->common()->setVar($key, $value);
        }
        return $this->view->common()->render($view_file);
    }

    /**
     * Copy all controller public variables to the view object
     */
    private function viewPrepare()
    {
        $data = array_merge([
            'title' => "",
            'page' => "",
        ], $this->data);
        unset($this->data);
        foreach ($data as $key=>$value) {
            $this->view->setVar($key, $value);
        }
        $this->view->loadControllerVars($this);
    }
}
