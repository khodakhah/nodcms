<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Articles_model extends NodCMS_Model
{    
    function __construct()
    {
        $table_name = "article";
        $primary_key = "article_id";
        $fields = array(
            'article_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'name'=>"varchar(255) DEFAULT NULL",
            'article_uri'=>"varchar(255) DEFAULT NULL",
            'image'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL",
            'public'=>"int(11) unsigned NOT NULL",
            'top'=>"int(1) unsigned NOT NULL",
            'order'=>"int(11) unsigned DEFAULT NULL",
            'parent'=>"int(11) unsigned DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = array('title','description','keywords','content');
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}