<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 25-May-19
 * Time: 4:24 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Gallery_images_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "gallery_images";
        $primary_key = "image_id";
        $fields = array(
            'image_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'gallery_id'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'image_name'=>"varchar(255) DEFAULT NULL",
            'image_url'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = array('title');
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

}