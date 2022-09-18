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

namespace NodCMS\Portfolio\Controllers;

use Config\Services;
use NodCMS\Core\Controllers\Frontend;
use NodCMS\Portfolio\Config\Models;
use NodCMS\Portfolio\Config\ViewFrontend;

class Portfolio extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        Services::layout()->setConfig(new ViewFrontend());
    }

    /**
     * Home preview
     *
     * @return string
     */
    public static function home(): string
    {
        // TODO: Remove the $CI after change the language translation keys reader
        $CI = 0;
        $data = [];
        $data['title'] = _l("Portfolio", $CI);
        $data_list = Models::portfolio()->getAllTrans(array('portfolio_public'=>1), null, 1, array('portfolio_date', 'DESC'));
        if (is_array($data_list)) {
            $data['data_list'] = $data_list;
        }
        return Services::layout(new ViewFrontend(), false)->setData($data)->render("portfolio_home");
    }

    /**
     * @param $id
     * @return string
     */
    public function portfolio($id)
    {
        $data = Models::portfolio()->getOneTrans($id);
        if (!is_array($data) || count($data)==0) {
            return $this->showError(_l("Portfolio not found.", $this));
        }
        $this->data['data'] = $data;
        if (Services::request()->isAJAX()) {
            return Services::layout()->setData($this->data)->render('portfolio_details_ajax');
        }

        return "";
    }
}
