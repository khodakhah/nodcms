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

class BlogPostsCategory extends Model
{
    public function init()
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
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
