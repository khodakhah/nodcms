<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 21-Jun-19
 * Time: 1:12 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Portfolio extends NodCMS_Controller
{
    function __construct()
    {
        parent::__construct("frontend");
    }

    /**
     * Home preview
     *
     * @param $CI
     * @return string
     */
    static function home($CI){
        $CI->data['title'] = _l("Portfolio", $CI);
        $data_list = $CI->Portfolio_model->getAllTrans(array('portfolio_public'=>1), null, 1, array('portfolio_date', 'DESC'));
        if(is_array($data_list)){
            $CI->data['data_list'] = $data_list;
        }
        return $CI->load->view($CI->mainTemplate."/portfolio_home", $CI->data, true);
    }

    function portfolio($lang, $id){
        $this->preset($lang);
        $data = $this->Portfolio_model->getOneTrans($id);
        if(!is_array($data) || count($data)==0){
            $this->showError(_l("Portfolio not found.", $this));
            return;
        }
        $this->data['data'] = $data;
        if($this->input->is_ajax_request()){
            echo $this->load->view($this->mainTemplate."/portfolio_details_ajax", $this->data, true);
            return;
        }
    }
}