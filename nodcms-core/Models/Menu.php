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

use CodeIgniter\Database\MySQLi\Builder;

class Menu extends Model
{
    function init()
    {
        $table_name = "menu";
        $primary_key = "menu_id";
        $fields = array(
            'menu_id'=>"int(10) unsigned NOT NULL AUTO_INCREMENT",
            'menu_name'=>"varchar(255) DEFAULT NULL",
            'menu_icon'=>"varchar(255) DEFAULT NULL",
            'sub_menu'=>"int(10) unsigned DEFAULT '0'",
            'created_date'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'menu_order'=>"int(10) unsigned NOT NULL DEFAULT '0'",
            'public'=>"int(1) unsigned NOT NULL DEFAULT '0'",
            'menu_url'=>"varchar(300) DEFAULT NULL",
            'menu_key'=>"varchar(300) DEFAULT NULL",
        );
        $foreign_tables = null;
        $translation_fields = ["menu_name"];
        parent::setup($table_name, $primary_key, $fields, $foreign_tables, $translation_fields);
    }

    /**
     * @param string $key
     * @param int|null $parent
     * @return array
     */
    public function getMenu(string $key, int $parent=null): array
    {
        $conditions = [
            'public' => 1,
            'menu_key' => $key
        ];
        if($parent!=null)
            $conditions['sub_menu'] = $parent;

        return $this->getAllTrans($conditions, null, 1, ['menu_order', 'ASC']);
    }
}
