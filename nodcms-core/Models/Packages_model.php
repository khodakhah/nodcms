<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 23-Jan-19
 * Time: 12:03 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Packages_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "packages";
        $primary_key = "package_id";
        $fields = array(
            'package_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'package_name'=>"varchar(255) DEFAULT NULL",
            'package_sort'=>"int(11) unsigned NOT NULL",
            'created_date'=>"int(11) unsigned NOT NULL",
            'active'=>"int(1) unsigned NOT NULL",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}