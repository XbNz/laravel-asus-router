<?php

namespace XbNz\AsusRouter;


use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;
use XbNz\AsusRouter\Console\SetupCommand;
use XbNz\AsusRouter\Data\DataObject;
use XbNz\AsusRouter\Data\System;
use XbNz\AsusRouter\Data\TestFeature;
use XbNz\AsusRouter\Data\Validators\SystemRsaListValidator;
use XbNz\AsusRouter\Data\Validators\ValidatorInterface;
use XbNz\AsusRouter\Data\Validators\WanDnsListValidator;
use XbNz\AsusRouter\Data\Validators\WanIpListValidator;
use XbNz\AsusRouter\Data\Wan;

class AsusRouterServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'router-config');

        $this->app->tag([
            WanIpListValidator::class,
            SystemRsaListValidator::class,
        ], 'data-validators');

        $this->app->singleton(Ssh::class, function (){
            $session = new Ssh(
                config('router-config.router_username'),
                config('router-config.router_ip_address'),
                config('router-config.router_port'),
            );

            return $session
                ->configureProcess(fn (Process $process)
                => $process->setTimeout(config('router-config.timeout')));
        });

    }


    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupCommand::class,
            ]);

            $this->publishes([
                    __DIR__.'/../config/config.php' =>
                    config_path('router-config.php')
            ], 'router-config');
        }
    }
}