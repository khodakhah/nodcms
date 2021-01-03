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

namespace NodCMS\Core\Models;

class Registration_model extends CI_Model
{
    function __construct()
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