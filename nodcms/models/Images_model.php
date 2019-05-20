<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Images_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "images";
        $primary_key = "image_id";
        $fields = array(
            'image_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'name'=>"varchar(255) DEFAULT NULL",
            'image'=>"varchar(255) DEFAULT NULL",
            'width'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'height'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'size'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'root'=>"varchar(255) DEFAULT NULL",
            'folder'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'user_id'=>"int(11) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}