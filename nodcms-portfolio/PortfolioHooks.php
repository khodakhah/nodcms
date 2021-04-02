<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 3:29 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class PortfolioHooks extends NodcmsHooks
{
    private function necessary(){
        $this->CI->load->add_package_path(APPPATH."third_party/Portfolio");
        $this->CI->load->model("Portfolio_model");
    }

    function backend()
    {
        define('PORTFOLIO_ADMIN_URL',base_url().'admin-portfolio/');
        $this->necessary();
        if($this->CI->userdata["group_id"]==1){
            $addon_sidebar = array(
                'portfolio_posts' => array(
                    'url'=>PORTFOLIO_ADMIN_URL.'posts',
                    'icon'=>'fas fa-camera-retro',
                    'title'=>_l("Portfolio", $this->CI),
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