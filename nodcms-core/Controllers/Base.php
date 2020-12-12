<?php
/**
 * NodCMS
 *
 * Copyright (c) 2015-2020.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2020 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Controllers;

use CodeIgniter\Controller;
use NodCMS\Core\Config\Settings;

abstract class Base extends Controller
{
    public $system_urls;

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
    // User data from DB
    public $userdata;
    // All add-ons css files
    public $css_files = array();
    // All add-ons js files
    public $js_files = array();
    // Static sidebar close status
    public $page_sidebar = "frontend_sidebar";
    public $page_sidebar_closed = false;
    public $page_sidebar_items = array();
    public $page_sidebar_close = FALSE;
    public $page_sidebar_menu_closed = FALSE;
    // Title display status
    public $display_title = TRUE;
    public $display_page_title = FALSE;

    /**
     * @var \CodeIgniter\Router\Router
     */
    protected $router;

    /**
     * @var \NodCMS\Core\View\View
     */
    public $view;

    public function __construct()
    {
        $config = new Settings();
        $this->settings = $config->settings_default;
        $this->router = \Config\Services::router();
        $this->view = new \NodCMS\Core\View\View(new \NodCMS\Core\Config\View());
    }

    /*
     * This method useful for stop your system with an error
     */
    protected function errorMessage($error, $redirect = "")
    {
        if($this->input->is_ajax_request()){
            $data = array(
                "status"=>"error",
                "url"=>$redirect,
                "error"=> $error
            );
            echo  json_encode($data);
            exit;
        }else{
            $this->session->set_flashdata('error', $error);
            redirect($redirect);
        }
    }

    /*
     * This method useful for return successful messages
     */
    protected function successMessage($message, $redirect = "" , $add_on_data = null, $translate = false)
    {
        if($this->input->is_ajax_request()){
            $data = array(
                "status"=>"success",
                "url"=>$redirect,
                "msg"=> $message
            );
            if($add_on_data!=null)
                $data["data"] = $add_on_data;
            echo  json_encode($data);
            exit;
        }else{
            $this->session->set_flashdata('success', $message);
            redirect($redirect);
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
        $ch = curl_init( $url);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $header = array();
        $header[] = 'Accept: text/xml,application/xml,application/xhtml+xml, text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: html/text';

        if($internal){
            $_SESSION['my_session_id'] = session_id();
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if (isset($_COOKIE[session_name()]))
                curl_setopt($ch, CURLOPT_COOKIE, session_name().'='.$_COOKIE[session_name()].'; path=/');
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, SSL_PROTOCOL);
            session_write_close();
            $data = curl_exec( $ch );
            curl_getinfo( $ch,CURLINFO_HTTP_CODE );
            curl_close( $ch );
            session_start();
        }else{
            curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
            $data = curl_exec( $ch );
            curl_getinfo( $ch,CURLINFO_HTTP_CODE );
            curl_close( $ch );
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
        $ch = curl_init( $url);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $header = array();
        $header[] = 'Content-type: application/json';

        if($data!=null){
            if($data_post == 1){
                curl_setopt($ch, CURLOPT_POST, true);
            }
            $data_string = json_encode($data);
            if(is_array($data)){
                foreach ($data as &$item){
                    if(is_array($item)){
                        $item = json_encode($item);
                    }
                }
            }
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
            $header[] = 'Content-Length: '. strlen($data_string);
        }

        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, $ssl);
        if($internal){
            $_SESSION['my_session_id'] = session_id();
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if (isset($_COOKIE[session_name()]))
                curl_setopt($ch, CURLOPT_COOKIE, session_name().'='.$_COOKIE[session_name()].'; path=/');
            session_write_close();
            $return_data = curl_exec( $ch );
            curl_getinfo( $ch,CURLINFO_HTTP_CODE );
            curl_close( $ch );
            session_start();
        }else{
            curl_getinfo( $ch,CURLINFO_HTTP_CODE );
            $return_data = curl_exec( $ch );
            curl_close( $ch );
        }

        $json_data = json_decode($return_data, true);
        if($json_data==null){
            return array(
                'status'=>"error",
                'error'=>"Data transfer result: $return_data",
            );
        }
        return $json_data;
    }

    /**
     * @param string $view_file
     * @param bool|null $saveData
     * @return string
     */
    protected function viewRender(string $view_file, bool $saveData = null): string
    {
        $this->view->loadControllerVars($this);
        $this->data['content'] =  $this->view->render($view_file, $this->data);
        return $this->view->renderFrame($this->data, $saveData);
    }
}