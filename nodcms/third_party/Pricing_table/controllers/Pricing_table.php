<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 24-Jun-19
 * Time: 2:11 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Pricing_table extends NodCMS_Controller
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
        $CI->data['title'] = $CI->settings['pricing_table_page_title'];
        $data_list = $CI->Pricing_table_model->getAllTrans(array('table_public'=>1), null, 1, array('sort_order', 'ASC'));
        if(is_array($data_list)){
            foreach($data_list as &$item){
                $item['records'] = $CI->Pricing_table_record_model->getAllTrans(array(
                    'table_id'=>$item['table_id'],
//                    'record_public'=>1
                ), null, 1, array('sort_order', 'ASC'));
            }
            $CI->data['data_list'] = $data_list;
        }
        return $CI->load->view($CI->mainTemplate."/pricing_table_home", $CI->data, true);
    }

    function prices($lang){
        $this->preset($lang);
        $data_list = $this->Pricing_table_model->getAllTrans(array('table_public'=>1), null, 1, array('sort_order', 'ASC'));
        if(is_array($data_list)){
            foreach($data_list as &$item){
                $item['records'] = $this->Pricing_table_record_model->getAllTrans(array(
                    'table_id'=>$item['table_id'],
//                    'record_public'=>1
                ), null, 1, array('sort_order', 'ASC'));
            }
            $this->data['data_list'] = $data_list;
        }
        $this->data['title'] = $this->settings['pricing_table_page_title'];
        $this->data['description'] = $this->settings['pricing_table_page_description'];
        $this->data['keyword'] = $this->settings['pricing_table_page_keywords'];
        $this->data['content'] = $this->load->view($this->mainTemplate."/pricing_table_home", $this->data, true);
        $this->load->view($this->frameTemplate, $this->data);
    }

}