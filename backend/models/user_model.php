<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class User_Model extends CI_Model 
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	function check_login($username,$pass)
	{
		$this->db->like('username', $username);
		$this->db->like('password', $pass); 
		$query = $this->db->get('users');
		return $query;
		
	}
}