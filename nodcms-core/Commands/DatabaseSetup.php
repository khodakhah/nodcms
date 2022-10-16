<?php

namespace NodCMS\Core\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Services;

class DatabaseSetup extends BaseCommand
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
    protected $name = 'database:setup';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Save database connection parameters in the .env file.';

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
        if (count($params) != 4) {
            CLI::error('Params missing!');
            CLI::write('Please make sure to use the command correctly.');
            $this->showHelp();
            return;
        }

        [$host, $username, $password, $database] = $params;

        $dataMapping = Services::databaseMapping();

        try {
            $dataMapping->setConnection($host, $username, $password, $database);
        } catch (DatabaseException $e) {
            CLI::write(CLI::color("Unable to connect database!", 'red'));
            CLI::newLine();
            CLI::write("Error Code: {$e->getCode()}");
            CLI::write("Error Message: {$e->getMessage()}");
            if ($e->getCode() == 2002) {
                CLI::newLine();
                $dbService = CLI::color('mysql-server', 'blue');
                CLI::write("Please make sure $dbService is available on your server!");
                CLI::newLine();
            }
            return;
        }

        Services::databaseEnvConfig()
            ->setHost($host)
            ->setPassword($password)
            ->setUsername($username)
            ->setDatabase($database)
            ->writeToEnv();

        CLI::write(CLI::color("Database connection have been successfully saved.", 'green'));

        CLI::newLine();
    }
}
