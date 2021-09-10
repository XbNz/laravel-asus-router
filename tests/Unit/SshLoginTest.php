<?php


namespace XbNz\AsusRouter\Tests\Unit;


use Illuminate\Support\Facades\File;
use XbNz\AsusRouter\Data\TestFeature;
use XbNz\AsusRouter\Router;
use XbNz\AsusRouter\Tests\TestCase;

class SshLoginTest extends TestCase
{
    /** @test */
    public function it_successfully_establishes_a_connection_with_asus_router()
    {
        $router = new Router();
        $raw = $router
            ->wanInfo()
            ->getIpList();
        dd($raw);
    }
}