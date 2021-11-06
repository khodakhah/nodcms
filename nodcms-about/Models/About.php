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

namespace NodCMS\About\Models;

use NodCMS\Core\Models\Model;

class About extends Model
{
    public function init()
    {
        $table_name = "about_profiles";
        $primary_key = "profile_id";
        $fields = array(
            'profile_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'profile_name'=>"varchar(255) DEFAULT NULL",
            'profile_image'=>"varchar(255) DEFAULT NULL",
            'profile_uri'=>"varchar(255) DEFAULT NULL",
            'profile_theme'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL",
            'public'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'order'=>"int(11) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = array("name", "keywords", "description", "content", "preview_description", "name_title");
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
