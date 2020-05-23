<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 28-Sep-18
 * Time: 1:50 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class About extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct("frontend");
    }

    static function home($CI){
        $result = "";
        $data_list = $CI->About_model->getAllTrans(array('profile_theme<>'=>"card"));
        foreach($data_list as $item){
            $result .= $CI->load->view($CI->mainTemplate."/about_item_$item[profile_theme]", array('item'=>$item), true);
        }
        $CI->data['data_list'] = $CI->About_model->getAllTrans(array('profile_theme'=>"card"));
        $result .= $CI->load->view($CI->mainTemplate."/about_home", $CI->data, true);
        return $result;
    }

    /**
     * Load preview of Aboutme profiles
     *
     * @param string $lang
     */
    function index($lang)
    {
        $this->preset($lang);
        $data_list = $this->About_model->getAllTrans();
        $this->data['data_list'] = $data_list;
        echo $this->load->view($this->mainTemplate."/about_home", $this->data, true);
    }

    /**
     * Display an AboutMe profile
     *
     * @param string $lang
     * @param string $uri
     */
    function profile($lang, $uri)
    {
        $this->preset($lang);
        $this->display_page_title = false;
        $data = $this->About_model->getOneTrans(null, array('profile_uri'=>$uri));
        $this->data['data'] = $data;
        $this->data['title'] = $data['name'];
        $this->data['description'] = $data['description'];
        $this->data['keyword'] = $data['keywords'];
        $this->data['content'] = $this->load->view($this->mainTemplate."/about_$data[profile_theme]", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }
}