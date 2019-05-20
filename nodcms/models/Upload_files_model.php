<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 17-May-19
 * Time: 9:24 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Upload_files_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "upload_files";
        $primary_key = "file_id";
        $fields = array(
            'file_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'user_id'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'session_id'=>"varchar(255) NOT NULL DEFAULT '0'",
            'unique_cookie'=>"varchar(255) DEFAULT NULL",
            'file_key'=>"varchar(255) DEFAULT NULL",
            'upload_key'=>"varchar(255) DEFAULT NULL",
            'remove_key'=>"varchar(255) DEFAULT NULL",
            'file_path'=>"varchar(255) DEFAULT NULL",
            'file_type'=>"varchar(255) DEFAULT NULL",
            'file_thumbnail'=>"varchar(255) DEFAULT NULL",
            'name'=>"varchar(255) DEFAULT NULL",
            'size'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'created_date'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'deadline'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'file_using'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'host'=>"varchar(255) NOT NULL",
            'download_validation'=>"varchar(255) NOT NULL",
            'download_password'=>"varchar(255) DEFAULT NULL",
            'download_count'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'last_download'=>"int(10) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}