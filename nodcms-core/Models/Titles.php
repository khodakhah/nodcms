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

class Titles extends Model
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
