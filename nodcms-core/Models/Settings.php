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

namespace NodCMS\Core\Models;

class Settings extends Model
{
    function init()
    {
        $table_name = "settings";
        $primary_key = "id";
        $fields = array(
            'id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'language_id'=>"int(11) unsigned DEFAULT '0'",
            'field_name'=>"varchar(200) DEFAULT NULL",
            'field_value'=>"text",
        );
        $foreign_tables = null;
        $translation_fields = null;
        $this->unique_keys = array(array("language_id","field_name"));
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * Update settings storage
     *
     * @param null|array $data
     * @param int $language_id
     * @return bool
     */
    public function updateSettings($data = NULL, $language_id = 0): bool
    {
        if($data != NULL && is_array($data) && count($data)!=0)
            foreach($data as $name=>$value){
                $sql = 'INSERT INTO settings'.
                    '  (field_name, language_id, field_value)'.
                    'VALUES'.
                    '  (?, ?, ?)'.
                    'ON DUPLICATE KEY UPDATE'.
                    '  field_value = ?';
                $this->db->query($sql, array($name, $language_id, $value, $value));
            }
        return true;
    }

    /**
     * Return settings as an array with key and values
     *
     * @param int $language_id
     * @return array
     */
    public function getSettings(int $language_id=0): array
    {
        $builder = $this->getBuilder();
        $builder->select('*');
        $builder->where('language_id', $language_id);
        $query = $builder->get();
        $result = $query->getResultArray();
        return array_combine(array_column($result, 'field_name'), array_column($result, 'field_value'));
    }
}