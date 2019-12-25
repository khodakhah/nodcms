<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 15-Apr-19
 * Time: 2:57 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class About_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "about_profiles";
        $primary_key = "profile_id";
        $fields = array(
            'profile_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'profile_name'=>"varchar(255) DEFAULT NULL",
            'profile_image'=>"varchar(255) DEFAULT NULL",
            'profile_uri'=>"varchar(255) DEFAULT NULL",
            'profile_theme'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL",
            'public'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'order'=>"int(11) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = array("name", "keywords", "description", "content", "preview_description", "name_title");
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}