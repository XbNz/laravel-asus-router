<?php


namespace XbNz\AsusRouter\Tests\Unit;


use Illuminate\Support\Facades\File;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;
use XbNz\AsusRouter\Data\TestFeature;
use XbNz\AsusRouter\Router;
use XbNz\AsusRouter\Tests\TestCase;

class SshLoginTest extends TestCase
{
    /** @test */
    public function it_successfully_establishes_a_connection_with_asus_router()
    {
        \Config::set(['router-config.router_ip_address' => '192.168.50.1']);
        \Config::set(['router-config.router_username' => 'ASUS']);
        \Config::set(['router-config.router_port' => '22']);


        $processMock = $this->createMock(Process::class);
        $processMock->method('getOutput')
            ->willReturn('1.1.1.1');
        $processMock->method('isSuccessful')
            ->willReturn(true);

        $sshMock = $this->mock(Ssh::class);
        $sshMock->shouldReceive('execute')
            ->andReturn($processMock);

        $router = new Router();
        dd($router->wanInfo()->getIpList());
    }
}