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
        $language_id = \Config\Services::language()->get()['language_id'];
        $builder = $this->getBuilder();
        $builder->select('*');
        $builder->from('menu');
        $builder->join('titles',"titles.relation_id = menu_id");
        $builder->where('titles.data_type',"menu");
        $builder->where('titles.language_id', $language_id);
        $builder->where('public',1);
        $builder->where('menu_key', $key);
        if($parent!==null)
            $builder->where('sub_menu',$parent);
        $builder->orderBy('menu_order', "ASC");
        $query = $builder->get();
        return $query->getResultArray();
    }
}