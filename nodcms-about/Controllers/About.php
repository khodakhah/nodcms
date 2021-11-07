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
