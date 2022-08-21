<?php

namespace NodCMS\Core\Libraries;

class DatabaseEnvConfig
{
    private array $databaseConfig;

    public function __set(string $name, $value): void
    {
        $this->databaseConfig[$name] = $value;
    }

    public function __get(string $name)
    {
        return $this->databaseConfig[$name];
    }

    public function writeToEnv()
    {
        foreach ($this->databaseConfig as $key => $value) {
            switch ($key) {
                case 'host':
                    $this->saveToEnvFile("database.default.hostname = " . $value);
                    break;
                case 'username':
                    $this->saveToEnvFile("database.default.username = " . $value);
                    break;
                case 'password':
                    $this->saveToEnvFile("database.default.password = " . $value);
                    break;
                case 'database':
                    $this->saveToEnvFile("database.default.database = " . $value);
                    break;
            }
        }

        $this->saveToEnvFile("database.default.DBDriver = MySQLi");

        $this->saveToEnvFile("database.default.port = 3306");
    }

    private function saveToEnvFile(string $entry)
    {
        file_put_contents(ROOTPATH . '/.env', PHP_EOL. $entry . PHP_EOL,FILE_APPEND);
    }
}