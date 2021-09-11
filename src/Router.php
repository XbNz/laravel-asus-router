<?php


namespace XbNz\AsusRouter;


use Illuminate\Support\Collection;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
use XbNz\AsusRouter\Data\Wan;
use XbNz\AsusRouter\Exceptions\RouterSshException;

class Router extends RouterSetup
{
    public function healthCheck(): bool
    {
        try {
            return app(Ssh::class)
                ->execute('ifconfig')
                ->isSuccessful();
        } catch (ProcessTimedOutException $e) {
            throw new RouterSshException('Timeout: I ran a health check on the SSH connection and it failed. I suspect the router is not online with this IP.');
        }
    }

    public function wanInfo(): Wan
    {
        return app(Wan::class);
    }
}