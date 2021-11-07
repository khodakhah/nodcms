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

class UploadFiles extends Model
{
    function init()
    {
        $table_name = "upload_files";
        $primary_key = "file_id";
        $fields = array(
            'file_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'user_id'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'session_id'=>"varchar(255) NOT NULL DEFAULT '0'",
            'unique_cookie'=>"varchar(255) DEFAULT NULL",
            'file_key'=>"varchar(255) DEFAULT NULL",
            'upload_key'=>"varchar(255) DEFAULT NULL",
            'remove_key'=>"varchar(255) DEFAULT NULL",
            'file_path'=>"varchar(255) DEFAULT NULL",
            'file_type'=>"varchar(255) DEFAULT NULL",
            'file_thumbnail'=>"varchar(255) DEFAULT NULL",
            'name'=>"varchar(255) DEFAULT NULL",
            'size'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'created_date'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'deadline'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'file_using'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'host'=>"varchar(255) NOT NULL",
            'download_validation'=>"varchar(255) NOT NULL",
            'download_password'=>"varchar(255) DEFAULT NULL",
            'download_count'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'last_download'=>"int(10) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * Add session id automatically on each insert
     *
     * @param array $data
     * @return int
     */
    public function add(array $data): int
    {
        if(!key_exists("session_id", $data))
            $data['session_id'] = session_id();
        return parent::add($data);
    }

    /**
     * Specific update; set using file
     *
     * @param int $id
     * @param bool $file_using
     */
    public function updateFileUsing(int $id, bool $file_using = true)
    {
        $this->update(['file_using'=>intval($file_using)], [$this->primaryKey()=>$id]);
    }
}
