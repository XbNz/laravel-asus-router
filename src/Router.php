<?php


namespace XbNz\AsusRouter;


use Illuminate\Support\Collection;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
use XbNz\AsusRouter\Data\System;
use XbNz\AsusRouter\Data\Wan;
use XbNz\AsusRouter\Exceptions\RouterSshException;

class Router
{
    public function __construct()
    {
        if (! $this->healthCheck()){
            throw new RouterSshException('I ran a health check on the SSH connection and it failed. I suspect there is something wrong with the port, username or SSH key.');
        }
    }

    public function healthCheck(): bool
    {
        try {
            return app(Ssh::class)
                ->execute('nvram get sshd_authkeys')
                ->isSuccessful();
        } catch (ProcessTimedOutException $e) {
            throw new RouterSshException('Timeout: I ran a health check on the SSH connection and it failed. I suspect the router is not online with this IP.');
        }
    }

    public function wan(): Wan
    {
        return app(Wan::class);
    }

    public function system(): System
    {
        return app(System::class);
    }
}