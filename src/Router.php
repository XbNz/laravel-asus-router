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
    protected function healthCheck(): bool
    {
        try {
            return $this->loggedInShell
                ->execute('ifconfig')
                ->isSuccessful();
        } catch (ProcessTimedOutException $e) {
            throw new RouterSshException('Timeout: I ran a health check on the SSH connection and it failed. I suspect the router is not online with this IP.');
        }
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