<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 15-Dec-19
 * Time: 12:51 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

trait NodCMSUtilities
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

    public function __construct()
    {
        parent::__construct();
        $this->settings = $this->config->item('settings_default');
    }

    /*
     * This method useful for stop your system with an error
     */
    function errorMessage($error, $redirect = "")
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
    function successMessage($message, $redirect = "" , $add_on_data = null, $translate = false)
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
    function curlWebPage($url, $internal = false)
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
    function curlJSON($url, $data = null, $data_post = 0, $ssl = 0, $internal = false)
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
}