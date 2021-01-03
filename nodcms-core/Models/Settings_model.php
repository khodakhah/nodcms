<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Settings_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "settings";
        $primary_key = "id";
        $fields = array(
            'id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'language_id'=>"int(11) unsigned DEFAULT '0'",
            'field_name'=>"varchar(200) DEFAULT NULL",
            'field_value'=>"text",
        );
        $foreign_tables = null;
        $translation_fields = null;
        $this->unique_keys = array(array("language_id","field_name"));
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * Update settings storage
     *
     * @param null|array $data
     * @param int $language_id
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
}