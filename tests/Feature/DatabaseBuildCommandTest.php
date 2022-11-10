<?php

namespace Tests\Feature;

use CodeIgniter\Log\Logger;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Filters\CITestStreamFilter;
use Config\Services;
use NodCMS\Core\Commands\DatabaseBuild;
use Tests\TestCase;

class DatabaseBuildCommandTest extends TestCase
{
    use DatabaseTestTrait;

    private Logger $logger;

    private $streamFilter;

    protected function setUp(): void
    {
        CITestStreamFilter::$buffer = '';

        $this->streamFilter = stream_filter_append(STDOUT, 'CITestStreamFilter');

        $this->streamFilter = stream_filter_append(STDERR, 'CITestStreamFilter');

        parent::setUp();
        $this->logger = Services::logger();
    }

    protected function getBuffer()
    {
        return CITestStreamFilter::$buffer;
    }

    public function testDatabaseBuildHasGroupAttribute()
    {
        $command = new DatabaseBuild($this->logger, service('commands'));

        $this->assertObjectHasAttribute('group', $command);
    }

    public function testDatabaseBuildWorks()
    {
        command('database:build');

        $this->assertStringContainsString('DONE', $this->getBuffer());
    }
}