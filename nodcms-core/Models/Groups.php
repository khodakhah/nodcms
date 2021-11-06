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

class Groups extends Model
{
    function init()
    {
        $table_name = "groups";
        $primary_key = "group_id";
        $fields = array(
            'group_id'=>"int(10) NOT NULL AUTO_INCREMENT",
            'group_name'=>"varchar(50) DEFAULT NULL",
            'backend_login'=>"int(1) DEFAULT '0'",
        );
        $foreign_tables = array("gallery_image");
        $translation_fields = null;
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * Insert first data
     */
    function defaultData()
    {
        $data = array(
            array('group_id'=>1,'group_name'=>"Admin", 'backend_login'=>1),
            array('group_id'=>20,'group_name'=>"Users", 'backend_login'=>0),
        );
        foreach($data as $item) {
            $this->add($item);
        }
    }
}
