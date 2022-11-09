<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;

class TestCase extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        define('DB_CONFIG_PATH', '../'.getenv('app.dbConfigPath'));
    }
}
