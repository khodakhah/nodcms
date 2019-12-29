<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Mojtaba Khodakhah.
 * Date: 26-Sep-18
 * Time: 10:49 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

class NodCMS_Model extends CI_Model
{
    private $table_name;
    private $primary_key;
    private $fields;
    private $foreign_tables;
    private $translation_fields;
    protected $unique_keys;
    protected $keys;

    /**
     * NodCMS_Model constructor.
     * @param $table_name
     * @param $primary_key
     * @param $fields
     * @param null $foreign_tables
     * @param null $translation_fields
     */
    function __construct($table_name, $primary_key, $fields, $foreign_tables = null, $translation_fields = null)
    {
        $this->table_name = $table_name;
        $this->primary_key = $primary_key;
        $this->fields = $fields;
        $this->foreign_tables = $foreign_tables;
        $this->translation_fields = $translation_fields;
    }

    /**
     * Insert to the database
     *
     * @param $data
     */
    function add($data)
    {
        foreach ($data as $key=>$val){
            if(!key_exists($key, $this->fields))
                unset($data[$key]);
        }
        if(key_exists('created_date', $this->fields) && !isset($data['created_date']))
            $data['created_date'] = time();
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * Update a database
     *
     * @param $id
     * @param $data
     */
    function edit($id, $data)
    {
        $this->db->update($this->table_name, $data, array($this->primary_key=>$id));
    }

    /**
     * Delete form a database
     *
     * @param $id
     */
    function remove($id)
    {
        // Delete Foreign Tables
        if($this->foreign_tables != null && is_array($this->foreign_tables) && count($this->foreign_tables)!=0){
            foreach ($this->foreign_tables as $table_name){
                $this->db->delete($table_name, array($this->primary_key=>$id));
            }
        }
        // Delete from main table
        $this->db->delete($this->table_name, array($this->primary_key=>$id));
        // Delete Translations
        if($this->translation_fields != null && is_array($this->translation_fields) && count($this->translation_fields)!=0){
            $this->db->where('table_name',$this->table_name);
            $this->db->where('table_id',$id);
            $this->db->where('field_name IN ("'.join('","', $this->translation_fields).'")');
            $this->db->delete("translations");
        }
    }

    /**
     * Delete a list rows from a table
     *
     * @param $conditions
     */
    function clean($conditions)
    {
        $all = $this->getAll($conditions);
        $this->db->delete($this->table_name, $conditions);
        // Delete Foreign Tables
        if($this->foreign_tables != null && is_array($this->foreign_tables) && count($this->foreign_tables)!=0){
            foreach ($this->foreign_tables as $table_name){
                foreach ($all as $item){
                    $this->db->delete($table_name, array($this->primary_key=>$item[$this->primary_key]));
                }
            }
        }
    }

    /**
     * Universal count query
     *
     * @param null|array $conditions
     * @return mixed
     */
    function getCount($conditions = null)
    {
        $this->db->from($this->table_name);
        if ($conditions != null)
            $this->searchDecode($conditions);
        return $this->db->count_all_results();
    }

    /**
     * Universal sum query
     *
     * @param $field
     * @param null $conditions
     * @return int
     */
    function getSum($field, $conditions = null)
    {
        $this->db->select("sum($field)");
        $this->db->from($this->table_name);
        if ($conditions != null)
            $this->searchDecode($conditions);
        $query = $this->db->get();
        $result = $query->row_array();
        return count($result)!=0?$result["sum($field)"]:0;
    }

    /**
     * Select a list from a database
     *
     * @param null|array $conditions
     * @param null|int $limit
     * @param int $page
     * @param null|array $sort_by
     * @return mixed
     */
    function getAll($conditions = null, $limit=null, $page=1, $sort_by = null)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        if($conditions!=null) {
            $this->searchDecode($conditions);
        }
        if($sort_by=="rand") {
            $this->db->order_by("RAND()");
        }
        elseif($sort_by!=null){
            $this->db->order_by($sort_by[0], $sort_by[1]);
        }
        if($limit!=null) $this->db->limit($limit, ($page-1)*$limit);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Select a row from a database
     *
     * @param $id
     * @param null $conditions
     * @return mixed
     */
    function getOne($id, $conditions = null)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        if($id!=null)
            $this->db->where($this->primary_key, $id);
        if ($conditions != null) $this->db->where($conditions);
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
                if(preg_match('/^([A-Za-z\_]+)(\_\_|\s)(like|not_like|or_like|or_not_like)$/', strtolower($key), $parameters) == true){
                    if($value=='')
                        continue;
                    $values = explode(' ',$value);
                    $this->db->group_start();
                    foreach ($values as $item){
                        $this->db->$parameters[3]($parameters[1],$item);
                    }
                    $this->db->group_end();
                    continue;
                }
                $this->db->where($key,$value);
            }
            unset($conditions['search']);
        }
        if(isset($conditions['trans_search']) && count($conditions['trans_search'])!=0){
            foreach ($conditions['trans_search'] as $key=>$value){
                if($value == 'null'){
                    continue;
                }
                if(preg_match('/^([a-z\_\.]+)(\_\_|\s)(like|not_like|or_like|or_not_like)$/', strtolower($key), $parameters) == true){
                    if($value=='')
                        continue;
                    $values = explode(' ',$value);
                    $this->db->group_start();
                    $connector = array(
                        'like'=>" AND translated_text ",
                        'not_like'=>" AND translated_text NOT ",
                        'or_like'=>" OR translated_text ",
                        'or_not_like'=>" OR translated_text NOT ",
                    );
                    $where = "";
                    foreach ($values as $item){
                        if($where!=""){
                            $where .= $connector[$parameters[3]];
                        }else{
                            $where .= "translated_text ";
                        }
                        $where .= "LIKE '$item'";
                    }
                    $where = $where!=""?"($where) AND":"";
                    $this->db->where($this->primary_key." IN (SELECT table_id FROM translations WHERE $where field_name = '$parameters[1]' AND table_name = '".$this->table_name."' AND language_id = ".$this->language['language_id'].")");
                    $this->db->group_end();
                    continue;
                }
                $this->db->where($this->primary_key." IN (SELECT table_id FROM translations WHERE field_name = '$key' AND translated_text = '$value' AND table_name = '".$this->table_name."' AND language_id = ".$this->language['language_id'].")");
            }
            unset($conditions['trans_search']);
        }
        foreach($conditions as $key=>$val){
            $matches = array();
            $is_match = preg_match('/^([a-z\_\.]+)[\s](IN|in|NOT IN|not in)/', $key, $matches);
            if(!$is_match)
                continue;
            if(is_array($val))
                $val = join(',', array_unique($val));
            $this->db->where("$matches[1] $matches[2] ($val)");
            unset($conditions[$key]);
        }
        if(count($conditions)!=0){
            $this->db->where($conditions);
        }
    }

    /**
     * Select a row from database and merging with related translations
     *
     * @param $id
     * @param null $conditions
     * @param null $language_id
     * @return array
     */
    function getOneTrans($id, $conditions = null, $language_id = null)
    {
        $default_trans = array_fill_keys($this->translation_fields,"");
        $first_result = $this->getOne($id, $conditions);
        if(!is_array($first_result))
            return $first_result;
        if($language_id==null)
            $language_id = $this->language['language_id'];
        $this->db->select("*");
        $this->db->from('translations');
        $this->db->where("table_id", $first_result[$this->primary_key]);
        $this->db->where("table_name", $this->table_name);
        $this->db->where('field_name IN ("'.join('","',$this->translation_fields).'")');
        $this->db->where("language_id", $language_id);
        $query = $this->db->get();
        $translations = $query->result_array();
        $second_result = array_combine(array_column($translations, 'field_name'),array_column($translations,'translated_text'));
        $second_result = array_merge($default_trans, $second_result);
        return array_merge($first_result, $second_result);
    }

    function getAllTrans($conditions = null, $limit=null, $page=1, $sort_by = null, $language_id = null)
    {

        $first_result = $this->getAll($conditions, $limit, $page, $sort_by);
        if($this->translation_fields == null)
            return $first_result;
        $default_trans = array_fill_keys($this->translation_fields,"");
        if($language_id==null)
            $language_id = $this->language['language_id'];
        foreach ($first_result as &$item){
            $this->db->select("*");
            $this->db->from('translations');
            $this->db->where("table_id", $item[$this->primary_key]);
            $this->db->where("table_name", $this->table_name);
            $this->db->where('field_name IN ("'.join('","',$this->translation_fields).'")');
            $this->db->where("language_id", $language_id);
            $query = $this->db->get();
            $translations = $query->result_array();
            $second_result = array_combine(array_column($translations, 'field_name'),array_column($translations,'translated_text'));
            $second_result = array_merge($default_trans, $second_result);
            $item = array_merge($item, $second_result);
        }
        return $first_result;
    }

    /**
     * Select a row from "translations"
     *
     * @param int $id
     * @param string $field
     * @param null|int $language_id
     * @return array
     */
    function getTranslation($id, $field, $language_id = null)
    {
        if($language_id==null)
            $language_id = $this->language['language_id'];
        $this->db->select("*");
        $this->db->from('translations');
        $this->db->where("table_id", $id);
        $this->db->where("table_name", $this->table_name);
        $this->db->where("field_name", $field);
        $this->db->where("language_id", $language_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Get a result of translations
     *
     * @param $id
     * @param null $language_id
     * @return array
     */
    function getTranslations($id, $language_id = null)
    {
        if($language_id==null)
            $language_id = $this->language['language_id'];
        $this->db->select("*");
        $this->db->from('translations');
        $this->db->where("table_id", $id);
        $this->db->where("table_name", $this->table_name);
        $this->db->where("language_id", $language_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return array_combine(array_column($result, "field_name"), array_column($result, "translated_text"));
    }

    /**
     * Insert/Update a translation row
     *
     * @param $id
     * @param $value
     * @param $field
     * @param $language_id
     */
    function updateTranslation($id, $value, $field, $language_id)
    {
        if(!in_array($field, $this->translation_fields)){
            show_error("The field '$field' doesn't exists in the translation_files of $this->table_name.", 500);
            return;
        }
        $condition = array(
            'table_name'=>$this->table_name,
            'table_id'=>$id,
            'field_name'=>$field,
            'language_id'=>$language_id,
        );
        $data = array('translated_text'=>$value);

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

    /**
     * Update translations of a field
     *
     * @param $id
     * @param $values
     * @param $languages
     */
    function updateTranslations($id, $values, $languages)
    {
        foreach ($languages as $language) {
            foreach ($this->translation_fields as $field_name){
                if(!isset($values[$language['language_id']][$field_name]))
                    continue;
                $this->updateTranslation($id, $values[$language['language_id']][$field_name],$field_name,$language['language_id']);
            }
        }
    }

    /**
     * Get the table_name
     *
     * @return mixed
     */
    function tableName()
    {
        return $this->table_name;
    }

    /**
     * Get the primary_key
     *
     * @return mixed
     */
    function primaryKey()
    {
        return $this->primary_key;
    }

    /**
     * Get the fields
     *
     * @return mixed
     */
    function fields()
    {
        return $this->fields;
    }

    /**
     * Get the foreign_tables
     *
     * @return null
     */
    function foreignTables()
    {
        return $this->foreign_tables;
    }

    /**
     * Get the translation_fields
     *
     * @return null
     */
    function translationFields()
    {
        return $this->translation_fields;
    }

    /**
     * Select data from a table to use in statistics
     *
     * @param $type
     * @param null $sum
     * @param null $conditions
     * @return array|null
     */
    function getDateStatistic($type, $sum = null, $conditions = null)
    {
        $types = array(
            'weekdays'=>array(
                'order_by'=>"DAYNAME(FROM_UNIXTIME(created_date))",
                'select'=>"DAYNAME(FROM_UNIXTIME(max(created_date))) as date_label"
            ),
            'daily'=>array(
                'order_by'=>"DATE_FORMAT(FROM_UNIXTIME(created_date),'%d')",
                'select'=>"DATE_FORMAT(FROM_UNIXTIME(max(created_date)),'%d') as date_label"
            ),
            'monthly'=>array(
                'order_by'=>"MONTH(FROM_UNIXTIME(created_date))",
                'select'=>"MONTH(FROM_UNIXTIME(max(created_date))) as date_label"
            ),
        );
        if(!key_exists($type, $types)){
            show_error("The statistic \"$type\" has not been defined.");
            return null;
        }
        $this->db->select("count(*) as count_value, ".($sum!=null?"sum($sum) as sum_value, ":"").$types[$type]['select']);
        $this->db->from($this->table_name);
        if($conditions!=null) {
            $this->searchDecode($conditions);
        }
        $this->db->order_by($types[$type]['order_by']);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Create table
     *
     * @return mixed
     */
    public function installTable()
    {
        $query_items = array();
        foreach($this->fields as $field_name => $field_codes) {
            $query_items[] = "`$field_name` $field_codes";
        }
        if(!empty($this->primary_key))
            $query_items[] = "PRIMARY KEY (`{$this->primary_key}`)";

        if(!empty($this->unique_keys)){
            foreach($this->unique_keys as $name=>$value) {
                $_keys = join('`, `', $value);
                $_name = is_string($name)?$name:join('', $value);
                $query_items[] = "UNIQUE INDEX `{$_name}` (`{$_keys}`)";
            }
        }

        if(!empty($this->keys)){
            foreach($this->keys as $name=>$value) {
                $_keys = join('`, `', $value);
                $_name = is_string($name)?$name:join('', $value);
                $query_items[] = "KEY `{$_name}` (`{$_keys}`)";
            }
        }

        $query_items = join(',', $query_items);
        $auto_increment = !empty($this->primary_key)?" AUTO_INCREMENT=1":"";
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table_name}` ($query_items) ENGINE=InnoDB{$auto_increment} DEFAULT CHARSET=utf8;";
        return $this->db->query($sql);
    }

    /**
     * Drop table
     *
     * @return bool
     */
    public function dropTable()
    {
        $sql = "DROP TABLE IF EXISTS `{$this->table_name}`;";
        return $this->db->query($sql);
    }

    /**
     * Return true if the table exists
     *
     * @return bool
     */
    public function tableExists()
    {
        $sql = "show tables like '{$this->table_name}';";
        $query = $this->db->query($sql);
        return count($query->result_array()) > 0;
    }
}