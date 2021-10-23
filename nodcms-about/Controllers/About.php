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
 *  @since      Version 3.2.0
 *  @filesource
 *
 */

namespace NodCMS\About\Controllers;

use Config\Services;
use NodCMS\About\Config\Models;
use NodCMS\About\Config\ViewFrontend;
use NodCMS\Core\Controllers\Frontend;

class About extends Frontend
{
    /**
     * About constructor.
     */
    public function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new ViewFrontend());
    }

    /**
     * Display a preview content of the module in the public homepage
     *
     * @return string
     */
    static function home(): string
    {
        $result = "";
        $data_list = Models::about()->getAllTrans(['profile_theme<>'=>"card"], null, 1, ["order", "ASC"]);
        foreach($data_list as $item){
            $result .= Services::layout(new ViewFrontend(), false)->setData(array('item'=>$item))->render("about_item_$item[profile_theme]");
        }
        $data = ['data_list' => Models::about()->getAllTrans(['profile_theme'=>"card"], null, 1, ["order", "ASC"])];
        $result .= Services::layout(new ViewFrontend(), false)->setData($data)->render("about_home");
        return $result;
    }

    /**
     * Load preview of Aboutme profiles
     * @return string
     */
    function index()
    {
        $data_list = Models::about()->getAllTrans();
        $this->data['data_list'] = $data_list;
        return Services::layout()->setData($this->data)->render("about_home");
    }

    /**
     * Display an AboutMe profile
     *
     * @param string $uri
     * @return string
     */
    function profile(string $uri)
    {
        $this->display_page_title = false;
        $data = Models::about()->getOneTrans(null, array('profile_uri'=>$uri));
        if(empty($data))
            return $this->showError();
        $this->data['data'] = $data;
        $this->data['title'] = $data['name'];
        $this->data['description'] = $data['description'];
        $this->data['keyword'] = $data['keywords'];
        return $this->viewRender("about_$data[profile_theme]");
    }
}