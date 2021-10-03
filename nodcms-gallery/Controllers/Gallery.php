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

namespace NodCMS\Gallery\Controllers;

use Config\Services;
use NodCMS\Core\Controllers\Frontend;
use NodCMS\Gallery\Config\Models;
use NodCMS\Gallery\Config\ViewFrontend;

class Gallery extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig( new ViewFrontend());
    }

    /**
     * Home preview
     *
     * @return string
     */
    static function home(): string
    {
        $data = [];
        $data_list = Models::gallery()->getAllTrans(['gallery_public'=>1]);
        $data['data_list'] = $data_list;

        $data['title'] = Services::settings()->get()['gallery_page_title'];
        $data['description'] = Services::settings()->get()['gallery_page_descriptions'];
        return Services::layout(new ViewFrontend(), false)->setData($data)->render("gallery_homepage");
    }

    /**
     * @param int $id
     * @return string
     */
    function album(int $id): string
    {
        $data = Models::gallery()->getOneTrans($id);
        $this->data['data'] = $data;
        if(Services::request()->isAJAX()){
            return Services::layout()->setData($this->data)->render("gallery_details_ajax");
        }
        $this->display_page_title = false;
        $this->data['data_list'] = Models::galleryImages()->getAllTrans(array('gallery_id'=>$id));
        $this->data['title'] = $data['title'];
        $this->data['breadcrumb'] = array(
            array('title'=>_l("Gallery", $this), 'url'=>base_url("$this->lang/gallery")),
            array('title'=>$this->data['title']),
        );
        $this->data['description'] = $data['description'];
        $this->data['keyword'] = $data['keywords'];
        $this->data['title_bg'] = isset($this->settings['gallery_page_title_bg'])?base_url($this->settings['gallery_page_title_bg']):"";
        return $this->viewRender('gallery_details');
    }

    /**
     * Gallery page
     */
    function gallery()
    {
        $this->display_page_title = true;
        $data_list = Models::gallery()->getAllTrans(['gallery_public'=>1]);
        $this->data['data_list'] = $data_list;
        $setting = array_merge(array(
            'gallery_page_title'=> _l("Gallery", $this),
            'gallery_page_title_bg'=>"",
            'gallery_page_descriptions'=>"",
            'gallery_page_keywords'=>"",
        ), $this->settings);
        $this->data['title'] = $setting['gallery_page_title'];
        $this->data['breadcrumb'] = array(
            array('title'=>$this->data['title']),
        );
        $this->data['description'] = $setting['gallery_page_descriptions'];
        $this->data['keyword'] = $setting['gallery_page_keywords'];
        $this->data['title_bg'] = $setting['gallery_page_title_bg']!=""?base_url($setting['gallery_page_title_bg']):'';
        return $this->viewRender("gallery_list");
    }
}