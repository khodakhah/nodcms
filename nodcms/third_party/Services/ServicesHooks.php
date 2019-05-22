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
    function backend()
    {
        define('SERVICES_ADMIN_URL',base_url().'admin-services/');
        $this->CI->load->add_package_path(APPPATH."third_party/Services");
        $this->CI->load->model("Services_model");

        if($this->CI->userdata["group_id"]==1){
            $addon_sidebar = array(
                'Services' => array(
                    'url'=>SERVICES_ADMIN_URL.'services',
                    'icon'=>'fas fa-concierge-bell',
                    'title'=>_l("Services", $this->CI),
                ),
            );
            $this->CI->addToAdminSidebar($addon_sidebar);
        }
    }
}