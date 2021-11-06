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

class Languages extends Model
{
    function init()
    {
        $table_name = "languages";
        $primary_key = "language_id";
        $fields = array(
            'language_id'=>"int(11) NOT NULL AUTO_INCREMENT",
            'language_name'=>"varchar(255) DEFAULT NULL",
            'language_title'=>"varchar(255) DEFAULT NULL",
            'code'=>"varchar(255) DEFAULT NULL",
            'public'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'rtl'=>"int(1) unsigned DEFAULT '0'",
            'sort_order'=>"int(11) DEFAULT NULL",
            'created_date'=>"int(11) DEFAULT NULL",
            'default'=>"int(11) DEFAULT '0'",
            'image'=>"varchar(255) DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = null;
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * @return array
     */
    public function getDefaultRecord(): array
    {
        return array('language_id'=>1, 'language_name'=>'english', 'language_title'=>'English', 'code'=>'en', 'public'=>1, 'rtl'=>0, 'sort_order'=>1, 'created_date'=>time(), 'default'=>1, 'image'=>'upload_file/lang/en.png');
    }

    /**
     * Insert default data by install
     */
    public function defaultData()
    {
        $data = [self::getDefaultRecord()];
        foreach($data as $item) {
            $this->add($item);
        }
    }

    /**
     * Returns a recorde by language code(Locale)
     *
     * @param string $code
     * @return array|null
     */
    public function getByCode(string $code): ?array
    {
        $result = $this->getOne(null, ['code'=>$code]);
        if(empty($result)) {
            $result = $this->getOne(null);
        }
        return $result;
    }

    /**
     * Returns all language that already has been in backend activated
     *
     * @return array
     */
    public function getAllActives(): array
    {
        return $this->getAll(['public'=>1]);
    }
}
