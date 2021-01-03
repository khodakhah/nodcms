<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Translates_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "translates";
        $primary_key = "translate_id";
        $fields = array(
            'translate_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'language_id'=>"int(10) unsigned DEFAULT NULL",
            'translate_table'=>"varchar(255) DEFAULT NULL",
            'translate_table_key'=>"int(10) unsigned DEFAULT NULL",
            'translate_field'=>"varchar(255) DEFAULT NULL",
            'translate_text'=>"text",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}