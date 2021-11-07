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

namespace NodCMS\Portfolio\Models;

use NodCMS\Core\Models\Model;

class Portfolio extends Model
{
    public function init()
    {
        $table_name = "portfolio";
        $primary_key = "portfolio_id";
        $fields = array(
            'portfolio_id'=>"INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
            'portfolio_name'=>"VARCHAR(255) NULL DEFAULT NULL",
            'portfolio_image'=>"VARCHAR(255) NULL DEFAULT NULL",
            'portfolio_public'=>"INT(1) UNSIGNED NULL DEFAULT NULL",
            'portfolio_date'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
            'created_date'=>"INT(11) UNSIGNED NULL DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = array('title','details');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
