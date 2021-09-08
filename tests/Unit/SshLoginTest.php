<?php


namespace XbNz\AsusRouter\Tests\Unit;


use XbNz\AsusRouter\Router;
use XbNz\AsusRouter\Tests\TestCase;

class SshLoginTest extends TestCase
{
    /** @test */
    public function it_successfully_establishes_a_connection_with_asus_router()
    {
        dd((new Router())->getWanIp());
    }
}