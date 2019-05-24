<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 24-May-19
 * Time: 10:20 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Pricing_tableHooks extends NodcmsHooks
{
    function backend()
    {
        define('PRICING_TABLE_ADMIN_URL',base_url().'admin-pricing-table/');
        $this->CI->load->add_package_path(APPPATH."third_party/Pricing_table");
        $this->CI->load->model("Pricing_table_model");
        $this->CI->load->model("Pricing_table_record_model");

        if($this->CI->userdata["group_id"]==1){
            $addon_sidebar = array(
                'pricing_tables' => array(
                    'url'=>PRICING_TABLE_ADMIN_URL.'tables',
                    'icon'=>'fas fa-table',
                    'title'=>_l("Pricing Tables", $this->CI),
                ),
            );
            $this->CI->addToAdminSidebar($addon_sidebar);
        }
    }
}