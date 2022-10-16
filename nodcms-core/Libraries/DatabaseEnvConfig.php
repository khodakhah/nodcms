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

namespace NodCMS\Core\Libraries;

use CodeIgniter\CLI\CLI;
use Exception;

class DatabaseEnvConfig
{
    private ?string $host;
    private ?string $username;
    private ?string $password;
    private ?string $database;

    /**
     * Read and write database connection settings from and into .env file.
     */
    public function __construct()
    {
        $this->host = env('database.default.hostname');
        $this->username = env('database.default.username');
        $this->password = env('database.default.password');
        $this->database = env('database.default.database');
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @param $host
     * @return DatabaseEnvConfig
     */
    public function setHost($host): self
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @param $username
     * @return DatabaseEnvConfig
     */
    public function setUsername($username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param $password
     * @return DatabaseEnvConfig
     */
    public function setPassword($password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param $database
     * @return DatabaseEnvConfig
     */
    public function setDatabase($database): self
    {
        $this->database = $database;
        return $this;
    }

    /**
     * Save database parameters to env file.
     *
     * @return void
     * @throws Exception
     */
    public function writeToEnv(): void
    {
        $this->checkRequiredParameters();

        $this->saveToEnvFile("database.default.hostname=" . $this->host);

        $this->saveToEnvFile("database.default.username=" . $this->username);

        $this->saveToEnvFile("database.default.password=" . $this->password);

        $this->saveToEnvFile("database.default.database=" . $this->database);

        $this->saveToEnvFile("database.default.DBDriver=MySQLi");

        $this->saveToEnvFile("database.default.port=3306");
    }

    /**
     * Save a string to env file.
     *
     * @param string $entry
     * @return void
     */
    private function saveToEnvFile(string $entry): void
    {
        $key = explode('=', $entry)[0];
        if (getenv($key) == null) {
            file_put_contents(ROOTPATH . '.env', PHP_EOL . $entry . PHP_EOL, FILE_APPEND);
        }

        $content = file_get_contents(ROOTPATH . '/.env');
        $content = preg_replace('/'.$key.'\s?\=.*\n/', $entry."\n", $content);
        file_put_contents(ROOTPATH . '.env', $content);
    }

    /**
     *  It checks if required parameters have been set.
     *
     * @throws Exception
     */
    public function checkRequiredParameters()
    {
        if (is_null($this->host)) {
            throw new Exception('The database host is required.');
        }

        if (is_null($this->username)) {
            throw new Exception('The database username is required.');
        }

        if (is_null($this->password)) {
            throw new Exception('The database password is required.');
        }

        if (is_null($this->database)) {
            throw new Exception('The database name is required.');
        }
    }
}
