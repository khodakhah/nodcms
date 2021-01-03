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
class Nodcms_admin_sign_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    function check_login($username,$pass)
    {
        $this->db->where('username', $username);
        $this->db->where('password', $pass);
        $query = $this->db->get('users');
        return $query;
    }
}
