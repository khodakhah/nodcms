<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 20-May-19
 * Time: 2:41 PM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Blog_posts_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "blog_posts";
        $primary_key = "post_id";
        $fields = array(
            'post_id'=>"INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'post_name'=>"VARCHAR(255) NULL DEFAULT NULL",
            'post_image'=>"VARCHAR(255) NULL DEFAULT NULL",
            'post_categories'=>"VARCHAR(255) NULL DEFAULT NULL",
            'post_public'=>"INT(1) UNSIGNED NULL DEFAULT NULL",
            'post_private'=>"INT(1) UNSIGNED NULL DEFAULT NULL",
            'comment_status'=>"INT(1) UNSIGNED NULL DEFAULT NULL",
            'created_date'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
            'user_id'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
        );
        $foreign_tables = array("blog_comments");
        $translation_fields = array('title','description','keywords','content');
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}