<?php
/**
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
 *  @since      Version 3.2.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Controllers;

use Config\Models;
use Config\Services;
use Config\ViewFrontend;

abstract class Frontend extends App
{
    public $lang;

    public function __construct()
    {
        parent::__construct();

        // Set loaded language
        $this->language = \Config\Services::language()->get();
        $this->lang = $this->language['code'];

        // Sidebar view file
        $this->page_sidebar = "frontend_sidebar";
        Services::layout()->setConfig(new ViewFrontend());

        if($this->frameTemplate==null)
            $this->frameTemplate = $this->mainTemplate;

        $this->data['lang_url'] = base_url().uri_string();

        if(Services::identity()->hasSession()) {
            $this->userdata = Services::identity()->getUserData();
        }
        else {
            $this->userdata = null;
        }

        $this->data['socialMediaLinks'] = Models::socialLinks()->getAll();

        $this->setMenus();
    }

    /**
     * Set the top and bottom menus
     */
    private function setMenus()
    {
        $all_menus = array('top_menu', 'footer_menu');
        foreach($all_menus as $menu_type){
            $data_menu = array();
            $menu = Models::menu()->getMenu($menu_type, 0);
            foreach($menu as $item){
                $menu_item = array(
                    'name' =>$item['menu_name'],
                    'title' =>$item['menu_name'],
                    'icon' =>$item['menu_icon'],
                    'url' =>substr($item['menu_url'],0,4)=="http"?$item['menu_url']:base_url($this->language['code']."/".$item['menu_url']),
                );
                $sub_menu = Models::menu()->getMenu("top_menu", $item['menu_id']);

                if(count($sub_menu)!=0){
                    $sub_menu_data = array();
                    foreach ($sub_menu as $sub_item){
                        $sub_menu_item = array(
                            'name' =>$sub_item['menu_name'],
                            'title' =>$sub_item['menu_name'],
                            'icon' =>$sub_item['menu_icon'],
                            'url' =>substr($sub_item['menu_url'],0,4)=="http"?$sub_item['menu_url']:base_url($this->language['code']."/".$sub_item['menu_url']),
                        );
                        array_push($sub_menu_data, $sub_menu_item);
                    }
                    $menu_item['sub_menu'] = $sub_menu_data;
                }

                array_push($data_menu, $menu_item);
            }
            $this->data[$menu_type] = $data_menu;
        }
    }
}