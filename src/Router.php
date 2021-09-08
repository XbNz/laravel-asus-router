<?php


namespace XbNz\AsusRouter;


use Illuminate\Support\Collection;
use Spatie\Ssh\Ssh;
use XbNz\AsusRouter\Data\Wan;

class Router extends RouterSetup
{
    protected function login(): Ssh
    {
        return Ssh::create('ASUS', '192.168.50.1')->usePort(22);
    }

    protected function healthCheck(): bool
    {
        return $this->loggedInShell
            ->execute('ifconfig')
            ->isSuccessful();
    }

    public function getWanIp(): Wan
    {
        $result = $this->loggedInShell
            ->execute([
                'nvram get wan_ipaddr',
                'nvram get wan0_ipaddr',
                'nvram get wan1_ipaddr',
            ])->getOutput();

        Wan::withIps($result);
    }
}