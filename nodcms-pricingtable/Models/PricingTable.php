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

class PricingTable extends Model
{
    public function init()
    {
        $table_name = "pricing_tables";
        $primary_key = "table_id";
        $fields = array(
            'table_id'=>"INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'table_url'=>"VARCHAR(255) NULL DEFAULT NULL",
            'table_name'=>"VARCHAR(255) NULL DEFAULT NULL",
            'table_price'=>"float UNSIGNED NULL DEFAULT NULL",
            'created_date'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
            'sort_order'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
            'table_public'=>"INT(1) UNSIGNED NOT NULL DEFAULT '0'",
            'table_highlight'=>"INT(1) UNSIGNED NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = array('title', 'btn_label');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
