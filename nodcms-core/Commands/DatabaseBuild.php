<?php

namespace NodCMS\Core\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Services;
use NodCMS\Core\Libraries\DatabaseMapping;

class DatabaseBuild extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'NodCMS';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'database:build';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Create database tables.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'database:build [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '-overwrite' => 'This option will overwrite exists tables.'
    ];

    /**
     * Actually execute a command.
     *
     * @param array $params
     * @throws \Exception
     */
    public function run(array $params)
    {
        try {
            Services::databaseEnvConfig()->checkRequiredParameters();
        } catch (\Exception $exception) {
            CLI::write(CLI::color("Database settings doesn't exists!", 'red'));
            CLI::newLine();
            CLI::write("1. Make sure that .env file exists.");
            CLI::write("2. Run: ".CLI::color("php spark database:setup", 'yellow'));
            CLI::newLine();
            return;
        }

        $host = Services::databaseEnvConfig()->getHost();
        $username = Services::databaseEnvConfig()->getUsername();
        $password = Services::databaseEnvConfig()->getPassword();
        $database = Services::databaseEnvConfig()->getDatabase();

        $dataMapping = Services::databaseMapping();

        try {
            $dataMapping->setConnection($host, $username, $password, $database);
        } catch (DatabaseException $e) {
            CLI::error("Unable to connect database!");
            CLI::newLine();
            CLI::write("Error Code: {$e->getCode()}");
            CLI::write("Error Message: {$e->getMessage()}");
            CLI::newLine();
            if ($e->getCode() == 2002) {
                $dbService = CLI::color('mysql-server', 'blue');
                CLI::write("Please make sure $dbService is available on your server!");
                CLI::newLine();
            }
            return;
        }

        $dataMapping->setOverwriteTables(key_exists('overwrite', $params));

        $hasBuild = $dataMapping->buildTables(
            function ($table, $action) {
                $_table = CLI::color($table, 'blue');
                if ($action == DatabaseMapping::BUILD_ACTION_IGNORE) {
                    CLI::write("Table \"{$_table}\" already exists. It has been " . CLI::color("ignored", 'yellow') . "!");
                } elseif ($action == DatabaseMapping::BUILD_ACTION_DROP) {
                    CLI::write("Table \"{$_table}\" has been " . CLI::color("dropped", 'yellow') . "!");
                } elseif ($action == DatabaseMapping::BUILD_ACTION_CREATE) {
                    CLI::write("Table \"{$_table}\" has been " . CLI::color("created", 'green') . "!");
                } elseif ($action == DatabaseMapping::BUILD_ACTION_INSERT_DEFAULTS) {
                    CLI::write("Default values on \"{$_table}\" has been " . CLI::color("inserted", 'green') . "!");
                }
            },
            function ($table, $action) {
                $_table = CLI::color($table, 'blue');
                $report = '';
                if ($action == DatabaseMapping::BUILD_ACTION_DROP) {
                    $report = "DROP TABLE {$_table}";
                } elseif ($action == DatabaseMapping::BUILD_ACTION_CREATE) {
                    $report = "CREATE TABLE {$_table}";
                } elseif ($action == DatabaseMapping::BUILD_ACTION_INSERT_DEFAULTS) {
                    $report = "INSERT INTO {$_table}";
                }
                CLI::write(CLI::color('[Failed]', 'red') . " {$report}");
            }
        );

        if ($hasBuild) {
            CLI::write(CLI::color("DONE", 'green'));
            return;
        }

        CLI::write(CLI::color("STOPPED", 'red'));

        CLI::newLine();
    }
}
