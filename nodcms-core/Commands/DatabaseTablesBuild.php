<?php

namespace NodCMS\Core\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Services;
use NodCMS\Core\Libraries\DatabaseMapping;

class DatabaseTablesBuild extends BaseCommand
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
    protected $description = 'Builds database tables on the given connection.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'database:build [host] [user] [pass] [tableName]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'host' => 'Database host (for example: localhost)',
        'user' => 'Database username.',
        'password' => 'Database password.',
        'database' => 'Database name. (Database should be created before)',
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     * @throws \Exception
     */
    public function run(array $params)
    {
        if(count($params) != 4) {
            CLI::write(CLI::color('Params missing!', 'red'));
            CLI::write('Please make sure to use the command correctly.');
            $this->showHelp();
            return;
        }

        [$host, $user, $pass, $table] = $params;

        $dataMapping = Services::databaseMapping();

        try {
            $dataMapping->setConnection($host, $user, $pass, $table);
        }
        catch (DatabaseException $e) {
            CLI::write(CLI::color("Unable to connect database!", 'red'));
            CLI::newLine();
            CLI::write("Error Code: {$e->getCode()}");
            CLI::write("Error Message: {$e->getMessage()}");
            if($e->getCode() == 2002) {
                CLI::newLine();
                $dbService = CLI::color('mysql-server', 'blue');
                CLI::write("Please make sure $dbService is available on your server!");
                CLI::newLine();
            }
            return;
        }

        $hasBuild = $dataMapping->buildTables(
            function ($table, $action) {
                $_table = CLI::color($table, 'blue');
                if($action == DatabaseMapping::BUILD_ACTION_DROP) {
                    CLI::write("Table \"{$_table}\" has been " . CLI::color("dropped", 'yellow') . "!");
                }
                elseif($action == DatabaseMapping::BUILD_ACTION_CREATE) {
                    CLI::write("Table \"{$_table}\" has been " . CLI::color("created", 'green') . "!");
                }
                elseif($action == DatabaseMapping::BUILD_ACTION_INSERT_DEFAULTS) {
                    CLI::write("Default values on \"{$_table}\" has been " . CLI::color("inserted", 'green') . "!");
                }
            },
            function ($table, $action) {
                $_table = CLI::color($table, 'blue');
                $report = '';
                if($action == DatabaseMapping::BUILD_ACTION_DROP) {
                    $report = "DROP TABLE {$_table}";
                }
                elseif($action == DatabaseMapping::BUILD_ACTION_CREATE) {
                    $report = "CREATE TABLE {$_table}";
                }
                elseif($action == DatabaseMapping::BUILD_ACTION_INSERT_DEFAULTS) {
                    $report = "INSERT INTO {$_table}";
                }
                CLI::write(CLI::color('[Failed]', 'red') . " {$report}");
            }
        );

        if($hasBuild) {
            CLI::write(CLI::color("DONE", 'green'));
            return;
        }

        CLI::write(CLI::color("STOPPED", 'red'));

        CLI::newLine();
    }
}
