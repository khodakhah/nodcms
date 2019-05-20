<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Gallery_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "gallery";
        $primary_key = "gallery_id";
        $fields = array(
            'gallery_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'gallery_name'=>"varchar(255) DEFAULT NULL",
            'user_id'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'relation_id'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'data_type'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'gallery_order'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'status'=>"int(1) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = array("gallery_image");
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}