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

namespace NodCMS\Articles\Models;

use NodCMS\Core\Models\Model;

class Articles extends Model
{
    public function init()
    {
        $table_name = "article";
        $primary_key = "article_id";
        $fields = array(
            'article_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'name'=>"varchar(255) DEFAULT NULL",
            'article_uri'=>"varchar(255) DEFAULT NULL",
            'image'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL",
            'public'=>"int(11) unsigned NOT NULL",
            'top'=>"int(1) unsigned NOT NULL",
            'order'=>"int(11) unsigned DEFAULT NULL",
            'parent'=>"int(11) unsigned DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = array('title','description','keywords','content');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
