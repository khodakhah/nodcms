<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.0.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Models;

class Upload_files_model extends Model
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
}