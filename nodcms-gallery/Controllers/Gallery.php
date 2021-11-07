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
