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

namespace NodCMS\Pricingtable\Controllers;

use Config\Services;
use NodCMS\Core\Controllers\Frontend;
use NodCMS\Pricingtable\Config\Models;
use NodCMS\Pricingtable\Config\ViewFrontend;

class PricingTable extends Frontend
{
    function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new ViewFrontend());
    }

    /**
     * Home preview
     *
     * @param $CI
     * @return string
     */
    static function home($CI){
        $data = [];
        $data['title'] = Services::settings()->get()['pricing_table_page_title'];
        $data_list = Models::pricingTable()->getAllTrans(array('table_public'=>1), null, 1, array('sort_order', 'ASC'));
        if(is_array($data_list)){
            foreach($data_list as &$item){
                $item['records'] = Models::pricingTableRecord()->getAllTrans(array(
                    'table_id'=>$item['table_id'],
//                    'record_public'=>1
                ), null, 1, array('sort_order', 'ASC'));
            }
            $data['data_list'] = $data_list;
        }
        return Services::layout()->setData($data)->render('pricing_table_home');
    }

    /**
     * @return string
     */
    function prices(){
        $data_list = Models::pricingTable()->getAllTrans(array('table_public'=>1), null, 1, array('sort_order', 'ASC'));
        if(is_array($data_list)){
            foreach($data_list as &$item){
                $item['records'] = Models::pricingTableRecord()->getAllTrans(array(
                    'table_id'=>$item['table_id'],
//                    'record_public'=>1
                ), null, 1, array('sort_order', 'ASC'));
            }
            $this->data['data_list'] = $data_list;
        }
        $this->data['title'] = $this->settings['pricing_table_page_title'];
        $this->data['description'] = $this->settings['pricing_table_page_description'];
        $this->data['keyword'] = $this->settings['pricing_table_page_keywords'];
        return $this->viewRender("pricing_table_home");
    }
}