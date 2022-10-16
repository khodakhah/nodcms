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

namespace NodCMS\Core\Libraries;

use CodeIgniter\Database\BaseConnection;
use Config\Autoload;
use NodCMS\Core\Models\ModelInterface;

class DatabaseMapping
{
    public const BUILD_ACTION_IGNORE = 'ignore';

    public const BUILD_ACTION_DROP = 'drop';

    public const BUILD_ACTION_CREATE = 'create';

    public const BUILD_ACTION_INSERT_DEFAULTS = 'insert';

    /**
     * @var null|BaseConnection
     */
    private ?BaseConnection $connection;

    /**
     * @var bool
     */
    private bool $overwriteTables = false;

    /**
     * Connection exists.
     *
     * @return bool
     */
    public function hasConnection(): bool
    {
        return $this->connection instanceof BaseConnection;
    }

    /**
     * @param bool $overwriteTables
     * @return DatabaseMapping
     */
    public function setOverwriteTables(bool $overwriteTables): DatabaseMapping
    {
        $this->overwriteTables = $overwriteTables;
        return $this;
    }

    /**
     * Set database connection
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     */
    public function setConnection(string $host, string $username, string $password, string $database)
    {
        $custom = [
            'DSN'      => '',
            'hostname' => $host,
            'username' => $username,
            'password' => $password,
            'database' => $database,
            'DBDriver' => 'MySQLi',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug'  => false,
            'cacheOn'  => false,
            'cacheDir' => '',
            'charset'  => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre'  => '',
            'encrypt'  => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port'     => 3306,
        ];

        $connection = \Config\Database::connect($custom);
        $connection->connect();
        $this->connection = $connection;
    }

    /**
     * Build tables on object connection
     *
     * @param callable $report
     * @param callable $failedReport
     * @return bool
     * @throws \Exception
     */
    public function buildTables(callable $report, callable $failedReport): bool
    {
        if (!$this->hasConnection()) {
            throw new \Exception("No connection exists. setConnection() should be executed before.");
        }

        $paths = $this->getMaps();

        foreach ($paths as $models) {
            foreach ($models as $model) {
                if ($model->tableExists()) {
                    if (!$this->overwriteTables) {
                        $report($model->tableName(), self::BUILD_ACTION_IGNORE);
                        continue;
                    }
                    $model->dropTable();
                    $report($model->tableName(), self::BUILD_ACTION_DROP);
                }

                if (!$model->installTable()) {
                    $failedReport($model->tableName(), self::BUILD_ACTION_CREATE);
                    return false;
                }
                $report($model->tableName(), self::BUILD_ACTION_CREATE);

                // Insert default data
                if (method_exists($model, 'defaultData')) {
                    $model->defaultData();
                    $report($model->tableName(), self::BUILD_ACTION_INSERT_DEFAULTS);
                }
            }
        }

        return true;
    }

    /**
     * Init and returns all NodCMS models
     *
     * @return ModelInterface[][]
     * @throws \Exception
     */
    public function getMaps(): array
    {
        helper('core_helper');
        $moduleModels = get_all_php_files(COREPATH . 'Models'.DIRECTORY_SEPARATOR);
        if ($moduleModels === false) {
            throw new \Exception("On path: \"".COREPATH . 'Models'.DIRECTORY_SEPARATOR."\" no Models have been found.", 500);
        }
        $coreModelMaps = $this->getModelMaps($moduleModels);

        $maps = ['' => $coreModelMaps];

        $modulesDirs = Autoload::modulesPaths();
        foreach ($modulesDirs as $dir) {
            $moduleModels = get_all_php_files($dir . 'Models'.DIRECTORY_SEPARATOR);
            if ($moduleModels === false) {
                continue;
            }

            $modelMaps = $this->getModelMaps($moduleModels);
            if (!empty($modelMaps)) {
                $maps[basename($dir)] = $modelMaps;
            }
        }

        return $maps;
    }

    /**
     * Init all models in the given paths
     *
     * @param array $modelsFilePaths
     * @return array [modelPath => $modelObject]
     */
    private function getModelMaps(array $modelsFilePaths): array
    {
        $maps = [];
        foreach ($modelsFilePaths as $modelPath) {
            $modelObject = $this->getModelFromPath($modelPath);

            if ($modelObject === null) {
                continue;
            }

            $maps[$modelPath] = $modelObject;
        }

        return $maps;
    }

    /**
     * Init the Model Object from model file path
     *
     * @param string $path
     * @return ModelInterface|null
     */
    private function getModelFromPath(string $path): ?ModelInterface
    {
        $model = basename($path, ".php");
        if (in_array($model, ["Model", "CoreModel", "ModelInterface"])) {
            return null;
        }

        $dirname = basename(dirname(realpath($path), 2));
        $break = explode('-', $dirname);
        $namespace = '\\NodCMS\\' . ucfirst($break[1]) . '\Models\\';
        $model = $namespace . $model;
        $theModel = new $model($this->connection);
        if (!is_subclass_of($theModel, "\NodCMS\Core\Models\Model")) {
            return null;
        }

        if (!method_exists($theModel, 'tableName')) {
            return  null;
        }

        return $theModel;
    }
}
