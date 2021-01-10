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


class Nodcms_general_model extends CoreModel
{
    function get_website_info()
    {
        $this->db->select('*');
        $this->db->from('setting');
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }
    function get_website_info_options($language_id=null)
    {
        $this->db->select('*');
        $this->db->from('setting_options_per_lang');
        if($language_id!=null)
            $this->db->where('language_id',$language_id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }

    function get_menu()
    {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->join('titles',"titles.relation_id=menu_id");
        $this->db->where('titles.data_type',"menu");
        $this->db->where('titles.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('public',1);
        $this->db->order_by('menu_order', "ASC");
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_languages()
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('public',1);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_language_by_code($code)
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('public',1);
        $this->db->where('code',$code);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }
    function get_language_default()
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('public',1);
        $this->db->where('default',1);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)?$return[0]:0;
    }

    function get_preview_pages()
    {
        $this->db->select('*');
        $this->db->from('page');
        $this->db->join('titles',"titles.relation_id=page_id");
        $this->db->where('titles.data_type',"page");
        $this->db->where('titles.language_id',$_SESSION["language"]["language_id"]);
        $this->db->where('preview',1);
        $this->db->where('public',1);
        $this->db->order_by('page_order',"ASC");
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_user_by_email_hash_and_active_code($email_hash,$active_code)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email_hash',$email_hash);
        $this->db->where('active_code',$active_code);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }
    function get_user_by_email_and_password($email,$password)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email',$email);
        $this->db->where('password',$password);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }
    function check_username_exists($text){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username',$text);
        $this->db->where('active_register',1);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?1:0;
    }
    function check_email_exists($text,$active_register=true){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email',$text);
        if($active_register==true)
            $this->db->where('active_register',1);
        elseif($active_register==false)
            $this->db->where('active_register',0);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?1:0;
    }
    function update_user_by_email($data,$email){
        $this->db->where('email',$email);
        $this->db->update('users',$data);
    }
    function insert_user($data){
        $this->db->insert('users',$data);
    }

    function update_user_login($user_id,$keep_me_time,$user_agent){
        $this->db->where('user_id',$user_id);
        $this->db->set('keep_me_time',$keep_me_time);
        $this->db->set('user_agent',$user_agent);
        $this->db->update('users');
    }
    function user_set_new_password($user_id,$new_password){
        $this->db->where('user_id',$user_id);
        $this->db->set('password',$new_password);
        $this->db->set('active_register',1);
        $this->db->set('active',1);
        $this->db->set('active_code',"");
        $this->db->update('users');
    }
    function user_edit_password($user_id,$new_password){
        $this->db->where('user_id',$user_id);
        $this->db->set('password',$new_password);
        $this->db->update('users');
    }
}