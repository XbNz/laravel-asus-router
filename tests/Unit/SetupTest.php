<?php


namespace XbNz\AsusRouter\Tests\Unit;


use Carbon\Carbon;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;
use XbNz\AsusRouter\Exceptions\RouterSshException;
use XbNz\AsusRouter\Router;
use XbNz\AsusRouter\RouterSetup;
use XbNz\AsusRouter\Tests\TestCase;

class SetupTest extends TestCase
{
    /** @test */
    public function an_exception_is_thrown_if_health_check_fails()
    {
        $processMock = $this->createMock(Process::class);
        $processMock->method('getOutput')
            ->willReturn('gibberish');
        $processMock->method('isSuccessful')
            ->willReturn(false);

        $sshMock = $this->mock(Ssh::class);
        $sshMock->shouldReceive('execute')
            ->andReturn($processMock);

        try {
            $router = new Router();
        } catch(RouterSshException $e) {
            return;
        }

        $this->fail('Did not throw exception even though the health check is forced to fail');

    }

    /** @test */
    public function an_exception_is_thrown_if_health_check_times_out()
    {
        \Config::set([
            'router-config.router_username' => 'ASUS',
            'router-config.router_ip_address' => '99.99.99.99',
            'router-config.router_port' => '22',
            'router-config.timeout' => '1'
        ]);

        try {
            $expectedFailureAt = Carbon::now()->addSeconds(1);
            $router = app(Router::class);
        } catch(RouterSshException $e) {
            $this->assertEquals($expectedFailureAt->timestamp, Carbon::now()->timestamp);
            return;
        }

        $this->fail('Did not throw exception even though the health check is forced to fail');

    }
}