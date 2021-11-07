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

class Nodcms_admin_model extends CoreModel
{
    /**
     * Update settings storage
     *
     * @param null $data
     * @return bool
     */
    function updateSettings($data = NULL, $language_id = 0){
        if($data != NULL && is_array($data) && count($data)!=0)
            foreach($data as $name=>$value){
                $sql = 'INSERT INTO settings'.
                '  (field_name, language_id, field_value)'.
                'VALUES'.
                '  (?, ?, ?)'.
                'ON DUPLICATE KEY UPDATE'.
                '  field_value = ?';
                $this->db->query($sql, array($name, $language_id, $value, $value));
            }
        return true;
    }

    function get_all_language($conditions=null){
        $this->db->select("*");
        $this->db->from('languages');
        if($conditions!=null) $this->db->where($conditions);
        $this->db->order_by('sort_order','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_language_detail($id)
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('language_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }
    function language_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if (!isset($data['rtl'])) {
            $data['rtl']=0;
        }
        if (!isset($data['public'])) {
            $data['public']=0;
        }
        if (!isset($data['default'])) {
            $data['default']=0;
        }

        if ($this->session->userdata('group') != 1 && isset($data['default'])) {
            unset($data['default']);
        }
        if ($this->session->userdata('group') != 1 && isset($data['public'])) {
            unset($data['public']);
        }

        if(isset($data['default']) && $data['default']==1)
        {
            $this->db->set('default',0);
            $this->db->update('languages');
        }

        if($id!=null) // update
        {
            $this->db->where('language_id',$id);
            $this->db->update('languages',$data);
        }
        else	//add
        {
            $data['created_date']=time();
            $this->db->insert('languages',$data);
            $id = $this->db->insert_id();
        }
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
            return 0;
        else
            return $id;
    }

    function get_all_menu($conditions = null)
    {
        $this->db->select('*');
        $this->db->from('menu');
        if($conditions != null) $this->db->where($conditions);
        $this->db->order_by('menu_order', "ASC");
        $query = $this->db->get();
        return $query->result_array();
    }
    function getMenuDetail($id)
    {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->where('menu_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    function menu_manipulate($data,$id=0)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['default'])) {
            unset($data['default']);
        }
        if ($this->session->userdata('group') != 1 && isset($data['public'])) {
            unset($data['public']);
        }

        if(isset($data["titles"]) && count($data["titles"])!=0){
            $titles = $data["titles"];
            unset($data["titles"]);
        }

        if($id!=0) // update
        {
            $this->db->where('menu_id',$id);
            $this->db->update('menu',$data);
        }
        else	//add
        {
            $data['created_date']=time();
            $this->db->insert('menu',$data);
            $id=$this->db->insert_id();
        }

        if(isset($titles)){
            $this->db->delete('titles', array("relation_id"=>$id,"data_type"=>"menu"));
            foreach ($titles as $key=>$value) {
                if($value!='') $this->db->insert('titles',array("language_id"=>$key,"title_caption"=>$value,"relation_id"=>$id,"data_type"=>"menu"));
            }
        }

        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_currency()
    {
        $this->db->select("*");
        $this->db->from('currency');
        $this->db->order_by('default','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_currency_detail($id)
    {
        $this->db->select('*');
        $this->db->from('currency');
        $this->db->where('currency_id', $id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:null;
    }
    function currency_manipulate($data,$id=null)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['default'])) {
            unset($data['default']);
        }
        if ($this->session->userdata('group') != 1 && isset($data['status'])) {
            unset($data['status']);
        }

        if(isset($data['default']))
        {
            $this->db->set('default',0);
            $this->db->update('currency');
        }
        $data['last_updated']=time();

        if($id!=null) // update
        {
            $this->db->where('currency_id',$id);
            $this->db->update('currency',$data);
        }
        else	//add
        {
            $this->db->insert('currency',$data);
        }
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    function get_all_groups(){
        $this->db->select("*");
        $this->db->from('groups');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get all users
     * - for pagination pages
     *
     * @param int $per_page
     * @return mixed
     */
    function getLastRegistrations($per_page = 10)
    {
        $this->db->select("*");
        $this->db->from('users');
        $this->db->join('groups','users.group_id = groups.group_id');
        $this->db->order_by('user_id','DESC');
        $this->db->limit($per_page);
        $query = $this->db->get();
        return $query->result_array();
    }
    /**
     * Get all users
     * - for pagination pages
     *
     * @param null $page
     * @param int $per_page
     * @return mixed
     */
    function getAllUser($page = null, $per_page = 10)
    {
        $this->db->select("*");
        $this->db->from('users');
        $this->db->join('groups','users.group_id = groups.group_id');
        $this->db->order_by('users.group_id','ASC');
        $this->db->order_by('user_id','DESC');
        if($page != null){
            $this->db->limit($per_page, $page*$per_page);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get count of all users
     *
     * @return int
     */
    function countAllUser()
    {
        $this->db->select("count(*)");
        $this->db->from('users');
        $query = $this->db->get();
        $result = $query->row_array();
        return count($result)!=0?$result["count(*)"]:0;
    }

    function getUserDetail($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('groups','users.group_id = groups.group_id');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    function userManipulate($data,$id=null)
    {
        $this->db->trans_start();

        if ($this->session->userdata('group') != 1 && isset($data['status'])) {
            unset($data['status']);
        }
        if (isset($data['password']) && $data["password"]=="") {
            unset($data['password']);
        }elseif(isset($data['password']) && $data['password']!=""){
            $data['password']=md5($data['password']);
        }

        if($id!=null) // update
        {
            $this->db->where('user_id',$id);
            $this->db->update('users',$data);
        }
        else	//add
        {
            $data['created_date'] = time();
            $this->db->insert('users',$data);
        }
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    /**
     * @param $id
     */
    function userDelete($id)
    {
        $this->db->delete('users', array('user_id'=>$id));
    }

    function get_gallery($data_type=null,$relation_id=null)
    {
        $this->db->select("*");
        $this->db->from('gallery');
        $this->db->where('data_type',$data_type);
        $this->db->where('relation_id',$relation_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_gallery_detail($gallery_id)
    {
        $this->db->select("*");
        $this->db->from('gallery');
        $this->db->where('gallery_id',$gallery_id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }
    function get_insert_gallery($data){
        $this->db->trans_start();
        $data['created_date']=time();
        $this->db->insert('gallery',$data);
        $id=$this->db->insert_id();
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return $id;
        }
    }
    function gallery_manipulate($data,$id=null)
    {
        $this->db->trans_start();
        if(!isset($data['status'])){
            $data['status']=0;
        }
        if ($this->session->userdata('group') != 1 && isset($data['status'])) {
            unset($data['status']);
        }
        if(isset($data["titles"]) && count($data["titles"])!=0){
            $titles = $data["titles"];
        }
        unset($data["titles"]);

        if($id!=null && $id!=0) // update
        {
            $this->db->where('gallery_id',$id);
            $data['updated_date']=time();
            $this->db->update('gallery',$data);
        }
        else	//add
        {
            $data['user_id']=$this->session->userdata['user_id'];
            $data['created_date']=time();
            $this->db->insert('gallery',$data);
            $id=$this->db->insert_id();

        }
        $this->db->delete('titles', array("relation_id"=>$id,"data_type"=>"gallery"));
        if(isset($titles)){
            foreach ($titles as $key=>$value) {
                if($value!='') $this->db->insert('titles',array("language_id"=>$key,"title_caption"=>$value,"relation_id"=>$id,"data_type"=>"gallery"));
            }
        }

        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }
    function get_gallery_image($gallery_id)
    {
        $this->db->select("*");
        $this->db->from('gallery_image');
        $this->db->where('gallery_id',$gallery_id);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_gallery_image_detail($gallery_image_id)
    {
        $this->db->select("*");
        $this->db->from('gallery_image');
        $this->db->where('image_id',$gallery_image_id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }
    function get_insert_gallery_image($data){
        $this->db->trans_start();
        $data['created_date']=time();
        $this->db->insert('gallery_image',$data);
        $id=$this->db->insert_id();
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return $id;
        }
    }
    function get_all_images(){
        $this->db->select("*");
        $this->db->from('images');
        $this->db->order_by('image_id','DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    function insert_image($data){
        $this->db->trans_start();
        $data['created_date']=time();
        $data['user_id']=$this->session->userdata['user_id'];
        $this->db->insert('images',$data);
        $id=$this->db->insert_id();
        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return $id;
        }
    }
    function get_image_detail($image_id)
    {
        $this->db->select("*");
        $this->db->from('images');
        $this->db->where('image_id',$image_id);
        $query = $this->db->get();
        $return = $query->result_array();
        return count($return)!=0?$return[0]:0;
    }
    function get_all_titles($data_type=null,$relation_id=null)
    {
        $this->db->select("*");
        $this->db->from('titles');
        $this->db->where('data_type',$data_type);
        $this->db->where('relation_id',$relation_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function count_gallery($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('gallery');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function count_gallery_image($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('gallery_image');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function count_uploaded_image($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('images');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function count_users($where = null)
    {
        $this->db->select("count(*)");
        $this->db->from('users');
        if($where!=null){
            foreach($where as $key=>$value){
                $this->db->where($key,$value);
            }
        }
        $this->db->where("status",1);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }
    function get_all_setting_options(){
        $this->db->select("*");
        $this->db->from('setting_options_per_lang');
        $query = $this->db->get();
        return $query->result_array();
    }
    function check_setting_options($language_id){
        $this->db->select("count(*)");
        $this->db->from('setting_options_per_lang');
        $this->db->where('language_id',$language_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return isset($result[0]["count(*)"])?$result[0]["count(*)"]:0;
    }

    function insert_setting_options($language_id,$data){
        $data['language_id']= $language_id;
        $this->db->insert('setting_options_per_lang', $data);
    }

    function getSocialLinks()
    {
        $this->db->select("*");
        $this->db->from('social_links');
        $query = $this->db->get();
        return $query->result_array();
    }
    function getSocialLink($id)
    {
        $this->db->select("*");
        $this->db->from('social_links');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    function socialLinksManipulate($data,$id=null)
    {
        $this->db->trans_start();

        if($id!=null) // update
        {
            $this->db->where('id',$id);
            $this->db->update('social_links', $data);
        }
        else	//add
        {
            $this->db->insert('social_links',$data);
            $id=$this->db->insert_id();
        }

        $this->db->trans_complete();
        // end TRANSACTION
        if ($this->db->trans_status() == FALSE)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    /**
     * Select all rows from "article" table
     *
     * @param null $conditions
     * @return mixed
     */
    function getArticles($conditions = null)
    {
        $this->db->select("*");
        $this->db->from('article');
        if($conditions != NULL) $this->db->where($conditions);
        $this->db->order_by('article_id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }


    /**
     * @param $table
     * @param $id
     * @param $values
     * @param $languages
     */
    function updateTitles($table, $id, $values, $languages)
    {
        foreach ($languages as $language) {
            $titles_condition = array(
                'data_type'=>$table,
                'relation_id'=>$id,
                'language_id'=>$language['language_id'],
            );
            $this->db->from("titles");
            $this->db->where($titles_condition);
            $translation_count = $this->db->count_all_results();
            // * Update the exists title
            if($translation_count!=0){
                $this->db->update("titles", array(
                    'title_caption'=>$values[$language['language_id']],
                ),$titles_condition);
            }
            // * Create new title
            else{
                $this->db->insert("titles", array(
                    'data_type'=>$table,
                    'relation_id'=>$id,
                    'language_id'=>$language['language_id'],
                    'title_caption'=>$values[$language['language_id']],
                ));
            }
        }
    }

    /**
     * Remove a list and related titles
     *
     * @param $table
     * @param $key
     * @param $list
     */
    function removeListWithTitle($table, $key,$list){
        $list_items = join(',',$list);
        $this->db->delete($table, "$key IN ($list_items)");
        $translation_conditions = "relation_id IN ($list_items) AND data_type = '$table'";
        $this->db->delete("titles", $translation_conditions);
    }

    function updateTable($table, $data, $conditions)
    {
        $this->db->update($table, $data, $conditions);
    }

    function insertTable($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * Get the size of some uploaded files
     *
     * @param null|array $conditions
     * @return mixed
     */
    function getUploadedSize($conditions = null)
    {
        $this->db->select('sum(size)');
        $this->db->from("upload_files");
        if($conditions!=null) $this->db->where($conditions);
        $query = $this->db->get();
        $row = $query->row_array();
        return count($row)!=0?$row['sum(size)']:0;
    }

    /**
     * Check user exists
     *
     * @param $conditions
     * @param $id
     * @return bool
     */
    function checkUserUnique($conditions, $id){
        $this->db->select("*");
        $this->db->from('users');
        $this->db->where($conditions);
        $this->db->where('user_id !=', $id);
        $query = $this->db->get();
        return (count($query->result_array())!=0)?TRUE:FALSE;
    }
}
