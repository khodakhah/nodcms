<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 25-May-19
 * Time: 4:23 PM
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
            'gallery_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'gallery_uri'=>"varchar(255) DEFAULT NULL",
            'gallery_name'=>"varchar(255) DEFAULT NULL",
            'gallery_image'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'sort_order'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'gallery_public'=>"int(1) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = array("gallery_images");
        $translation_fields = array('title', 'description', 'keywords', 'details');
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}