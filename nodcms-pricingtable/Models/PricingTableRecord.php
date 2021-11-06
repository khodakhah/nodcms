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

namespace NodCMS\Pricingtable\Models;

use NodCMS\Core\Models\Model;

class PricingTableRecord extends Model
{
    public function init()
    {
        $table_name = "pricing_tables_records";
        $primary_key = "record_id";
        $fields = array(
            'record_id'=>"INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'table_id'=>"INT(11) UNSIGNED NOT NULL DEFAULT '0'",
            'record_name'=>"VARCHAR(255) NULL DEFAULT NULL",
            'sort_order'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
            'record_public'=>"INT(1) UNSIGNED NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = array('label');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
