<?php
/**
 * Created by PhpStorm.
 * User: Mojtaba
 * Date: 9/16/2015
 * Time: 1:03 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Nodcms_admin_sign extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model("NodCMS_general_admin_model");
        $this->load->model("NodCMS_admin_sign_model");
        $banner = @reset($this->NodCMS_general_admin_model->get_website_info());
        $_SESSION['language'] = $language = $this->NodCMS_general_admin_model->get_language_detail($banner["language_id"]);
        $this->lang->load($language["code"], $language["language_name"]);
    }

    function index()
    {
        if ($this->session->userdata('logged_in_status') == TRUE)
        {
            redirect('/admin/');
        }
        else
        {
            $this->load->view('nodcms_admin_login');
        }
    }
    function login()
    {
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = md5($this->input->post('password'));
        $result=$this->NodCMS_admin_sign_model->check_login($username,$password);
        $tam =  $result->result_array();
        if($result->num_rows()>0 && ($tam[0]['group_id']==1 || $tam[0]['group_id']==2))
        {
            foreach ($result->result_array() as $row)
            {
                $data = array(
                    'fullname'  => $row['firstname']." ".$row['firstname'],
                    'username'  => $row['username'],
                    'user_id' => $row['user_id'],
                    'group'   => $row['group_id'],
                    'avatar'   => $row['avatar'],
                    'email'   => $row['email'],
                    'logged_in_status'   => true,

                );
            }
            $this->session->set_userdata($data);
            $_SESSION['Session_Admin'] = $data['user_id'];
            redirect(base_url().'admin/');

        }
        else
        {
            $this->session->set_flashdata('message', _l('Oopsie, Username or password is incorrect',$this));
            redirect(base_url().'admin-sign');
        }
    }

    function logout()
    {
        //$this->session->sess_destroy();
        $this->session->unset_userdata('logged_in_status');
        redirect(base_url().'admin-sign');
    }
}
