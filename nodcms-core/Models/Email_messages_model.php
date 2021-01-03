<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 23-Mar-19
 * Time: 4:50 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Email_messages_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "auto_email_messages";
        $primary_key = "msg_id";
        $fields = array(
            'msg_id'=> "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'code_key'=> "varchar(100) DEFAULT NULL",
            'subject'=> "varchar(255) DEFAULT NULL",
            'content'=> "text",
            'language_id'=> "int(10) unsigned NOT NULL DEFAULT '0'",
            'lang'=> "varchar(2) DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}