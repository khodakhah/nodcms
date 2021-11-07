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

namespace NodCMS\Core\Models;

class Images extends Model
{
    function init()
    {
        $table_name = "images";
        $primary_key = "image_id";
        $fields = array(
            'image_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'name'=>"varchar(255) DEFAULT NULL",
            'image'=>"varchar(255) DEFAULT NULL",
            'width'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'height'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'size'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'root'=>"varchar(255) DEFAULT NULL",
            'folder'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'user_id'=>"int(11) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
