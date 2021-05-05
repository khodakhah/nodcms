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

namespace NodCMS\Portfolio\Controllers;

use Config\Services;
use NodCMS\Core\Controllers\Frontend;
use NodCMS\Portfolio\Config\Models;
use NodCMS\Portfolio\Config\ViewFrontend;

class Portfolio extends Frontend
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
        // TODO: Remove the $CI after change the language translation keys reader
        $CI = 0;
        $data = [];
        $data['title'] = _l("Portfolio", $CI);
        $data_list = Models::portfolio()->getAllTrans(array('portfolio_public'=>1), null, 1, array('portfolio_date', 'DESC'));
        if(is_array($data_list)){
            $data['data_list'] = $data_list;
        }
        return Services::layout(new ViewFrontend(), false)->setData($data)->render("portfolio_home");
    }

    /**
     * @param $id
     * @return string
     */
    public function portfolio($id){
        $data = Models::portfolio()->getOneTrans($id);
        if(!is_array($data) || count($data)==0){
            return $this->showError(_l("Portfolio not found.", $this));
        }
        $this->data['data'] = $data;
        if(Services::request()->isAJAX()){
            return Services::layout()->setData($this->data)->render('portfolio_details_ajax');
        }

        return "";
    }
}