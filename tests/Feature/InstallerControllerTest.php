<?php

namespace Tests\Feature;

use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\TestResponse;
use Exception;
use NodCMS\Installer\Controllers\Installer;
use Tests\TestCase;

class InstallerControllerTest extends TestCase
{
    use ControllerTestTrait;

    /**
     * @test
     * @throws Exception
     */
    public function installerStartTest()
    {
        /** @var TestResponse $result */
        $result = $this->controller(Installer::class)->execute('start');
        $result->assertSee('Welcome');
        $result->assertOk();
    }

    /**
     * @test
     * @throws Exception
     */
    public function installerLicenseTest()
    {
        /** @var TestResponse $result */
        $result = $this->controller(Installer::class)->execute('license');
        $result->assertSee('License Agreement');
        $result->assertOk();
    }
}
