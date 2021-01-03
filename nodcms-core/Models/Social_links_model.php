<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Social_links_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "social_links";
        $primary_key = "id";
        $fields = array(
            'id'=>"int(11) NOT NULL AUTO_INCREMENT",
            'url'=>"varchar(255) DEFAULT NULL",
            'title'=>"varchar(255) DEFAULT NULL",
            'class'=>"varchar(255) DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}