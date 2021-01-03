<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 7/1/2016
 * Time: 11:28 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */


defined('BASEPATH') OR exit('No direct script access allowed');
class Public_model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
    }

    // Select from "auto_email_message" for notification emails
    function getAutoMessages($key, $language_id = NULL){
        if($language_id==NULL)
            $language_id = $this->language["language_id"];
        $this->db->select("*");
        $this->db->where('code_key',$key);
        $this->db->where('language_id',$language_id);
        $this->db->from('auto_email_messages');
        $query = $this->db->get();
        return $query->row_array();
    }

    function getAllLanguages($conditions = NULL){
        $this->db->select("*");
        $this->db->from('languages');
        if($conditions != NULL) $this->db->where($conditions);
        $this->db->order_by('default', 'DESC');
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getLanguage($language_id, $conditions = NULL){
        $this->db->select("*");
        $this->db->from('languages');
        $this->db->where('language_id', $language_id);
        if($conditions != NULL) $this->db->where($conditions);
        $query = $this->db->get();
        return $query->row_array();
    }

    function getDefaultLanguage($conditions = NULL){
        $this->db->select("*");
        $this->db->from('languages');
        $this->db->order_by('default', 'DESC');
        $this->db->order_by('sort_order', 'ASC');
        if($conditions != NULL) $this->db->where($conditions);
        $query = $this->db->get();
        return $query->row_array();
    }

    function getTitle($table, $id, $language_id = null)
    {
        if($language_id==null)
            $language_id = $this->language['language_id'];
        $this->db->select("*");
        $this->db->from('titles');
        $this->db->where("relation_id", $id);
        $this->db->where("data_type", $table);
        $this->db->where("language_id", $language_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    function getTitleTranslations($table, $id)
    {
        $this->db->select("*");
        $this->db->from('titles');
        $this->db->where("relation_id", $id);
        $this->db->where("data_type", $table);
        $query = $this->db->get();
        $result = $query->result_array();
        if(count($result)==0)
            return array();
        else
            return array_combine(array_column($result, "language_id"), $result);
    }

    function getAllUsers($conditions = null)
    {
        $this->db->select('*');
        $this->db->from('users');
        if($conditions!=null)
            $this->db->where($conditions);
        $query = $this->db->get();
        return $query->result_array();
    }

    function getUserDetails($id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id',$id);
        $query = $this->db->get();
        return $query->row_array();
    }

    function socialLinks()
    {
        $this->db->select('*');
        $this->db->from('social_links');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getSetting()
    {
        $this->db->select('*');
        $this->db->from('setting');
        $query = $this->db->get();
        return $query->row_array();
    }

    function getSettings($language_id = 0)
    {
        $this->db->select('*');
        $this->db->from('settings');
        $this->db->where('language_id', $language_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return array_combine(array_column($result, 'field_name'), array_column($result, 'field_value'));
    }

    function getMenu($key, $parent = null)
    {
        $this->db->select('*');
        $this->db->from('menu');
        $this->db->join('titles',"titles.relation_id = menu_id");
        $this->db->where('titles.data_type',"menu");
        $this->db->where('titles.language_id',$this->language["language_id"]);
        $this->db->where('public',1);
        $this->db->where('menu_key', $key);
        if($parent!==null)
            $this->db->where('sub_menu',$parent);
        $this->db->order_by('menu_order', "ASC");
        $query = $this->db->get();
        return $query->result_array();
    }

    function isUnique($value,$table,$filed,$except_field = null, $except_value = null, $conditions = null)
    {
        $this->db->select($filed);
        $this->db->from($table);
        $this->db->where($filed, $value);
        if($except_field!=null && $except_value!=null)
            $this->db->where("$except_field <>", $except_value);
        if($conditions!=null)
            $this->db->where($conditions);
        return $this->db->count_all_results();
    }

    function getFiles($file_ids)
    {
        if(is_array($file_ids)) $file_ids = join(',', $file_ids);
        $this->db->select('*, CONCAT("file-",file_id,"-",file_key) as file_download_uri, CONCAT("image-",file_id,"-",file_key) as file_image_uri');
        $this->db->from("upload_files");
        $this->db->where("file_id IN($file_ids)");
        $query = $this->db->get();
        return $query->result_array();
    }

    function getFile($file_id)
    {
        $this->db->select('*, CONCAT("file-",file_id,"-",file_key) as file_download_uri, CONCAT("image-",file_id,"-",file_key) as file_image_uri');
        $this->db->from("upload_files");
        $this->db->where('file_id',$file_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    function getFileDetails($conditions)
    {
        $this->db->select('*, CONCAT("file-",file_id,"-",file_key) as file_download_uri, CONCAT("image-",file_id,"-",file_key) as file_image_uri');
        $this->db->from("upload_files");
        $this->db->where($conditions);
        $query = $this->db->get();
        return $query->row_array();
    }

    function setFileUsing($file_id)
    {
        $this->db->update("upload_files", array('file_using'=>1), array('file_id'=>$file_id));
    }

    /**
     * Get result of rows of a table
     *
     * @param $table
     * @param null $conditions
     * @param null $limit
     * @param null $offset
     * @param null $order_by
     * @param $order
     * @return mixed
     */
    function getResult($table, $conditions = null, $limit = null, $offset = null, $order_by = null, $order = 'ASC')
    {
        $this->db->select('*')->from($table);
        if($conditions!=null){
            $this->searchDecode($conditions);
        }
        if($limit!=null){
            $this->db->limit($limit, $offset);
        }
        if($order_by!=null){
            $this->db->order_by($order_by, $order);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Get count of all rows of a table
     *
     * @param $table
     * @param null $conditions
     * @return int
     */
    function getCount($table, $conditions = null)
    {
        $this->db->select('count(*)')->from($table);
        if($conditions!=null){
            $this->searchDecode($conditions);
        }
        $query = $this->db->get();
        $result = $query->row_array();
        return count($result)!=0?$result['count(*)']:0;
    }

    /**
     * Get a row of a table
     *
     * @param $table
     * @param $key_filed
     * @param $value
     * @param null $conditions
     * @return mixed
     */
    function getDetails($table,$key_filed, $value, $conditions = null)
    {
        $this->db->select('*')->from($table);
        $this->db->where($key_filed, $value);
        if($conditions!=null){
            $this->searchDecode($conditions);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * @param $table
     * @param $offset
     * @param null $conditions
     * @param null $order_by
     * @param string $order
     * @return mixed
     */
    function getLastRow($table,$offset, $conditions = null, $order_by = null, $order = 'ASC')
    {
        $this->db->select('*')->from($table);
        if($conditions!=null){
            $this->searchDecode($conditions);
        }
        $this->db->limit(1,$offset);
        if($order_by!=null){
            $this->db->order_by($order_by, $order);
        }
        $query = $this->db->get();
        return $query->row_array();
    }


    /**
     * Decode search data
     *
     * @param $conditions
     */
    function searchDecode($conditions)
    {
        if(!is_array($conditions)){
            $this->db->where($conditions);
            return;
        }
        if(isset($conditions['search']) && count($conditions['search'])!=0){
            foreach ($conditions['search'] as $key=>$value){
                if($value == 'null'){
                    continue;
                }
                if(preg_match('/^([A-Za-z\_]+)\_\_(like|not_like|or_like|or_not_like)$/', $key, $parameters) == true){
                    if($value=='')
                        continue;
                    $values = explode(' ',$value);
                    $this->db->group_start();
                    foreach ($values as $item){
                        $this->db->$parameters[2]($parameters[1],$item);
                    }
                    $this->db->group_end();
                    continue;
                }
                $this->db->where($key,$value);
            }
            unset($conditions['search']);
        }
        if(count($conditions)!=0){
            $this->db->where($conditions);
        }
    }

    /**
     * Universal insert query
     *
     * @param $table
     * @param $data
     * @return mixed
     */
    function add($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * Universal insert query including created_date
     *
     * @param $table
     * @param $data
     * @return mixed
     */
    function addTo($table, $data)
    {
        $data['created_date'] = time();
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    /**
     * Universal update a table
     *
     * @param $table
     * @param $data
     * @param null $conditions
     */
    function edit($table, $data, $conditions = null)
    {
        $this->db->update($table, $data, $conditions);
    }

    /**
     * Update a table row
     *  - Check the conditions if exists it will update, if not it will insert
     *
     * @param $table
     * @param $data
     * @param $conditions
     */
    function updateTable($table, $data, $conditions)
    {
        $count = $this->db->select("*")->from($table)->where($conditions)->count_all_results();
        if($count){
            $this->db->update($table, $data, $conditions);
        }else{
            $this->db->insert($table, array_merge($data, $conditions));
        }
    }

    /**
     * Select a row from "translations"
     *
     * @param string $table
     * @param int $id
     * @param string $field
     * @param null|int $language_id
     * @return array
     */
    function getTranslation($table, $id, $field, $language_id = null)
    {
        if($language_id==null)
            $language_id = $this->language['language_id'];
        $this->db->select("*");
        $this->db->from('translations');
        $this->db->where("table_id", $id);
        $this->db->where("table_name", $table);
        $this->db->where("field_name", $field);
        $this->db->where("language_id", $language_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get a result of translations
     *
     * @param $table
     * @param $id
     * @param null $language_id
     * @return array
     */
    function getTranslations($table, $id, $language_id = null)
    {
        if($language_id==null)
            $language_id = $this->language['language_id'];
        $this->db->select("*");
        $this->db->from('translations');
        $this->db->where("table_id", $id);
        $this->db->where("table_name", $table);
        $this->db->where("language_id", $language_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return array_combine(array_column($result, "field_name"), array_column($result, "translated_text"));
    }
    /**
     * Insert/Update a translation row
     *
     * @param string $table
     * @param int $id
     * @param string $field
     * @param int $language_id
     * @param string $value
     */
    function updateTranslation($table, $id, $language_id, $field, $value)
    {
        $conditions = array(
            'table_name'=>$table,
            'table_id'=>$id,
            'field_name'=>$field,
            'language_id'=>$language_id,
        );
        $data = array('value'=>$value);
        $select = $this->db->select("*")->from("translations")->where($conditions)->get();
        $row = $select->row_array();
        if(count($row)!=0){
            $this->db->update("translations", $data, $conditions);
        }else{
            $this->db->insert("translations", array_merge($data, $conditions));
        }
    }

    function updateTranslations($table, $id, $values, $languages, $keys)
    {
        foreach ($languages as $language) {
            foreach ($keys as $field_name){
                if(!isset($values[$language['language_id']][$field_name]))
                    continue;
                $condition = array(
                    'table_name'=>$table,
                    'table_id'=>$id,
                    'field_name'=>$field_name,
                    'language_id'=>$language['language_id'],
                );
                $data = array(
                    'translated_text'=>$values[$language['language_id']][$field_name],
                );
                $translation_count = $this->db->from("translations")->where($condition)->count_all_results();
                // * Update the exists title
                if($translation_count!=0){
                    $this->db->update("translations", $data,$condition);
                }
                // * Create new title
                else{
                    $this->db->insert("translations", array_merge($condition,$data));
                }
            }
        }
    }
}