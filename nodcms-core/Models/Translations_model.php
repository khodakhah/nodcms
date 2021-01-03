<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Translations_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "translations";
        $primary_key = "translation_id";
        $fields = array(
            'translation_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'table_id'=>"int(10) unsigned DEFAULT NULL",
            'table_name'=>"varchar(255) DEFAULT NULL",
            'field_name'=>"varchar(255) DEFAULT NULL",
            'language_id'=>"int(10) unsigned DEFAULT NULL",
            'translated_text'=>"text",
        );
        $foreign_tables = null;
        $translation_fields = null;
        $unique = array(
            'table_id_table_name_field_name_language_id'=>array('table_id','table_name','field_name','language_id'),
        );
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}