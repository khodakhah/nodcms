<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Gallery_image_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "gallery_image";
        $primary_key = "image_id";
        $fields = array(
            'image_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'relation_id'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'data_type'=>"varchar(200) DEFAULT NULL",
            'gallery_id'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'image'=>"varchar(255) DEFAULT NULL",
            'name'=>"varchar(255) DEFAULT NULL",
            'size'=>"float unsigned NOT NULL DEFAULT '0'",
            'width'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'height'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'count_view'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'created_date'=>"int(10) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}