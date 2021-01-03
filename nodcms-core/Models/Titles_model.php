<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Titles_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "titles";
        $primary_key = "title_id";
        $fields = array(
            'title_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'title_caption'=>"varchar(255) DEFAULT NULL",
            'relation_id'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'data_type'=>"varchar(255) DEFAULT NULL",
            'language_id'=>"int(10) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}