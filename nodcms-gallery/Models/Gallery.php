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

namespace NodCMS\Gallery\Models;

use NodCMS\Core\Models\Model;

class Gallery extends Model
{
    public function init()
    {
        $table_name = "gallery";
        $primary_key = "gallery_id";
        $fields = array(
            'gallery_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'gallery_uri'=>"varchar(255) DEFAULT NULL",
            'gallery_name'=>"varchar(255) DEFAULT NULL",
            'gallery_image'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'sort_order'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'gallery_public'=>"int(1) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = array("gallery_images");
        $translation_fields = array('title', 'description', 'keywords', 'details');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
