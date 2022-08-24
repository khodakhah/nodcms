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

use Exception;

class DatabaseEnvConfig
{
    private $host;
    private $username;
    private $password;
    private $database;

    /**
     * @param $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @param $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @param $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @param $database
     */
    public function setDatabase($database): void
    {
        $this->database = $database;
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
            file_put_contents(ROOTPATH . '/.env', PHP_EOL . $entry . PHP_EOL, FILE_APPEND);
        }

        $content = file_get_contents(ROOTPATH . '/.env');
        preg_replace('/^'.$key.'=.*$/', $entry, $content);
    }

    /**
     *  It checks if required parameters have been set.
     * @throws Exception
     */
    private function checkRequiredParameters()
    {
        if(is_null($this->host)){
            throw new Exception('The database host is required.');
        }

        if(is_null($this->username)){
            throw new Exception('The database username is required.');
        }

        if(is_null($this->password)){
            throw new Exception('The database password is required.');
        }

        if(is_null($this->database)){
            throw new Exception('The database name is required.');
        }
    }
}
