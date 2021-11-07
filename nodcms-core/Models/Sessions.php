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

class Sessions extends Model
{
    function init()
    {
        $table_name = "ci_sessions";
        $primary_key = "";
        $fields = array(
            'id'=>"varchar(40) DEFAULT NULL",
            'ip_address'=>"varchar(45) DEFAULT NULL",
            'timestamp'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'data'=>"blob",
        );
        $foreign_tables = null;
        $translation_fields = null;
        $this->keys = array('ci_sessions_timestamp'=>array("timestamp"));
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}
