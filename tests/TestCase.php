<?php


namespace XbNz\AsusRouter\Tests;


use XbNz\AsusRouter\AsusRouterServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            AsusRouterServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
    }

    protected function setUp(): void
    {
        parent::setUp();
    }
}