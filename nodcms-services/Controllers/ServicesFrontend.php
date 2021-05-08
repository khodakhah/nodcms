<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
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
        return Services::layout()->setData($data)->render("services_home");
    }

    /**
     * Display page of a service
     *
     * @param int $uri
     * @return \CodeIgniter\HTTP\RedirectResponse|false|string
     */
    function service(int $uri)
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
                    $item['service_url'] = base_url().$this->language['code']."/service-$item[service_uri]";
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