<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 24-Dec-19
 * Time: 9:59 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Sessions_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "ci_sessions";
        $primary_key = "";
        $fields = array(
            'id'=>"varchar(40) DEFAULT NULL",
            'ip_address'=>"varchar(45) DEFAULT NULL",
            'timestamp'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'data'=>"blob",
        );
        $foreign_tables = null;
        $translation_fields = null;
        $this->keys = array('ci_sessions_timestamp'=>array("timestamp"));
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}