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
 *  @since      Version 3.2.0
 *  @filesource
 *
 */

namespace NodCMS\Gallery\Models;

use NodCMS\Core\Models\Model;

class Gallery extends Model
{
    public function init()
    {
        $table_name = "gallery";
        $primary_key = "gallery_id";
        $fields = array(
            'gallery_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'gallery_uri'=>"varchar(255) DEFAULT NULL",
            'gallery_name'=>"varchar(255) DEFAULT NULL",
            'gallery_image'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'sort_order'=>"int(11) unsigned NOT NULL DEFAULT '0'",
            'gallery_public'=>"int(1) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = array("gallery_images");
        $translation_fields = array('title', 'description', 'keywords', 'details');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}