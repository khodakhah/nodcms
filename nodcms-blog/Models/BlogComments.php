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

class BlogComments extends Model
{
    public function init()
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
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
