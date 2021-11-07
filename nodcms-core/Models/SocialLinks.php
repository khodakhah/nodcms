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

class SocialLinks extends Model
{
    function init()
    {
        $table_name = "social_links";
        $primary_key = "id";
        $fields = array(
            'id'=>"int(11) NOT NULL AUTO_INCREMENT",
            'url'=>"varchar(255) DEFAULT NULL",
            'title'=>"varchar(255) DEFAULT NULL",
            'class'=>"varchar(255) DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
