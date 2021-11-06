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

namespace NodCMS\Services\Models;

use NodCMS\Core\Models\Model;

class Services extends Model
{
    public function init()
    {
        $table_name = "services";
        $primary_key = "service_id";
        $fields = array(
            'service_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'service_name'=>"varchar(255) DEFAULT NULL",
            'service_uri'=>"varchar(255) DEFAULT NULL",
            'service_image'=>"varchar(255) DEFAULT NULL",
            'service_icon'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL",
            'service_public'=>"int(11) unsigned NOT NULL",
            'service_price'=>"float unsigned NOT NULL",
            'sort_order'=>"int(11) unsigned DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = array('title','home_preview','description','keywords','content');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
