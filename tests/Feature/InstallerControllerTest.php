<?php

namespace Tests\Feature;

use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use Exception;
use NodCMS\Installer\Controllers\Installer;
use Tests\TestCase;

class InstallerControllerTest extends TestCase
{
    use DatabaseTestTrait;
    use ControllerTestTrait;

    /**
     * @test
     * @throws Exception
     */
    public function testUserLogin()
    {
        $result = $this->controller(Installer::class)->execute('start');
        $result->assertSee('Welcome');
        $result->assertOk();
    }
}
