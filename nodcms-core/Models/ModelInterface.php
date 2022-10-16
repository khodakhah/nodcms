<?php
/*
 *  This file is part of nodcms.
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

interface ModelInterface
{
    /**
     * @param ConnectionInterface|null $db
     */
    public function __construct(ConnectionInterface $db = null);

    /**
     * The function to setup the model in child classes
     */
    public function init();

    /**
     * NodCMS_Model constructor.
     *
     * @param $table_name
     * @param $primary_key
     * @param $fields
     * @param null $foreign_tables
     * @param null $translation_fields
     */
    public function setup($table_name, $primary_key, $fields, $foreign_tables = null, $translation_fields = null);

    /**
     * Insert to the database
     *
     * @param array $data
     */
    public function add(array $data): int;

    /**
     * Update a table in database with id as condition
     *
     * @param $id
     * @param $data
     */
    public function edit($id, $data);

    /**
     * Update a table in database with conditions
     * @param array $data
     * @param array|string $conditions
     */
    public function update(array $data, $conditions);

    /**
     * Delete form a database
     *
     * @param $id
     */
    public function remove($id);

    /**
     * Delete a list rows from a table
     *
     * @param $conditions
     */
    public function clean($conditions = null);

    /**
     * Universal count query
     *
     * @param null|array $conditions
     * @return mixed
     */
    public function getCount($conditions = null);

    /**
     * Universal sum query
     *
     * @param $field
     * @param null $conditions
     * @return int
     */
    public function getSum($field, $conditions = null): int;

    /**
     * Universal max query
     *
     * @param $field
     * @param null $conditions
     * @return int
     */
    public function getMax($field, $conditions = null): int;

    /**
     * Select a list from a database
     *
     * @param null|array $conditions
     * @param null|int $limit
     * @param int $page
     * @param null|array $sort_by
     * @param BaseBuilder|null $builder
     * @return mixed
     */
    public function getAll($conditions = null, $limit=null, $page=1, ?array $sort_by = null, BaseBuilder $builder = null): array;

    /**
     * Select a row from a database
     *
     * @param $id
     * @param null $conditions
     * @return mixed
     */
    public function getOne($id, $conditions = null);

    /**
     * Decode search data
     *
     * @param array|string $conditions
     * @param BaseBuilder $builder
     */
    public function searchDecode($conditions, BaseBuilder $builder);

    /**
     * Select a row from database and merging with related translations
     *
     * @param $id
     * @param null $conditions
     * @param null $language_id
     * @return null|array
     */
    public function getOneTrans($id, $conditions = null, $language_id = null): ?array;

    /**
     * Select a list from a database with translation keys
     *
     * @param array|null $conditions
     * @param int|null $limit
     * @param int $page
     * @param array|null $sort_by
     * @param int|null $language_id
     * @return array
     */
    public function getAllTrans(array $conditions = null, int $limit = null, int $page = 1, ?array $sort_by = null, int $language_id = null): array;

    /**
     * Select a row from "translations"
     *
     * @param int $id
     * @param string $field
     * @param null|int $language_id
     * @return array
     */
    public function getTranslation(int $id, string $field, int $language_id = null): array;

    /**
     * Get a result of translations
     *
     * @param int $id
     * @param int $language_id
     * @return array
     */
    public function getTranslations(int $id, int $language_id = 0): array;

    /**
     * Insert/Update a translation row
     *
     * @param $id
     * @param $value
     * @param $field
     * @param $language_id
     */
    public function updateTranslation($id, $value, $field, $language_id);

    /**
     * Update translations of a field
     *
     * @param $id
     * @param $values
     * @param $languages
     */
    public function updateTranslations($id, $values, $languages);

    /**
     * Get the table_name
     *
     * @return mixed
     */
    public function tableName();

    /**
     * Get the primary_key
     *
     * @return mixed
     */
    public function primaryKey();

    /**
     * Get the fields
     *
     * @return mixed
     */
    public function fields();

    /**
     * Get the foreign_tables
     *
     * @return null
     */
    public function foreignTables();

    /**
     * Get the translation_fields
     *
     * @return null
     */
    public function translationFields();

    /**
     * Select data from a table to use in statistics
     *
     * @param $type
     * @param null $sum
     * @param null $conditions
     * @return array
     */
    public function getDateStatistic($type, $sum = null, $conditions = null): array;

    /**
     * Create table
     *
     * @return mixed
     */
    public function installTable();

    /**
     * Repair fields of a table
     *
     * @return bool
     */
    public function repairTable();

    /**
     * Drop table
     *
     * @return bool
     */
    public function dropTable();

    /**
     * Return true if the table exists
     *
     * @return bool
     */
    public function tableExists();
}
