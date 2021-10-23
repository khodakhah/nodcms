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

namespace NodCMS\Services\Models;

use NodCMS\Core\Models\Model;

class Services extends Model
{
    public function init()
    {
        $table_name = "services";
        $primary_key = "service_id";
        $fields = array(
            'service_id'=>"int(11) unsigned NOT NULL AUTO_INCREMENT",
            'service_name'=>"varchar(255) DEFAULT NULL",
            'service_uri'=>"varchar(255) DEFAULT NULL",
            'service_image'=>"varchar(255) DEFAULT NULL",
            'service_icon'=>"varchar(255) DEFAULT NULL",
            'created_date'=>"int(11) unsigned NOT NULL",
            'service_public'=>"int(11) unsigned NOT NULL",
            'service_price'=>"float unsigned NOT NULL",
            'sort_order'=>"int(11) unsigned DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = array('title','home_preview','description','keywords','content');
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }
}