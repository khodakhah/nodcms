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
