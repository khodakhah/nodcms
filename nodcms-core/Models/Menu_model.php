<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Menu_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "menu";
        $primary_key = "menu_id";
        $fields = array(
            'menu_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'menu_name'=>"varchar(255) DEFAULT NULL",
            'menu_icon'=>"varchar(255) DEFAULT NULL",
            'sub_menu'=>"int(10) unsigned DEFAULT '0'",
            'created_date'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'menu_order'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'public'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'menu_url'=>"varchar(300) DEFAULT NULL",
            'menu_key'=>"varchar(300) DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}