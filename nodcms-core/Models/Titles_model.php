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

use Config\Services;

class Titles_model extends Model
{
    function init()
    {
        $table_name = "titles";
        $primary_key = "title_id";
        $fields = array(
            'title_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'title_caption'=>"varchar(255) DEFAULT NULL",
            'relation_id'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'data_type'=>"varchar(255) DEFAULT NULL",
            'language_id'=>"int(10) unsigned NOT NULL DEFAULT '0'",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * Get a row on specific conditions
     *
     * @param string $table
     * @param int $id
     * @param int|null $language_id
     * @return array|null
     */
    public function getTitle(string $table, int $id, int $language_id = null) : ?array
    {
        if($language_id==null)
            $language_id = Services::language()->get()['language_id'];

        $builder = $this->getBuilder();

        $builder->select("*");
        $builder->where("relation_id", $id);
        $builder->where("data_type", $table);
        $builder->where("language_id", $language_id);
        $query = $builder->get();
        return $query->getRowArray();
    }
}