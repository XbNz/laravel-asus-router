<?php


namespace XbNz\AsusRouter;



use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
use XbNz\AsusRouter\Exceptions\RouterSshException;

abstract class RouterSetup
{
    protected Ssh $loggedInShell;
    public function __construct()
    {
        $this->loggedInShell = app(Ssh::class);

        if (! $this->healthCheck()){
            throw new RouterSshException('I ran a health check on the SSH connection and it failed. I suspect there is something wrong with the port, username or SSH key.');
        }
    }

    abstract protected function healthCheck(): bool;
}