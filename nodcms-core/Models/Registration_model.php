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

namespace NodCMS\Core\Models;

class Registration_model extends CoreModel
{
    function init()
    {
        // Call the Model constructor
    }

    // Check unique username in "users" table
    function userUniqueUsername($text){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $text);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?FALSE:TRUE;
    }

    // Check unique email in "users" table
    function userUniqueEmail($text){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $text);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?FALSE:TRUE;
    }

    // Get user datails with email
    function userDetails($text){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $text);
        $query = $this->db->get();
        return $query->row_array();
    }

    // Get login match
    function loginMatch($username, $password){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get();
        return $query->row_array();
    }

    // Insert to "users" table
    function insertUser($data){
        $this->db->insert('users',$data);
    }

    // Select a row from "users" table
    function getUserByUniqueCode($user_unique_key,$active_code)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_unique_key',$user_unique_key);
        $this->db->where('active_code',$active_code);
        $query = $this->db->get();
        return $query->row_array();
    }

    // Update a user after click on activate code
    function activeUser($id){
        $this->db->set('status', 1);
        $this->db->set('active_register', 1);
        $this->db->where('user_id', $id);
        $this->db->update('users');
    }

}
