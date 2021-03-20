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

namespace NodCMS\About\Controllers;

use Config\Services;
use NodCMS\About\Config\Models;
use NodCMS\About\Config\View;
use NodCMS\Core\Controllers\Frontend;

class About extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new View());
    }

    static function home($CI){
        $result = "";
        $data_list = $CI->About_model->getAllTrans(array('profile_theme<>'=>"card"));
        foreach($data_list as $item){
            $result .= $CI->load->view($CI->mainTemplate."/about_item_$item[profile_theme]", array('item'=>$item), true);
        }
        $CI->data['data_list'] = $CI->About_model->getAllTrans(array('profile_theme'=>"card"));
        $result .= $CI->load->view($CI->mainTemplate."/about_home", $CI->data, true);
        return $result;
    }

    /**
     * Load preview of Aboutme profiles
     */
    function index()
    {
        $data_list = Models::about()->getAllTrans();
        $this->data['data_list'] = $data_list;
        echo Services::layout()->setData($this->data)->render("about_home");
    }

    /**
     * Display an AboutMe profile
     *
     * @param string $uri
     */
    function profile(string $uri)
    {
        $this->display_page_title = false;
        $data = Models::about()->getOneTrans(null, array('profile_uri'=>$uri));
        $this->data['data'] = $data;
        $this->data['title'] = $data['name'];
        $this->data['description'] = $data['description'];
        $this->data['keyword'] = $data['keywords'];
        $this->viewRender("about_$data[profile_theme]");
    }
}