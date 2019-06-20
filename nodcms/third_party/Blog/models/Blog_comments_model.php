<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 27-May-19
 * Time: 11:22 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

class Blog_comments_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "blog_comments";
        $primary_key = "comment_id";
        $fields = array(
            'comment_id'=>"int(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'session_id'=>"varchar(255) NULL DEFAULT NULL",
            'reply_to'=>"int(11) UNSIGNED NOT NULL DEFAULT '0'",
            'admin_side'=>"int(1) UNSIGNED NOT NULL DEFAULT '0'",
            'post_id'=>"int(11) UNSIGNED NOT NULL DEFAULT '0'",
            'user_id'=>"int(11) UNSIGNED NOT NULL DEFAULT '0'",
            'language_id'=>"int(11) UNSIGNED NOT NULL DEFAULT '0'",
            'comment_name'=>"varchar(255) NULL DEFAULT NULL",
            'comment_email'=>"varchar(255) NULL DEFAULT NULL",
            'comment_notification'=>"int(1) NOT NULL DEFAULT '0'",
            'comment_content'=>"text NULL DEFAULT NULL",
            'created_date'=>"int(11) UNSIGNED NOT NULL DEFAULT '0'",
            'comment_read'=>"int(1) UNSIGNED NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}