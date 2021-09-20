<?php


namespace XbNz\AsusRouter\Tests\Unit;


use Illuminate\Support\Collection;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;
use XbNz\AsusRouter\Exceptions\NoPublicIpDetectedException;
use XbNz\AsusRouter\Router;
use XbNz\AsusRouter\RouterSetup;
use XbNz\AsusRouter\Tests\TestCase;

class WanTest extends TestCase
{

    /** @test */
    public function it_filters_for_valid_ips_and_non_private_or_reserved_ranges_and_returns_the_routers_wan_ips()
    {
        $processMock = $this->createMock(Process::class);
        $processMock->method('getOutput')
            ->willReturn(
                '127.0.0.1' .
                PHP_EOL .
                'fd12:3456:789a:1::'.
                PHP_EOL .
                '192.168.0.0'.
                PHP_EOL.
                '172.16.0.0'.
                PHP_EOL.
                'fd12:3456:789a:1::1'.
                PHP_EOL.
                '1.1.1.1'.
                PHP_EOL.
                '2606:4700:4700::1111'
            );

        $processMock->method('isSuccessful')
            ->willReturn(true);

        $sshMock = $this->mock(Ssh::class);
        $sshMock->shouldReceive('execute')
            ->andReturn($processMock);

        $router = new Router();
        $ipList = $router->wan()->getIpList();

        $this->assertCount(2, $ipList);
        $this->assertTrue($ipList->contains('1.1.1.1'));
        $this->assertTrue($ipList->contains('2606:4700:4700::1111'));
    }


    /** @test */
    public function it_returns_a_collection_of_wan_dns()
    {
        $processMock = $this->createMock(Process::class);
        $processMock->method('getOutput')
            ->willReturn(
                '1.1.1.1' . PHP_EOL . '8.8.8.8'
            );

        $processMock->method('isSuccessful')
            ->willReturn(true);

        $sshMock = $this->mock(Ssh::class);
        $sshMock->shouldReceive('execute')
            ->andReturn($processMock);

        $router = new Router();
        $dnsList = $router->wan()->getDnsList();
        $this->assertInstanceOf(Collection::class, $dnsList);
    }


    /** @test */
    public function it_throws_an_exception_if_no_dns_is_set()
    {
        $processMock = $this->createMock(Process::class);
        $processMock->method('getOutput')
            ->willReturn(
                'definitely not a dns server'
            );

        $processMock->method('isSuccessful')
            ->willReturn(true);

        $sshMock = $this->mock(Ssh::class);
        $sshMock->shouldReceive('execute')
            ->andReturn($processMock);

        $router = new Router();

        try {
            $dnsList = $router->wan()->getDnsList();
        } catch (NoPublicIpDetectedException $e){
            return;
        }

        $this->fail('Should have thrown exception but did not');
    }


}