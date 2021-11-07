<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

namespace NodCMS\Services\Controllers;

use Config\Services;
use NodCMS\Core\Controllers\Frontend;
use NodCMS\Services\Config\ViewFrontend;
use NodCMS\Services\Config\Models;

class ServicesFrontend extends Frontend
{
    function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new ViewFrontend());
    }

    /**
     * Home preview
     *
     * @return string
     */
    static function home(){
        $data = [];
        $data_list = Models::services()->getAllTrans(array('service_public'=>1), null, 1, array('sort_order', 'ASK'));
        if(is_array($data_list)){
            $data['services_has_price'] = Services::settings()->get()['services_display_price'];
            $data['services_has_content'] = Services::settings()->get()['services_page'];
            if($data['services_has_content'])
                foreach ($data_list as &$item){
                    $item['service_url'] = base_url(Services::language()->getLocale()."/service-{$item['service_uri']}");
                }
            $data['data_list'] = $data_list;
        }
        $data['title'] = Services::settings()->get()['services_page_title'];
        return Services::layout(new ViewFrontend(), false)->setData($data)->render("services_home");
    }

    /**
     * Display page of a service
     *
     * @param string $uri
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     */
    function service(string $uri)
    {
        $data = Models::services()->getOneTrans(null, array('service_uri'=>$uri,'service_public'=>1));
        if(!is_array($data) || count($data)==0){
            return $this->errorMessage(_l("Service not found.", $this));
        }

        $this->display_page_title = false;

        $this->data['services_has_price'] = $this->settings['services_display_price'];
        $this->data['services_has_content'] = $this->settings['services_page'];

        $data_list = Models::services()->getAllTrans(array('service_id <>'=>$data['service_id'],'service_public'=>1));
        if(is_array($data_list)){
            if($this->data['services_has_content'])
                foreach ($data_list as &$item){
                    $item['service_url'] = base_url($this->language['code']."/service-$item[service_uri]");
                }
            $this->data['data_list'] = $data_list;
        }

        $this->data['data'] = $data;
        $this->data['title'] = $data['title'];
        $this->data['sun_title'] = $this->settings['site_title'];
        $this->data['keywords'] = $data['keywords'];
        $this->data['description'] = $data['description'];
        return $this->viewRender("services_detail");
    }
}
