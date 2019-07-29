<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 20-Jun-19
 * Time: 10:15 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Services extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct("frontend");
    }

    /**
     * Home preview
     *
     * @param $CI
     * @return string
     */
    static function home($CI){
        $data_list = $CI->Services_model->getAllTrans(array('service_public'=>1), null, 1, array('sort_order', 'ASK'));
        if(is_array($data_list)){
            $CI->data['services_has_price'] = $CI->settings['services_display_price'];
            $CI->data['services_has_content'] = $CI->settings['services_page'];
            if($CI->data['services_has_content'])
                foreach ($data_list as &$item){
                    $item['service_url'] = base_url().$CI->language['code']."/service-$item[service_uri]";
                }
            $CI->data['data_list'] = $data_list;
        }
        $CI->data['title'] = $CI->settings['services_page_title'];
        return $CI->load->view($CI->mainTemplate."/services_home", $CI->data, true);
    }

    /**
     * Display page of a service
     *
     * @param $lang
     * @param $uri
     */
    function service($lang, $uri)
    {
        $this->preset($lang);
        $data = $this->Services_model->getOneTrans(null, array('service_uri'=>$uri,'service_public'=>1));
        if(!is_array($data) || count($data)==0){
            $this->showError(_l("Service not found.", $this));
            return;
        }

        $this->display_page_title = false;

        $this->data['services_has_price'] = $this->settings['services_display_price'];
        $this->data['services_has_content'] = $this->settings['services_page'];

        $data_list = $this->Services_model->getAllTrans(array('service_id <>'=>$data['service_id'],'service_public'=>1));
        if(is_array($data_list)){
            if($this->data['services_has_content'])
                foreach ($data_list as &$item){
                    $item['service_url'] = base_url().$this->language['code']."/service-$item[service_uri]";
                }
            $this->data['data_list'] = $data_list;
        }

        $this->data['data'] = $data;
        $this->data['title'] = $data['title'];
        $this->data['sun_title'] = $this->settings['site_title'];
        $this->data['keywords'] = $data['keywords'];
        $this->data['description'] = $data['description'];
        $this->data['content'] = $this->load->view($this->mainTemplate."/services_detail", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }
}