<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 21-Jun-19
 * Time: 4:35 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Gallery extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct('frontend');
    }

    static function home($CI)
    {
        $data_list = $CI->Gallery_model->getAllTrans();
        $CI->data['data_list'] = $data_list;
        $setting = array_merge(array(
            'gallery_page_title'=> _l("Gallery", $CI),
            'gallery_page_title_bg'=>"",
            'gallery_page_descriptions'=>"",
            'gallery_page_keywords'=>"",
        ), $CI->settings);
        $CI->data['title'] = $setting['gallery_page_title'];
        $CI->data['description'] = $setting['gallery_page_descriptions'];
        $doc = $CI->load->view($CI->mainTemplate."/gallery_homepage", $CI->data, true);
        return $doc;
    }

    function album($lang, $id)
    {
        $this->preset($lang);
        $data = $this->Gallery_model->getOneTrans($id);
        $this->data['data'] = $data;
        if($this->input->is_ajax_request()){
            echo $this->load->view($this->mainTemplate."/gallery_details_ajax", $this->data, true);
            return;
        }
        $this->display_page_title = false;
        $this->data['data_list'] = $this->Gallery_images_model->getAllTrans(array('gallery_id'=>$id));
        $this->data['title'] = $data['title'];
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Gallery", $this), 'url'=>base_url()."$lang/gallery"),
            array('title'=>$this->data['title']),
        );
        $this->data['description'] = $data['description'];
        $this->data['keyword'] = $data['keywords'];
        $this->data['title_bg'] = isset($this->settings['gallery_page_title_bg'])?base_url().$this->settings['gallery_page_title_bg']:"";
        $this->data['content'] = $this->load->view($this->mainTemplate."/gallery_details", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

    function gallery($lang)
    {
        $this->preset($lang);
        $this->display_page_title = true;
        $data_list = $this->Gallery_model->getAllTrans();
        $this->data['data_list'] = $data_list;
        $setting = array_merge(array(
            'gallery_page_title'=> _l("Gallery", $this),
            'gallery_page_title_bg'=>"",
            'gallery_page_descriptions'=>"",
            'gallery_page_keywords'=>"",
        ), $this->settings);
        $this->data['title'] = $setting['gallery_page_title'];
        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title']),
        );
        $this->data['description'] = $setting['gallery_page_descriptions'];
        $this->data['keyword'] = $setting['gallery_page_keywords'];
        $this->data['title_bg'] = $setting['gallery_page_title_bg']!=""?base_url().$setting['gallery_page_title_bg']:'';
        $this->data['content'] = $this->load->view($this->mainTemplate."/gallery_list", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }
}