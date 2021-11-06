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

class BlogCategory extends Model
{
    public function init()
    {
        $table_name = "blog_categories";
        $primary_key = "category_id";
        $fields = array(
            'category_id'=>"INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'category_name'=>"VARCHAR(255) NULL DEFAULT NULL",
            'category_image'=>"VARCHAR(255) NULL DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = array('title');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    function idExists($values)
    {
        $builder = $this->getBuilder();
        $builder->select('category_id');
        $builder->where("category_id IN ($values)");
        $query = $builder->get();
        $result = $query->getResultArray();
        return $result!=null?array_column($result, "category_id"):array();
    }
}
