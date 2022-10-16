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

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Services;

class Model extends CoreModel implements ModelInterface
{
    private $table_name;
    private $primary_key;
    private $fields;
    protected $change_fields;
    private $foreign_tables;
    private $translation_fields;
    protected $unique_keys;
    protected $keys;
    protected $DBGroup;

    public function __construct(ConnectionInterface $db = null)
    {
        parent::__construct($db);
        $this->init();
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        // Call setup() method
    }

    /**
     * @inheritDoc
     */
    public function setup($table_name, $primary_key, $fields, $foreign_tables = null, $translation_fields = null)
    {
        $this->table_name = $table_name;
        $this->primary_key = $primary_key;
        $this->fields = $fields;
        $this->foreign_tables = $foreign_tables;
        $this->translation_fields = $translation_fields;
    }

    /**
     * @inheritDoc
     */
    public function add(array $data): int
    {
        foreach ($data as $key=>$val) {
            if (!key_exists($key, $this->fields)) {
                unset($data[$key]);
            }
        }
        if (key_exists('created_date', $this->fields) && !isset($data['created_date'])) {
            $data['created_date'] = time();
        }
        $this->getBuilder()->insert($data);
        return $this->db->insertID();
    }

    /**
     * @inheritDoc
     */
    public function edit($id, $data)
    {
        $this->getBuilder()->update($data, array($this->primary_key=>$id));
    }

    /**
     * @inheritDoc
     */
    public function update(array $data, $conditions)
    {
        $this->getBuilder()->update($data, $conditions);
    }

    /**
     * @inheritDoc
     */
    public function remove($id)
    {
        // Delete Foreign Tables
        if ($this->foreign_tables != null && is_array($this->foreign_tables) && count($this->foreign_tables)!=0) {
            foreach ($this->foreign_tables as $table_name) {
                $builder = $this->db->table($table_name);
                $builder->delete(array($this->primary_key=>$id));
            }
        }
        // Delete from main table
        $this->getBuilder()->delete(array($this->primary_key=>$id));
        // Delete Translations
        if ($this->translation_fields != null && is_array($this->translation_fields) && count($this->translation_fields)!=0) {
            $translations = new Translations();
            $translations->cleanup($this->table_name, $id, $this->translation_fields);
        }
    }

