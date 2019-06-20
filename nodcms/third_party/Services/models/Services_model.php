<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 11:41 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

class Services_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "services";
        $primary_key = "service_id";
        $fields = array(
            'service_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'service_name'=>"varchar(255) DEFAULT NULL",
            'service_uri'=>"varchar(255) DEFAULT NULL",
            'service_image'=>"varchar(255) DEFAULT NULL",
            'service_icon'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL",
            'service_public'=>"int(11) unsigned NOT NULL",
            'service_price'=>"float unsigned NOT NULL",
            'sort_order'=>"int(11) unsigned DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = array('title','home_preview','description','keywords','content');
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}