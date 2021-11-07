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

class Translates extends Model
{
    function init()
    {
        $table_name = "translates";
        $primary_key = "translate_id";
        $fields = array(
            'translate_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'language_id'=>"int(10) unsigned DEFAULT NULL",
            'translate_table'=>"varchar(255) DEFAULT NULL",
            'translate_table_key'=>"int(10) unsigned DEFAULT NULL",
            'translate_field'=>"varchar(255) DEFAULT NULL",
            'translate_text'=>"text",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
