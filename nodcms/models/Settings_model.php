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
        $unique = array(
            'field_name_language_id'=>array("field_name","language_id"),
        );
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}