    /**
     * @inheritDoc
     */
    public function clean($conditions = null)
    {
        $all = $this->getAll($conditions);
        if ($conditions == null) {
            $this->getBuilder()->emptyTable();
        } else {
            $this->getBuilder()->delete($conditions);
        }

        // Delete Foreign Tables
        if ($this->foreign_tables != null && is_array($this->foreign_tables) && count($this->foreign_tables)!=0) {
            foreach ($this->foreign_tables as $table_name) {
                foreach ($all as $item) {
                    $builder = $this->db->table($table_name);
                    $builder->delete(array($this->primary_key=>$item[$this->primary_key]));
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getCount($conditions = null)
    {
        $builder = $this->getBuilder();
        if ($conditions != null) {
            $this->searchDecode($conditions, $builder);
        }
        return $builder->countAllResults();
    }

    /**
     * @inheritDoc
     */
    public function getSum($field, $conditions = null): int
    {
        $builder = $this->getBuilder();
        $builder->select("sum($field)");
        if ($conditions != null) {
            $this->searchDecode($conditions, $builder);
        }
        $query = $builder->get();
        $result = $query->getRowArray();
        return count($result)!=0 ? $result["sum($field)"] : 0;
    }

    /**
     * @inheritDoc
     */
    public function getMax($field, $conditions = null): int
    {
        $builder = $this->getBuilder();
        $builder->select("max($field)");
        if ($conditions != null) {
            $this->searchDecode($conditions, $builder);
        }
        $query = $builder->get();
        $result = $query->getRowArray();
        return count($result)!=0 ? (int)$result["max($field)"] : 0;
    }

    /**
     * @inheritDoc
     */
    public function getAll($conditions = null, $limit=null, $page=1, ?array $sort_by = null, BaseBuilder $builder = null): array
    {
        if (!$builder != null) {
            $builder = $this->getBuilder();
        }
        $builder->select('*');

        if ($conditions!=null) {
            $this->searchDecode($conditions, $builder);
        }

        if ($sort_by=="rand") {
            $builder->orderBy("RAND()");
        } elseif ($sort_by!=null) {
            $builder->orderBy($sort_by[0], $sort_by[1]);
        }

        if ($limit!=null) {
            $builder->limit($limit, ($page-1)*$limit);
        }

        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * @inheritDoc
     */
    public function getOne($id, $conditions = null)
    {
        $builder = $this->getBuilder();
        $builder->select('*');

        if ($id!=null) {
            $builder->where($this->primary_key, $id);
        }

        if ($conditions != null) {
            $builder->where($conditions);
        }

        $query = $builder->get();
        return $query->getRowArray();
    }

    /**
     * @inheritDoc
     */
    public function searchDecode($conditions, BaseBuilder $builder)
    {
        if (!is_array($conditions)) {
            $builder->where($conditions);
            return;
        }
        if (isset($conditions['search']) && count($conditions['search'])!=0) {
            foreach ($conditions['search'] as $key=>$value) {
                if ($value == 'null') {
                    continue;
                }
                if (preg_match('/^([A-Za-z\_]+)(\_\_|\s)(like|not_like|or_like|or_not_like)$/', strtolower($key), $parameters) == true) {
                    if ($value=='') {
                        continue;
                    }
                    $values = explode(' ', $value);
                    $builder->groupStart();
                    foreach ($values as $item) {
                        $builder->$parameters[3]($parameters[1], $item);
                    }
                    $builder->groupEnd();
                    continue;
                }
                $builder->where($key, $value);
            }
            unset($conditions['search']);
        }
        if (isset($conditions['trans_search']) && count($conditions['trans_search'])!=0) {
            foreach ($conditions['trans_search'] as $key=>$value) {
                if ($value == 'null') {
                    continue;
                }
                if (preg_match('/^([a-z\_\.]+)(\_\_|\s)(like|not_like|or_like|or_not_like)$/', strtolower($key), $parameters) == true) {
                    if ($value=='') {
                        continue;
                    }
                    $values = explode(' ', $value);
                    $builder->groupStart();
                    $connector = array(
                        'like'=>" AND translated_text ",
                        'not_like'=>" AND translated_text NOT ",
                        'or_like'=>" OR translated_text ",
                        'or_not_like'=>" OR translated_text NOT ",
                    );
                    $where = "";
                    foreach ($values as $item) {
                        if ($where!="") {
                            $where .= $connector[$parameters[3]];
                        } else {
                            $where .= "translated_text ";
                        }
                        $where .= "LIKE '$item'";
                    }
                    $where = $where!="" ? "($where) AND" : "";
                    $builder->where($this->primary_key." IN (SELECT table_id FROM translations WHERE $where field_name = '$parameters[1]' AND table_name = '".$this->table_name."' AND language_id = ".Services::language()->get()['language_id'].")");
                    $builder->groupEnd();
                    continue;
                }
                $builder->where($this->primary_key." IN (SELECT table_id FROM translations WHERE field_name = '$key' AND translated_text = '$value' AND table_name = '".$this->table_name."' AND language_id = ".Services::language()->get()['language_id'].")");
            }
            unset($conditions['trans_search']);
        }
        foreach ($conditions as $key=>$val) {
            $matches = array();
            $is_match = preg_match('/^([a-z\_\.]+)[\s](IN|in|NOT IN|not in)/', $key, $matches);
            if (!$is_match) {
                continue;
            }
            if (is_array($val)) {
                $val = join(',', array_unique($val));
            }
            $builder->where("$matches[1] $matches[2] ($val)");
            unset($conditions[$key]);
        }
        if (count($conditions)!=0) {
            $builder->where($conditions);
        }
    }

    /**
     * @inheritDoc
     */
    public function getOneTrans($id, $conditions = null, $language_id = null): ?array
    {
        $default_trans = array_fill_keys($this->translation_fields, "");
        $first_result = $this->getOne($id, $conditions);

        if (!is_array($first_result)) {
            return $first_result;
        }

        if ($language_id==null) {
            $language_id = Services::language()->get()['language_id'];
        }

        $_translations = new Translations();
        $translations = $_translations->getAllOfATable($this->table_name, $first_result[$this->primary_key], $this->translation_fields, $language_id);

        $second_result = array_combine(array_column($translations, 'field_name'), array_column($translations, 'translated_text'));
        $second_result = array_merge($default_trans, $second_result);
        return array_merge($first_result, $second_result);
    }

    /**
     * @inheritDoc
     */
    public function getAllTrans(array $conditions = null, int $limit = null, int $page = 1, ?array $sort_by = null, int $language_id = null): array
    {
        $first_result = $this->getAll($conditions, $limit, $page, $sort_by);

        if ($this->translation_fields == null) {
            return $first_result;
        }

        $default_trans = array_fill_keys($this->translation_fields, "");

        if ($language_id==null) {
            $language_id = Services::language()->get()['language_id'];
        }

        foreach ($first_result as $key=>$item) {
            $_translations = new Translations();
            $translations = $_translations->getAllOfATable($this->table_name, $item[$this->primary_key], $this->translation_fields, $language_id);

            $second_result = array_combine(array_column($translations, 'field_name'), array_column($translations, 'translated_text'));
            $second_result = array_merge($default_trans, $second_result);
            $first_result[$key] = array_merge($item, $second_result);
        }
        return $first_result;
    }

    /**
     * @inheritDoc
     */
    public function getTranslation(int $id, string $field, int $language_id = null): array
    {
        if ($language_id==null) {
            $language_id = Services::language()->get()['language_id'];
        }

        $_translations = new Translations();
        return $_translations->getAllOfATable($this->table_name, $id, array($field), $language_id);
    }

    /**
     * @inheritDoc
     */
    public function getTranslations(int $id, int $language_id = 0): array
    {
        if ($language_id != 0) {
            $language_id = Services::language()->get()['language_id'];
        }

        $_translations = new Translations();
        $result = $_translations->getAll(array(
            'table_id' => $id,
            'table_name' => $this->table_name,
            'language_id' => $language_id,
        ));

        return array_combine(array_column($result, "field_name"), array_column($result, "translated_text"));
    }

    /**
     * @inheritDoc
     */
    public function updateTranslation($id, $value, $field, $language_id)
    {
        if (!in_array($field, $this->translation_fields)) {
            throw new DatabaseException("The field '$field' doesn't exists in the translation_files of $this->table_name.");
        }

        $condition = array(
            'table_name'=>$this->table_name,
            'table_id'=>$id,
            'field_name'=>$field,
            'language_id'=>$language_id,
        );

        $data = array('translated_text'=>$value);

        $_translations = new Translations();

        $translation_count = $_translations->getCount($condition);

        // * Update the exists title
        if ($translation_count!=0) {
            $_translations->update($data, $condition);
        }
        // * Create new title
        else {
            $_translations->add(array_merge($condition, $data));
        }
    }

    /**
     * @inheritDoc
     */
    public function updateTranslations($id, $values, $languages)
    {
        foreach ($languages as $language) {
            foreach ($this->translation_fields as $field_name) {
                if (!isset($values[$language['language_id']][$field_name])) {
                    continue;
                }
                $this->updateTranslation($id, $values[$language['language_id']][$field_name], $field_name, $language['language_id']);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function tableName()
    {
        return $this->table_name;
    }

    /**
     * @inheritDoc
     */
    public function primaryKey()
    {
        return $this->primary_key;
    }

    /**
     * @inheritDoc
     */
    public function fields()
    {
        return $this->fields;
    }

    /**
     * @inheritDoc
     */
    public function foreignTables()
    {
        return $this->foreign_tables;
    }

    /**
     * @inheritDoc
     */
    public function translationFields()
    {
        return $this->translation_fields;
    }

    /**
     * @inheritDoc
     */
    public function getDateStatistic($type, $sum = null, $conditions = null): array
    {
        $types = array(
            'weekdays'=>array(
                'order_by'=>"DAYNAME(FROM_UNIXTIME(created_date))",
                'select'=>"DAYNAME(FROM_UNIXTIME(max(created_date))) as date_label"
            ),
            'daily'=>array(
                'order_by'=>"DATE_FORMAT(FROM_UNIXTIME(created_date),'%d')",
                'select'=>"DATE_FORMAT(FROM_UNIXTIME(max(created_date)),'%d') as date_label"
            ),
            'monthly'=>array(
                'order_by'=>"MONTH(FROM_UNIXTIME(created_date))",
                'select'=>"MONTH(FROM_UNIXTIME(max(created_date))) as date_label"
            ),
        );
        if (!key_exists($type, $types)) {
            throw new DatabaseException("The statistic \"{$type}\" has not been defined.");
        }
        $builder = $this->getBuilder();
        $builder->select("count(*) as count_value, ".($sum!=null ? "sum($sum) as sum_value, " : "").$types[$type]['select']);
        if ($conditions!=null) {
            $this->searchDecode($conditions, $builder);
        }
        $builder->orderBy($types[$type]['order_by']);
        $query = $builder->get();
        return $query->getResultArray();
    }

    /**
     * @inheritDoc
     */
    public function installTable()
    {
        $query_items = array();
        foreach ($this->fields as $field_name => $field_codes) {
            $query_items[] = "`$field_name` $field_codes";
        }
        if (!empty($this->primary_key)) {
            $query_items[] = "PRIMARY KEY (`{$this->primary_key}`)";
        }

        if (!empty($this->unique_keys)) {
            foreach ($this->unique_keys as $name=>$value) {
                $_keys = join('`, `', $value);
                $_name = is_string($name) ? $name : join('', $value);
                $query_items[] = "UNIQUE INDEX `{$_name}` (`{$_keys}`)";
            }
        }

        if (!empty($this->keys)) {
            foreach ($this->keys as $name=>$value) {
                $_keys = join('`, `', $value);
                $_name = is_string($name) ? $name : join('', $value);
                $query_items[] = "KEY `{$_name}` (`{$_keys}`)";
            }
        }

        $query_items = join(',', $query_items);
        $auto_increment = !empty($this->primary_key) ? " AUTO_INCREMENT=1" : "";
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table_name}` ($query_items) ENGINE=InnoDB{$auto_increment} DEFAULT CHARSET=utf8;";
        return $this->db->query($sql);
    }

    /**
     * @inheritDoc
     */
    public function repairTable()
    {
        $query_items = array();
        $fields = $this->db->getFieldData($this->table_name);
        $_fields = array();
        foreach ($fields as $field) {
            $_fields[$field->name] = array(
                'name'=>$field->name,
                'type'=>$field->type,
                'max_length'=>$field->max_length,
                'primary_key'=>$field->primary_key,
            );
            if (!key_exists($field->name, $this->fields)) {
                $query_items[] = "DROP COLUMN `{$field->name}`";
            }
        }

        foreach ($this->fields as $field_name => $field_codes) {
            if (key_exists($field_name, $_fields)) {
                if (!empty($this->change_fields) && key_exists($field_name, $this->change_fields)) {
                    $query_items[] = "CHANGE COLUMN `{$this->change_fields[$field_name]}` `$field_name` $field_codes";
                }
                continue;
            }
            $query_items[] = "ADD COLUMN `$field_name` $field_codes";
        }

        if (empty($query_items)) {
            return true;
        }

        $query_items = join(', ', $query_items);
        $sql = "ALTER TABLE `{$this->table_name}` $query_items;";
        return $this->db->query($sql);
    }

    /**
     * @inheritDoc
     */
    public function dropTable()
    {
        $sql = "DROP TABLE IF EXISTS `{$this->table_name}`;";
        return $this->db->query($sql);
    }

    /**
     * @inheritDoc
     */
    public function tableExists()
    {
        $sql = "show tables like '{$this->table_name}';";
        $query = $this->db->query($sql);
        return count($query->getResultArray()) > 0;
    }

    /**
     * Returns an instance of the query builder for this connection.
     *
     * @return BaseBuilder
     * @throws DatabaseException
     */
    protected function getBuilder(): BaseBuilder
    {
        return $this->db->table($this->table_name);
    }
}
