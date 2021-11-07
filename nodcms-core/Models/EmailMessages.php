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

use Config\Services;
use http\Params;

class EmailMessages extends Model
{
    function init()
    {
        $table_name = "auto_email_messages";
        $primary_key = "msg_id";
        $fields = array(
            'msg_id'=> "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'code_key'=> "varchar(100) DEFAULT NULL",
            'subject'=> "varchar(255) DEFAULT NULL",
            'content'=> "text",
            'language_id'=> "int(10) unsigned NOT NULL DEFAULT '0'",
            'lang'=> "varchar(2) DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * @param $key
     * @param null $language_id
     * @return array|null
     */
    public function getOneByKey($key, $language_id = NULL): ?array
    {
        if($language_id==NULL)
            $language_id = Services::language()->get()["language_id"];
        return $this->getOne(null, [
            'code_key' => $key,
            'language_id' => $language_id,
        ]);
    }
}
