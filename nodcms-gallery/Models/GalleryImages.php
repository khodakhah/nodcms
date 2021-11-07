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

class GalleryImages extends Model
{
    public function init()
    {
        $table_name = "gallery_images";
        $primary_key = "image_id";
        $fields = array(
            'image_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'gallery_id'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'image_name'=>"varchar(255) DEFAULT NULL",
            'image_url'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = array('title');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
