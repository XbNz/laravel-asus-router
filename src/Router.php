<?php


namespace XbNz\AsusRouter;


use Illuminate\Support\Collection;
use Spatie\Ssh\Ssh;
use XbNz\AsusRouter\Data\Wan;

class Router extends RouterSetup
{
    protected function login(): Ssh
    {
        return Ssh::create(
            config('router-config.router_username'),
            config('router-config.router_ip_address'),
        )->usePort(config('router-config.router_port'));
    }

    protected function healthCheck(): bool
    {
        return $this->loggedInShell
            ->execute('ifconfig')
            ->isSuccessful();
    }

    public function wanInfo(): Wan
    {
        $result = $this->loggedInShell
            ->execute([
                'nvram get wan_ipaddr',
                'nvram get wan0_ipaddr',
                'nvram get wan1_ipaddr',
            ])->getOutput();

        return app(Wan::class)
            ->setTerminalOutput($result);
    }
}