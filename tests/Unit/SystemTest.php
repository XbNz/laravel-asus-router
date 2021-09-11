<?php


namespace XbNz\AsusRouter\Tests\Unit;


use XbNz\AsusRouter\Router;
use XbNz\AsusRouter\Tests\TestCase;

class SystemTest extends TestCase
{
    /** @test */
    public function printing_the_ssh_keys_in_use_by_the_router()
    {
        $router = new Router();
        $sshKeys = $router->system()->getRsaKeyList();
        dd($sshKeys);
    }
}