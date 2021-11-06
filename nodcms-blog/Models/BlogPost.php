<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

namespace NodCMS\Blog\Models;

use NodCMS\Core\Models\Model;

class BlogPost extends Model
{
    public function init()
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
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
