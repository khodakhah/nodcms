<?php
/**
 * Created by Mojtaba Khodakhah.
 * Date: 27-May-19
 * Time: 11:22 AM
 * Project: NodCMS
 * Website: http://www.nodcms.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');
class Blog_posts_category_model extends NodCMS_Model
{
    function __construct()
    {
        $table_name = "blog_posts_categories";
        $primary_key = "post_cat_id";
        $fields = array(
            'post_cat_id'=>"int(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'category_id'=>"int(11) UNSIGNED NOT NULL DEFAULT '0'",
            'post_id'=>"int(11) UNSIGNED NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::__construct($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}