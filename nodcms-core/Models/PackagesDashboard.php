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

class PackagesDashboard extends Model
{
    function init()
    {
        $table_name = "packages_dashboard";
        $primary_key = "package_id";
        $fields = array(
            'package_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'package_name'=>"varchar(255) DEFAULT NULL",
            'package_sort'=>"int(11) unsigned NOT NULL",
            'created_date'=>"int(11) unsigned NOT NULL",
            'active'=>"int(1) unsigned NOT NULL",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
