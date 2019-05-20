<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Groups_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "groups";
        $primary_key = "group_id";
        $fields = array(
            'group_id'=>"tinyint(3) NOT NULL AUTO_INCREMENT",
            'group_name'=>"varchar(50) DEFAULT NULL",
        );
        $foreign_tables = array("gallery_image");
        $translation_fields = null;
        $defaults = array(
            array(1, 'Admin'),
	        array(20, 'Users')
        );
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}