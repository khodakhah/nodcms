<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 25-May-19
 * Time: 7:33 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

class GalleryHooks extends NodcmsHooks
{
    private function necessary()
    {
        $this->CI->load->add_package_path(APPPATH."third_party/Gallery");
        $this->CI->load->model("Gallery_model");
        $this->CI->load->model("Gallery_images_model");
    }

    function backend()
    {
        define('GALLERY_ADMIN_URL',base_url().'admin-gallery/');
        $this->necessary();

        if($this->CI->userdata["group_id"]==1){
            $addon_sidebar = array(
                'galleries' => array(
                    'url'=>GALLERY_ADMIN_URL.'galleries',
                    'icon'=>'far fa-images',
                    'title'=>_l("Galleries", $this->CI),
                ),
            );
            $this->CI->addToAdminSidebar($addon_sidebar);

            $this->CI->system_urls[] = array('title'=>_l("Gallery", $this->CI), 'url'=>"gallery");
        }
    }

    function preset($lang)
    {
        $this->necessary();
    }
}