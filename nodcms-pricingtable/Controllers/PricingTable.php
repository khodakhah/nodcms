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
     * @return string
     */
    static function home(): string
    {
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
        return Services::layout(new ViewFrontend(), false)->setData($data)->render('pricing_table_home');
    }

    /**
     * @return string
     */
    function prices(): string
    {
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
