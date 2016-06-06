<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 3/1/2016
 * Time: 5:56 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class NodCMS_Controller extends CI_Controller {
    public $_website_info,$data,$mainTemplate;
    public $langArray=array();

    function __construct($controllerType='backend'){ // "backend" OR "frontend"
        parent::__construct();
        $models = $this->config->item($controllerType.'_models');
        $this->load->model($models);
        $helpers = $this->config->item($controllerType.'_helpers');
        $this->load->helper($helpers);
        $this->$controllerType();
    }

    private function backend(){
        $this->mainTemplate = $this->config->item('NodCMS_general_admin_templateFolderName');
        $this->_website_info = @reset($this->NodCMS_general_admin_model->get_website_info());
        if(!isset($this->session->userdata['user_id'])) redirect(base_url()."admin-sign");
        $_SESSION['language'] = $language = $this->NodCMS_general_admin_model->get_language_detail($this->_website_info["language_id"]);
        $this->lang->load('backend', $language["language_name"]);
        $this->data['settings'] = $this->_website_info;
        $this->data['base_url'] = base_url()."admin/";
        $this->load->library('spyc');
        $this->data['all_page_type'] = spyc_load_file(getcwd()."/page_type.yml") ;
        $this->data['page_list'] = $this->NodCMS_general_admin_model->get_all_page();
    }

    private function frontend(){
        $this->mainTemplate = $this->config->item('NodCMS_general_templateFolderName');

    }
}