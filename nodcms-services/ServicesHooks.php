<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 11:35 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class ServicesHooks extends NodcmsHooks
{
    private function necessary(){

        define('SERVICES_ADMIN_URL',base_url().'admin-services/');
        $this->CI->load->add_package_path(APPPATH."third_party/Services");
        $this->CI->load->model("Services_model");
        $this->CI->settings =  array_merge(array(
            'services_display_mode'=>"image", // Or "icon"
            'services_display_price'=>0,
            'services_page'=>0,
            'services_page_title'=>_l("Services", $this->CI),
        ), $this->CI->settings);
    }

    function backend()
    {
        $this->necessary();
        if($this->CI->userdata["group_id"]==1){
            $addon_sidebar = array(
                'services' => array(
                    'url'=>'javascript:;',
                    'icon'=>'fas fa-concierge-bell',
                    'title'=>_l("Services", $this->CI),
                    'sub_menu'=>array(
                        'services_list'=>array(
                            'url'=>SERVICES_ADMIN_URL.'services',
                            'title'=>_l("Services' list", $this->CI),
                        ),
                        'services_settings'=>array(
                            'url'=>SERVICES_ADMIN_URL.'settings',
                            'title'=>_l("Display settings", $this->CI),
                        ),
                    )
                ),
            );
            $this->CI->addToAdminSidebar($addon_sidebar);
        }
    }

    function preset($lang)
    {
        $this->necessary();
    }
}