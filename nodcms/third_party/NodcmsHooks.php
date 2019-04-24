<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 01-Feb-19
 * Time: 1:17 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class NodcmsHooks
{
    public $CI;

    function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Backend setups
     */
    function backend()
    {
        // Backend setups
    }

    /**
     * Frontend setups
     *
     * @param $lang
     */
    function preset($lang)
    {}

    /**
     * Membership setups
     */
    function membership()
    {}

    function paidInvoice($param)
    {
        return array('status'=>"success");
    }

    function prePaymentInvoice($param)
    {
        return array('status'=>"success");
    }
}