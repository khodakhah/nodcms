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

class Translations extends Model
{
    function init()
    {
        $table_name = "translations";
        $primary_key = "translation_id";
        $fields = array(
            'translation_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'table_id'=>"int(10) unsigned DEFAULT NULL",
            'table_name'=>"varchar(255) DEFAULT NULL",
            'field_name'=>"varchar(255) DEFAULT NULL",
            'language_id'=>"int(10) unsigned DEFAULT NULL",
            'translated_text'=>"text",
        );
        $foreign_tables = null;
        $translation_fields = null;
        $unique = array(
            'table_id_table_name_field_name_language_id'=>array('table_id','table_name','field_name','language_id'),
        );
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * @param string $tableName
     * @param int $tableId
     * @param array $fields
     * @param int $language_id
     * @return array
     */
    public function getAllOfATable(string $tableName, int $tableId, array $fields, int $language_id): array
    {
        $builder = $this->getBuilder();
        $builder->select("*")
            ->where("table_id", $tableId)
            ->where("table_name", $tableName)
            ->where('field_name IN ("'.join('","',$fields).'")')
            ->where("language_id", $language_id);
        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * @param string $tableName
     * @param int $tableId
     * @param array $fields
     */
    public function cleanup(string $tableName, int $tableId, array $fields)
    {
        $builder = $this->getBuilder();
        $builder->where('table_name', $tableName)
            ->where('table_id', $tableId)
            ->where('field_name IN ("'.join('","', $fields).'")')
            ->delete();
    }
}
