<?php


namespace XbNz\AsusRouter;



use Spatie\Ssh\Ssh;
use XbNz\AsusRouter\Exceptions\RouterSshException;

abstract class RouterSetup
{
    protected Ssh $loggedInShell;

    public function __construct()
    {
        $this->loggedInShell = $this->login();
        if (! $this->healthCheck()){
            throw new RouterSshException('I ran a health check on the SSH connection and it failed. Check your config file and ensure your SSH info is correct.');
        }
    }

    abstract protected function login(): Ssh;
    abstract protected function healthCheck(): bool;
}