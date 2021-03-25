<?php

/**
 * Created by Mojtaba Khodakhah.
 * Date: 11/11/2016
 * Time: 5:54 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class ArticlesHooks
{
    // Current controller
    public $CI;

    function __construct()
    {
        // Get my called controller
        $this->CI =& get_instance();
    }

    function backend()
    {
        define('ARTICLES_ADMIN_URL',base_url().'admin-articles/');
        $this->CI->load->add_package_path(APPPATH."third_party/Articles");
        $this->CI->load->model("Articles_model");

        if($this->CI->userdata["group_id"]==1){
            $addon_sidebar = array(
                'article_list' => array(
                    'url'=>ARTICLES_ADMIN_URL.'article',
                    'icon'=>'far fa-file-alt',
                    'title'=>_l('Articles',$this->CI),
                ),
            );
            $this->CI->addToAdminSidebar($addon_sidebar);
        }

        $articles = $this->CI->Articles_model->getAll();
        foreach($articles as $item){
            $this->CI->system_urls[] = array('title'=>_l("Article", $this->CI).": ".$item['name'], 'url'=>"article/".$item['article_uri']);
        }
    }
}