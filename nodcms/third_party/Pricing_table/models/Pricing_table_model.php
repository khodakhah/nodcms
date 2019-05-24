<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 22-May-19
 * Time: 8:46 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Pricing_table_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "pricing_tables";
        $primary_key = "table_id";
        $fields = array(
            'table_id'=>"INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'table_url'=>"VARCHAR(255) NULL DEFAULT NULL",
            'table_name'=>"VARCHAR(255) NULL DEFAULT NULL",
            'table_price'=>"float UNSIGNED NULL DEFAULT NULL",
            'created_date'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
            'sort_order'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
            'table_public'=>"INT(1) UNSIGNED NOT NULL DEFAULT '0'",
            'table_highlight'=>"INT(1) UNSIGNED NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = array('title', 'btn_label');
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}